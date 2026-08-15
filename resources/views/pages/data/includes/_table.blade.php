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
                        $userKantor = Auth::user()->name ?? 'Guest';
                        $isSPV = $userKantor === 'SPV Retail';
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
