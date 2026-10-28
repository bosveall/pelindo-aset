<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\Ruangan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class RuanganController extends Controller
{
    private const DEFAULT_RUANGAN = [
        'Ruang Rapat',
        'Ruang Server',
        'Ruang Keuangan',
        'Ruang Teknik',
        'Ruang TI',
        'Ruang SISMAN',
        'Ruang Umum',
        'Ruang Pak GM',
    ];

    public function index()
    {
        $this->ensureRuanganTableExists();
        $ruangan = $this->allRooms();

        return view('ruangan.index', compact('ruangan'));
    }

    public function show(string $lokasi)
    {
        $this->ensureRuanganTableExists();
        $ruangan = $this->resolveRuangan($lokasi);
        abort_if(! $ruangan, 404);

        $asets = Aset::withCount('pemeliharaans')
            ->where('lokasi', $ruangan->nama)
            ->orderBy('nama')
            ->get();
        $jenisOptions = Pemeliharaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '<>', '')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis');

        return view('ruangan.show', ['lokasi' => $ruangan->nama, 'asets' => $asets, 'jenisOptions' => $jenisOptions]);
    }

    public function store(Request $request, string $lokasi)
    {
        $this->ensureRuanganTableExists();
        $ruangan = $this->resolveRuangan($lokasi);
        abort_if(! $ruangan, 404);

        $data = $request->validate([
            'aset_id' => ['required', 'integer'],
            'jenis' => ['required', 'string', 'max:255'],
            'keterangan' => ['required', 'string'],
        ]);

        $aset = Aset::whereKey($data['aset_id'])
            ->where('lokasi', $ruangan->nama)
            ->firstOrFail();

        Pemeliharaan::create([
            'aset_id' => $aset->id,
            'tanggal' => now(),
            'jenis' => $data['jenis'],
            'keterangan' => $data['keterangan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('ruangan.show', ['lokasi' => $ruangan->nama])
            ->with('success', 'Permintaan pemeliharaan berhasil dikirim.');
    }

    public function storeRoom(Request $request)
    {
        $this->ensureRuanganTableExists();
        abort_unless($request->user()?->role === 'admin', 403);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:ruangans,nama'],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'nama.required' => 'Nama ruangan wajib diisi.',
            'nama.unique' => 'Nama ruangan sudah ada.',
        ]);

        $ruangan = Ruangan::create([
            'nama' => trim($data['nama']),
            'slug' => Str::slug($data['nama']),
            'deskripsi' => $data['deskripsi'] ?? null,
        ]);

        return redirect()->route('ruangan.index')->with('success', 'Ruangan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $this->ensureRuanganTableExists();
        abort_unless($request->user()?->role === 'admin', 403);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:ruangans,nama,' . $ruangan->id],
            'deskripsi' => ['nullable', 'string'],
        ], [
            'nama.required' => 'Nama ruangan wajib diisi.',
            'nama.unique' => 'Nama ruangan sudah ada.',
        ]);

        $oldName = $ruangan->nama;
        $ruangan->fill($data);
        $ruangan->slug = Str::slug($data['nama']);
        $ruangan->save();

        if ($oldName !== $ruangan->nama) {
            Aset::where('lokasi', $oldName)->update(['lokasi' => $ruangan->nama]);
        }

        return back()->with('success', 'Nama ruangan berhasil diperbarui.');
    }

    private function ensureRuanganTableExists(): void
    {
        if (! Schema::hasTable('ruangans')) {
            Schema::create('ruangans', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->string('slug')->unique();
                $table->text('deskripsi')->nullable();
                $table->timestamps();
            });
        }
    }

    private function allRooms()
    {
        if (Ruangan::query()->count() === 0) {
            foreach (self::DEFAULT_RUANGAN as $nama) {
                Ruangan::firstOrCreate(
                    ['nama' => $nama],
                    ['slug' => Str::slug($nama)]
                );
            }
        }

        return Ruangan::query()
            ->orderBy('nama')
            ->get()
            ->map(function (Ruangan $ruangan) {
                return (object) [
                    'id' => $ruangan->id,
                    'lokasi' => $ruangan->nama,
                    'slug' => $ruangan->slug,
                    'deskripsi' => $ruangan->deskripsi,
                    'jumlah_aset' => Aset::where('lokasi', $ruangan->nama)->count(),
                ];
            });
    }

    private function resolveRuangan(string $lokasi): ?Ruangan
    {
        $normalized = trim($lokasi);

        $room = Ruangan::query()
            ->where(function ($query) use ($normalized) {
                $query->where('nama', $normalized)
                    ->orWhere('slug', Str::slug($normalized))
                    ->orWhereRaw('LOWER(nama) = ?', [mb_strtolower($normalized)]);
            })
            ->first();

        if ($room) {
            return $room;
        }

        if (in_array($normalized, self::DEFAULT_RUANGAN, true)) {
            return Ruangan::firstOrCreate(
                ['nama' => $normalized],
                ['slug' => Str::slug($normalized)]
            );
        }

        return null;
    }
}