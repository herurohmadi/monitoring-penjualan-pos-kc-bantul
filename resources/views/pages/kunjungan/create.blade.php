@extends('layouts.main')

@section('title', config('app.name') . ' - Kunjungan')

@section('content')
    <div class="col-xl-7 mx-auto">
        <div class="card-title d-flex align-items-center mb-3">
            <div class="me-1 font-22 text-danger"><i class="fadeIn animated bx bx-map"></i></div>
            <h6 class="mb-0 text-uppercase">Kunjungan</h6>
        </div>
        <hr />
        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('kunjungan.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Kantor POS --}}
                    <div class="col-12 col-md-6">
                        <label for="kantor" class="form-label">Kantor POS</label>
                        @php
                            use App\Models\User;

                            $user = Auth::user();
                            $userId = $user->id ?? null;

                            // Ambil semua nama user dari tabel users
                            $kantorList = User::pluck('name')->unique();
                        @endphp

                        @if ($userId === 1)
                            {{-- Supervisor (ID 1) bisa pilih kantor dari dropdown --}}
                            <select name="kantor" id="kantor" class="form-select">
                                <option value="" disabled selected>Pilih Kantor POS</option>
                                @foreach ($kantorList as $kantor)
                                    {{-- Kecualikan name milik user ID 1 sendiri --}}
                                    @if ($kantor !== $user->name)
                                        <option value="{{ $kantor }}">{{ $kantor }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            {{-- User biasa: otomatis pakai nama login --}}
                            @php
                                $kantor = $user->name ?? 'Guest';
                            @endphp
                            <input type="hidden" name="kantor" value="{{ $kantor }}">
                            <input type="text" class="form-control" id="kantor" value="{{ $kantor }}" disabled
                                placeholder="Nama Kantor POS">
                        @endif

                        @error('kantor')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="col-12 col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal"
                            value="{{ old('tanggal', now()->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i')) }}"
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
                            <option value="" disabled {{ old('jenis_kunjungan') ? '' : 'selected' }}>-- Pilih Jenis
                                Kunjungan --</option>
                            <option value="LKN Pensiun" {{ old('jenis_kunjungan') === 'LKN Pensiun' ? 'selected' : '' }}>
                                LKN
                                Pensiun</option>
                            <option value="Kunjungan Lainnya"
                                {{ old('jenis_kunjungan') === 'Kunjungan Lainnya' ? 'selected' : '' }}>Kunjungan Lainnya
                            </option>
                        </select>
                        @error('jenis_kunjungan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tujuan Kunjungan --}}
                    <div class="col-12">
                        <label for="tujuan_kunjungan" class="form-label">Tujuan Kunjungan</label>
                        <textarea class="form-control" id="tujuan_kunjungan" name="tujuan_kunjungan" rows="3"
                            placeholder="Isikan tujuan kunjungan">{{ old('tujuan_kunjungan') }}</textarea>
                        @error('tujuan_kunjungan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hasil Kunjungan --}}
                    <div class="col-12">
                        <label for="hasil_kunjungan" class="form-label">Hasil Kunjungan</label>
                        <textarea class="form-control" id="hasil_kunjungan" name="hasil_kunjungan" rows="3"
                            placeholder="Isikan hasil kunjungan">{{ old('hasil_kunjungan') }}</textarea>
                        @error('hasil_kunjungan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label for="keterangan_lainnya" class="form-label">Keterangan Lainnya</label>
                        <textarea class="form-control" id="keterangan_lainnya" name="keterangan_lainnya" rows="3"
                            placeholder="Isikan keterangan lainnya">{{ old('keterangan_lainnya') }}</textarea>
                        @error('keterangan_lainnya')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="foto" class="form-label">Foto Aktivasi Seller</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="file" class="form-control" id="foto" name="foto[]" multiple
                                accept="image/*" capture="environment" required>
                            <button type="button" class="btn btn-outline-primary me-2" id="cameraTriggerBtn"
                                aria-label="Ambil foto dari kamera" title="Ambil foto dari kamera">
                                <i class="bx bx-camera fs-4 me-1"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted fst-italic">Boleh lebih dari 1 (maks. 10MB / file)</small>

                        @if ($errors->has('foto'))
                            @foreach ($errors->get('foto') as $error)
                                <div class="text-danger small mt-1">{{ $error }}</div>
                            @endforeach
                        @endif
                        @if ($errors->has('foto.*'))
                            @foreach ($errors->get('foto.*') as $error)
                                <div class="text-danger small mt-1">{{ $error[0] ?? $error }}</div>
                            @endforeach
                        @endif
                    </div>

                    {{-- Preview Foto --}}
                    <div class="d-flex overflow-auto gap-2 mt-2" id="preview-container" style="padding-bottom:8px;">
                    </div>

                    {{-- Checkbox --}}
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">Saya telah mengisi data dengan benar</label>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between gap-2 mt-3">
                        <button type="submit" class="btn btn-primary px-5" id="submitBtn" disabled>Simpan</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary px-5">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Hapus Foto --}}
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
                            class="bx bx-x me-1"></i>
                        Batal</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn"><i
                            class="bx bx-trash me-1"></i> Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/support-views.js') }}"></script>
@endpush
