<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AktivasiSeller;
use App\Models\Canvasing;
use App\Models\Kunjungan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BankDataController extends Controller
{
    public function index(Request $request)
    {
        $user   = Auth::user();               // ambil data user login
        $userId = $user->id ?? null;          // ambil ID user

        // jika ID = 1, ambil name sesuai ID tersebut
        if ($userId === 1) {
            $kantor = User::find(1)->name;    // ambil nama user dengan ID 1
        } else {
            $kantor = $user->name ?? 'Guest'; // kalau bukan ID 1, pakai nama user login
        }

        $monthInput = $request->get('month') ?? Carbon::now()->format('Y-m');
        $startDate  = Carbon::parse($monthInput . '-01')->startOfMonth();
        $endDate    = Carbon::parse($monthInput . '-01')->endOfMonth();

        // flag boolean: true hanya jika ID user = 1
        $isSPV = ($userId === 1);

        // Ambil data sesuai filter
        $aktivasiData = AktivasiSeller::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
            ->whereBetween('tanggal', [$startDate, $endDate])->get();

        $canvasingData = Canvasing::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
            ->whereBetween('tanggal', [$startDate, $endDate])->get();

        $kunjunganData = Kunjungan::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
            ->whereBetween('tanggal', [$startDate, $endDate])->get();

        // Rekap summary
        $summary = [];
        foreach (['aktivasi' => $aktivasiData, 'canvasing' => $canvasingData, 'kunjungan' => $kunjunganData] as $tipe => $data) {
            foreach ($data as $item) {
                $tgl = Carbon::parse($item->tanggal)->format('Y-m-d');
                $summary[$item->kantor][$tgl][$tipe] = ($summary[$item->kantor][$tgl][$tipe] ?? 0) + 1;
            }
        }

        // Ambil semua kantor (selain SPV Retail)
        $daftarKantor = User::where('id', '!=', 1)   // exclude user dengan ID 1 (SPV)
            ->distinct()
            ->pluck('name');

        // Pastikan setiap kantor ada di summary
        foreach ($daftarKantor as $namaKantor) {
            if (!isset($summary[$namaKantor])) {
                $summary[$namaKantor] = [];
            }
        }


        // Detail data
        $detailData = [];
        foreach (['aktivasi' => $aktivasiData, 'canvasing' => $canvasingData, 'kunjungan' => $kunjunganData] as $tipe => $data) {
            foreach ($data as $item) {
                $tgl = Carbon::parse($item->tanggal)->format('Y-m-d');
                $detailData[$item->kantor][$tgl][$tipe][] = $item;
            }
        }

        return view('pages.data.data_saya', compact('monthInput', 'summary', 'detailData'));
    }

    public function downloadLaporan(Request $request)
    {
        $user   = Auth::user();               // ambil data user login
        $userId = $user->id ?? null;          // ambil ID user

        // jika ID = 1, ambil name sesuai ID tersebut
        if ($userId === 1) {
            $kantor = User::find(1)->name;    // nama user dengan ID 1 (Supervisor)
        } else {
            $kantor = $user->name ?? 'Guest'; // nama user login biasa
        }

        $monthInput = $request->get('month') ?? Carbon::now()->format('Y-m');
        $startDate  = Carbon::parse($monthInput . '-01')->startOfMonth();
        $endDate    = Carbon::parse($monthInput . '-01')->endOfMonth();
        $tipeFilter = $request->get('tipe') ?? 'semua';

        // flag boolean: true hanya jika ID user = 1
        $isSPV = ($userId === 1);


        // Ambil data sesuai filter
        $aktivasiData = AktivasiSeller::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
            ->whereBetween('tanggal', [$startDate, $endDate])->get();

        $canvasingData = Canvasing::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
            ->whereBetween('tanggal', [$startDate, $endDate])->get();

        $kunjunganData = Kunjungan::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
            ->whereBetween('tanggal', [$startDate, $endDate])->get();

        $spreadsheet = new Spreadsheet();

        $writeSheet = function ($sheet, $data, $tipe) {
            $sheet->setTitle(ucfirst($tipe));
            $headers = [];
            switch ($tipe) {
                case 'aktivasi':
                    $headers = [
                        'No',
                        'Kantor',
                        'Tanggal',
                        'Jenis Aktivasi Seller',
                        'Nama Olshop',
                        'Nama Pemilik',
                        'Alamat Lengkap',
                        'Nomor HP',
                        'Jenis Produk',
                        'Pesaing',
                        'Link Toko',
                        'Keterangan Lainnya',
                        'Foto'
                    ];
                    break;
                case 'canvasing':
                    $headers = ['No', 'Kantor', 'Tanggal', 'Jenis Canvasing', 'Alamat Canvasing', 'Keterangan', 'Foto'];
                    break;
                case 'kunjungan':
                    $headers = ['No', 'Kantor', 'Tanggal', 'Jenis Kunjungan', 'Alamat Kunjungan', 'Tujuan Kunjungan', 'Hasil Kunjungan', 'Keterangan Lainnya', 'Foto'];
                    break;
            }

            $sheet->fromArray($headers, null, 'A1');
            $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '007bff']]
            ]);

            $row = 2;
            $counter = 1; // nomor urut per sheet
            $numBaseColumns = count($headers) - 1; // tanpa kolom No

            foreach ($data as $item) {
                $fotoList = is_array(json_decode($item->foto ?? '[]', true)) ? json_decode($item->foto ?? '[]', true) : [];

                if (empty($fotoList)) $fotoList = ['-'];

                foreach ($fotoList as $i => $foto) {
                    $link = $foto !== '-' ? url($foto) : '-';

                    // build base values (tanpa kolom No)
                    switch ($tipe) {
                        case 'aktivasi':
                            if ($i === 0) {
                                $jenisAktivasi = $item->jenis_aktivasi_seller ?? '-';
                                if ($jenisAktivasi == 1) {
                                    $jenisAktivasi = 'Aktivasi Seller Baru';
                                } elseif ($jenisAktivasi == 0) {
                                    $jenisAktivasi = 're-Aktivasi Seller';
                                }
                                $baseValues = [
                                    $item->kantor ?? '-',
                                    $item->tanggal ?? '-',
                                    $jenisAktivasi,
                                    $item->nama_olshop ?? '-',
                                    $item->nama_pemilik ?? '-',
                                    $item->alamat_lengkap ?? '-',
                                    $item->nomor_hp ?? '-',
                                    $item->jenis_produk ?? '-',
                                    $item->pesaing ?? '-',
                                    $item->link_toko ?? '-',
                                    $item->keterangan_lainnya ?? '-',
                                    $link
                                ];
                            } else {
                                // kosongkan semua kecuali foto (pastikan panjang = numBaseColumns)
                                $baseValues = array_merge(array_fill(0, $numBaseColumns - 1, ''), [$link]);
                            }
                            break;
                        case 'canvasing':
                            if ($i === 0) {
                                $baseValues = [
                                    $item->kantor ?? '-',
                                    $item->tanggal ?? '-',
                                    $item->jenis_canvasing ?? '-',
                                    $item->alamat_canvasing ?? '-',
                                    $item->keterangan ?? '-',
                                    $link
                                ];
                            } else {
                                $baseValues = array_merge(array_fill(0, $numBaseColumns - 1, ''), [$link]);
                            }
                            break;
                        case 'kunjungan':
                            if ($i === 0) {
                                $baseValues = [
                                    $item->kantor ?? '-',
                                    $item->tanggal ?? '-',
                                    $item->jenis_kunjungan ?? '-',
                                    $item->alamat_kunjungan ?? '-',
                                    $item->tujuan_kunjungan ?? '-',
                                    $item->hasil_kunjungan ?? '-',
                                    $item->keterangan_lainnya ?? '-',
                                    $link
                                ];
                            } else {
                                $baseValues = array_merge(array_fill(0, $numBaseColumns - 1, ''), [$link]);
                            }
                            break;
                        default:
                            $baseValues = array_merge(array_fill(0, $numBaseColumns - 1, ''), [$link]);
                            break;
                    }

                    // Tentukan apakah kolom B (index 0 dari baseValues) kosong
                    $bValue = trim((string)($baseValues[0] ?? ''));
                    $num = $bValue !== '' && $bValue !== '-' ? $counter++ : '';

                    $finalValues = array_merge([$num], $baseValues);

                    $sheet->fromArray($finalValues, null, "A{$row}");

                    if ($link !== '-') {
                        $lastColIndex = count($finalValues);
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColIndex);
                        $sheet->getCell($colLetter . $row)->getHyperlink()->setUrl($link);
                    }

                    $row++;
                }
            }

            // Auto width
            foreach (range('A', $sheet->getHighestColumn()) as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        };

        // Jika filter semua → buat sheet berbeda
        if ($tipeFilter === 'semua') {
            $writeSheet($spreadsheet->getActiveSheet(), $aktivasiData, 'aktivasi');

            $sheet2 = $spreadsheet->createSheet();
            $writeSheet($sheet2, $canvasingData, 'canvasing');

            $sheet3 = $spreadsheet->createSheet();
            $writeSheet($sheet3, $kunjunganData, 'kunjungan');
        } else {
            $dataMap = [
                'aktivasi' => $aktivasiData,
                'canvasing' => $canvasingData,
                'kunjungan' => $kunjunganData,
            ];
            $writeSheet($spreadsheet->getActiveSheet(), $dataMap[$tipeFilter] ?? [], $tipeFilter);
        }

        $filename = 'Laporan_' . $monthInput . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function grafikTahunan(Request $request)
    {
        $user   = Auth::user();               // ambil data user login
        $userId = $user->id ?? null;          // ambil ID user

        // jika ID = 1, ambil name sesuai ID tersebut
        if ($userId === 1) {
            $kantor = User::find(1)->name;    // nama user dengan ID 1 (Supervisor)
        } else {
            $kantor = $user->name ?? 'Guest'; // nama user login biasa
        }

        // flag boolean: true hanya jika ID user = 1
        $isSPV = ($userId === 1);


        // Default periode 7 hari terakhir + hari ini
        $start = $request->get('periode_awal')
            ? Carbon::createFromFormat('Y-m-d', $request->periode_awal)->startOfDay()
            : now()->subDays(6)->startOfDay(); // 6 hari lalu

        $end = $request->get('periode_akhir')
            ? Carbon::createFromFormat('Y-m-d', $request->periode_akhir)->endOfDay()
            : now()->endOfDay(); // hari ini

        if ($end->lessThan($start)) $end = (clone $start)->endOfDay();

        $models = [
            'aktivasi'  => AktivasiSeller::class,
            'canvasing' => Canvasing::class,
            'kunjungan' => Kunjungan::class,
        ];

        $grafik = ['labels' => [], 'aktivasi' => [], 'canvasing' => [], 'kunjungan' => []];
        $detailPerHari = [];

        $cursor = $start->copy();
        while ($cursor <= $end) {
            $dayKey = $cursor->format('Y-m-d');
            $grafik['labels'][] = $cursor->format('d M Y');

            foreach ($models as $key => $model) {
                $data = $model::when(!$isSPV, fn($q) => $q->where('kantor', $kantor))
                    ->whereDate('tanggal', $dayKey)
                    ->get();

                $grafik[$key][] = $data->count();
                $detailPerHari[$dayKey][$key] = $data;
            }

            $cursor->addDay();
        }

        return view('pages.data.grafik_tahunan', [
            'grafik' => $grafik,
            'detailPerHari' => $detailPerHari,
            'periodeAwal' => $start->format('Y-m-d'),
            'periodeAkhir' => $end->format('Y-m-d'),
        ]);
    }
}
