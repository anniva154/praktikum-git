@extends('frontend.layouts.master')

@section('title', 'Ecommerce Laravel || All Categories')

@section('main-content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="#">All Categories</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- All Categories -->
<section class="category-page section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="category-header">
                    <h2 class="text-center">All Categories</h2>
                    <p>Browse through all our product categories below. Click on a category to explore the products!</p>
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $categories = App\Models\Category::getAllCategories();
            @endphp

            @foreach($categories as $cat)
                <div class="col-6 col-md-4 col-lg-3 mb-4 text-center">
                    <a href="{{ $cat->parent_id 
                                ? route('product-sub-cat', [$cat->parent_info->slug, $cat->slug]) 
                                : route('product-cat', $cat->slug) }}" 
                       class="d-block category-item">
                        @if($cat->photo)
                            <img src="{{ $cat->photo }}" alt="{{ $cat->title }}" class="img-fluid mb-2">
                        @else
                            <img src="https://via.placeholder.com/100" alt="No Image" class="img-fluid mb-2">
                        @endif
                        <p class="font-weight-bold text-truncate">{{ $cat->title }}</p>
                    </a>
                </div>
            @endforeach

            @if($categories->isEmpty())
                <div class="col-12 text-center">
                    <p>No categories available at the moment. Please check back later!</p>
                </div>
            @endif
        </div>
    </div>
</section>
<!-- End All Categories -->
@endsection

@push('styles')
<style>
    /* Gaya untuk gambar */
    img {
        max-height: 150px; /* Atur tinggi maksimal gambar */
        object-fit: cover; /* Agar gambar tidak melar */
    }

    /* Jarak antara gambar dan teks */
    .category-item img {
        margin-bottom: 15px; /* Tambahkan jarak bawah untuk gambar */
    }

    /* Teks kategori terpotong jika terlalu panjang */
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Header kategori */
    .category-header h2 {
        font-size: 2rem; /* Atur ukuran font */
        font-weight: bold; /* Tebalkan teks */
        margin-bottom: 15px; /* Tambahkan jarak bawah */
    }

    .category-header p {
        color: #6c757d; /* Warna teks abu-abu */
        margin-bottom: 30px; /* Tambahkan jarak bawah */
    }

    /* Card kategori */
    .category-item {
        text-align: center; /* Ratakan teks di tengah */
        display: block;
        transition: transform 0.3s ease; /* Animasi saat hover */
    }

    /* Hover efek pada kategori */
    .category-item:hover {
        transform: scale(1.05); /* Perbesar sedikit saat hover */
    }
</style>
@endpush
