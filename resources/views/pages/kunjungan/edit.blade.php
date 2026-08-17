@extends('layouts.main')

@section('title', config('app.name') . ' - Edit Kunjungan')

@section('content')
    <div class="col-xl-7 mx-auto">

        <div class="card-title d-flex align-items-center">
            <div class="me-1 font-22 text-danger">
                <i class="bx bx-edit"></i>
            </div>
            <h6 class="mb-0 text-uppercase">Edit Kunjungan</h6>
        </div>

        <hr>

        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body">

                <form class="row g-3" method="POST" action="{{ route('kunjungan.update', $item->id) }}"
                    enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    {{-- Kantor --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">Kantor POS</label>
                        <input type="text" class="form-control" value="{{ $item->kantor }}" readonly>
                        <input type="hidden" name="kantor" value="{{ $item->kantor }}">
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

                    {{-- Jenis Kunjungan --}}
                    <div class="col-12">
                        <label for="jenis_kunjungan" class="form-label">Jenis Kunjungan</label>
                        <select class="form-select" id="jenis_kunjungan" name="jenis_kunjungan" required>
                            <option value="" disabled
                                {{ old('jenis_kunjungan', $item->jenis_kunjungan) == '' ? 'selected' : '' }}>
                                -- Pilih Jenis Kunjungan --
                            </option>

                            <option value="LKN Pensiun"
                                {{ old('jenis_kunjungan', $item->jenis_kunjungan) == 'LKN Pensiun' ? 'selected' : '' }}>
                                LKN Pensiun
                            </option>

                            <option value="Lainnya"
                                {{ old('jenis_kunjungan', $item->jenis_kunjungan) == 'Lainnya' ? 'selected' : '' }}>
                                Lainnya
                            </option>
                        </select>

                        @error('jenis_kunjungan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tujuan --}}
                    <div class="col-12">
                        <label for="tujuan_kunjungan" class="form-label">Tujuan Kunjungan</label>
                        <textarea class="form-control" id="tujuan_kunjungan" name="tujuan_kunjungan" rows="3" required>{{ old('tujuan_kunjungan', $item->tujuan_kunjungan) }}</textarea>
                        @error('tujuan_kunjungan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hasil --}}
                    <div class="col-12">
                        <label for="hasil_kunjungan" class="form-label">Hasil Kunjungan</label>
                        <textarea class="form-control" id="hasil_kunjungan" name="hasil_kunjungan" rows="3" required>{{ old('hasil_kunjungan', $item->hasil_kunjungan) }}</textarea>
                        @error('hasil_kunjungan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label for="keterangan_lainnya" class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" id="keterangan_lainnya" name="keterangan_lainnya" rows="3">{{ old('keterangan_lainnya', $item->keterangan_lainnya) }}</textarea>
                    </div>
                    
                    {{-- Foto Lama --}}
                    @php $fotoLama = json_decode($item->foto ?? '[]', true); @endphp
                    @if (!empty($fotoLama))
                        <div class="col-12">
                            <label class="form-label">Foto Lama</label>
                            <div class="d-flex flex-wrap gap-2" id="existing-photos">
                                @foreach ($fotoLama as $foto)
                                    <div class="position-relative foto-wrapper">
                                        <img src="{{ asset($foto) }}" loading="lazy" alt="Foto Lama"
                                            style="width:120px; height:auto; object-fit:cover; border-radius:6px;">
                                        <span class="hapus-foto" data-foto="{{ $foto }}"
                                            style="position:absolute; top:4px; right:4px; cursor:pointer; font-size:20px; color:#e53935;">
                                            <i class="bx bx-trash"></i>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 🟢 Tempatkan input hidden dinamis di sini --}}
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
                            <button type="button" class="btn btn-outline-primary" id="cameraTriggerBtn"
                                aria-label="Ambil foto dari kamera" title="Ambil foto dari kamera">

                                <i class="bx bx-camera fs-4"></i>

                            </button>

                        </div>

                        <small class="form-text text-muted fst-italic">
                            Boleh lebih dari 1 (maks. 10MB / file)
                        </small>

                    </div>

                    <div class="mt-3 d-flex flex-wrap gap-2" id="preview-container" style="overflow-x:auto;"></div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">Saya telah memeriksa data dengan benar</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between gap-2 mt-3">
                        <button type="submit" class="btn btn-primary px-5" id="submitBtn" disabled>Perbarui</button>
                        <a href="{{ route('canvasing') }}" class="btn btn-secondary px-5">Kembali</a>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
            <script>
                document.getElementById('gridCheck').addEventListener('change', function() {
                    document.getElementById('submitBtn').disabled = !this.checked;
                });
            </script>
        @endpush
        {{-- Modal Hapus Foto (sama seperti Canvasing) --}}
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header bg-gradient-danger text-white">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bx bx-error-circle me-2 fs-4"></i> Konfirmasi Hapus Foto
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bx bx-trash text-danger mb-3" style="font-size:3rem;"></i>
                        <h6 class="fw-bold">Yakin ingin menghapus foto ini?</h6>
                        <p class="text-muted small mb-0">Tindakan ini tidak bisa dibatalkan setelah disimpan.</p>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"><i
                                class="bx bx-x me-1"></i> Batal</button>
                        <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn"><i
                                class="bx bx-trash me-1"></i> Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="{{ asset('assets/js/support-views.js') }}"></script>
        @endpush
    @endsection
