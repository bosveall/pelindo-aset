<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pemeliharaan Pelindo</title>
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
        <h1>Laporan Pemeliharaan Aset</h1>
        <p class="subtitle">Rekap riwayat pemeliharaan dan jadwal aset.</p>
    </div>

    <table class="meta">
        <tr>
            <td width="33%">
                <div class="meta-label">Jumlah riwayat</div>
                <div class="meta-value">{{ $pemeliharaans->count() }} data</div>
            </td>
            <td width="33%">
                <div class="meta-label">Periode laporan</div>
                <div class="meta-value">
                    {{ !empty($filters['dari']) && !empty($filters['sampai']) ? $filters['dari'] . ' – ' . $filters['sampai'] : 'Semua periode' }}
                </div>
            </td>
            <td width="34%">
                <div class="meta-label">Tanggal dokumen</div>
                <div class="meta-value">{{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th class="center" width="5%">No</th>
                <th width="24%">Aset</th>
                <th width="16%">Tanggal</th>
                <th width="19%">Jadwal Pemeliharaan</th>
                <th width="18%">Jenis</th>
                <th width="18%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemeliharaans as $index => $pemeliharaan)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $pemeliharaan->aset?->nama ?? '-' }}</td>
                    <td>{{ $pemeliharaan->tanggal?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $pemeliharaan->jadwal_pemeliharaan?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $pemeliharaan->jenis ?? '-' }}</td>
                    <td>{{ $pemeliharaan->petugas?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">Tidak ada data pemeliharaan untuk periode yang dipilih.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen dibuat pada {{ now()->format('d/m/Y H:i') }} · Pelindo Regional 1 Dumai
    </div>
</body>

</html>
