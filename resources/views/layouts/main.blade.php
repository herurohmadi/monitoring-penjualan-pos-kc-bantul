<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="@yield('meta_description', 'Deskripsi default aplikasi Anda')" />

    <link rel="icon" href="{{ asset('assets/images/favicon/favicon-32x32.png') }}" type="image/png" />

    <!-- LOADER PACE -->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <!-- Core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="{{ asset('assets/css/dark-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/semi-dark.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/header-colors.css') }}" rel="stylesheet" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <title>@yield('title', config('app.name', 'App'))</title>

    @stack('head')

    <style>
        /* Responsive fix for sidebar */
        @media (max-width: 991px) {
            .nav-container {
                position: fixed;
                top: 0;
                left: -260px;
                width: 240px;
                height: 100%;
                background: #fff;
                transition: .3s;
                z-index: 1050;
            }

            .nav-container.active {
                left: 0;
            }

            .page-wrapper {
                margin-left: 0 !important;
                padding: 1rem;
            }
        }

        @media (max-width: 575px) {
            .page-content {
                padding: 0.5rem;
            }
        }

        /* Floating home button */
        .btn-home-float {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 55px;
            height: 55px;
            background: #0d6efd;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
            z-index: 1100;
            transition: all .3s;
        }

        .btn-home-float:hover {
            background: #0b5ed7;
            transform: scale(1.1);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.35);
        }

        .btn-home-float i {
            font-size: 22px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        @include('partials.header')

        <div class="page-wrapper" style="margin-top: 60px;">
            <div class="page-content">
                @yield('content')
            </div>
        </div>

        <div class="overlay toggle-icon"></div>
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>

        @include('partials.footer')
    </div>

    @if (!Request::routeIs('dashboard'))
        <a href="{{ route('dashboard') }}" class="btn-home-float" title="Kembali ke Home">
            <i class='bx bx-home'></i>
        </a>
    @endif

    @include('partials.scripts')
    @stack('scripts')
</body>

</html>
