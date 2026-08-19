@extends('layouts.main')

@section('title', config('app.name') . ' - Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style-dashboard.css') }}">
@endpush

@section('content')

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <svg class="d-inline-block me-2" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.2));">

                <defs>
                    <linearGradient id="checkGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#28a745;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#20c997;stop-opacity:1" />
                    </linearGradient>
                </defs>

                <circle cx="12" cy="12" r="11" fill="url(#checkGradient)" stroke="white" stroke-width="2" />

                <path d="M 7 12 L 10.5 15.5 L 17 8" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>

            {!! session('success') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Filter Bulan --}}
    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">

        <div class="d-flex flex-column flex-md-row align-items-md-center mb-3 gap-2">

            <div class="flex-grow-1">
                <h6 class="mb-0 text-uppercase fw-bold">
                    DASHBOARD
                </h6>

                <span class="text-secondary">
                    Rekap Data Laporan Bulan
                    <strong id="bulanTerpilih"></strong>
                </span>
            </div>

            <div class="d-flex gap-2 align-items-center">

                <label for="filterBulan" class="form-label mb-0 text-nowrap">
                    Pilih Bulan:
                </label>

                <div class="input-group input-group-sm">

                    <input type="month" name="month" class="form-control" id="filterBulan" value="{{ $monthInput }}"
                        aria-describedby="bulanTerpilih">
                </div>

            </div>

        </div>

    </form>


    <hr>


    {{-- Dashboard Cards --}}
    @php
        $cards = [
            [
                'img' => 'aktivasi.png',
                'title' => 'Aktivasi',
                'count' => $jumlahAktivasi,
                'text' => 'Layanan Aktivasi & re-Aktivasi Seller',
                'route' => 'aktivasiseller',
                'btn' => 'Tambah Baru',
            ],
            [
                'img' => 'canvasing.png',
                'title' => 'Canvasing',
                'count' => $jumlahCanvasing,
                'text' => 'Layanan KURLOG & JASKUG',
                'route' => 'canvasing',
                'btn' => 'Tambah Baru',
            ],
            [
                'img' => 'kunjungan.png',
                'title' => 'Kunjungan',
                'count' => $jumlahKunjungan,
                'text' => 'Kunjungan LKN & Prospek',
                'route' => 'kunjungan',
                'btn' => 'Tambah Baru',
            ],
            [
                'img' => 'laporan.png',
                'title' => 'Laporan',
                'count' => $jumlahData,
                'text' => 'Pusat Informasi & Kinerja',
                'route' => 'data.saya',
                'btn' => 'Lihat Data',
            ],
        ];

        $totalSlots = 6;
        $placeholders = max(0, $totalSlots - count($cards));
    @endphp


    <div class="dashboard-cards">

        <div class="row row-cols-2 row-cols-md-2 row-cols-lg-6 g-3">

            @php
                // Extract "Laporan" card so it can be rendered after placeholders
                $laporanCard = null;

                foreach ($cards as $idx => $c) {
                    if (isset($c['title']) && $c['title'] === 'Laporan') {
                        $laporanCard = $c;
                        unset($cards[$idx]);
                        break;
                    }
                }

                // Reindex remaining cards
                $cards = array_values($cards);
            @endphp


            {{-- Dashboard Cards --}}
            @foreach ($cards as $card)
                <div class="col">

                    <div class="card shadow-sm h-100" role="button" tabindex="0" aria-label="Akses {{ $card['title'] }}">

                        <div class="dashboard-card-image">

                            <img src="{{ asset('assets/images/bg/icon/' . $card['img']) }}" class="w-100 h-100"
                                alt="Ikon {{ $card['title'] }}" loading="lazy">

                        </div>


                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title mb-1">

                                {{ $card['title'] }}

                                <small class="text-muted">
                                    [{{ $card['count'] }}]
                                </small>

                            </h5>


                            <p class="card-text text-muted mb-3">
                                {{ $card['text'] }}
                            </p>


                            {{-- Tombol --}}
                            <a href="{{ route($card['route']) }}"
                                class="btn {{ $card['btn'] === 'Lihat Data' ? 'btn-success' : 'btn-primary' }} mt-auto w-100"
                                aria-label="{{ $card['btn'] }} untuk {{ $card['title'] }}">

                                {{ $card['btn'] }}

                            </a>

                        </div>

                    </div>

                </div>
            @endforeach


            {{-- Placeholder jika slot kosong --}}
            @for ($i = 0; $i < $placeholders; $i++)
                <div class="col d-none d-lg-block">

                    <div class="card shadow-sm h-100">

                        <div class="dashboard-card-image">

                            <img src="{{ asset('assets/images/bg/icon/kosong.png') }}" class="w-100 h-100"
                                alt="Belum tersedia" loading="lazy" style="filter: grayscale(100%);">
                        </div>


                        <div class="card-body d-flex flex-column bg-light text-muted">

                            <h5 class="card-title mb-1 text-muted placeholder-title">

                                Tidak Ada

                                <small class="text-muted">
                                    [0]
                                </small>

                            </h5>


                            <p class="card-text mb-3">
                                Belum tersedia
                            </p>


                            <button class="btn btn-secondary mt-auto w-100" disabled>

                                Belum Tersedia

                            </button>

                        </div>

                    </div>

                </div>
            @endfor


            {{-- Render card "Laporan" setelah placeholder --}}
            @if ($laporanCard)
                <div class="col">

                    <div class="card shadow-sm h-100" role="button" tabindex="0"
                        aria-label="Akses {{ $laporanCard['title'] }}">

                        <div class="dashboard-card-image">

                            <img src="{{ asset('assets/images/bg/icon/' . $laporanCard['img']) }}" class="w-100 h-100"
                                alt="Ikon {{ $laporanCard['title'] }}" loading="lazy">

                        </div>


                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title mb-1">

                                {{ $laporanCard['title'] }}

                                <small class="text-muted">
                                    [{{ $laporanCard['count'] }}]
                                </small>

                            </h5>


                            <p class="card-text text-muted mb-3">
                                {{ $laporanCard['text'] }}
                            </p>


                            {{-- Tombol --}}
                            <a href="{{ route($laporanCard['route']) }}"
                                class="btn {{ $laporanCard['btn'] === 'Lihat Data' ? 'btn-success' : 'btn-primary' }} mt-auto w-100"
                                aria-label="{{ $laporanCard['btn'] }} untuk {{ $laporanCard['title'] }}">

                                {{ $laporanCard['btn'] }}

                            </a>

                        </div>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection


@push('scripts')
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
