@extends('layouts.main')

@section('title', config('app.name') . ' - Edit Canvasing')

@section('content')
    <div class="col-xl-7 mx-auto">
        {{-- Header --}}
        <div class="card-title d-flex align-items-center">
            <div class="me-1 font-22 text-danger">
                <i class="fadeIn animated bx bx-edit"></i>
            </div>
            <h6 class="mb-0 text-uppercase">Edit Canvasing</h6>
        </div>
        <hr />

        {{-- Form Card --}}
        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('canvasing.update', $item->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Kantor POS --}}
                    <div class="col-12 col-md-6">
                        <label for="kantor" class="form-label">Kantor POS</label>
                        <input type="text" class="form-control" id="kantor" value="{{ $item->kantor }}" disabled>
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-12 col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal"
                            value="{{ old('tanggal', \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d\TH:i')) }}"
                            max="{{ now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') }}"
                            placeholder="Tanggal dan waktu canvasing">
                        @error('tanggal')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Canvasing --}}
                    <div class="col-12">
                        <label for="jenis_canvasing" class="form-label">Jenis Canvasing</label>
                        <select class="form-select" id="jenis_canvasing" name="jenis_canvasing" required>
                            <option value="" disabled>-- Pilih Jenis Canvasing --</option>
                            <option value="KURLOG"
                                {{ old('jenis_canvasing', $item->jenis_canvasing) == 'KURLOG' ? 'selected' : '' }}>KURLOG
                            </option>
                            <option value="JASKUG"
                                {{ old('jenis_canvasing', $item->jenis_canvasing) == 'JASKUG' ? 'selected' : '' }}>JASKUG
                            </option>
                            <option value="Canvasing Lainnya"
                                {{ old('jenis_canvasing', $item->jenis_canvasing) == 'Canvasing Lainnya' ? 'selected' : '' }}>
                                Canvasing Lainnya</option>
                        </select>
                        @error('jenis_canvasing')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan Canvasing</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $item->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto Lama --}}
                    @php
                        $fotoLama = json_decode($item->foto ?? '[]', true);
                    @endphp

                    @if (!empty($fotoLama))
                        <div class="col-12">
                            <label class="form-label">Foto Lama</label>

                            <div class="d-flex flex-wrap gap-2" id="existing-photos">

                                @foreach ($fotoLama as $foto)
                                    <div class="position-relative foto-wrapper"
                                        style="width:120px; height:120px; flex:0 0 auto;">

                                        <img src="{{ asset($foto) }}" loading="lazy" alt="Foto Aktivasi Seller"
                                            style="
                            width:100%;
                            height:100%;
                            object-fit:cover;
                            border-radius:6px;
                            border:1px solid #ddd;
                        ">

                                        <span class="hapus-foto" data-foto="{{ $foto }}"
                                            style="
                            position:absolute;
                            top:4px;
                            right:4px;
                            cursor:pointer;
                            font-size:20px;
                            color:#e53935;
                        "
                                            title="Hapus foto">

                                            <i class="bx bx-trash"></i>

                                        </span>

                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endif

                    {{-- Hidden container untuk foto yang dihapus --}}
                    <div id="deleted-photos-container"></div>

                    {{-- Foto Baru --}}
                    <div class="col-12 mt-2">

                        <label for="foto" class="form-label">
                            Tambah Foto Baru

                            <span class="text-danger required-star"
                                style="display: {{ empty($fotoLama) ? 'inline' : 'none' }};">
                                *
                            </span>
                        </label>

                        <div class="d-flex align-items-center gap-2">

                            {{-- Input foto --}}
                            <input type="file" class="form-control" id="foto" name="foto[]" multiple
                                accept="image/*" capture="environment" {{ empty($fotoLama) ? 'required' : '' }}>

                            {{-- Tombol kamera --}}
                            <button type="button"
                                class="btn btn-outline-primary d-flex align-items-center justify-content-center"
                                id="cameraTriggerBtn" aria-label="Ambil foto dari kamera" title="Ambil foto dari kamera"
                                style="width: 42px; height: 38px; min-width: 42px; padding: 0;">

                                <i class="bx bx-camera fs-4"></i>

                            </button>

                        </div>

                        <small class="form-text text-muted fst-italic">
                            Boleh lebih dari 1 (maks. 10MB / file)
                        </small>

                    </div>

                    {{-- Preview foto baru --}}
                    <div class="d-flex flex-wrap gap-2 mt-2" id="preview-container" style="overflow-x:auto;">
                    </div>

                    {{-- Checkbox Konfirmasi --}}
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">Saya telah memeriksa data dengan benar</label>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between gap-2 mt-3">
                        <button type="submit" class="btn btn-primary px-5" id="submitBtn" disabled>Perbarui</button>
                        <a href="{{ route('canvasing') }}" class="btn btn-secondary px-5">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<!-- Modal Konfirmasi Hapus Foto (Rukada Styled) -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-gradient-danger text-white">
                <h5 class="modal-title d-flex align-items-center" id="confirmDeleteModalLabel">
                    <i class="bx bx-error-circle me-2 fs-4"></i> Konfirmasi Hapus Foto
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bx bx-trash text-danger mb-3" style="font-size: 3rem;"></i>
                <h6 class="fw-bold">Yakin ingin menghapus foto ini?</h6>
                <p class="text-muted small mb-0">Tindakan ini tidak bisa dibatalkan setelah disimpan.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="bx bx-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('assets/js/support-views.js') }}"></script>
@endpush
