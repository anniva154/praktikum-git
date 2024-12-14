@extends('frontend.layouts.master')
@section('title','Ecommerce Laravel || HOME PAGE')
@section('main-content')
<!-- Slider Area -->
@if(count($banners)>0)
    <section id="Gslider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($banners as $key=>$banner)
        <li data-target="#Gslider" data-slide-to="{{$key}}" class="{{(($key==0)? 'active' : '')}}"></li>
            @endforeach

        </ol>
        <div class="carousel-inner" role="listbox">
                @foreach($banners as $key=>$banner)
                <div class="carousel-item {{(($key==0)? 'active' : '')}}">
                    <img class="first-slide" src="{{$banner->photo}}" alt="First slide">
                    
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#Gslider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#Gslider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
        </a>
    </section>
@endif

<!--/ End Slider Area -->

<section class="small-banner">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Category</h2>
            
            <div class="nav-container d-flex align-items-center">
                <!-- Link View All -->
                <a href="{{ route('all-categories') }}" class="view-all me-3">View All</a>
                <!-- Button Navigasi -->
                <button class="btn-nav btn-prev me-2">&lt;</button>
                <button class="btn-nav btn-next">&gt;</button>
            </div>
        </div>

        <!-- Banner Container -->
        <div class="category-banner-container d-flex overflow-hidden" id="category-carousel">
        @php
            $category_lists = App\Models\Category::where('status', 'active')->limit(25)->get();
        @endphp

            @if($category_lists)
                @foreach($category_lists as $cat)
                    <div class="category-single-banner text-center mx-2">
                        <!-- Menambahkan link kategori dan sub-kategori -->
                        @if($cat->parent_id) <!-- Cek apakah kategori ini adalah sub-kategori -->
                            <a href="{{ route('product-sub-cat', [$cat->parent_info->slug, $cat->slug]) }}">
                        @else
                            <a href="{{ route('product-cat', $cat->slug) }}">
                        @endif
                            @if($cat->photo)
                                <img src="{{ $cat->photo }}" alt="{{ $cat->title }}" class="img-fluid">
                            @else
                                <img src="https://via.placeholder.com/100" alt="No Image" class="img-fluid">
                            @endif
                            <p class="mt-2">{{ $cat->title }}</p>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>



<style>
/* Banner Container khusus kategori */
.category-banner-container {
    display: flex;
    overflow-x: scroll;
    scroll-behavior: smooth; /* Smooth scrolling untuk pengalaman pengguna */
    gap: 1rem; /* Jarak antar elemen */
    padding: 10px 0; /* Padding pada container */
    justify-content: flex-start; /* Menyusun elemen dari kiri */
    align-items: center; /* Menyelaraskan elemen secara vertikal */
}

/* Sembunyikan scrollbar di berbagai browser */
.category-banner-container::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.category-banner-container {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}

/* Elemen Single Banner khusus kategori */
.category-single-banner {
    flex: 0 0 auto; /* Agar elemen tidak menyusut */
    width: 120px; /* Ukuran tetap untuk setiap kategori */
    text-align: center;
    display: flex;
    flex-direction: column; /* Agar gambar dan teks disusun vertikal */
    justify-content: center; /* Menyelaraskan gambar dan teks ke tengah */
    align-items: center; /* Menyelaraskan gambar dan teks ke tengah */
    margin: 0 auto; /* Memastikan elemen tidak terjauhkan ke kiri/kanan */
}

</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const bannerContainer = document.getElementById('category-carousel');
    const prevButton = document.querySelector('.btn-prev');
    const nextButton = document.querySelector('.btn-next');

    const scrollAmount = 200; // Jarak scroll setiap klik tombol

    // Tombol untuk geser ke kiri
    prevButton.addEventListener('click', () => {
        bannerContainer.scrollLeft -= scrollAmount;
    });

    // Tombol untuk geser ke kanan
    nextButton.addEventListener('click', () => {
        bannerContainer.scrollLeft += scrollAmount;
    });
});


</script>
<!-- Start Product Area -->
<div class="product-area section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>New Items</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="product-info">
                    <div class="nav-main">
                        <!-- Tab Nav -->
                        <ul class="nav nav-tabs filter-tope-group" id="myTab" role="tablist">
                            @php
                                $categories = DB::table('categories')->where('status', 'active')->where('status', 'active')->limit(4)->get();
                            @endphp
                            @if($categories)
                                <button class="btn" style="background" data-filter="*">Recently Added</button>
                                @foreach($categories as $cat)
                                    <button class="btn su" style="background:none;color:black;" data-filter=".{{$cat->id}}">
                                        {{$cat->title}}
                                    </button>
                                @endforeach
                            @endif
                        </ul>
                        <!--/ End Tab Nav -->
                    </div>
                    <div class="tab-content isotope-grid" id="myTabContent">
                        @php
                            $recentlyAddedProducts = DB::table('products')
                                ->where('status', 'active')
                                ->orderBy('created_at', 'desc')
                                ->take(8)
                                ->get();
                        @endphp

                        <div class="row">
                            @foreach($recentlyAddedProducts as $product)
                                <div class="col-sm-6 col-md-3 col-lg-2 p-b-35 isotope-item {{$product->cat_id}}">
                                    <div class="single-product" style="background-color: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                        <div class="product-img" style="text-align: center; padding: 10px; position: relative;">
                                            <a href="{{route('product-detail', $product->slug)}}">
                                                @php
                                                    $photos = explode(',', $product->photo);
                                                @endphp
                                                <img src="{{$photos[0] ?? 'path/to/default-image.jpg'}}" alt="Product Image" style="max-width: 100%; height: auto; border-radius: 8px;">
                                                @if($product->stock <= 0)
                                                    <span class="out-of-stock">Sold Out</span>
                                                @elseif($product->condition == 'new')
                                                    <span class="new">New</span>
                                                @elseif($product->condition == 'hot')
                                                    <span class="hot">Hot</span>
                                                @else
                                                    <span class="price-dec">{{$product->discount}}% Off</span>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="product-content" style="text-align: center; margin-top: 10px;">
                                            <h3>
                                                <a href="{{route('product-detail', $product->slug)}}" style="color: #333; text-decoration: none;">{{$product->title}}</a>
                                            </h3>
                                            @php
                                                $after_discount = ($product->price - ($product->price * $product->discount) / 100);
                                            @endphp
                                            <div class="product-price" style="margin-top: 8px; font-size: 16px;">
                                                <span style="color: #000; font-weight: bold;">Rp{{number_format($after_discount, 2)}}</span>
                                                @if($product->discount > 0)
                                                    <del style="color: #aaa; padding-left: 4%;">Rp{{number_format($product->price, 2)}}</del>
                                                @endif
                                            </div>
                                            <div class="product-actions" style="margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                                                
                                                <button class="btn-outline-secondary btn-sm quantity-minus" style="border-radius: 4px; padding: 6px 6px; display: flex; align-items: center; justify-content: center;">-</button>
                                                <span class="quantity-value" style="font-size: 16px;">1</span>
                                                <button class="btn-outline-secondary btn-sm quantity-plus" style="border-radius: 4px; padding: 6px 6px; display: flex; align-items: center; justify-content: center;">+</button>
                                                <button class="btn-primary btn-sm add-to-cart" style="border-radius: 4px; background-color: #007bff; color: white; padding: 6px 8px; font-size: 16px; display: flex; align-items: center; justify-content: center;">
                                                    <a href="{{ route('add-to-cart', $product->slug) }}" style="color: white; text-decoration: none;">
                                                        <i class="fa fa-shopping-cart"></i>
                                                    </a>
                                                </button>
                                                <button class="btn-wishlist" style="border-radius: 4px; background-color: #B73B52; color: white; padding: 10px 8px; font-size: 16px; display: flex; align-items: center; justify-content: center;">
                                                    <a href="{{ route('add-to-wishlist', $product->slug) }}" style="color: white; text-decoration: none;">
                                                        <i class="ti-heart"></i>
                                                    </a>
                                                </button>  
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Start Midium Banner  -->
<section class="midium-banner">
    <div class="container">
        <div class="row">
            @if($featured)
                @foreach($featured as $data)
                    <!-- Single Banner  -->
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="single-banner">
                            @php
                                $photo=explode(',',$data->photo);
                            @endphp
                            <img src="{{$photo[0]}}" alt="{{$photo[0]}}">
                            <div class="content">
                            <p>{{ isset($data->cat_info['title']) ? $data->cat_info['title'] : 'Default Category' }}</p>

                                <h3>{{$data->title}} <br>Up to<span> {{$data->discount}}%</span></h3>
                                <a href="{{route('product-detail',$data->slug)}}">Shop Now</a>
                            </div>
                        </div>
                    </div>
                    <!-- /End Single Banner  -->
                @endforeach
            @endif
        </div>
    </div>
</section>
<!-- End Midium Banner -->
 
<!-- Start Most Popular -->
<div class="product-area most-popular section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Hot Item</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel popular-slider">
                    @foreach($product_lists as $product)
                        @if($product->condition=='hot')
                            <!-- Start Single Product -->
                        <div class="single-product">
                            <div class="product-img">
                                <a href="{{route('product-detail',$product->slug)}}">
                                    @php
                                        $photo=explode(',',$product->photo);
                                    // dd($photo);
                                    @endphp
                                    <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                    <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                </a>
                                <div class="button-head">
                                    <div class="product-action">
                                        <a title="Wishlist" href="{{route('add-to-wishlist',$product->slug)}}" ><i class=" ti-heart "></i><span>Add to Wishlist</span></a>
                                    </div>
                                    
                                    <div class="product-action-2">
                                        <a href="{{route('add-to-cart',$product->slug)}}">Add to cart</a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                <div class="product-price">
                                    <span class="old">Rp{{number_format($product->price,2)}}</span>
                                    @php
                                    $after_discount=($product->price-($product->price*$product->discount)/100)
                                    @endphp
                                    <span>Rp{{number_format($after_discount,2)}}</span>
                                </div>
                            </div>
                        </div>
                        <!-- End Single Product -->
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Most Popular Area -->

<!-- Start Shop Home List  -->
<section class="shop-home-list section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="shop-section-title">
                            <h1>Latest Items</h1>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @php
                        $product_lists=DB::table('products')->where('status','active')->orderBy('id','DESC')->limit(6)->get();
                    @endphp
                    @foreach($product_lists as $product)
                        <div class="col-md-4">
                            <!-- Start Single List  -->
                            <div class="single-list">
                                <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="list-image overlay">
                                        @php
                                            $photo=explode(',',$product->photo);
                                            // dd($photo);
                                        @endphp
                                        <img src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                        <a href="{{route('add-to-cart',$product->slug)}}" class="buy"><i class="fa fa-shopping-bag"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 no-padding">
                                    <div class="content">
                                        <h4 class="title"><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h4>
                                        <p class="price with-discount">{{number_format($product->discount,2)}}% OFF</p>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <!-- End Single List  -->
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Shop Home List  -->

<!-- Start Shop Services Area -->
<section class="shop-services section home">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-rocket"></i>
                    <h4>Free shiping</h4>
                    <p>Orders over $100</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-reload"></i>
                    <h4>Free Return</h4>
                    <p>Within 30 days returns</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-lock"></i>
                    <h4>Sucure Payment</h4>
                    <p>100% secure payment</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-tag"></i>
                    <h4>Best Peice</h4>
                    <p>Guaranteed price</p>
                </div>
                <!-- End Single Service -->
            </div>
        </div>
    </div>
</section>
<!-- End Shop Services Area -->

@include('frontend.layouts.newsletter')


@endsection

@push('styles')
    <style>
        /* Banner Sliding */
        #Gslider .carousel-inner {
        background: #000000;
        color:black;
        }

        #Gslider .carousel-inner{
        height: 380px;
        }
        #Gslider .carousel-inner img{
            width: 100% !important;
            opacity: .8;
        }

        #Gslider .carousel-inner .carousel-caption {
        bottom: 60%;
        }

        #Gslider .carousel-inner .carousel-caption h1 {
        font-size: 50px;
        font-weight: bold;
        line-height: 100%;
        /* color: #F7941D; */
        color: #1e1e1e;
        }

        #Gslider .carousel-inner .carousel-caption p {
        font-size: 18px;
        color: black;
        margin: 28px 0 28px 0;
        }

        #Gslider .carousel-indicators {
        bottom: 70px;
        }
    </style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>

        /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
         function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
    </script>
    <script>
    const bannerContainer = document.querySelector('.banner-container');
    bannerContainer.addEventListener('wheel', (event) => {
        event.preventDefault();
        bannerContainer.scrollLeft += event.deltaY;
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Event untuk tombol minus dan plus
        document.addEventListener("click", function (e) {
            if (e.target.classList.contains("quantity-minus")) {
                const quantityValue = e.target.nextElementSibling;
                let currentValue = parseInt(quantityValue.textContent);
                if (currentValue > 1) {
                    quantityValue.textContent = currentValue - 1;
                }
            } else if (e.target.classList.contains("quantity-plus")) {
                const quantityValue = e.target.previousElementSibling;
                let currentValue = parseInt(quantityValue.textContent);
                quantityValue.textContent = currentValue + 1;
            }
        });

        // Event untuk tombol Add to Cart
        document.addEventListener("click", function (e) {
            if (e.target.closest(".add-to-cart")) {
                const button = e.target.closest(".add-to-cart");
                const productSlug = button.querySelector("a").dataset.slug;  // Ambil slug dari data-slug
                const quantityValue = button.closest(".product-actions").querySelector(".quantity-value");
                const quantity = parseInt(quantityValue.textContent);

                if (productSlug && quantity > 0) {
                    // AJAX Call untuk menambahkan produk ke keranjang
                    fetch(`/add-to-cart/${productSlug}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            quantity: quantity,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mengarahkan pengguna ke halaman produk dan memperbarui jumlah keranjang
                            window.location.href = window.location.href; // Refresh halaman untuk memperbarui jumlah keranjang
                        } else {
                            alert("Gagal menambahkan produk ke keranjang.");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan.");
                    });
                }
            }
        });
    });
</script>
@endpush
