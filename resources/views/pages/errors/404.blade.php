@extends('layouts.error')

@section('title', config('app.name') . ' - Error 404')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">404</h1>
            <h2 class="mb-4">Halaman Tidak Ditemukan</h2>
            <p class="mb-4">Kami tidak dapat menemukan halaman yang Anda maksud.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
