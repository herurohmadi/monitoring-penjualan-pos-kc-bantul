document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const checkbox = document.getElementById('gridCheck');
    const submitBtn = document.getElementById('submitBtn');

    const fotoInput = document.getElementById('foto');
    const previewContainer = document.getElementById('preview-container');
    const deletedPhotosContainer = document.getElementById('deleted-photos-container');

    const modalElement = document.getElementById('confirmDeleteModal');
    const confirmDeleteModal = modalElement
        ? new bootstrap.Modal(modalElement)
        : null;

    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    const cameraTriggerBtn = document.getElementById('cameraTriggerBtn');

    const useCurrentLocationBtn =
        document.getElementById('useCurrentLocationBtn');

    const alamatLengkapInput =
        document.getElementById('alamat_lengkap');

    const locationStatus =
        document.getElementById('location-status');

    const mapContainer =
        document.getElementById('map');


    /*
    |--------------------------------------------------------------------------
    | VARIABLE
    |--------------------------------------------------------------------------
    */

    let fotoToDelete = null;
    let validFiles = [];
    let isFromCamera = false; // Flag penanda asal foto


    /*
    |--------------------------------------------------------------------------
    | LOCATION STATUS
    |--------------------------------------------------------------------------
    */

    function setLocationStatus(message, isError = false) {

        if (!locationStatus) return;

        locationStatus.textContent = message;

        locationStatus.classList.toggle(
            'text-danger',
            isError
        );

        locationStatus.classList.toggle(
            'text-muted',
            !isError
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESET LOCATION BUTTON
    |--------------------------------------------------------------------------
    */

    function resetLocationButton() {

        if (!useCurrentLocationBtn) return;

        useCurrentLocationBtn.disabled = false;

        useCurrentLocationBtn.innerHTML =
            '<i class="bx bx-current-location me-1"></i> Lokasi Saat Ini';
    }


    /*
    |--------------------------------------------------------------------------
    | GET CURRENT LOCATION
    |--------------------------------------------------------------------------
    */

    if (useCurrentLocationBtn && alamatLengkapInput) {

        useCurrentLocationBtn.addEventListener('click', function () {

            if (!navigator.geolocation) {

                setLocationStatus(
                    'Browser Anda tidak mendukung fitur lokasi.',
                    true
                );

                return;
            }

            useCurrentLocationBtn.disabled = true;

            useCurrentLocationBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-1"></span> Mengambil...';

            setLocationStatus(
                'Mengambil lokasi saat ini...'
            );

            navigator.geolocation.getCurrentPosition(

                async function (position) {

                    const latitude =
                        position.coords.latitude;

                    const longitude =
                        position.coords.longitude;

                    const koordinat =
                        `Latitude: ${latitude.toFixed(6)}, Longitude: ${longitude.toFixed(6)}`;

                    if (mapContainer) {
                        mapContainer.style.display = 'block';
                    }

                    try {

                        const response = await fetch(

                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`,

                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'Accept-Language': 'id-ID'
                                }
                            }

                        );

                        if (!response.ok) {

                            throw new Error(
                                'Gagal mengambil alamat'
                            );

                        }

                        const data =
                            await response.json();

                        const alamat =
                            data.display_name ||
                            'Alamat tidak tersedia';

                        alamatLengkapInput.value =
                            `${koordinat} | ${alamat}`;

                        setLocationStatus(

                            `Lokasi berhasil didapatkan (${latitude.toFixed(5)}, ${longitude.toFixed(5)}).`

                        );

                    } catch (error) {

                        alamatLengkapInput.value =
                            `${koordinat} | Alamat tidak dapat ditemukan. Silakan isi alamat manual.`;

                        setLocationStatus(

                            'Lokasi berhasil didapatkan, tetapi alamat lengkap tidak bisa didapatkan. Silakan isi manual.',

                            true

                        );

                        console.error(
                            'Reverse geocoding gagal:',
                            error
                        );

                    } finally {

                        resetLocationButton();

                    }

                },

                function (error) {

                    let message =
                        'Gagal mengambil lokasi.';

                    if (error.code === 1) {

                        message =
                            'Akses lokasi ditolak. Izinkan akses lokasi browser.';

                    } else if (error.code === 2) {

                        message =
                            'Lokasi tidak tersedia saat ini.';

                    } else if (error.code === 3) {

                        message =
                            'Waktu pengambilan lokasi habis.';

                    }

                    setLocationStatus(
                        message,
                        true
                    );

                    resetLocationButton();

                },

                {
                    enableHighAccuracy: true,
                    timeout: 20000,
                    maximumAge: 0
                }

            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | HELPER GEOLOCATION LANGSUNG UNTUK WATERMARK
    |--------------------------------------------------------------------------
    */

    function getCurrentGPSAddress() {
        return new Promise((resolve) => {
            if (!navigator.geolocation) {
                resolve('Lokasi GPS tidak didukung');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const coordsText = `Lat: ${lat.toFixed(6)}, Long: ${lon.toFixed(6)}`;

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`,
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'Accept-Language': 'id-ID'
                                }
                            }
                        );

                        if (response.ok) {
                            const data = await response.json();
                            const address = data.display_name || 'Alamat tidak ditemukan';
                            resolve(`${coordsText} | ${address}`);
                        } else {
                            resolve(`${coordsText} | Alamat tidak ditemukan`);
                        }
                    } catch (err) {
                        resolve(`${coordsText} | Gagal mendapatkan lokasi`);
                    }
                },
                (error) => {
                    resolve('Gagal mengambil akses GPS');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | WATERMARK FOTO VIA CANVAS (MAX LEBAR 50% & FONT KECIL)
    |--------------------------------------------------------------------------
    */

    function getWrappedLines(ctx, text, maxWidth) {
        const words = text.split(' ');
        const lines = [];
        let currentLine = words[0] || '';

        for (let i = 1; i < words.length; i++) {
            const word = words[i];
            const width = ctx.measureText(currentLine + " " + word).width;
            if (width < maxWidth) {
                currentLine += " " + word;
            } else {
                lines.push(currentLine);
                currentLine = word;
            }
        }
        lines.push(currentLine);
        return lines;
    }

    async function addWatermarkToImage(file) {
        // Ambil alamat langsung dari GPS saat proses pembuatan watermark
        const textAddress = await getCurrentGPSAddress();

        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = new Image();
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    canvas.width = img.width;
                    canvas.height = img.height;

                    // Gambar Foto Utama
                    ctx.drawImage(img, 0, 0);

                    // Ambil waktu saat ini
                    const now = new Date();
                    const timeStr = now.toLocaleTimeString('id-ID', { hour12: false });
                    
                    const days = ['Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

                    // Font diperkecil (canvas.height / 50)
                    const baseFontSize = Math.max(12, Math.floor(canvas.height / 50));
                    const padding = Math.floor(baseFontSize * 1.2);
                    let currentY = canvas.height - padding;

                    ctx.textAlign = 'left';
                    ctx.shadowColor = 'rgba(0, 0, 0, 0.8)';
                    ctx.shadowBlur = 3;
                    ctx.fillStyle = '#FFFFFF';

                    // Maksimal Lebar 50% dari Lebar Gambar
                    const maxWidth = canvas.width * 0.5;
                    
                    // 1. Tulis Alamat (Wrap)
                    ctx.font = `${Math.floor(baseFontSize * 0.7)}px sans-serif`;
                    const addressLines = getWrappedLines(ctx, textAddress, maxWidth);
                    
                    for (let i = addressLines.length - 1; i >= 0; i--) {
                        ctx.fillText(addressLines[i], padding, currentY);
                        currentY -= Math.floor(baseFontSize * 0.85);
                    }

                    // 2. Tulis Tanggal
                    ctx.font = `bold ${Math.floor(baseFontSize * 0.85)}px sans-serif`;
                    ctx.fillText(dateStr, padding, currentY);
                    currentY -= Math.floor(baseFontSize * 1.1);

                    // 3. Tulis Jam Besar
                    ctx.font = `bold ${Math.floor(baseFontSize * 1.5)}px sans-serif`;
                    ctx.fillText(timeStr, padding, currentY);

                    // Konversi Canvas ke File Objek
                    canvas.toBlob((blob) => {
                        const watermarkedFile = new File([blob], file.name, {
                            type: file.type || 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(watermarkedFile);
                    }, file.type || 'image/jpeg', 0.92);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | ENABLE SUBMIT BUTTON
    |--------------------------------------------------------------------------
    */

    if (checkbox && submitBtn) {

        checkbox.addEventListener(
            'change',
            () => {

                submitBtn.disabled =
                    !checkbox.checked;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CAMERA TRIGGER (SET FLAG Kamera)
    |--------------------------------------------------------------------------
    */

    if (cameraTriggerBtn && fotoInput) {

        cameraTriggerBtn.addEventListener(
            'click',
            function () {

                isFromCamera = true; // Tandai bahwa klik berasal dari tombol kamera
                fotoInput.click();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE FILE INPUT
    |--------------------------------------------------------------------------
    */

    function updateFileInput() {

        if (!fotoInput) return;

        const dt = new DataTransfer();

        validFiles.forEach(file => {

            dt.items.add(file);

        });

        fotoInput.files = dt.files;

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER PREVIEW FOTO BARU
    |--------------------------------------------------------------------------
    */

    function renderNewPhotoPreview() {

        if (!previewContainer) return;

        previewContainer.innerHTML = '';

        validFiles.forEach(
            (file, index) => {

                const reader = new FileReader();

                reader.onload =
                    function (e) {

                        const wrapper =
                            document.createElement('div');

                        wrapper.className =
                            'position-relative rounded overflow-hidden border shadow-sm foto-wrapper';

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

                        icon.addEventListener(
                            'click',
                            function () {

                                fotoToDelete = {
                                    type: 'new',
                                    index: index,
                                    wrapper: wrapper
                                };

                                if (confirmDeleteModal) {
                                    confirmDeleteModal.show();
                                }

                            }
                        );

                        wrapper.appendChild(img);
                        wrapper.appendChild(icon);
                        previewContainer.appendChild(wrapper);

                    };

                reader.readAsDataURL(file);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | INPUT FOTO BARU
    |--------------------------------------------------------------------------
    */

    if (fotoInput) {

        fotoInput.addEventListener(
            'change',
            async function (event) {

                const files = Array.from(event.target.files);
                const maxSize = 10 * 1024 * 1024;

                validFiles = [];

                if (files.length > 0 && isFromCamera) {
                    setLocationStatus('Memproses GPS & watermark kamera...');
                }

                for (const file of files) {
                    if (file.size <= maxSize) {
                        // Watermark HANYA diberikan jika isFromCamera === true
                        if (isFromCamera) {
                            const watermarkedFile = await addWatermarkToImage(file);
                            validFiles.push(watermarkedFile);
                        } else {
                            validFiles.push(file); // Foto biasa tanpa watermark jika dari pilih file
                        }
                    } else {
                        alert(`File "${file.name}" lebih dari 10MB dan tidak akan dipilih.`);
                    }
                }

                if (files.length > 0) {
                    if (isFromCamera) {
                        setLocationStatus('Foto kamera berhasil diberi watermark lokasi GPS.');
                    } else {
                        setLocationStatus('Foto berhasil dipilih.');
                    }
                }

                // Reset flag kamera kembali ke false
                isFromCamera = false;

                updateFileInput();
                renderNewPhotoPreview();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DELETE EXISTING PHOTO
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll(
        '.hapus-foto'
    ).forEach(icon => {

        icon.addEventListener(
            'click',
            function () {

                fotoToDelete = {
                    type: 'existing',
                    element: icon
                };

                if (confirmDeleteModal) {
                    confirmDeleteModal.show();
                }

            }
        );

    });


    /*
    |--------------------------------------------------------------------------
    | CONFIRM DELETE PHOTO
    |--------------------------------------------------------------------------
    */

    if (confirmDeleteBtn) {

        confirmDeleteBtn.addEventListener(
            'click',
            function () {

                if (!fotoToDelete) return;

                /*
                | DELETE FOTO LAMA
                */
                if (fotoToDelete.type === 'existing') {

                    const icon = fotoToDelete.element;
                    const fotoPath = icon.getAttribute('data-foto');

                    if (deletedPhotosContainer && fotoPath) {

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'deleted_photos[]';
                        input.value = fotoPath;

                        deletedPhotosContainer.appendChild(input);

                    }

                    const wrapper = icon.closest('.foto-wrapper');

                    if (wrapper) {

                        wrapper.style.transition = 'opacity 0.3s ease';
                        wrapper.style.opacity = '0';

                        setTimeout(
                            function () {

                                wrapper.remove();

                                const existingPhotos = document.querySelectorAll('.foto-wrapper').length;

                                if (fotoInput) {
                                    fotoInput.required =
                                        existingPhotos === 0 &&
                                        validFiles.length === 0;
                                }

                                const star = document.querySelector('.required-star');

                                if (star) {
                                    star.style.display =
                                        existingPhotos === 0 &&
                                        validFiles.length === 0;
                                }

                            },
                            300
                        );

                    }

                }

                /*
                | DELETE FOTO BARU
                */
                if (fotoToDelete.type === 'new') {

                    validFiles.splice(fotoToDelete.index, 1);

                    updateFileInput();
                    renderNewPhotoPreview();

                }

                fotoToDelete = null;

                if (confirmDeleteModal) {
                    confirmDeleteModal.hide();
                }

            }
        );

    }

});