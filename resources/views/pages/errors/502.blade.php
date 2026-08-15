@extends('layouts.error')

@section('title', config('app.name') . ' - Error 502')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">502</h1>
            <h2 class="mb-4">Gerbang Rusak</h2>
            <p class="mb-4">Sistem menerima respons yang tidak sesuai. Silakan coba beberapa saat lagi.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
