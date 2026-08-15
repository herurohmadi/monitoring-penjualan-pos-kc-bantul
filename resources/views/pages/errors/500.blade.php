@extends('layouts.error')

@section('title', config('app.name') . ' - Error 500')

@section('content')

    <div class="page-content d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-danger">500</h1>
            <h2 class="mb-4">Kesalahan Server</h2>
            <p class="mb-4">Maaf, terjadi gangguan pada sistem kami. Tim teknis sedang melakukan perbaikan.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

@endsection
