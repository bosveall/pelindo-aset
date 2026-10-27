<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Aset Pelindo</title>
    <style>
        @page { margin: 32px 34px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1e293b; font-size: 10px; }
        .masthead { border-bottom: 3px solid #0f766e; padding-bottom: 12px; width: 100%; }
        .logo-cell { padding-right: 8px; text-align: center; vertical-align: middle; width: 52px; }
        .logo { height: 44px; width: 44px; }
        .identity { padding-left: 8px; vertical-align: middle; }
        .brand { color: #0f766e; font-size: 9px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
        .unit { color: #64748b; font-size: 8px; letter-spacing: 1.5px; margin-top: 4px; text-transform: uppercase; }
        h1 { color: #0f172a; font-size: 22px; letter-spacing: 0.5px; margin: 14px 0 4px; text-transform: uppercase; }
        .subtitle { color: #64748b; font-size: 10px; margin: 0; }
        .meta { margin: 16px 0; width: 100%; }
        .meta td { background: #f1f5f9; border: 1px solid #e2e8f0; padding: 9px 11px; }
        .meta-label { color: #64748b; font-size: 8px; text-transform: uppercase; }
        .meta-value { color: #0f172a; font-size: 11px; font-weight: bold; margin-top: 4px; }
        table.data { border-collapse: collapse; width: 100%; }
        .data th { background: #0f766e; color: #fff; font-size: 8px; padding: 9px 7px; text-align: left; text-transform: uppercase; }
        .data td { border-bottom: 1px solid #e2e8f0; color: #334155; padding: 8px 7px; }
        .data tr:nth-child(even) td { background: #f8fafc; }
        .center { text-align: center; }
        .footer { border-top: 1px solid #e2e8f0; color: #64748b; font-size: 8px; margin-top: 18px; padding-top: 8px; }
        .empty { color: #64748b; padding: 22px !important; text-align: center; }
    </style>
</head>

<body>
    <table class="masthead">
        <tr>
            <td class="logo-cell">
                <img class="logo" src="{{ public_path('assets/icons/icon.jpeg') }}" alt="Logo Pelindo">
            </td>
            <td class="identity">
                <div class="brand">Pelindo</div>
                <div class="unit">Regional 1 Dumai</div>
            </td>
        </tr>
    </table>
    <div>
        <h1>Laporan Inventaris Aset</h1>
        <p class="subtitle">Rekap inventaris aset berdasarkan kondisi, kategori, dan lokasi.</p>
    </div>

    <table class="meta">
        <tr>
            <td width="25%">
                <div class="meta-label">Jumlah aset</div>
                <div class="meta-value">{{ $asets->count() }} aset</div>
            </td>
            <td width="25%">
                <div class="meta-label">Kondisi</div>
                <div class="meta-value">{{ !empty($filters['kondisi']) ? ucfirst(str_replace('_', ' ', $filters['kondisi'])) : 'Semua kondisi' }}</div>
            </td>
            <td width="25%">
                <div class="meta-label">Kategori</div>
                <div class="meta-value">{{ !empty($filters['kategori']) ? $filters['kategori'] : 'Semua kategori' }}</div>
            </td>
            <td width="25%">
                <div class="meta-label">Lokasi</div>
                <div class="meta-value">{{ !empty($filters['lokasi']) ? $filters['lokasi'] : 'Semua lokasi' }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="center" width="5%">No</th>
                <th width="16%">Kode Aset</th>
                <th width="25%">Nama Aset</th>
                <th width="17%">Kategori</th>
                <th width="21%">Lokasi</th>
                <th width="16%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asets as $index => $aset)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $aset->kode_aset }}</td>
                    <td>{{ $aset->nama }}</td>
                    <td>{{ $aset->kategori ?? '-' }}</td>
                    <td>{{ $aset->lokasi ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $aset->kondisi)) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Tidak ada data aset untuk filter yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen dibuat pada {{ now()->format('d/m/Y H:i') }} · Pelindo Regional 1 Dumai
    </div>
</body>

</html>
