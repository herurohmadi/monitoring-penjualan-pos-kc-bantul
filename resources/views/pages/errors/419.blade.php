@extends('layouts.error')

@section('title', config('app.name') . ' - Error 419')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">419</h1>
            <h2 class="mb-4">Halaman Kedaluwarsa</h2>
            <p class="mb-4">Sesi Anda telah habis. Silakan muat ulang halaman dan kirim ulang formulir jika perlu.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Login</a>
        </div>
    </div>

@endsection
