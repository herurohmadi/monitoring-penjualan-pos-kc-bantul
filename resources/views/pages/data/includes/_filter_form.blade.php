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
