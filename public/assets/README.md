# Folder Assets

Folder ini berisi aset statis yang dapat diakses langsung dari browser.

## Struktur

```
public/
└── assets/
    ├── logo/          → Logo & brand aplikasi
    │   ├── pemeliharaan-aset.svg         → Logo utama
    │   └── pemeliharaan-aset-favicon.svg → Ikon tab browser
    ├── images/        → Gambar umum (foto aset, placeholder, dsb.)
    │   └── placeholder.svg
    └── icons/         → Ikon custom (SVG/PNG)
```

## Cara Menggunakan

Akses file aset langsung dari URL, contoh:

```
http://localhost:8000/assets/logo/pemeliharaan-aset.svg
```

Dalam Blade view:

```blade
<img src="{{ asset('assets/logo/pemeliharaan-aset.svg') }}" alt="Logo Pemeliharaan Aset">
```

## Catatan

- Folder ini hanya untuk aset **statis** (SVG, PNG, JPG, dsb).
- File yang di-compile melalui Vite (CSS/JS) tetap berada di `public/build`.
- Logo dapat diganti dengan file milik perusahaan langsung di folder `logo/`.
