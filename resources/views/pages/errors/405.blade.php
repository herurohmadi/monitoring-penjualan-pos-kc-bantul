@extends('layouts.error')

@section('title', config('app.name') . ' - Error 405')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">405</h1>
            <h2 class="mb-4">Metode Tidak Diizinkan</h2>
            <p class="mb-4">Metode permintaan tidak sesuai. Silakan gunakan cara akses yang benar.</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
