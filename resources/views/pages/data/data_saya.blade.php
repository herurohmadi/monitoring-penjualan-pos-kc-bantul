@extends('layouts.main')

@section('title', config('app.name') . ' - Data Saya')

@section('content')

    {{-- ====== STYLE (dipisah) ====== --}}
    <style>
        /* ====== STYLE UMUM MODAL ====== */
        #detailModal .modal-content {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        #detailModal .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 18px;
        }

        #detailModal .modal-title {
            font-weight: 700;
            font-size: 16px;
            color: #313234;
            letter-spacing: 0.5px;
        }

        #detailModal .modal-body {
            padding: 20px;
        }

        /* ====== STYLE TAB ====== */
        #activityTabs {
            border-bottom: 1px solid #dee2e6;
        }

        #activityTabs .nav-link {
            font-weight: 500;
            color: #495057;
            border-radius: 0.5rem 0.5rem 0 0;
            padding: 8px 16px;
            transition: all 0.2s ease-in-out;
        }

        #activityTabs .nav-link.active {
            color: #0d6efd;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-bottom: none;
        }

        #activityTabs .badge {
            font-size: 13px;
            margin-left: 4px;
        }

        /* Modern tabs style */
        #activityTabs .nav-link.active {
            color: #fff !important;
            background-color: #0d6efd !important;
            /* border-radius: 50px; */
            box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
        }

        #activityTabs .badge {
            font-size: 0.75rem;
            padding: 0.25em 0.6em;
        }

        /* ====== STYLE CARD ISI TAB ====== */
        #detailModal .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            border: 1px solid #e9ecef;
            margin-bottom: 1rem;
        }

        #detailModal .card-body {
            padding: 16px;
        }

        /* ====== STYLE FOTO ====== */
        .image-wrapper {
            position: relative;
            display: block;
            width: 100%;
            max-height: 220px;
            overflow: hidden;
            margin-bottom: 8px;
            border-radius: 8px;
        }

        .image-wrapper img {
            width: 100%;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        }

        .preview-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .image-wrapper:hover .preview-button {
            opacity: 1;
        }

        /* ====== STYLE DETAIL DATA (Label: Value) ====== */
        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 4px;
            font-size: 14px;
            line-height: 1.5;
        }

        .detail-label {
            flex: 0 0 180px;
            font-weight: 600;
            color: #333;
            position: relative;
        }

        .detail-label::after {
            content: ":";
            position: absolute;
            right: 5px;
        }

        .detail-value {
            flex: 1;
            color: #555;
            word-break: break-word;
        }

        /* ====== STYLE SECTION ====== */
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #0d6efd;
            margin-top: 18px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 576px) {
            #detailModal .modal-body {
                padding: 14px;
            }

            #activityTabs .nav-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                max-width: 120px;
                padding: 6px 8px;
                font-size: 14px;
            }

            .detail-row {
                flex-direction: column;
                margin-bottom: 8px;
            }

            .detail-label {
                flex: unset;
                margin-bottom: 2px;
            }

            .detail-label::after {
                content: "";
            }

            .detail-value {
                padding-left: 4px;
            }

            .image-wrapper img {
                max-height: 180px;
            }
        }
    </style>


    {{-- ====== FORM FILTER ====== --}}
    {{-- =======================
 FORM FILTER BULAN
======================= --}}
    <form method="GET" action="{{ route('data.saya') }}">
        <div class="mb-3">
            <div class="row align-items-center g-2">
                {{-- Judul --}}
                <div class="col-12 col-md-8">
                    <h6 class="mb-0 text-uppercase fw-bold">DATA BULANAN</h6>
                    <p class="text-secondary mb-1">
                        Rekap Data Laporan Bulan
                        @if ($monthInput)
                            <strong>{{ \Carbon\Carbon::parse($monthInput)->translatedFormat('F Y') }}</strong>
                        @endif
                    </p>
                </div>

                {{-- Filter Bulan & Tipe --}}
                <div class="col-12 col-md-3">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <input type="month" name="month" class="form-control" id="filterBulan"
                            value="{{ $monthInput }}">
                        <select name="tipe" class="form-select" id="filterTipe">
                            <option value="" {{ empty(request('tipe')) ? 'selected' : '' }}>Semua</option>
                            <option value="aktivasi" {{ request('tipe') == 'aktivasi' ? 'selected' : '' }}>Aktivasi Seller
                            </option>
                            <option value="canvasing" {{ request('tipe') == 'canvasing' ? 'selected' : '' }}>Canvasing
                            </option>
                            <option value="kunjungan" {{ request('tipe') == 'kunjungan' ? 'selected' : '' }}>Kunjungan
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Tombol Download --}}
                <div class="col-12 col-md-1">
                    <a href="#" id="downloadBtn" class="btn btn-success w-100">Download</a>
                </div>
            </div>
        </div>
    </form>


    <hr class="mb-4">

    {{-- ====== TABEL DATA ====== --}}
    @php
        use Carbon\Carbon;
        $startDate = Carbon::parse($monthInput . '-01')->startOfMonth();
        $endDate = Carbon::parse($monthInput . '-01')->endOfMonth();
    @endphp

    <div class="card shadow-sm border-0 my-4 mx-auto" style="max-width: 98%; border-radius: 12px;">
        <div
            class="card-header bg-light fw-semibold py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bx bx-calendar me-2 text-primary fs-5"></i>
                <span>Data <b>{{ $startDate->translatedFormat('F Y') }}</b></span>
            </div>

            <!-- Tombol Lihat Grafik -->
            <a href="{{ route('grafik', ['month' => $monthInput]) }}" class="btn btn-sm btn-primary">
                <i class="bx bx-line-chart"></i>Grafik
            </a>
        </div>

        <div class="card-body p-3">
            <div class="table-responsive rounded-3" style="max-height: 75vh; overflow-y: auto;">
                <table class="table table-bordered table-sm align-middle text-center mb-0">
                    @php
                        $hariIndo = [
                            'Mon' => 'Sen',
                            'Tue' => 'Sel',
                            'Wed' => 'Rab',
                            'Thu' => 'Kam',
                            'Fri' => 'Jum',
                            'Sat' => 'Sab',
                            'Sun' => 'Min',
                        ];
                    @endphp

                    <thead class="table-light sticky-top" style="top: 0; z-index: 2;">
                        <tr>
                            <th rowspan="2" class="text-start px-3 py-2 align-middle" style="min-width: 200px;">
                                Nama Kantor
                            </th>

                            @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                @php
                                    $isSunday = $date->isSunday();
                                    $tanggalMerah = [];
                                    $isTanggalMerah = in_array($date->format('Y-m-d'), $tanggalMerah);
                                @endphp
                                <th class="py-2"
                                    @if ($isSunday || $isTanggalMerah) style="color: red; font-weight: 600; background-color: #fff5f5;" @endif>
                                    {{ $date->format('d') }}
                                </th>
                            @endfor
                        </tr>

                        <tr>
                            @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                @php
                                    $isSunday = $date->isSunday();
                                    $tanggalMerah = [];
                                    $isTanggalMerah = in_array($date->format('Y-m-d'), $tanggalMerah);
                                    $hari = $hariIndo[$date->format('D')] ?? $date->format('D');
                                @endphp
                                <th class="py-1 small fw-semibold"
                                    @if ($isSunday || $isTanggalMerah) style="color: red; background-color: #fff5f5;" @endif>
                                    {{ $hari }}
                                </th>
                            @endfor
                        </tr>
                    </thead>


                    <tbody>
                        @php
                            $user = Auth::user();
                            $userId = $user->id ?? null;

                            // jika ID = 1, ambil nama Supervisor (ID 1)
                            if ($userId === 1) {
                                $userKantor = \App\Models\User::find(1)?->name ?? 'Unknown';
                            } else {
                                $userKantor = $user->name ?? 'Guest';
                            }

                            // flag boolean: true hanya jika ID user = 1
                            $isSPV = $userId === 1;
                        @endphp

                        @foreach ($summary as $kantor => $tanggalData)
                            @if ($isSPV || $kantor === $userKantor)
                                <tr>
                                    <td class="text-start fw-semibold bg-light px-3 py-2" style="white-space: nowrap;">
                                        {{ $kantor }}
                                    </td>

                                    @for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay())
                                        @php
                                            $tgl = $date->format('Y-m-d');
                                            $selectedTipe = request('tipe');
                                            $aktivasi = $tanggalData[$tgl]['aktivasi'] ?? 0;
                                            $canvasing = $tanggalData[$tgl]['canvasing'] ?? 0;
                                            $kunjungan = $tanggalData[$tgl]['kunjungan'] ?? 0;

                                            $isSunday = $date->isSunday();
                                            $isTanggalMerah = in_array($tgl, $tanggalMerah);

                                            $showButton = match ($selectedTipe) {
                                                'aktivasi' => $aktivasi,
                                                'canvasing' => $canvasing,
                                                'kunjungan' => $kunjungan,
                                                default => $aktivasi || $canvasing || $kunjungan,
                                            };
                                        @endphp

                                        <td class="py-1"
                                            @if ($isSunday || $isTanggalMerah) style="background-color: #fff5f5; color: red;" @endif>
                                            @if ($showButton)
                                                <button
                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center mx-auto"
                                                    style="gap: 4px;" data-bs-toggle="modal" data-bs-target="#detailModal"
                                                    data-kantor="{{ $kantor }}"
                                                    data-tanggal="{{ $tgl }}">Lihat
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ====== MODAL DETAIL ====== --}}
    <!-- =======================
                                                             MODAL DETAIL AKTIVITAS
                                                             ======================= -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">

                <!-- Header -->
                <div class="modal-header bg-light py-3 px-4 border-0 d-flex align-items-center justify-content-between">
                    <h5 class="modal-title mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-info-circle text-primary fs-4"></i>
                        <span>
                            Detail Aktivitas
                            <span id="kantorName" class="fw-medium"></span>
                        </span>
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body px-3 px-lg-4 pb-4">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" id="activityTabs" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center gap-2" id="tab-aktivasi"
                                data-bs-toggle="tab" data-bs-target="#aktivasi" type="button" role="tab">
                                Aktivasi
                                <span class="badge rounded-pill bg-primary" id="count-aktivasi">0</span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="tab-canvasing" data-bs-toggle="tab"
                                data-bs-target="#canvasing" type="button" role="tab">
                                Canvasing
                                <span class="badge rounded-pill bg-primary" id="count-canvasing">0</span>
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="tab-kunjungan" data-bs-toggle="tab"
                                data-bs-target="#kunjungan" type="button" role="tab">
                                Kunjungan
                                <span class="badge rounded-pill bg-primary" id="count-kunjungan">0</span>
                            </button>
                        </li>

                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content pt-2" id="activityTabContent">

                        <div class="tab-pane fade show active" id="aktivasi" role="tabpanel">
                            <p class="text-muted">Belum ada data Aktivasi.</p>
                        </div>

                        <div class="tab-pane fade" id="canvasing" role="tabpanel">
                            <p class="text-muted">Belum ada data Canvasing.</p>
                        </div>

                        <div class="tab-pane fade" id="kunjungan" role="tabpanel">
                            <p class="text-muted">Belum ada data Kunjungan.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- =======================
                                                             STYLE RESPONSIVE
                                                             ======================= -->
    <style>
        /* =========================
                                                               MOBILE FIRST (DEFAULT)
                                                               ========================= */

        #activityTabs {
            display: flex;
            gap: .25rem;
        }

        #activityTabs .nav-item {
            flex: 1 1 0;
        }

        #activityTabs .nav-link {
            width: 100%;
            justify-content: center;
            text-align: center;
            font-size: .85rem;
            padding: .55rem .4rem;
            white-space: normal;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            transition: background-color .2s;
        }

        #activityTabs .nav-link.active {
            background-color: #e7f1ff;
            font-weight: 600;
        }

        #activityTabs .nav-link:hover {
            background-color: #f1f5f9;
        }

        #activityTabs .badge {
            font-size: .7rem;
            padding: .35em .55em;
        }

        .modal-body p {
            margin-bottom: .75rem;
        }

        /* =========================
                                                               DESKTOP ENHANCEMENT
                                                               ========================= */

        @media (min-width: 992px) {

            #activityTabs {
                gap: .5rem;
            }

            #activityTabs .nav-item {
                flex: 0 0 auto;
            }

            #activityTabs .nav-link {
                width: auto;
                font-size: .95rem;
                padding: .6rem 1rem;
                justify-content: flex-start;
            }

            #activityTabs .badge {
                font-size: .65rem;
            }

            .modal-header h5 {
                font-size: 1.1rem;
            }

            .tab-pane {
                min-height: 300px;
            }
        }
    </style>

    <!-- =======================
                                                             MODAL KONFIRMASI HAPUS
                                                             ======================= -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p id="deleteMessage"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- =======================
                                                             MODAL PREVIEW GAMBAR
                                                             ======================= -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carouselInner"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ====== SCRIPT ====== --}}
    {{-- ===================== STYLE ===================== --}}
    <style>
        /* ===== UMUM ===== */
        .modal-body p {
            margin-bottom: 0.5rem;
            word-break: break-word;
        }

        /* ===== FOTO ===== */
        .foto-item {
            aspect-ratio: 1 / 1;
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
            background: #f8f9fa;
        }

        .foto-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .foto-item button {
            position: absolute;
            bottom: 6px;
            right: 6px;
            font-size: 12px;
            padding: 2px 6px;
        }

        /* ===== DATA ROW ===== */
        .data-row {
            display: grid;
            grid-template-columns: 160px 1fr;
            margin-bottom: 0.4rem;
        }

        .data-row .label {
            font-weight: 600;
        }

        /* ===== ACTION BUTTON - DESKTOP ===== */
        .action-area {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 12 dibagi 4 */
            margin-top: 0.75rem;
        }

        /* 👉 PINDAH KE KOLOM KIRI */
        .action-box {
            grid-column: 1 / 2;
            /* kolom pertama */
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Edit | Hapus */
            gap: 0.5rem;
        }

        .action-box .btn {
            width: 100%;
            font-size: 0.75rem;
            padding: 0.35rem 0;
        }

        /* ===== MOBILE MODE ===== */
        @media (max-width: 576px) {

            .data-row {
                grid-template-columns: 1fr;
            }

            .data-row .label {
                font-size: 0.75rem;
                color: #6c757d;
                margin-bottom: 2px;
            }

            .data-row .value {
                font-size: 0.875rem;
            }

            /* mobile: 12 dibagi 2 */
            .action-area {
                grid-template-columns: 1fr;
            }

            .action-box {
                grid-column: 1 / -1;
                grid-template-columns: 1fr 1fr;
            }

            .action-box .btn {
                font-size: 0.85rem;
                padding: 0.5rem 0;
            }
        }
    </style>
    {{-- download --}}
    <script>
        const downloadBtn = document.getElementById('downloadBtn');
        const filterBulan = document.getElementById('filterBulan');
        const filterTipe = document.getElementById('filterTipe');

        function updateDownloadLink() {
            const month = filterBulan.value;
            const tipe = filterTipe.value;
            let url = "{{ route('laporan.download') }}?month=" + month;
            if (tipe) url += "&tipe=" + tipe;
            downloadBtn.href = url;
        }

        // Inisialisasi awal
        updateDownloadLink();

        // Event perubahan filter
        filterBulan.addEventListener('change', updateDownloadLink);
        filterTipe.addEventListener('change', updateDownloadLink);

        // Submit otomatis saat tipe berubah
        filterTipe.addEventListener('change', function() {
            this.form.submit();
        });

        // Set default bulan ke bulan saat ini jika kosong
        const inputBulan = document.getElementById("filterBulan");
        if (!inputBulan.value) {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            inputBulan.value = `${year}-${month}`;
        }
        inputBulan.addEventListener('change', function() {
            this.form.submit();
        });
    </script>
    {{-- ===================== SWEETALERT ===================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ===================== MAIN SCRIPT ===================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const baseUrl = @json(url('/'));
            const assetBaseUrl = @json(rtrim(asset(''), '/') . '/');
            const detailData = @json($detailData);

            const detailModalEl = document.getElementById('detailModal');
            const imagePreviewModalEl = document.getElementById('imagePreviewModal');
            const carouselEl = document.getElementById('imageCarousel');
            const carouselInner = document.getElementById('carouselInner');

            let galleryCounter = 0;
            const imageGalleries = new Map();
            let detailModalWasVisible = false;

            const capitalize = (value) => {
                if (!value) return '';
                return value.charAt(0).toUpperCase() + value.slice(1);
            };

            const escapeHtml = (value) => {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            /* =========================================================
               URL FOTO
               Foto dari controller disimpan seperti:
               images/aktivasi_seller/nama-file.jpg
               dan file fisiknya berada di folder public/images/...
            ========================================================= */
            const getImageUrl = (path) => {
                if (!path) return '';

                let value = String(path).trim().replace(/\\/g, '/');

                if (/^(https?:)?\/\//i.test(value) || /^data:image\//i.test(value)) {
                    return value;
                }

                value = value.replace(/^\/+/, '');
                value = value.replace(/^public\//i, '');

                // Normalisasi jika database pernah menyimpan public/images/...
                if (value.toLowerCase().startsWith('images/')) {
                    return `${assetBaseUrl}${value}`;
                }

                if (value.toLowerCase().startsWith('storage/')) {
                    return `${assetBaseUrl}${value}`;
                }

                return `${assetBaseUrl}${value}`;
            };

            const getImageCandidates = (path) => {
                if (!path) return [];

                const value = String(path).trim().replace(/\\/g, '/').replace(/^\/+/, '');

                if (/^(https?:)?\/\//i.test(value) || /^data:image\//i.test(value)) {
                    return [value];
                }

                const clean = value.replace(/^public\//i, '');
                const candidates = [
                    getImageUrl(clean),
                    `${baseUrl}/${clean}`,
                    `${window.location.origin}/${clean}`
                ];

                return [...new Set(candidates)];
            };

            const setImageFallback = (img, originalPath) => {
                const candidates = getImageCandidates(originalPath);
                let index = 0;

                img.onerror = function() {
                    index++;
                    if (index < candidates.length) {
                        this.src = candidates[index];
                    } else {
                        this.onerror = null;
                    }
                };

                if (candidates.length) {
                    img.src = candidates[0];
                }
            };

            const parseFoto = (foto) => {
                if (Array.isArray(foto)) {
                    return foto.filter(Boolean);
                }

                if (!foto) {
                    return [];
                }

                try {
                    const parsed = JSON.parse(foto);

                    if (Array.isArray(parsed)) {
                        return parsed.filter(Boolean);
                    }

                    return [];
                } catch (error) {
                    return [];
                }
            };

            const row = (label, value) => `
        <div class="data-row">
            <div class="label">${escapeHtml(label)}</div>
            <div class="value">${value ?? '-'}</div>
        </div>
    `;

            const formatWaktu = (waktu) => {
                if (!waktu) return '-';

                const d = new Date(waktu);

                if (Number.isNaN(d.getTime())) {
                    return escapeHtml(waktu);
                }

                const h = String(d.getHours()).padStart(2, '0');
                const m = String(d.getMinutes()).padStart(2, '0');

                if (window.innerWidth <= 576) {
                    return `${String(d.getDate()).padStart(2, '0')}-${String(d.getMonth() + 1).padStart(2, '0')}-${d.getFullYear()} ${h}:${m}`;
                }

                return d.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) + ` ${h}:${m}`;
            };

            /* =========================================================
               BOOTSTRAP COMPATIBILITY
               Mendukung Bootstrap 5 versi lama yang belum memiliki
               getOrCreateInstance(). Tampilan tidak diubah.
            ========================================================= */
            const getBootstrapModal = (element) => {
                if (!element || !window.bootstrap || !bootstrap.Modal) return null;

                if (typeof bootstrap.Modal.getInstance === 'function') {
                    return bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element);
                }

                return new bootstrap.Modal(element);
            };

            const getBootstrapCarousel = (element, options = {}) => {
                if (!element || !window.bootstrap || !bootstrap.Carousel) return null;

                if (typeof bootstrap.Carousel.getInstance === 'function') {
                    return bootstrap.Carousel.getInstance(element) || new bootstrap.Carousel(element, options);
                }

                return new bootstrap.Carousel(element, options);
            };

            /* =========================================================
               IMAGE PREVIEW / CAROUSEL
            ========================================================= */

            window.openImagePreview = function(images, start = 0) {
                if (!imagePreviewModalEl || !carouselInner || !carouselEl) {
                    return;
                }

                const validImages = Array.isArray(images) ? images.filter(Boolean) : [];
                if (!validImages.length) return;

                let activeIndex = Number(start);
                if (!Number.isInteger(activeIndex) || activeIndex < 0 || activeIndex >= validImages.length) {
                    activeIndex = 0;
                }

                carouselInner.innerHTML = validImages.map((src, index) => `
            <div class="carousel-item ${index === activeIndex ? 'active' : ''}">
                <img
                    src="${escapeHtml(getImageUrl(src))}"
                    class="d-block w-100"
                    style="height:100vh;object-fit:contain"
                    alt="Preview gambar ${index + 1}"
                    draggable="false"
                    data-preview-path="${escapeHtml(src)}"
                >
            </div>
        `).join('');

                // Jika modal detail masih terbuka, sembunyikan sementara agar
                // modal fullscreen preview tidak bertabrakan dengan backdrop Bootstrap.
                detailModalWasVisible = !!(
                    detailModalEl && detailModalEl.classList.contains('show')
                );

                if (detailModalWasVisible) {
                    const detailModal = getBootstrapModal(detailModalEl);
                    if (detailModal) detailModal.hide();
                }

                const modal = getBootstrapModal(imagePreviewModalEl);
                if (!modal) {
                    console.error('Bootstrap Modal tidak tersedia. Pastikan bootstrap.bundle.min.js dimuat.');
                    return;
                }
                modal.show();

                const carousel = getBootstrapCarousel(carouselEl, {
                    interval: false,
                    ride: false,
                    wrap: true,
                    touch: true
                });

                // Pastikan posisi awal sesuai thumbnail yang diklik.
                carousel.to(activeIndex);

                // Fallback jika URL foto pertama gagal dimuat.
                carouselInner.querySelectorAll('img[data-preview-path]').forEach(img => {
                    setImageFallback(img, img.dataset.previewPath);
                });
            };

            const createGallery = (fotoList) => {
                const galleryId = `gallery-${++galleryCounter}`;

                imageGalleries.set(galleryId, fotoList.map(getImageUrl));

                return galleryId;
            };

            /* Fallback thumbnail jika URL pertama gagal */
            document.addEventListener('error', function(event) {
                const img = event.target.closest('img[data-image-path]');
                if (!img || img.dataset.fallbackTried === '1') return;

                img.dataset.fallbackTried = '1';
                setImageFallback(img, img.dataset.imagePath);
            }, true);

            /* Klik gambar / tombol preview */
            document.addEventListener('click', function(event) {
                const trigger = event.target.closest('[data-image-gallery]');

                if (!trigger) {
                    return;
                }

                event.preventDefault();

                const galleryId = trigger.dataset.imageGallery;
                const index = Number(trigger.dataset.imageIndex || 0);
                const images = imageGalleries.get(galleryId) || [];

                openImagePreview(images, index);
            });

            /* Bersihkan isi carousel setelah modal ditutup */
            imagePreviewModalEl?.addEventListener('hidden.bs.modal', function() {
                if (carouselInner) {
                    carouselInner.innerHTML = '';
                }

                if (detailModalWasVisible && detailModalEl) {
                    const detailModal = getBootstrapModal(detailModalEl);
                    if (detailModal) detailModal.show();
                }

                detailModalWasVisible = false;
            });

            /* =========================================================
               RENDER FOTO
            ========================================================= */

            const renderFoto = (fotoList) => {
                if (!fotoList.length) {
                    return `<p class="text-muted"><em>Tidak ada foto</em></p>`;
                }

                const galleryId = createGallery(fotoList);

                return `
            <div class="row mb-3">
                ${fotoList.map((foto, index) => {
                    const imageUrl = getImageUrl(foto);

                    return `
                                                                                <div class="col-lg-3 col-md-4 col-6 mb-3">
                                                                                    <div class="foto-item">
                                                                                        <img
                                                                                            src="${escapeHtml(imageUrl)}"
                                                                                            alt="Foto ${index + 1}"
                                                                                            data-image-gallery="${galleryId}"
                                                                                            data-image-index="${index}"
                                                                                            data-image-path="${escapeHtml(foto)}"
                                                                                            loading="lazy"
                                                                                        >

                                                                                        <button
                                                                                            type="button"
                                                                                            class="btn btn-light btn-sm"
                                                                                            data-image-gallery="${galleryId}"
                                                                                            data-image-index="${index}"
                                                                                            aria-label="Lihat foto"
                                                                                        >🔍</button>
                                                                                    </div>
                                                                                </div>
                                                                            `;
                }).join('')}
            </div>
        `;
            };

            /* =========================================================
           RENDER DATA
        ========================================================= */

            const renderItem = (item, tipe, kantor) => {

                const fotoList = parseFoto(item.foto);
                const fotoHTML = renderFoto(fotoList);

                const id = escapeHtml(item.id);
                const tipeSafe = escapeHtml(tipe);
                const kantorSafe = escapeHtml(kantor);
                const tanggal = formatWaktu(item.tanggal ?? item.created_at);


                /* =====================================================
                   ACTION BUTTONS
                ===================================================== */

                const actionButtons = `
        <div class="action-area">
            <div class="action-box">

                <a
                    href="${baseUrl}/${encodeURIComponent(tipe)}/${encodeURIComponent(item.id)}/edit"
                    class="btn btn-secondary btn-sm"
                >
                    Edit
                </a>

                <button
                    type="button"
                    class="btn btn-danger btn-sm js-hapus-data"
                    data-id="${id}"
                    data-tipe="${tipeSafe}"
                    data-kantor="${kantorSafe}"
                    data-tanggal="${escapeHtml(tanggal)}"
                >
                    Hapus
                </button>

            </div>
        </div>
    `;


                /* =====================================================
                   ALAMAT + GOOGLE MAPS
                ===================================================== */

                const getAlamatGoogleMaps = (alamat) => {

                    if (!alamat || alamat === '-') {
                        return '-';
                    }

                    const alamatText = String(alamat).trim();


                    /*
                     * Contoh format database:
                     *
                     * Latitude: -7.974758, Longitude: 110.299207 |
                     * Tirtomulyo, Kretek, Bantul, DIY
                     */

                    const koordinatMatch = alamatText.match(
                        /Latitude:\s*(-?\d+(?:\.\d+)?),\s*Longitude:\s*(-?\d+(?:\.\d+)?)/i
                    );


                    /*
                     * Jika tidak ada Latitude / Longitude,
                     * tampilkan alamat seperti biasa.
                     */

                    if (!koordinatMatch) {
                        return escapeHtml(alamatText);
                    }


                    const latitude = koordinatMatch[1];
                    const longitude = koordinatMatch[2];


                    /*
                     * Ambil alamat setelah tanda |
                     */

                    const alamatBersih = alamatText
                        .replace(
                            /Latitude:\s*-?\d+(?:\.\d+)?,\s*Longitude:\s*-?\d+(?:\.\d+)?\s*\|\s*/i,
                            ''
                        )
                        .trim();


                    /*
                     * URL Google Maps
                     */

                    const googleMapsUrl =
                        `https://www.google.com/maps/search/?api=1&query=${latitude},${longitude}`;


                    /*
                     * Tampilkan alamat sebagai link Google Maps
                     */

                    return `
            <a
                href="${googleMapsUrl}"
                target="_blank"
                rel="noopener noreferrer"
                class="text-decoration-none"
                title="Buka lokasi di Google Maps"
            >
                ${escapeHtml(alamatBersih || `${latitude}, ${longitude}`)}
            </a>
        `;
                };


                /* =====================================================
                   HEADER CARD
                ===================================================== */

                let html = `
        <div class="card shadow-sm border-0 mb-3">

            <div class="card-body">

                ${fotoHTML}

                ${row(
                    'Aktivitas',
                    tipe === 'aktivasi'
                        ? 'Aktivasi Seller'
                        : escapeHtml(capitalize(tipe))
                )}

                ${row(
                    'Tanggal & Jam',
                    escapeHtml(tanggal)
                )}
    `;


                /* =====================================================
                   AKTIVASI SELLER
                ===================================================== */

                if (tipe === 'aktivasi') {

                    const link = item.link_toko?.trim() || '-';

                    let linkHTML = '-';


                    if (link !== '-') {

                        const fullLink =
                            /^https?:\/\//i.test(link) ?
                            link :
                            `https://${link}`;


                        linkHTML = `
                <a
                    href="${escapeHtml(fullLink)}"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    ${escapeHtml(link)}
                </a>
            `;
                    }


                    const jenisAktivasiSeller =
                        item.jenis_aktivasi_seller === 1 ?
                        'Aktivasi Seller Baru' :
                        item.jenis_aktivasi_seller === 0 ?
                        're-Aktivasi Seller (Aktivasi Ulang)' :
                        (item.jenis_aktivasi_seller ?? '-');


                    html += `

            ${row(
                'Jenis Aktivasi Seller',
                escapeHtml(jenisAktivasiSeller)
            )}

            ${row(
                'Nama Olshops',
                escapeHtml(item.nama_olshop ?? '-')
            )}

            ${row(
                'Nama Pemilik',
                escapeHtml(item.nama_pemilik ?? '-')
            )}

            ${row(
                'Alamat',
                getAlamatGoogleMaps(item.alamat_lengkap)
            )}

            ${row(
                'Nomor HP',
                escapeHtml(item.nomor_hp ?? '-')
            )}

            ${row(
                'Jenis Produk',
                escapeHtml(item.jenis_produk ?? '-')
            )}

            ${row(
                'Pesaing',
                escapeHtml(item.pesaing ?? '-')
            )}

            ${row(
                'Keterangan',
                escapeHtml(item.keterangan_lainnya ?? '-')
            )}

            ${row(
                'Link Toko',
                linkHTML
            )}

            ${actionButtons}

        `;
                }


                /* =====================================================
                   CANVASING
                ===================================================== */

                if (tipe === 'canvasing') {

                    html += `

            ${row(
                'Jenis Canvasing',
                escapeHtml(item.jenis_canvasing ?? '-')
            )}

            ${row(
                'Alamat',
                getAlamatGoogleMaps(item.alamat_canvasing)
            )}

            ${row(
                'Keterangan',
                escapeHtml(item.keterangan ?? '-')
            )}

            ${actionButtons}

        `;
                }


                /* =====================================================
                   KUNJUNGAN
                ===================================================== */

                if (tipe === 'kunjungan') {

                    html += `

            ${row(
                'Jenis Kunjungan',
                escapeHtml(item.jenis_kunjungan ?? '-')
            )}

            ${row(
                'Alamat',
                getAlamatGoogleMaps(item.alamat_kunjungan)
            )}

            ${row(
                'Tujuan Kunjungan',
                escapeHtml(item.tujuan_kunjungan ?? '-')
            )}

            ${row(
                'Hasil Kunjungan',
                escapeHtml(item.hasil_kunjungan ?? '-')
            )}

            ${row(
                'Keterangan',
                escapeHtml(item.keterangan_lainnya ?? '-')
            )}

            ${actionButtons}

        `;
                }


                /* =====================================================
                   CLOSE CARD
                ===================================================== */

                return html + `
            </div>
        </div>
    `;
            };

            /* =========================================================
               MODAL DETAIL
            ========================================================= */

            detailModalEl?.addEventListener('show.bs.modal', function(event) {

                const button = event.relatedTarget;

                if (!button) {
                    return;
                }

                const kantor = button.dataset.kantor;
                const tanggal = button.dataset.tanggal;
                const data = detailData[kantor]?.[tanggal] ?? {};

                ['aktivasi', 'canvasing', 'kunjungan'].forEach((tipe) => {

                    const tabContent = document.getElementById(tipe);
                    const count = document.getElementById(`count-${tipe}`);
                    const items = Array.isArray(data[tipe]) ? data[tipe] : [];

                    if (count) {
                        count.textContent = items.length;
                    }

                    if (!tabContent) {
                        return;
                    }

                    tabContent.innerHTML = items.length ?
                        items.map(item => renderItem(item, tipe, kantor)).join('') :
                        `<p class="text-muted"><em>Belum ada data</em></p>`;
                });
            });

            /* =========================================================
               HAPUS DATA
            ========================================================= */

            window.hapusData = function(id, tipe, kantor, tanggal) {
                Swal.fire({
                    title: 'Yakin hapus?',
                    html: `<b>${escapeHtml(capitalize(tipe))} - ${escapeHtml(kantor)}</b><br>${escapeHtml(tanggal)}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then((result) => {

                    if (!result.isConfirmed) {
                        return;
                    }

                    const form = document.createElement('form');

                    form.method = 'POST';
                    form.action = `${baseUrl}/${encodeURIComponent(tipe)}/${encodeURIComponent(id)}`;

                    form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;

                    document.body.appendChild(form);
                    form.submit();
                });
            };

            /* Tombol hapus menggunakan event delegation */
            document.addEventListener('click', function(event) {

                const button = event.target.closest('.js-hapus-data');

                if (!button) {
                    return;
                }

                event.preventDefault();

                window.hapusData(
                    button.dataset.id,
                    button.dataset.tipe,
                    button.dataset.kantor,
                    button.dataset.tanggal
                );
            });

        });
    </script>

@endsection
