/* =========================================================
   DASHBOARD JAVASCRIPT
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    /* -----------------------------------------------------
       DOM Elements
       ----------------------------------------------------- */

    const inputBulan = document.getElementById('filterBulan');
    const bulanTeks = document.getElementById('bulanTerpilih');


    /* -----------------------------------------------------
       Nama Bulan
       ----------------------------------------------------- */

    const namaBulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];


    /* -----------------------------------------------------
       Processing Flag
       ----------------------------------------------------- */

    let isProcessing = false;


    /* -----------------------------------------------------
       Cek Element
       ----------------------------------------------------- */

    if (!inputBulan || !bulanTeks) {
        return;
    }


    /* -----------------------------------------------------
       Set Default Bulan
       ----------------------------------------------------- */

    if (!inputBulan.value) {

        const today = new Date();

        inputBulan.value =
            `${today.getFullYear()}-${String(
                today.getMonth() + 1
            ).padStart(2, '0')}`;
    }


    /* -----------------------------------------------------
       Tampilkan Bulan Saat Ini
       ----------------------------------------------------- */

    tampilkanBulan(inputBulan.value);


    /* -----------------------------------------------------
       Event Filter Bulan
       ----------------------------------------------------- */

    inputBulan.addEventListener('change', function () {

        if (isProcessing) {
            return;
        }


        isProcessing = true;


        /* Loading state */

        const form = this.closest('form');

        if (form) {
            form.classList.add('filter-loading');
        }


        /* Update tulisan bulan */

        tampilkanBulan(this.value);


        /* Submit form */

        setTimeout(() => {

            if (this.form) {
                this.form.submit();
            }

        }, 300);

    });


    /* -----------------------------------------------------
       Animasi Dashboard Cards
       ----------------------------------------------------- */

    const cards = document.querySelectorAll(
        '.dashboard-cards .card'
    );


    cards.forEach((card, index) => {

        setTimeout(() => {

            card.style.opacity = '1';

        }, index * 100);

    });


    /* -----------------------------------------------------
       Fungsi Tampilkan Bulan
       ----------------------------------------------------- */

    function tampilkanBulan(val) {

        if (!val) {
            return;
        }


        const parts = val.split('-');


        if (parts.length !== 2) {
            return;
        }


        const tahun = parts[0];
        const bulan = parseInt(parts[1], 10);


        if (
            isNaN(bulan) ||
            bulan < 1 ||
            bulan > 12
        ) {
            return;
        }


        bulanTeks.textContent =
            `${namaBulan[bulan - 1]} ${tahun}`;

    }


    /* -----------------------------------------------------
       Reset Loading State
       ----------------------------------------------------- */

    window.addEventListener('beforeunload', function () {

        isProcessing = false;

    });

});