<?php

namespace App\Http\Controllers;

use App\Models\AktivasiSeller;
use App\Models\Canvasing;
use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user   = Auth::user();               // ambil data user login
        $userId = $user->id ?? null;          // ambil ID user

        // jika ID = 1, ambil name sesuai ID tersebut
        if ($userId === 1) {
            $kantor = User::find(1)->name;    // nama user dengan ID 1 (Supervisor)
        } else {
            $kantor = $user->name ?? 'Guest'; // nama user login biasa
        }

        // Ambil filter bulan dari input, default ke bulan ini
        $monthInput = $request->get('month'); // format: YYYY-MM

        if ($monthInput) {
            $startDate = Carbon::parse($monthInput . '-01')->startOfMonth();
            $endDate = Carbon::parse($monthInput . '-01')->endOfMonth();
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            $monthInput = Carbon::now()->format('Y-m'); // agar tetap dikirim ke view
        }

        // Jika SPV Retail, ambil semua data
        $isSPV = ($userId === 1);

        $aktivasiQuery = AktivasiSeller::whereBetween('tanggal', [$startDate, $endDate]);
        $kunjunganQuery = Kunjungan::whereBetween('tanggal', [$startDate, $endDate]);
        $canvasingQuery = Canvasing::whereBetween('tanggal', [$startDate, $endDate]);

        if (!$isSPV) {
            // Bukan SPV, filter berdasarkan kantor
            $aktivasiQuery->where('kantor', $kantor);
            $kunjunganQuery->where('kantor', $kantor);
            $canvasingQuery->where('kantor', $kantor);
        }

        $jumlahAktivasi = $aktivasiQuery->count();
        $jumlahKunjungan = $kunjunganQuery->count();
        $jumlahCanvasing = $canvasingQuery->count();
        $jumlahData = $jumlahAktivasi + $jumlahKunjungan + $jumlahCanvasing;

        return view('pages.dashboard', compact(
            'jumlahAktivasi',
            'jumlahKunjungan',
            'jumlahCanvasing',
            'jumlahData',
            'monthInput'
        ));
    }
}
