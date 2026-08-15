@extends('layouts.error')

@section('title', config('app.name') . ' - Error 408')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">408</h1>
            <h2 class="mb-4">Waktu Permintaan Habis</h2>
            <p class="mb-4">Permintaan Anda terlalu lama untuk diproses. Silakan coba kembali.</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
