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
            <div class="card-body p-5">
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
                    <div class="col-6">
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
                    <div class="col-6">
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
                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3">{{ old('alamat_lengkap', $item->alamat_lengkap) }}</textarea>
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
                    @php $fotoLama = json_decode($item->foto ?? '[]', true); @endphp
                    @if (!empty($fotoLama))
                        <div class="col-12">
                            <label class="form-label">Foto Lama</label>
                            <div class="d-flex flex-wrap gap-2" id="existing-photos">
                                @foreach ($fotoLama as $foto)
                                    <div class="position-relative foto-wrapper"
                                        style="width:120px; height:120px; flex:0 0 auto;">
                                        <img src="{{ asset($foto) }}" loading="lazy"
                                            style="width:100%; height:100%; object-fit:cover; border-radius:6px; border:1px solid #ddd;">
                                        <span class="hapus-foto" data-foto="{{ $foto }}"
                                            style="position:absolute; top:4px; right:4px; cursor:pointer; font-size:20px; color:#e53935;">
                                            <i class="bx bx-trash"></i>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    {{-- Hidden container untuk foto dihapus --}}
                    <div id="deleted-photos-container"></div>

                    {{-- Foto Baru --}}
                    <div class="col-12 mt-2">
                        <label for="foto" class="form-label">
                            Tambah Foto Baru
                            <span class="text-danger required-star"
                                style="display: {{ empty($fotoLama) ? 'inline' : 'none' }};">*</span>
                        </label>
                        <input type="file" class="form-control" id="foto" name="foto[]" multiple
                            accept="image/*" {{ empty($fotoLama) ? 'required' : '' }}>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-2" id="preview-container" style="overflow-x:auto;"></div>
                    <div id="deleted-photos-container"></div>


                    <div class="mt-3 d-flex flex-wrap gap-2" id="preview-container" style="overflow-x:auto;"></div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('gridCheck');
            const submitBtn = document.getElementById('submitBtn');
            const fotoInput = document.getElementById('foto');
            const previewContainer = document.getElementById('preview-container');
            const deletedPhotosContainer = document.getElementById('deleted-photos-container');
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            let fotoToDelete = null; // object foto yang akan dihapus
            let validFiles = []; // array foto baru yang dipilih

            // Enable submit button saat checkbox dicentang
            checkbox.addEventListener('change', () => submitBtn.disabled = !checkbox.checked);

            // Fungsi hapus foto (lama maupun baru)
            const deleteFoto = () => {
                if (!fotoToDelete) return;

                if (fotoToDelete.type === 'existing') {
                    const icon = fotoToDelete.element;
                    const fotoPath = icon.getAttribute('data-foto');

                    // Buat input hidden untuk dikirim ke server
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deleted_photos[]';
                    input.value = fotoPath;
                    deletedPhotosContainer.appendChild(input);

                    // animasi fade out wrapper
                    const wrapper = icon.closest('.foto-wrapper');
                    wrapper.style.transition = 'opacity 0.3s ease';
                    wrapper.style.opacity = '0';
                    setTimeout(() => {
                        wrapper.remove();
                        // update required untuk foto baru jika tidak ada foto lama
                        const remaining = document.querySelectorAll('.foto-wrapper').length;
                        fotoInput.required = remaining === 0;
                        const star = document.querySelector('.required-star');
                        if (star) star.style.display = remaining === 0 ? 'inline' : 'none';
                    }, 300);
                }

                if (fotoToDelete.type === 'new') {
                    validFiles.splice(fotoToDelete.index, 1);

                    const dt = new DataTransfer();
                    validFiles.forEach(f => dt.items.add(f));
                    fotoInput.files = dt.files;

                    fotoToDelete.wrapper.remove();

                    // Update alt text preview
                    Array.from(previewContainer.children).forEach((child, i) => {
                        child.querySelector('img').alt = 'Foto ' + (i + 1);
                    });
                }

                fotoToDelete = null;
                confirmDeleteModal.hide();
            };

            confirmDeleteBtn.addEventListener('click', deleteFoto);

            // Preview foto baru
            fotoInput.addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                const maxSize = 10 * 1024 * 1024; // 10MB
                validFiles = [];

                files.forEach(file => {
                    if (file.size <= maxSize) validFiles.push(file);
                    else alert(`File "${file.name}" lebih dari 10MB dan tidak akan dipilih.`);
                });

                previewContainer.innerHTML = '';

                validFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const wrapper = document.createElement('div');
                        wrapper.className =
                            'position-relative rounded overflow-hidden border shadow-sm foto-wrapper';
                        wrapper.style.width = '120px';
                        wrapper.style.height = '120px';
                        wrapper.style.flex = '0 0 auto';
                        wrapper.style.marginRight = '8px';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Foto ' + (index + 1);
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '6px';
                        img.style.border = '1px solid #ddd';

                        const icon = document.createElement('span');
                        icon.className =
                            'position-absolute top-0 end-0 p-1 text-danger cursor-pointer';
                        icon.innerHTML = '<i class="bx bx-trash"></i>';
                        icon.onclick = () => {
                            fotoToDelete = {
                                type: 'new',
                                wrapper,
                                index
                            };
                            confirmDeleteModal.show();
                        };

                        wrapper.appendChild(img);
                        wrapper.appendChild(icon);
                        previewContainer.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                const dt = new DataTransfer();
                validFiles.forEach(f => dt.items.add(f));
                fotoInput.files = dt.files;
            });

            // Hapus foto lama klik icon
            document.querySelectorAll('.hapus-foto').forEach(icon => {
                icon.addEventListener('click', () => {
                    fotoToDelete = {
                        type: 'existing',
                        element: icon
                    };
                    confirmDeleteModal.show();
                });
            });
        });
    </script>
@endpush
