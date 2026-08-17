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
                            `Latitude: ${latitude}, Longitude: ${longitude}`;


                        alamatLengkapInput.value =
                            alamat;


                        setLocationStatus(

                            `Lokasi berhasil didapatkan (${latitude.toFixed(5)}, ${longitude.toFixed(5)}).`

                        );


                    } catch (error) {

                        alamatLengkapInput.value =
                            `Latitude: ${latitude}, Longitude: ${longitude}`;


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

                    }

                    else if (error.code === 2) {

                        message =
                            'Lokasi tidak tersedia saat ini.';

                    }

                    else if (error.code === 3) {

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
    | CAMERA TRIGGER
    |--------------------------------------------------------------------------
    */

    if (cameraTriggerBtn && fotoInput) {

        cameraTriggerBtn.addEventListener(
            'click',
            function () {

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


        const dt =
            new DataTransfer();


        validFiles.forEach(file => {

            dt.items.add(file);

        });


        fotoInput.files =
            dt.files;

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

                const reader =
                    new FileReader();


                reader.onload =
                    function (e) {

                        const wrapper =
                            document.createElement('div');


                        wrapper.className =
                            'position-relative rounded overflow-hidden border shadow-sm foto-wrapper';


                        wrapper.style.width =
                            '120px';

                        wrapper.style.height =
                            '120px';

                        wrapper.style.flex =
                            '0 0 auto';


                        const img =
                            document.createElement('img');


                        img.src =
                            e.target.result;


                        img.alt =
                            'Foto ' + (index + 1);


                        img.style.width =
                            '100%';

                        img.style.height =
                            '100%';

                        img.style.objectFit =
                            'cover';


                        const icon =
                            document.createElement('span');


                        icon.className =
                            'position-absolute top-0 end-0 p-1 text-danger cursor-pointer';


                        icon.innerHTML =
                            '<i class="bx bx-trash"></i>';


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

                        previewContainer.appendChild(
                            wrapper
                        );

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
            function (event) {

                const files =
                    Array.from(
                        event.target.files
                    );


                const maxSize =
                    10 * 1024 * 1024;


                validFiles = [];


                files.forEach(
                    file => {

                        if (
                            file.size <= maxSize
                        ) {

                            validFiles.push(
                                file
                            );

                        } else {

                            alert(

                                `File "${file.name}" lebih dari 10MB dan tidak akan dipilih.`

                            );

                        }

                    }
                );


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
                |------------------------------------------
                | DELETE FOTO LAMA
                |------------------------------------------
                */

                if (
                    fotoToDelete.type ===
                    'existing'
                ) {

                    const icon =
                        fotoToDelete.element;


                    const fotoPath =
                        icon.getAttribute(
                            'data-foto'
                        );


                    if (
                        deletedPhotosContainer &&
                        fotoPath
                    ) {

                        const input =
                            document.createElement(
                                'input'
                            );


                        input.type =
                            'hidden';


                        input.name =
                            'deleted_photos[]';


                        input.value =
                            fotoPath;


                        deletedPhotosContainer.appendChild(
                            input
                        );

                    }


                    const wrapper =
                        icon.closest(
                            '.foto-wrapper'
                        );


                    if (wrapper) {

                        wrapper.style.transition =
                            'opacity 0.3s ease';


                        wrapper.style.opacity =
                            '0';


                        setTimeout(
                            function () {

                                wrapper.remove();


                                /*
                                | Jika semua foto lama
                                | dihapus dan tidak ada
                                | foto baru, foto wajib
                                */

                                const existingPhotos =
                                    document.querySelectorAll(
                                        '.foto-wrapper'
                                    ).length;


                                if (fotoInput) {

                                    fotoInput.required =
                                        existingPhotos === 0 &&
                                        validFiles.length === 0;

                                }


                                const star =
                                    document.querySelector(
                                        '.required-star'
                                    );


                                if (star) {

                                    star.style.display =
                                        existingPhotos === 0 &&
                                        validFiles.length === 0
                                            ? 'inline'
                                            : 'none';

                                }

                            },
                            300
                        );

                    }

                }


                /*
                |------------------------------------------
                | DELETE FOTO BARU
                |------------------------------------------
                */

                if (
                    fotoToDelete.type ===
                    'new'
                ) {

                    validFiles.splice(
                        fotoToDelete.index,
                        1
                    );


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