@extends('layouts.main')

@section('title', config('app.name') . ' - Edit Canvasing')

@section('content')
    <div class="col-xl-7 mx-auto">
        <div class="card-title d-flex align-items-center">
            <div class="me-1 font-22 text-danger">
                <i class="fadeIn animated bx bx-edit"></i>
            </div>
            <h6 class="mb-0 text-uppercase">Edit Canvasing</h6>
        </div>
        <hr />
        <div class="card border-top border-0 border-4 border-danger">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('canvasing.update', $item->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="col-12 col-md-6">
                        <label for="kantor" class="form-label">Kantor POS</label>
                        <input type="text" class="form-control" id="kantor" value="{{ $item->kantor }}" disabled>
                    </div>

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
                            <option value="" disabled>
                                -- Pilih Jenis Canvasing --
                            </option>

                            <option value="KURLOG"
                                {{ old('jenis_canvasing', $item->jenis_canvasing) == 'KURLOG' ? 'selected' : '' }}>
                                KURLOG
                            </option>

                            <option value="JASKUG"
                                {{ old('jenis_canvasing', $item->jenis_canvasing) == 'JASKUG' ? 'selected' : '' }}>
                                JASKUG
                            </option>

                            <option value="Canvasing Lainnya"
                                {{ old('jenis_canvasing', $item->jenis_canvasing) == 'Canvasing Lainnya' ? 'selected' : '' }}>
                                Canvasing Lainnya
                            </option>
                        </select>

                        @error('jenis_canvasing')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan Canvasing</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $item->keterangan) }}</textarea>

                        @error('keterangan')
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
                    <div class="col-12">
                        <label for="foto" class="form-label">Tambah Foto Baru <span class="text-danger required-star"
                                style="display: {{ empty($fotoLama) ? 'inline' : 'none' }};">*</span></label>
                        <input type="file" class="form-control" id="foto" name="foto[]" multiple
                            accept="image/*"{{ empty($fotoLama) ? ' required' : '' }}>
                        @if ($errors->has('foto') || $errors->has('foto.*'))
                            @foreach ($errors->get('foto') as $error)
                                <div class="text-danger small mt-1">{{ $error[0] }}</div>
                            @endforeach
                            @foreach ($errors->get('foto.*') as $error)
                                <div class="text-danger small mt-1">{{ $error[0] }}</div>
                            @endforeach
                        @endif
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

        <style>
            /* Optimasi: Minify CSS dan gunakan media queries yang efisien */
            @media (max-width: 576px) {
                .card-body {
                    padding: 1rem !important;
                }

                .form-label {
                    font-size: 1rem;
                }

                .form-control,
                textarea {
                    font-size: 1rem;
                }

                #preview-container>div {
                    margin-bottom: 8px;
                }

                #preview-container {
                    gap: 8px !important;
                }

                .btn {
                    width: 100%;
                }
            }
        </style>
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
    <script>
        // Optimasi: Cache DOM elements dan gunakan isProcessing flag
        let isProcessing = false;
        const checkbox = document.getElementById('gridCheck');
        const submitBtn = document.getElementById('submitBtn');
        const deletedPhotosContainer = document.getElementById('deleted-photos-container');
        const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const fotoInput = document.getElementById('foto');
        const previewContainer = document.getElementById('preview-container');
        let fotoToDelete = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Enable submit kalau checkbox dicentang
            checkbox.addEventListener('change', function() {
                submitBtn.disabled = !this.checked;
            });

            // Klik ikon hapus → buka modal konfirmasi
            document.querySelectorAll('.hapus-foto').forEach(icon => {
                icon.addEventListener('click', function() {
                    if (isProcessing) return;
                    fotoToDelete = this;
                    confirmDeleteModal.show();
                });
            });

            // Saat klik "Hapus" di modal
            confirmDeleteBtn.addEventListener('click', function() {
                if (!fotoToDelete || isProcessing) return;
                isProcessing = true;

                const foto = fotoToDelete.getAttribute('data-foto');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_photos[]';
                input.value = foto;
                deletedPhotosContainer.appendChild(input);

                const wrapper = fotoToDelete.closest('.foto-wrapper');
                wrapper.style.transition = 'opacity 0.3s ease';
                wrapper.style.opacity = '0';
                setTimeout(() => {
                    wrapper.remove();
                    isProcessing = false;

                    // Update required jika tidak ada foto lagi
                    const remainingPhotos = document.querySelectorAll('.foto-wrapper').length;
                    if (remainingPhotos === 0) {
                        fotoInput.required = true;
                        document.querySelector('.required-star').style.display = 'inline';
                    } else {
                        fotoInput.required = false;
                        document.querySelector('.required-star').style.display = 'none';
                    }
                }, 300);

                confirmDeleteModal.hide();
            });

            // Preview foto baru dengan optimasi
            fotoInput.addEventListener('change', function(event) {
                if (isProcessing) return;
                isProcessing = true;

                previewContainer.innerHTML = '';
                const files = Array.from(event.target.files);
                const maxSize = 10 * 1024 * 1024;
                const validFiles = [];
                let loadedCount = 0;

                files.forEach((file, index) => {
                    if (file.size <= maxSize) {
                        validFiles.push(file);
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const wrapper = document.createElement('div');
                            wrapper.classList.add('position-relative');
                            wrapper.style.marginRight = '8px';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Foto ' + (index + 1);
                            img.style.width = '120px';
                            img.style.height = 'auto';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '6px';
                            img.style.boxShadow = '0 0 5px rgba(0,0,0,0.2)';
                            img.loading = 'lazy'; // Optimasi lazy loading

                            const icon = document.createElement('span');
                            icon.innerHTML = '<i class="bx bx-trash"></i>';
                            icon.style.position = 'absolute';
                            icon.style.top = '4px';
                            icon.style.right = '4px';
                            icon.style.cursor = 'pointer';
                            icon.style.fontSize = '20px';
                            icon.style.color = '#e53935';

                            icon.onclick = function() {
                                validFiles.splice(index, 1);
                                const dataTransfer = new DataTransfer();
                                validFiles.forEach(f => dataTransfer.items.add(f));
                                event.target.files = dataTransfer.files;
                                wrapper.remove();
                            };

                            wrapper.appendChild(img);
                            wrapper.appendChild(icon);
                            previewContainer.appendChild(wrapper);

                            loadedCount++;
                            if (loadedCount === validFiles.length) {
                                isProcessing = false;
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert(`File "${file.name}" lebih dari 10MB dan tidak akan dipilih.`);
                        loadedCount++;
                        if (loadedCount === files.length) {
                            isProcessing = false;
                        }
                    }
                });

                const dataTransfer = new DataTransfer();
                validFiles.forEach(f => dataTransfer.items.add(f));
                event.target.files = dataTransfer.files;

                if (validFiles.length === 0) {
                    isProcessing = false;
                }
            });
        });
    </script>
@endpush
