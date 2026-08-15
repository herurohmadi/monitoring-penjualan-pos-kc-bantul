<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Login ke POS INDONESIA KC BANTUL" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon/favicon-16x16.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('assets/images/favicon/favicon-32x32.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}" />

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />

    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/lock-screen.css') }}" rel="stylesheet" />

    <title>{{ config('app.name') }} - Sign In</title>
</head>

<body class="bg-lock-screen">
    <div class="wrapper">
        <div class="authentication-lock-screen d-flex align-items-center justify-content-center">
            <div class="card shadow-none bg-transparent">
                <div class="card-body p-md-5 text-center">

                    <!-- Jam & Tanggal -->
                    <h2 class="text-white" id="clock"></h2>
                    <h5 class="text-white" id="current-date"></h5>

                    <!-- Logo -->
                    <div>
                        <img src="{{ asset('assets/images/logo/logo-pos.png') }}" loading="lazy" class="mt-5"
                            width="120" alt="Logo" />
                    </div>
                    <p class="mt-2 text-white">POS INDONESIA KC BANTUL</p>

                    <!-- Form Login -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3 mt-3 text-start">
                            <input id="username" type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                placeholder="@error('username') {{ $message }} @else Masukkan Keyword @enderror"
                                value="{{ old('username') }}" />
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-white">Masuk</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- JS Jam & Tanggal -->
    <script src="{{ asset('assets/js/lock-screen.js') }}"></script>
</body>

</html>
