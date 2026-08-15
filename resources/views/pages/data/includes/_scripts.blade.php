{{-- ===================== STYLE ===================== --}}
<style>
    /* ===== UMUM ===== */
    .modal-body p {
        margin-bottom: 0.5rem;
        word-break: break-word;
    }

    /* ===== FOTO ===== */
    .foto-item {
        aspect-ratio: 1 / 1;
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
        background: #f8f9fa;
    }

    .foto-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }

    .foto-item button {
        position: absolute;
        bottom: 6px;
        right: 6px;
        font-size: 12px;
        padding: 2px 6px;
    }

    /* ===== DATA ROW ===== */
    .data-row {
        display: grid;
        grid-template-columns: 160px 1fr;
        margin-bottom: 0.4rem;
    }

    .data-row .label {
        font-weight: 600;
    }

    /* ===== ACTION BUTTON - DESKTOP ===== */
    .action-area {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        /* 12 dibagi 4 */
        margin-top: 0.75rem;
    }

    /* 👉 PINDAH KE KOLOM KIRI */
    .action-box {
        grid-column: 1 / 2;
        /* kolom pertama */
        display: grid;
        grid-template-columns: 1fr 1fr;
        /* Edit | Hapus */
        gap: 0.5rem;
    }

    .action-box .btn {
        width: 100%;
        font-size: 0.75rem;
        padding: 0.35rem 0;
    }

    /* ===== MOBILE MODE ===== */
    @media (max-width: 576px) {

        .data-row {
            grid-template-columns: 1fr;
        }

        .data-row .label {
            font-size: 0.75rem;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .data-row .value {
            font-size: 0.875rem;
        }

        /* mobile: 12 dibagi 2 */
        .action-area {
            grid-template-columns: 1fr;
        }

        .action-box {
            grid-column: 1 / -1;
            grid-template-columns: 1fr 1fr;
        }

        .action-box .btn {
            font-size: 0.85rem;
            padding: 0.5rem 0;
        }
    }
</style>
{{-- download --}}
<script>
    const downloadBtn = document.getElementById('downloadBtn');
    const filterBulan = document.getElementById('filterBulan');
    const filterTipe = document.getElementById('filterTipe');

    function updateDownloadLink() {
        const month = filterBulan.value;
        const tipe = filterTipe.value;
        let url = "{{ route('laporan.download') }}?month=" + month;
        if (tipe) url += "&tipe=" + tipe;
        downloadBtn.href = url;
    }

    // Inisialisasi awal
    updateDownloadLink();

    // Event perubahan filter
    filterBulan.addEventListener('change', updateDownloadLink);
    filterTipe.addEventListener('change', updateDownloadLink);

    // Submit otomatis saat tipe berubah
    filterTipe.addEventListener('change', function() {
        this.form.submit();
    });

    // Set default bulan ke bulan saat ini jika kosong
    const inputBulan = document.getElementById("filterBulan");
    if (!inputBulan.value) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        inputBulan.value = `${year}-${month}`;
    }
    inputBulan.addEventListener('change', function() {
        this.form.submit();
    });
</script>
{{-- ===================== SWEETALERT ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ===================== MAIN SCRIPT ===================== --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const baseUrl = "{{ url('/') }}";
        const detailData = @json($detailData);

        const capitalize = s =>
            s ? s.charAt(0).toUpperCase() + s.slice(1) : s;

        const row = (label, value) => `
        <div class="data-row">
            <div class="label">${label}</div>
            <div class="value">${value}</div>
        </div>
    `;

        const formatWaktu = waktu => {
            const d = new Date(waktu);
            const h = String(d.getHours()).padStart(2, '0');
            const m = String(d.getMinutes()).padStart(2, '0');

            if (window.innerWidth <= 576) {
                return `${String(d.getDate()).padStart(2,'0')}-${String(d.getMonth()+1).padStart(2,'0')}-${d.getFullYear()} ${h}:${m}`;
            }

            return d.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }) + ` ${h}:${m}`;
        };

        window.openImagePreview = (images, start = 0) => {
            const inner = document.getElementById('carouselInner');
            inner.innerHTML = images.map((src, i) => `
            <div class="carousel-item ${i===start?'active':''}">
                <img src="${src}" class="d-block w-100" style="height:100vh;object-fit:contain">
            </div>
        `).join('');
            new bootstrap.Modal('#imagePreviewModal').show();
        };

        const renderItem = (item, tipe, kantor) => {

            let fotoList = [];
            try {
                fotoList = JSON.parse(item.foto ?? '[]');
            } catch {}

            const fotoHTML = fotoList.length ?
                fotoList.map((f, i) => `
                <div class="col-lg-3 col-md-4 col-6 mb-3">
                    <div class="foto-item">
                        <img src="${baseUrl}/${f}"
                             onclick="openImagePreview([${fotoList.map(x=>`'${baseUrl}/${x}'`).join(',')}],${i})">
                        <button class="btn btn-light btn-sm"
                            onclick="openImagePreview([${fotoList.map(x=>`'${baseUrl}/${x}'`).join(',')}],${i})">🔍</button>
                    </div>
                </div>
            `).join('') :
                `<p class="text-muted"><em>Tidak ada foto</em></p>`;

            const actionButtons = `
            <div class="action-area">
                <div class="action-box">
                    <a href="${baseUrl}/${tipe}/${item.id}/edit"
                       class="btn btn-secondary btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm"
                        onclick="hapusData(
                            '${item.id}',
                            '${tipe}',
                            '${kantor}',
                            '${formatWaktu(item.tanggal ?? item.created_at)}'
                        )">
                        Hapus
                    </button>
                </div>
            </div>
        `;

            let html = `
        <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            ${fotoList.length ? `<div class="row mb-3">${fotoHTML}</div>` : fotoHTML}
            ${row('Aktivitas', tipe === 'aktivasi' ? 'Aktivasi Seller' : capitalize(tipe))}
            ${row('Tanggal & Jam', formatWaktu(item.tanggal ?? item.created_at))}
`;

            if (tipe === 'aktivasi') {
                const link = item.link_toko?.trim() || '-';
                const full = link.startsWith('http') ? link : `https://${link}`;
                html += `
                ${row('Nama Olshop', item.nama_olshop ?? '-')}
                ${row('Nama Pemilik', item.nama_pemilik ?? '-')}
                ${row('Alamat', item.alamat_lengkap ?? '-')}
                ${row('Nomor HP', item.nomor_hp ?? '-')}
                ${row('Jenis Produk', item.jenis_produk ?? '-')}
                ${row('Pesaing', item.pesaing ?? '-')}
                ${row('Keterangan', item.keterangan_lainnya ?? '-')}
                ${row('Link Toko', link==='-'?'-':`<a href="${full}" target="_blank">${link}</a>`)}
                ${actionButtons}
            `;
            }

            if (tipe === 'canvasing') {
                html += `
                ${row('Jenis Canvasing', item.jenis_canvasing ?? '-')}
                ${row('Keterangan', item.keterangan ?? '-')}
                ${actionButtons}
            `;
            }

            if (tipe === 'kunjungan') {
                html += `
                ${row('Jenis Kunjungan', item.jenis_kunjungan ?? '-')}
                ${row('Tujuan Kunjungan', item.tujuan_kunjungan ?? '-')}
                ${row('Hasil Kunjungan', item.hasil_kunjungan ?? '-')}
                ${row('Keterangan', item.keterangan_lainnya ?? '-')}
                ${actionButtons}
            `;
            }

            return html + `</div></div>`;
        };

        document.getElementById('detailModal')
            .addEventListener('show.bs.modal', e => {

                const btn = e.relatedTarget;
                const kantor = btn.dataset.kantor;
                const tanggal = btn.dataset.tanggal;
                const data = detailData[kantor]?.[tanggal] ?? {};

                ['aktivasi', 'canvasing', 'kunjungan'].forEach(tipe => {
                    const el = document.getElementById(tipe);
                    const items = data[tipe] || [];
                    document.getElementById(`count-${tipe}`).textContent = items.length;
                    el.innerHTML = items.length ?
                        items.map(i => renderItem(i, tipe, kantor)).join('') :
                        `<p class="text-muted"><em>Belum ada data</em></p>`;
                });
            });

        window.hapusData = (id, tipe, kantor, tanggal) => {
            Swal.fire({
                title: 'Yakin hapus?',
                html: `<b>${capitalize(tipe)} - ${kantor}</b><br>${tanggal}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33'
            }).then(r => {
                if (r.isConfirmed) {
                    const f = document.createElement('form');
                    f.method = 'POST';
                    f.action = `${baseUrl}/${tipe}/${id}`;
                    f.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                    document.body.appendChild(f);
                    f.submit();
                }
            });
        };
    });
</script>
