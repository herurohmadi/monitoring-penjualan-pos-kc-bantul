@extends('layouts.error')

@section('title', config('app.name') . ' - Error 401')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">401</h1>
            <h2 class="mb-4">Tidak Terautentikasi</h2>
            <p class="mb-4">Halaman ini memerlukan autentikasi. Silakan login untuk melanjutkan.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Login</a>
        </div>
    </div>

@endsection
