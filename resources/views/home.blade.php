@extends('layouts.frontend')

@section('title', 'Edulab | Home')

@section('main-content')

@php
    // Banner manual (tidak null, aman)
    $banners = collect([
        (object) ['photo' => asset('assets/img/a.png')],
        (object) ['photo' => asset('assets/img/a.png')],
        (object) ['photo' => asset('assets/img/a.png')],
    ]);
@endphp

{{-- SLIDER --}}
@if($banners->isNotEmpty())
<section id="Gslider" class="carousel slide" data-bs-ride="carousel">

    {{-- Indicator --}}
    <div class="carousel-indicators">
        @foreach($banners as $key => $banner)
            <button type="button"
                    data-bs-target="#Gslider"
                    data-bs-slide-to="{{ $key }}"
                    class="{{ $loop->first ? 'active' : '' }}"
                    aria-current="{{ $loop->first ? 'true' : 'false' }}">
            </button>
        @endforeach
    </div>

    {{-- Images --}}
    <div class="carousel-inner">
        @foreach($banners as $banner)
            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                <img src="{{ $banner->photo }}" alt="Banner">
            </div>
        @endforeach
    </div>

    {{-- Controls --}}
    <button class="carousel-control-prev" type="button" data-bs-target="#Gslider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#Gslider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>

</section>
{{-- LABORATORIUM --}}
<section class="container my-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Laboratorium SMKN 3 Bangkalan</h2>
        <p class="text-muted">Fasilitas praktik unggulan untuk menunjang pembelajaran siswa</p>
    </div>

    <div class="row g-4">
        {{-- Farmasi --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/img/f.jpeg') }}" class="card-img-top" alt="Lab Farmasi">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Laboratorium Farmasi</h5>
                    
                </div>
            </div>
        </div>

        {{-- TKJ --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/img/tkj.jpeg') }}" class="card-img-top" alt="Lab TKJ">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Laboratorium TKJ</h5>
                    
                </div>
            </div>
        </div>

        {{-- Perhotelan --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/img/aph.jpeg') }}" class="card-img-top" alt="Lab Perhotelan">
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold">Laboratorium Perhotelan</h5>
                
                </div>
            </div>
        </div>
    </div>
</section>
{{-- INFORMASI WEBSITE --}}
<section class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h2 class="fw-bold mb-3">Sistem Informasi Manajemen Laboratorium</h2>
                <h5 class="mb-4">SMKN 3 Bangkalan</h5>

                <p class="text-muted">
                    Selamat datang di website resmi Sistem Informasi Manajemen Laboratorium SMKN 3 Bangkalan.
                    Website ini dikembangkan sebagai media informasi dan pengelolaan laboratorium sekolah
                    yang meliputi Laboratorium Farmasi, Teknik Komputer dan Jaringan (TKJ), serta Perhotelan.
                </p>

                <p class="text-muted">
                    Sistem ini bertujuan untuk mendukung kegiatan praktikum, pengelolaan inventaris,
                    penjadwalan penggunaan laboratorium, serta pelaporan kerusakan sarana dan prasarana
                    secara terintegrasi dan terkomputerisasi.
                </p>

                <p class="text-muted">
                    Dengan adanya sistem ini, diharapkan pengelolaan laboratorium di SMKN 3 Bangkalan
                    dapat berjalan lebih efektif, efisien, dan transparan guna menunjang proses
                    pembelajaran siswa sesuai dengan kebutuhan dunia pendidikan dan industri.
                </p>
            </div>
        </div>
    </div>
</section>

@endif

@endsection
<style>
    .card img {
    height: 220px;
    object-fit: cover;
}

.card {
    border-radius: 12px;
}

section h2 {
    letter-spacing: 0.5px;
}

    /* offset navbar fixed */
.main-content {
    padding-top: 90px;
}

/* slider */
#Gslider {
    width: 100%;
}

#Gslider .carousel-item {
    height: 70vh; /* RESPONSIVE */
}

#Gslider img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* safety */
body {
    overflow-x: hidden;
}

</style>