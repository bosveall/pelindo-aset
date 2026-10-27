<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use App\Models\User;
use Illuminate\Http\Request;

class PemeliharaanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemeliharaan::with(['aset', 'petugas']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('aset', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_aset', 'like', "%{$search}%");
            });
        }

        if ($request->filled('aset_id')) {
            $query->where('aset_id', $request->aset_id);
        }

        $pemeliharaans = $query->latest()->paginate(10)->withQueryString();
        $asets = Aset::orderBy('nama')->get();

        return view('pemeliharaans.index', compact('pemeliharaans', 'asets'));
    }

    public function create()
    {
        $asets = Aset::orderBy('nama')->get();
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        $asetId = Aset::whereKey(request()->query('aset_id'))->value('id');
        $jenisOptions = $this->jenisOptions();

        return view('pemeliharaans.create', compact('asets', 'petugas', 'asetId', 'jenisOptions'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        Pemeliharaan::create($data);

        return redirect()->route('pemeliharaans.index')->with('success', 'Riwayat pemeliharaan berhasil ditambahkan.');
    }

    public function show(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->load(['aset', 'petugas']);
        return view('pemeliharaans.show', compact('pemeliharaan'));
    }

    public function edit(Pemeliharaan $pemeliharaan)
    {
        $asets = Aset::orderBy('nama')->get();
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        $jenisOptions = $this->jenisOptions();

        return view('pemeliharaans.edit', compact('pemeliharaan', 'asets', 'petugas', 'jenisOptions'));
    }

    public function update(Request $request, Pemeliharaan $pemeliharaan)
    {
        $data = $this->validateData($request);

        $pemeliharaan->update($data);

        return redirect()->route('pemeliharaans.index')->with('success', 'Riwayat pemeliharaan berhasil diperbarui.');
    }

    public function destroy(Pemeliharaan $pemeliharaan)
    {
        $pemeliharaan->delete();

        return redirect()->route('pemeliharaans.index')->with('success', 'Riwayat pemeliharaan berhasil dihapus.');
    }

    protected function validateData(Request $request)
    {
        return $request->validate([
            'aset_id' => ['required', 'exists:asets,id'],
            'tanggal' => ['nullable', 'date'],
            'jadwal_pemeliharaan' => ['nullable', 'date'],
            'jenis' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'petugas_id' => ['nullable', 'exists:users,id'],
            'status' => ['sometimes', 'required', 'in:menunggu,proses,selesai'],
        ], [
            'aset_id.required' => 'Aset wajib dipilih.',
            'aset_id.exists' => 'Aset tidak ditemukan.',
        ]);
    }

    private function jenisOptions()
    {
        return Pemeliharaan::query()
            ->whereNotNull('jenis')
            ->where('jenis', '<>', '')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis');
    }
}
