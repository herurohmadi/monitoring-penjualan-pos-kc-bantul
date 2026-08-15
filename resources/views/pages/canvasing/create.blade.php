@extends('layouts.main')

@section('title', config('app.name') . ' - Canvasing')

@section('content')
    <div class="col-xl-7 mx-auto">
        <div class="card-title d-flex align-items-center mb-3">
            <div class="me-1 font-22 text-success"><i class="fadeIn animated bx bx-card"></i></div>
            <h6 class="mb-0 text-uppercase">Canvasing</h6>
        </div>
        <hr />
        <div class="card border-top border-0 border-4 border-success">
            <div class="card-body">
                <form class="row g-3" method="POST" action="{{ route('canvasing.store') }}" enctype="multipart/form-data">
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

                    {{-- Jenis Canvasing --}}
                    <div class="col-12">
                        <label for="jenis_canvasing" class="form-label">Jenis Canvasing</label>
                        <select class="form-select" id="jenis_canvasing" name="jenis_canvasing" required>
                            <option value="" disabled {{ old('jenis_canvasing') ? '' : 'selected' }}>-- Pilih Jenis
                                Canvasing --</option>
                            <option value="KURLOG" {{ old('jenis_canvasing') == 'KURLOG' ? 'selected' : '' }}>KURLOG
                            </option>
                            <option value="JASKUG" {{ old('jenis_canvasing') == 'JASKUG' ? 'selected' : '' }}>JASKUG
                            </option>
                            <option value="Canvasing Lainnya"
                                {{ old('jenis_canvasing') == 'Canvasing Lainnya' ? 'selected' : '' }}>Canvasing Lainnya
                            </option>
                        </select>
                        @error('jenis_canvasing')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Isikan dengan jelas">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Foto Canvasing --}}
                    <div class="col-md-12">
                        <label for="foto" class="form-label">Foto Canvasing</label>
                        <input type="file" class="form-control" id="foto" name="foto[]" multiple accept="image/*"
                            required>
                        <small class="form-text text-muted fst-italic">Boleh lebih dari 1 (maks. 10MB / file)</small>

                        @if ($errors->has('foto') || $errors->has('foto.*'))
                            @foreach ($errors->get('foto') as $error)
                                <div class="text-danger small mt-1">{{ $error[0] ?? $error }}</div>
                            @endforeach
                            @foreach ($errors->get('foto.*') as $error)
                                <div class="text-danger small mt-1">{{ $error[0] ?? $error }}</div>
                            @endforeach
                        @endif
                    </div>

                    {{-- Preview Foto --}}
                    <div class="mt-3 d-flex flex-wrap gap-2" id="preview-container" style="overflow-x:auto;"></div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('gridCheck');
            const submitBtn = document.getElementById('submitBtn');
            const fotoInput = document.getElementById('foto');
            const previewContainer = document.getElementById('preview-container');
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            let fotoToDelete = null;
            let validFiles = [];

            // Enable submit button saat checkbox dicentang
            checkbox.addEventListener('change', () => submitBtn.disabled = !checkbox.checked);

            // Preview Foto
            fotoInput.addEventListener('change', function(event) {
                const files = Array.from(event.target.files);
                const maxSize = 10 * 1024 * 1024;
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
                            'position-relative rounded overflow-hidden border shadow-sm';
                        wrapper.style.width = '120px';
                        wrapper.style.height = '120px';
                        wrapper.style.flex = '0 0 auto';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Foto ' + (index + 1);
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';

                        const icon = document.createElement('span');
                        icon.className =
                            'position-absolute top-0 end-0 p-1 text-danger cursor-pointer';
                        icon.innerHTML = '<i class="bx bx-trash"></i>';
                        icon.onclick = () => {
                            fotoToDelete = {
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

            // Hapus foto dari preview dan input
            confirmDeleteBtn.addEventListener('click', () => {
                if (!fotoToDelete) return;
                const {
                    wrapper,
                    index
                } = fotoToDelete;
                validFiles.splice(index, 1);

                const dt = new DataTransfer();
                validFiles.forEach(f => dt.items.add(f));
                fotoInput.files = dt.files;

                wrapper.remove();

                // Re-render alt text
                Array.from(previewContainer.children).forEach((child, i) => child.querySelector('img').alt =
                    'Foto ' + (i + 1));

                confirmDeleteModal.hide();
                fotoToDelete = null;
            });
        });
    </script>
@endpush
