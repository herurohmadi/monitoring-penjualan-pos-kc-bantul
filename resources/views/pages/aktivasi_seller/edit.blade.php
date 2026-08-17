@extends('layouts.main')

@section('title', config('app.name') . ' - Edit Aktivasi Seller')

@section('content')
    <div class="col-xl-7 mx-auto">
        <div class="card-title d-flex align-items-center mb-3">
            <div class="me-1 font-22 text-primary"><i class="fadeIn animated bx bx-user"></i></div>
            <h6 class="mb-0 text-uppercase">Edit Aktivasi Seller</h6>
        </div>
        <hr />
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('aktivasiseller.update', $item->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Kantor POS --}}
                    <div class="col-12 col-md-6">
                        <label for="kantor" class="form-label">Kantor POS</label>
                        <input type="hidden" name="kantor" value="{{ $item->kantor }}">
                        <input type="text" class="form-control" id="kantor" value="{{ $item->kantor }}" disabled>
                        @error('kantor')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
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

                    {{-- Jenis Aktivasi Seller --}}
                    <div class="col-12 col-md-6">
                        <label for="jenis_aktivasi_seller" class="form-label">
                            Jenis Aktivasi Seller
                        </label>

                        @php
                            $jenisAktivasi = old('jenis_aktivasi_seller', $item->jenis_aktivasi_seller ? '1' : '0');
                        @endphp

                        <div class="input-group">
                            <select class="form-select" id="jenis_aktivasi_seller" name="jenis_aktivasi_seller">
                                <option value="1" {{ $jenisAktivasi == '1' ? 'selected' : '' }}>
                                    Aktivasi Seller Baru
                                </option>

                                <option value="0" {{ $jenisAktivasi == '0' ? 'selected' : '' }}>
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
                    <div class="col-12 col-md-6">
                        <label for="nama_olshop" class="form-label">Nama Online Shop</label>
                        <input type="text" class="form-control" id="nama_olshop" name="nama_olshop"
                            value="{{ old('nama_olshop', $item->nama_olshop) }}">
                        @error('nama_olshop')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Link Toko --}}
                    <div class="col-12">
                        <label for="link_toko" class="form-label">Link Toko Online</label>
                        <input type="text" class="form-control" id="link_toko" name="link_toko"
                            value="{{ old('link_toko', $item->link_toko) }}">
                        @error('link_toko')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Pemilik --}}
                    <div class="col-12 col-md-6">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik"
                            value="{{ old('nama_pemilik', $item->nama_pemilik) }}">
                        @error('nama_pemilik')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div class="col-12 col-md-6">
                        <label for="nomor_hp" class="form-label">No. Telepon / WhatsApp</label>
                        <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp"
                            value="{{ old('nomor_hp', $item->nomor_hp) }}">
                        @error('nomor_hp')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Produk --}}
                    <div class="col-12 col-md-6">
                        <label for="jenis_produk" class="form-label">Jenis Produk</label>
                        <input type="text" class="form-control" id="jenis_produk" name="jenis_produk"
                            value="{{ old('jenis_produk', $item->jenis_produk) }}">
                        @error('jenis_produk')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pesaing --}}
                    <div class="col-12 col-md-6">
                        <label for="pesaing" class="form-label">Pesaing</label>
                        <input type="text" class="form-control" id="pesaing" name="pesaing"
                            value="{{ old('pesaing', $item->pesaing) }}">
                        @error('pesaing')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="alamat_lengkap" class="form-label mb-0">Alamat Lengkap</label>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="useCurrentLocationBtn">
                                <i class="bx bx-current-location me-1"></i> Lokasi Saat Ini
                            </button>
                        </div>
                        <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3">{{ old('alamat_lengkap', $item->alamat_lengkap) }}</textarea>
                        <small class="form-text text-muted fst-italic">Klik tombol di atas untuk mengisi alamat otomatis
                            dari lokasi saat ini.</small>
                        @error('alamat_lengkap')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan Lainnya --}}
                    <div class="col-12">
                        <label for="keterangan_lainnya" class="form-label">Keterangan Lainnya</label>
                        <textarea class="form-control" id="keterangan_lainnya" name="keterangan_lainnya" rows="3">{{ old('keterangan_lainnya', $item->keterangan_lainnya) }}</textarea>
                        @error('keterangan_lainnya')
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
                            <button type="button" class="btn btn-outline-primary" id="cameraTriggerBtn"
                                aria-label="Ambil foto dari kamera" title="Ambil foto dari kamera">

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

                    {{-- Checkbox konfirmasi --}}
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="gridCheck">
                            <label class="form-check-label" for="gridCheck">Saya telah memeriksa data dengan
                                benar</label>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="col-12 d-flex flex-column flex-md-row justify-content-between gap-2 mt-3">
                        <button type="submit" class="btn btn-primary px-5" id="submitBtn" disabled>Perbarui</button>
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
