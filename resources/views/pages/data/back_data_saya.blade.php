@extends('layouts.main')

@section('title', config('app.name') . ' - Data Saya')

@section('content')

    {{-- ====== STYLE (dipisah) ====== --}}
    @include('pages.data.includes._style')

    {{-- ====== FORM FILTER ====== --}}
    @include('pages.data.includes._filter_form')

    <hr class="mb-4">

    {{-- ====== TABEL DATA ====== --}}
    @include('pages.data.includes._table')

    {{-- ====== MODAL DETAIL ====== --}}
    @include('pages.data.includes._modals')

    {{-- ====== SCRIPT ====== --}}
    @include('pages.data.includes._scripts')

@endsection
