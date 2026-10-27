<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Pemeliharaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        return view('laporan.index');
    }

    public function aset(Request $request)
    {
        $asets = $this->getAsetQuery($request)->withCount('pemeliharaans')->latest()->get();
        $kategoris = Aset::whereNotNull('kategori')->distinct()->pluck('kategori');
        $lokasis = Aset::query()->whereNotNull('lokasi')->where('lokasi', '<>', '')->distinct()->orderBy('lokasi')->pluck('lokasi');

        return view('laporan.aset', compact('asets', 'kategoris', 'lokasis'));
    }

    public function pemeliharaan(Request $request)
    {
        $pemeliharaans = $this->getPemeliharaanQuery($request)->latest()->get();
        return view('laporan.pemeliharaan', compact('pemeliharaans'));
    }

    public function exportAsetPdf(Request $request)
    {
        $asets = $this->getAsetQuery($request)->latest()->get();

        $pdf = Pdf::loadView('laporan.export-aset', [
            'asets' => $asets,
            'filters' => $request->only(['kondisi', 'status', 'kategori', 'lokasi']),
        ]);

        return $pdf->download('laporan-aset-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportAsetExcel(Request $request)
    {
        $asets = $this->getAsetQuery($request)->latest()->get();

        $rows = [
            [
                'No',
                'Kode Aset',
                'Nama',
                'Kategori',
                'Lokasi',
                'Kondisi',
            ]
        ];

        foreach ($asets as $index => $aset) {
            $rows[] = [
                $index + 1,
                $aset->kode_aset,
                $aset->nama,
                $aset->kategori ?? '-',
                $aset->lokasi ?? '-',
                str_replace('_', ' ', $aset->kondisi),
            ];
        }

        return $this->downloadExcel('laporan-aset-' . now()->format('Ymd_His') . '.xlsx', $rows);
    }

    public function exportPemeliharaanPdf(Request $request)
    {
        $pemeliharaans = $this->getPemeliharaanQuery($request)->latest()->get();

        $pdf = Pdf::loadView('laporan.export-pemeliharaan', [
            'pemeliharaans' => $pemeliharaans,
            'filters' => $request->only(['status', 'dari', 'sampai']),
        ]);

        return $pdf->download('laporan-pemeliharaan-' . now()->format('Ymd_His') . '.pdf');
    }

    public function exportPemeliharaanExcel(Request $request)
    {
        $pemeliharaans = $this->getPemeliharaanQuery($request)->latest()->get();

        $rows = [
            [
                'No',
                'Aset',
                'Tanggal',
                'Jadwal Pemeliharaan',
                'Jenis',
                'Petugas',
            ]
        ];

        foreach ($pemeliharaans as $index => $pemeliharaan) {
            $rows[] = [
                $index + 1,
                $pemeliharaan->aset?->nama ?? '-',
                $pemeliharaan->tanggal ? $pemeliharaan->tanggal->format('d/m/Y') : '-',
                $pemeliharaan->jadwal_pemeliharaan ? $pemeliharaan->jadwal_pemeliharaan->format('d/m/Y') : '-',
                $pemeliharaan->jenis ?? '-',
                $pemeliharaan->petugas?->name ?? '-',
            ];
        }

        return $this->downloadExcel('laporan-pemeliharaan-' . now()->format('Ymd_His') . '.xlsx', $rows);
    }

    protected function getAsetQuery(Request $request)
    {
        $query = Aset::query();

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        return $query;
    }

    protected function getPemeliharaanQuery(Request $request)
    {
        $query = Pemeliharaan::with(['aset', 'petugas']);

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal', [$request->dari . ' 00:00:00', $request->sampai . ' 23:59:59']);
        }

        return $query;
    }

    protected function downloadExcel(string $filename, array $rows): Response
    {
        $file = tempnam(sys_get_temp_dir(), 'xlsx');

        if ($file === false) {
            abort(500, 'Gagal membuat file Excel');
        }

        $zip = new ZipArchive();

        if ($zip->open($file, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Gagal membuat file Excel');
        }

        $sheetXml = $this->generateSheetXml($rows);

        $zip->addFromString('[Content_Types].xml', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>
XML);

        $zip->addFromString('_rels/.rels', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
XML);

        $zip->addFromString('docProps/app.xml', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>Microsoft Excel</Application>
</Properties>
XML);

        $zip->addFromString('docProps/core.xml', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:creator>Pelindo Aset</dc:creator>
  <cp:lastModifiedBy>Pelindo Aset</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">2026-01-01T00:00:00Z</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">2026-01-01T00:00:00Z</dcterms:modified>
</cp:coreProperties>
XML);

        $zip->addFromString('xl/workbook.xml', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Laporan" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>
XML);

        $zip->addFromString('xl/_rels/workbook.xml.rels', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
</Relationships>
XML);

        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        return response()->streamDownload(function () use ($file) {
            echo file_get_contents($file);
            unlink($file);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected function generateSheetXml(array $rows): string
    {
        $sheetRows = [];

        foreach ($rows as $rowIndex => $row) {
            $cells = [];

            foreach ($row as $columnIndex => $value) {
                $cellReference = $this->excelCellReference($columnIndex + 1, $rowIndex + 1);
                $text = htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_XML1, 'UTF-8');
                $cells[] = '<c r="' . $cellReference . '" t="inlineStr"><is><t>' . $text . '</t></is></c>';
            }

            $sheetRows[] = '<row r="' . ($rowIndex + 1) . '">' . implode('', $cells) . '</row>';
        }

        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <sheetData>
    {{rows}}
  </sheetData>
</worksheet>
XML;

        return str_replace('{{rows}}', implode('', $sheetRows), $xml);
    }

    protected function excelCellReference(int $column, int $row): string
    {
        $letters = '';
        $columnValue = $column;

        while ($columnValue > 0) {
            $columnValue--;
            $letters = chr(65 + ($columnValue % 26)) . $letters;
            $columnValue = intdiv($columnValue, 26);
        }

        return $letters . $row;
    }
}
