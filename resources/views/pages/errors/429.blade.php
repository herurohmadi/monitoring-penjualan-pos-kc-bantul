@extends('layouts.error')

@section('title', config('app.name') . ' - Error 429')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">429</h1>
            <h2 class="mb-4">Terlalu Banyak Permintaan</h2>
            <p class="mb-4">PAnda telah melebihi batas akses. Silakan tunggu beberapa saat sebelum mencoba kembali.</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
