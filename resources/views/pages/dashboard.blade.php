@extends('layouts.main')

@section('title', config('app.name') . ' - Dashboard')

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
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Bulan --}}
    <form method="GET" action="{{ route('dashboard') }}" class="mb-4">
        <div class="d-flex flex-column flex-md-row align-items-md-center mb-3 gap-2">
            <div class="flex-grow-1">
                <h6 class="mb-0 text-uppercase fw-bold">DASHBOARD</h6>
                <span class="text-secondary">
                    Rekap Data Laporan Bulan <strong id="bulanTerpilih"></strong>
                </span>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <label for="filterBulan" class="form-label mb-0 text-nowrap">
                    Pilih Bulan:
                </label>

                <div class="input-group input-group-sm">
                    <input type="month" name="month" class="form-control" id="filterBulan" value="{{ $monthInput }}"
                        aria-describedby="bulanTerpilih">

                    <span class="input-group-text">
                        <i class="bx bx-calendar"></i>
                    </span>
                </div>
            </div>
        </div>
    </form>

    <hr>

    {{-- Dashboard Cards --}}
    <style>
        /* Optimasi: CSS untuk dashboard cards dengan performa tinggi */
        .dashboard-cards .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .dashboard-cards .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .dashboard-cards .card img {
            transition: transform 0.3s ease;
        }

        .dashboard-cards .card:hover img {
            transform: scale(1.05);
        }

        .dashboard-cards .card-title {
            font-size: 1rem;
            line-height: 1.1;
            font-weight: 600;
        }

        .dashboard-cards .card-title small {
            font-size: .78em;
            opacity: 0.8;
        }

        .dashboard-cards .card-text {
            font-size: .88rem;
            color: #6c757d;
        }

        .dashboard-cards .card .btn {
            font-size: .9rem;
            padding: .45rem 0;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .dashboard-cards .card .btn:hover {
            transform: translateY(-1px);
        }

        /* Responsivitas yang dioptimasi */
        @media(min-width:768px) {
            .dashboard-cards .card-title {
                font-size: 1.02rem;
            }

            .dashboard-cards .card-text {
                font-size: .95rem;
            }

            .dashboard-cards .card .btn {
                font-size: 1rem;
            }

            .dashboard-cards .card-title small {
                font-size: .82em;
            }
        }

        @media(min-width:1200px) {
            .dashboard-cards .card-title {
                font-size: 1.12rem;
            }

            .dashboard-cards .card-text {
                font-size: 1rem;
            }

            .dashboard-cards .card .btn {
                font-size: 1.03rem;
            }

            .dashboard-cards .card-title small {
                font-size: .88em;
            }
        }

        .dashboard-cards .card-title,
        .dashboard-cards .card-text {
            word-break: break-word;
        }

        /* Animasi fade-in untuk kartu */
        .dashboard-cards .card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .dashboard-cards .card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .dashboard-cards .card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .dashboard-cards .card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .dashboard-cards .card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .dashboard-cards .card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .dashboard-cards .card:nth-child(6) {
            animation-delay: 0.6s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading state untuk filter */
        .filter-loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Placeholder cards styling */
        .dashboard-cards .card.bg-light {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .dashboard-cards .card.bg-light .card-title {
            color: #6c757d;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .dashboard-cards .card-text {
                color: #adb5bd;
            }

            .dashboard-cards .card.bg-light {
                background: linear-gradient(135deg, #343a40 0%, #495057 100%);
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {

            .dashboard-cards .card,
            .dashboard-cards .card:hover,
            .dashboard-cards .card img,
            .dashboard-cards .card .btn {
                transition: none;
                animation: none;
                transform: none;
            }
        }
    </style>

    @php
        $cards = [
            [
                'img' => 'aktivasi.png',
                'title' => 'Aktivasi Seller',
                'count' => $jumlahAktivasi,
                'text' => 'Layanan Aktivasi Seller',
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
                // Extract 'Laporan' card so it can be rendered after placeholders
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

            @foreach ($cards as $card)
                <div class="col">
                    <div class="card shadow-sm h-100" role="button" tabindex="0" aria-label="Akses {{ $card['title'] }}">
                        <div style="height:160px;overflow:hidden;">
                            <img src="{{ asset('assets/images/bg/icon/' . $card['img']) }}" class="w-100 h-100"
                                alt="Ikon {{ $card['title'] }}" loading="lazy" style="object-fit:cover;">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">
                                {{ $card['title'] }}
                                <small class="text-muted">[{{ $card['count'] }}]</small>
                            </h5>
                            <p class="card-text text-muted mb-3">{{ $card['text'] }}</p>

                            {{-- Tombol: hijau hanya untuk "Lihat Data" --}}
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
                        <div style="height:160px;overflow:hidden;">
                            <img src="{{ asset('assets/images/bg/icon/maintenance.png') }}" style="filter:grayscale(1)"
                                class="w-100 h-100" alt="{{ $card['title'] }}" loading="lazy" style="object-fit:cover;">
                        </div>
                        <div class="card-body d-flex flex-column bg-light text-muted">
                            <h5 class="card-title mb-1 text-muted" style="pointer-events: none; opacity: 0.6;">
                                Tidak Ada
                                <small class="text-muted">[0]</small>
                            </h5>
                            <p class="card-text mb-3">Belum tersedia</p>
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
                        <div style="height:160px;overflow:hidden;">
                            <img src="{{ asset('assets/images/bg/icon/' . $laporanCard['img']) }}" class="w-100 h-100"
                                alt="Ikon {{ $laporanCard['title'] }}" loading="lazy" style="object-fit:cover;">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">
                                {{ $laporanCard['title'] }}
                                <small class="text-muted">[{{ $laporanCard['count'] }}]</small>
                            </h5>
                            <p class="card-text text-muted mb-3">{{ $laporanCard['text'] }}</p>

                            {{-- Tombol: hijau hanya untuk "Lihat Data" --}}
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
    <script>
        // Optimasi: Cache DOM elements dan gunakan flag untuk performa
        const inputBulan = document.getElementById("filterBulan");
        const bulanTeks = document.getElementById("bulanTerpilih");
        const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
            "September", "Oktober", "November", "Desember"
        ];
        let isProcessing = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Set default bulan jika kosong
            if (!inputBulan.value) {
                const today = new Date();
                inputBulan.value = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}`;
            }

            // Tampilkan bulan saat ini
            tampilkanBulan(inputBulan.value);

            // Event listener untuk perubahan filter
            inputBulan.addEventListener("change", function() {
                if (isProcessing) return;
                isProcessing = true;

                // Tambahkan loading state
                this.closest('form').classList.add('filter-loading');

                tampilkanBulan(this.value);

                // Submit form dengan delay kecil untuk UX
                setTimeout(() => {
                    this.form.submit();
                }, 300);
            });

            // Tambahkan animasi stagger untuk kartu
            const cards = document.querySelectorAll('.dashboard-cards .card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                }, index * 100);
            });
        });

        function tampilkanBulan(val) {
            if (!val) return;
            const [tahun, bulan] = val.split("-");
            bulanTeks.textContent = `${namaBulan[parseInt(bulan)-1]} ${tahun}`;
        }

        // Fallback untuk loading state
        window.addEventListener('beforeunload', function() {
            isProcessing = false;
        });
    </script>
@endpush
