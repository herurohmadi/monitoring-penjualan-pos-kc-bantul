@extends('layouts.main')

@section('title', 'Maintenance')

@section('head')
    <!-- Animate.css untuk animasi yang lebih halus -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content')
    <div class="maintenance-page d-flex flex-column align-items-center justify-content-center text-center px-3">
        <div class="maintenance-icon mb-4">
            <img src="{{ asset('images/maintenance/repair.png') }}" alt="Maintenance - Sistem sedang diperbaiki"
                class="img-fluid rounded-circle shadow-lg" style="max-width: 200px; width: 100%;" loading="lazy">
        </div>

        <div class="maintenance-content">
            <h1 class="fw-bold mb-3 text-danger display-4 animate__animated animate__fadeInDown">
                <i class="bx bx-cog bx-spin me-2"></i>Maintenance
            </h1>
            <p class="text-muted mb-4 lead animate__animated animate__fadeIn animate__delay-1s">
                Sistem sedang dalam perbaikan untuk memberikan pengalaman yang lebih baik.
            </p>

            <div class="countdown-container mb-4 animate__animated animate__fadeIn animate__delay-2s">
                <p class="text-secondary mb-2">Anda akan dialihkan secara otomatis dalam:</p>
                <div id="countdown" class="display-1 fw-bold text-primary mb-3" role="timer" aria-live="polite">5</div>

                <div class="progress" style="height: 8px; max-width: 300px; margin: 0 auto;">
                    <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar" style="width: 100%"></div>
                </div>
            </div>

            <p class="text-secondary animate__animated animate__fadeIn animate__delay-3s">
                <small>Mohon tunggu sebentar atau <a href="{{ route('dashboard') }}" class="text-decoration-none">klik di
                        sini</a> untuk melanjutkan.</small>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Optimasi: Cache DOM elements untuk performa
        const countdownEl = document.getElementById('countdown');
        const progressBar = document.getElementById('progress-bar');
        let counter = 5;
        const totalTime = 5;
        let interval;

        document.addEventListener('DOMContentLoaded', function() {
            // Mulai countdown
            startCountdown();

            // Tambahkan animasi fade-in untuk elemen
            const elements = document.querySelectorAll('.animate__animated');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                }, index * 500);
            });
        });

        function startCountdown() {
            interval = setInterval(() => {
                counter--;
                countdownEl.textContent = counter;

                // Update progress bar
                const progress = ((totalTime - counter) / totalTime) * 100;
                progressBar.style.width = progress + '%';

                // Update ARIA untuk aksesibilitas
                countdownEl.setAttribute('aria-valuenow', counter);

                if (counter <= 0) {
                    clearInterval(interval);
                    // Tambahkan efek sebelum redirect
                    document.querySelector('.maintenance-page').style.opacity = '0.5';
                    setTimeout(() => {
                        window.location.href = "{{ route('dashboard') }}";
                    }, 500);
                }
            }, 1000);
        }

        // Fallback jika JavaScript disabled
        window.onload = function() {
            setTimeout(() => {
                window.location.href = "{{ route('dashboard') }}";
            }, 6000); // 6 detik sebagai fallback
        };
    </script>
@endpush

@push('styles')
    <style>
        /* Optimasi: CSS untuk halaman maintenance dengan performa tinggi */
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .maintenance-page {
            height: 100vh;
            width: 100vw;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0;
            box-shadow: none;
            transition: opacity 0.5s ease;
        }

        .maintenance-icon {
            animation: float 3s ease-in-out infinite;
        }

        .maintenance-content {
            max-width: 600px;
            margin: 0 auto;
        }

        #countdown {
            font-size: 4rem;
            animation: pulse 1s infinite, glow 2s ease-in-out infinite alternate;
            text-shadow: 0 0 20px rgba(13, 110, 253, 0.5);
        }

        .progress {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Animasi kustom */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 20px rgba(13, 110, 253, 0.5);
            }

            to {
                text-shadow: 0 0 30px rgba(13, 110, 253, 0.8), 0 0 40px rgba(13, 110, 253, 0.4);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Responsivitas untuk mobile */
        @media (max-width: 768px) {
            #countdown {
                font-size: 3rem;
            }

            .maintenance-content {
                padding: 0 20px;
            }

            .display-4 {
                font-size: 2.5rem;
            }

            .progress {
                max-width: 250px;
            }
        }

        @media (max-width: 576px) {
            #countdown {
                font-size: 2.5rem;
            }

            .maintenance-icon img {
                max-width: 150px;
            }

            .display-4 {
                font-size: 2rem;
            }
        }

        /* Aksesibilitas */
        @media (prefers-reduced-motion: reduce) {

            .maintenance-icon,
            #countdown,
            .progress-bar {
                animation: none;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .maintenance-page {
                background: rgba(33, 37, 41, 0.95);
                color: #fff;
            }

            .text-muted {
                color: #adb5bd !important;
            }

            .text-secondary {
                color: #6c757d !important;
            }
        }
    </style>
@endpush
