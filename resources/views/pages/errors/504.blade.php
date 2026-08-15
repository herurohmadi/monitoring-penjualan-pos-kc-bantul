@extends('layouts.error')

@section('title', config('app.name') . ' - Error 504')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">504</h1>
            <h2 class="mb-4">Batas Waktu Gerbang Habis</h2>
            <p class="mb-4">Permintaan tidak mendapat jawaban tepat waktu. Silakan coba kembali nanti.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
