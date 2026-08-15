@extends('layouts.main')

@section('title', 'Grafik Aktivitas')

@section('content')
<div class="card shadow-sm border-0 my-4 mx-auto" style="max-width: 98%; border-radius: 12px;">
    <div class="card-body">

        {{-- Header & Filter --}}
        <div class="d-flex flex-wrap align-items-end justify-content-between mb-4 gap-3">
            <h6 class="fw-semibold text-primary m-0">Grafik Aktivitas</h6>

            <form method="GET"
      class="d-flex flex-nowrap align-items-end gap-2"
      style="max-width:100%;">

    <input type="date"
           name="periode_awal"
           class="form-control form-control-sm"
           style="width:120px; flex:0 0 auto;"
           value="{{ request('periode_awal', $periodeAwal) }}">

    <input type="date"
           name="periode_akhir"
           class="form-control form-control-sm"
           style="width:120px; flex:0 0 auto;"
           value="{{ request('periode_akhir', $periodeAkhir) }}">

    <button type="submit"
            class="btn btn-sm btn-primary ms-auto flex-shrink-0">
        Filter
    </button>
</form>

        </div>

        {{-- Grafik --}}
        <div id="grafikTahunan" class="w-100 mb-4" style="height: 400px;"></div>

        {{-- Detail --}}
        <h6 class="fw-semibold mb-2 text-primary">Detail Aktivitas per Hari</h6>

        <div class="table-responsive mb-4">
            <table class="table table-striped table-hover align-middle text-center shadow-sm" id="tableDetail"
                style="border-radius:5px; overflow:hidden;">
                <thead style="background: linear-gradient(90deg,#4e73df,#1cc88a); color:white;">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Tanggal</th>
                        <th>
                            <select id="filterAktivitas" class="form-select form-select-sm mt-1">
                                <option value="all">Semua Aktivitas</option>
                                <option value="aktivasi">Aktivasi Seller</option>
                                <option value="canvasing">Canvasing</option>
                                <option value="kunjungan">Kunjungan</option>
                            </select>
                        </th>
                        <th>
                            <select id="filterKantor" class="form-select form-select-sm mt-1">
                                <option value="all">Semua Kantor</option>
                                @php
                                    $kantorList = [];
                                    foreach ($detailPerHari as $data) {
                                        foreach (['aktivasi', 'canvasing', 'kunjungan'] as $tipe) {
                                            if(!empty($data[$tipe])) {
                                                foreach ($data[$tipe] as $item) {
                                                    $kantorList[] = $item->kantor ?? 'Unknown';
                                                }
                                            }
                                        }
                                    }
                                    $kantorList = array_unique($kantorList);
                                @endphp
                                @foreach ($kantorList as $kantor)
                                    <option value="{{ $kantor }}">{{ $kantor }}</option>
                                @endforeach
                            </select>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp

                    @forelse ($detailPerHari as $tanggal => $data)
                        @foreach (['aktivasi', 'canvasing', 'kunjungan'] as $tipe)
                            @if(!empty($data[$tipe]))
                                @foreach ($data[$tipe] as $item)
                                    <tr data-tipe="{{ $tipe }}" data-kantor="{{ $item->kantor ?? 'Unknown' }}">
                                        <td>{{ $no++ }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                        <td class="text-capitalize">
                                            @if ($tipe === 'kunjungan')
                                                Kunjungan ({{ ucfirst($item->jenis_kunjungan) }})
                                            @elseif ($tipe === 'canvasing')
                                                Canvasing ({{ ucfirst($item->jenis_canvasing ?? 'Umum') }})
                                            @else
                                                Aktivasi Seller
                                            @endif
                                        </td>
                                        <td>{{ $item->kantor ?? 'Unknown' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4">Tidak ada data untuk periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{-- Rekapitulasi --}}
<div class="d-flex flex-wrap gap-3 mt-4">
    @php
        $totalAktivasi = 0;
        $totalCanvasing = 0;
        $totalKunjungan = 0;

        foreach ($detailPerHari as $data) {
            foreach (['aktivasi', 'canvasing', 'kunjungan'] as $tipe) {
                if(!empty($data[$tipe])) {
                    foreach ($data[$tipe] as $item) {
                        if ($tipe === 'aktivasi') $totalAktivasi++;
                        if ($tipe === 'canvasing') $totalCanvasing++;
                        if ($tipe === 'kunjungan') $totalKunjungan++;
                    }
                }
            }
        }
    @endphp

    <div class="card shadow-sm border-0 flex-fill" style="min-width: 200px;">
        <div class="card-body text-center">
            <h6 class="fw-semibold text-primary mb-2">Jumlah Aktivasi Seller</h6>
            <span class="fw-bold fs-4">{{ $totalAktivasi }}</span>
        </div>
    </div>

    <div class="card shadow-sm border-0 flex-fill" style="min-width: 200px;">
        <div class="card-body text-center">
            <h6 class="fw-semibold text-success mb-2">Jumlah Canvasing</h6>
            <span class="fw-bold fs-4">{{ $totalCanvasing }}</span>
        </div>
    </div>

    <div class="card shadow-sm border-0 flex-fill" style="min-width: 200px;">
        <div class="card-body text-center">
            <h6 class="fw-semibold text-danger mb-2">Jumlah Kunjungan</h6>
            <span class="fw-bold fs-4">{{ $totalKunjungan }}</span>
        </div>
    </div>
</div>

        </div>

    </div>
</div>

{{-- Filter Tabel --}}
<script>
    const filterAktivitas = document.getElementById('filterAktivitas');
    const filterKantor = document.getElementById('filterKantor');

    function applyFilters() {
        const aktivitas = filterAktivitas.value;
        const kantor = filterKantor.value;

        document.querySelectorAll('#tableDetail tbody tr').forEach(row => {
            const matchAktivitas = (aktivitas === 'all' || row.dataset.tipe === aktivitas);
            const matchKantor = (kantor === 'all' || row.dataset.kantor === kantor);
            row.style.display = (matchAktivitas && matchKantor) ? '' : 'none';
        });
    }

    filterAktivitas.addEventListener('change', applyFilters);
    filterKantor.addEventListener('change', applyFilters);
</script>

{{-- ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{-- ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    new ApexCharts(document.querySelector("#grafikTahunan"), {
        chart: {
            type: 'area',  // ubah menjadi area chart
            height: 400,
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth' }, // garis smooth untuk area chart
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        series: [
            { name: 'Aktivasi', data: @json($grafik['aktivasi']) },
            { name: 'Canvasing', data: @json($grafik['canvasing']) },
            { name: 'Kunjungan', data: @json($grafik['kunjungan']) }
        ],
        xaxis: { categories: @json($grafik['labels']) },
        yaxis: { title: { text: 'Jumlah Aktivitas' }, min: 0 },
        legend: { position: 'bottom' },
        tooltip: { shared: true, intersect: false }
    }).render();
</script>

@endsection
