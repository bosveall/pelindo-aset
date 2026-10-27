<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    public function index(Request $request)
    {
        $query = Aset::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_aset', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        $asets = $query->withCount('pemeliharaans')->latest()->paginate(10)->withQueryString();
        $kategoriOptions = $this->distinctAssetValues('kategori');
        $lokasiOptions = $this->distinctAssetValues('lokasi');

        $namaOptions = $this->distinctAssetValues('nama');

        return view('asets.index', compact('asets', 'kategoriOptions', 'lokasiOptions', 'namaOptions'));
    }

    public function create()
    {
        return view('asets.create', ['suggestions' => $this->formSuggestions()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('aset', 'public');
        }

        Aset::create($data);

        return redirect()->route('asets.index')->with('success', 'Data aset berhasil ditambahkan.');
    }

    public function show(Aset $aset)
    {
        $aset->load(['pemeliharaans.petugas']);
        return view('asets.show', compact('aset'));
    }

    public function scan(Request $request)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:255'],
        ]);

        $aset = Aset::where('kode_aset', $data['kode'])->first();

        if (!$aset) {
            return redirect()->route('asets.scan')
                ->with('scan_error', 'Kode aset tidak ditemukan. Periksa barcode atau masukkan kode secara manual.');
        }

        return redirect()->route('asets.show', $aset);
    }

    public function edit(Aset $aset)
    {
        return view('asets.edit', [
            'aset' => $aset,
            'suggestions' => $this->formSuggestions(),
        ]);
    }

    public function update(Request $request, Aset $aset)
    {
        $data = $this->validateData($request, $aset->id);

        if ($request->hasFile('gambar')) {
            if ($aset->gambar && \Storage::disk('public')->exists($aset->gambar)) {
                \Storage::disk('public')->delete($aset->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('aset', 'public');
        }

        $aset->update($data);

        return redirect()->route('asets.index')->with('success', 'Data aset berhasil diperbarui.');
    }

    public function destroy(Aset $aset)
    {
        $aset->delete();

        return redirect()->route('asets.index')->with('success', 'Data aset berhasil dihapus.');
    }

    protected function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'kode_aset' => ['required', 'unique:asets,kode_aset,' . $ignoreId],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:255'],
            'merk' => ['nullable', 'string', 'max:255'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'kondisi' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'tanggal_pengadaan' => ['nullable', 'date'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'kode_aset.required' => 'Kode aset wajib diisi.',
            'kode_aset.unique' => 'Kode aset sudah digunakan.',
            'nama.required' => 'Nama aset wajib diisi.',
            'gambar.image' => 'File gambar harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);
    }

    private function formSuggestions(): array
    {
        return [
            'nama' => $this->distinctAssetValues('nama'),
            'kategori' => $this->distinctAssetValues('kategori'),
            'merk' => $this->distinctAssetValues('merk'),
            'lokasi' => $this->distinctAssetValues('lokasi'),
        ];
    }

    private function distinctAssetValues(string $column)
    {
        return Aset::query()
            ->whereNotNull($column)
            ->where($column, '<>', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }
}
