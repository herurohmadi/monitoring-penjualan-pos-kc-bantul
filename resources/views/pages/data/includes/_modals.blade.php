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
