<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="@yield('meta_description', 'Halaman error aplikasi')" />

    <link rel="icon" href="{{ asset('assets/images/favicon/favicon-32x32.png') }}" type="image/png" />

    <!-- Bootstrap & App CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <title>@yield('title', config('app.name', 'Aplikasi'))</title>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 20px;
        }
    </style>

    @stack('head')
</head>

<body>
    <div class="error-container">
        @yield('content')
    </div>
</body>

</html>
