@extends('layouts.main')

@section('title', config('app.name') . ' - Aktivasi Seller')

@section('content')
    <div class="col-xl-7 mx-auto">
        <div class="card-title d-flex align-items-center mb-3">
            <div class="me-1 font-22 text-primary"><i class="fadeIn animated bx bx-user"></i></div>
            <h6 class="mb-0 text-uppercase">Aktivasi Seller</h6>
        </div>
        <hr />
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('aktivasiseller.store') }}"
                    enctype="multipart/form-data">
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


                    {{-- Jenis Aktivasi Seller --}}
                    <div class="col-md-6">
                        <label for="jenis_aktivasi_seller" class="form-label">
                            Jenis Aktivasi Seller
                        </label>

                        <div class="input-group">
                            <select class="form-select" id="jenis_aktivasi_seller" name="jenis_aktivasi_seller">
                                <option value="1" {{ old('jenis_aktivasi_seller', '1') == '1' ? 'selected' : '' }}>
                                    Aktivasi Seller Baru
                                </option>

                                <option value="0" {{ old('jenis_aktivasi_seller') === '0' ? 'selected' : '' }}>
                                    re-Aktivasi Seller
                                </option>
                            </select>
                        </div>

                        @error('jenis_aktivasi_seller')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nama Online Shop --}}
                    <div class="col-md-6">
                        <label for="nama_olshop" class="form-label">Nama Online Shop</label>
                        <input type="text" class="form-control" id="nama_olshop" name="nama_olshop"
                            value="{{ old('nama_olshop') }}" placeholder="Karya Elektronik">
                        @error('nama_olshop')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Link Toko Online --}}
                    <div class="col-md-12">
                        <label for="link_toko" class="form-label">Link Toko Online</label>
                        <input type="text" class="form-control" id="link_toko" name="link_toko"
                            value="{{ old('link_toko') }}" placeholder="https://tokoonline.com/nama-toko">
                        @error('link_toko')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Pemilik --}}
                    <div class="col-12 col-md-6">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik"
                            value="{{ old('nama_pemilik') }}" placeholder="Nama pemilik toko">
                        @error('nama_pemilik')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div class="col-12 col-md-6">
                        <label for="nomor_hp" class="form-label">No. Telepon / WhatsApp</label>
                        <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp"
                            value="{{ old('nomor_hp') }}" placeholder="08xxxxxxxxxx">
                        @error('nomor_hp')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Produk --}}
                    <div class="col-12 col-md-6">
                        <label for="jenis_produk" class="form-label">Jenis Produk</label>
                        <input type="text" class="form-control" id="jenis_produk" name="jenis_produk"
                            value="{{ old('jenis_produk') }}" placeholder="Fashion, Elektronik, Makanan">
                        @error('jenis_produk')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pesaing --}}
                    <div class="col-12 col-md-6">
                        <label for="pesaing" class="form-label">Pesaing</label>
                        <input type="text" class="form-control" id="pesaing" name="pesaing"
                            value="{{ old('pesaing') }}" placeholder="Tokopedia, Toko sebelah">
                        @error('pesaing')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="alamat_lengkap" class="form-label mb-0">Alamat Lengkap</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="useCurrentLocationBtn">
                                <i class="bx bx-current-location me-1"></i> Lokasi Saat Ini
                            </button>
                        </div>
                        <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3"
                            placeholder="Alamat lengkap toko">{{ old('alamat_lengkap') }}</textarea>
                        <small class="form-text text-muted fst-italic">Klik tombol di atas untuk mengisi alamat otomatis
                            dari lokasi saat ini.</small>
                        @error('alamat_lengkap')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan Lainnya --}}
                    <div class="col-12">
                        <label for="keterangan_lainnya" class="form-label">Keterangan Lainnya</label>
                        <textarea class="form-control" id="keterangan_lainnya" name="keterangan_lainnya" rows="3"
                            placeholder="Catatan tambahan, kondisi lapangan, dll">{{ old('keterangan_lainnya') }}</textarea>
                        @error('keterangan_lainnya')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto Aktivasi --}}
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
                            class="bx bx-x me-1"></i> Batal</button>
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
