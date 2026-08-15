@extends('layouts.error')

@section('title', config('app.name') . ' - Error 403')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">403</h1>
            <h2 class="mb-4">Akses Ditolak</h2>
            <p class="mb-4">Anda tidak memiliki hak akses ke halaman ini. Hubungi administrator jika perlu.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali </a>
        </div>
    </div>

@endsection
