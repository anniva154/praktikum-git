@extends('frontend.layouts.master')

@section('title','Ecommerce Laravel || About Us')

@section('main-content')

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="blog-single.html">About Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

 <!-- About Us Section -->
 <section class="about-us section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Left Content -->
                <div class="col-lg-6 col-12">
                    <div class="about-content">
                        <h3>Welcome to <span>NnivaMart</span></h3>
                        <p>At NNivamart, we bring you a seamless and enjoyable shopping experience, offering a wide range of quality products for all your needs. Whether you're looking for the latest electronics, trendy fashion items, or home essentials, NNivamart is your trusted online shopping destination.</p>
                        <p>Founded in Surabaya, NNivamart was created with one mission: to provide the best customer service, fast delivery, and a secure online shopping environment.</p>
                        <h4>Our Mission:</h4>
                        <p>To offer an extensive product range at competitive prices, with a focus on customer satisfaction and convenience. We believe in making online shopping easy, reliable, and enjoyable for everyone.</p>
                        <h4>Our Vision:</h4>
                        <p>To be a leading e-commerce platform known for trust, quality, and customer-first approach in Indonesia.</p>
                        <div class="button">
                            <a href="{{ route('contact') }}" class="btn_su">Contact Us</a>
                        </div>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="col-lg-6 col-12">
                    <div class="about-img overlay">
                        <!-- Input Image -->
                        <img src="{{ asset('storage/about.png') }}" alt="NNivamart Store">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Us Section -->

<!-- Start Shop Services Area -->
<section class="shop-services section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="single-service">
                    <i class="ti-rocket"></i>
                    <h4>Free Shipping</h4>
                    <p>Orders over $100</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="single-service">
                    <i class="ti-reload"></i>
                    <h4>Free Return</h4>
                    <p>Within 30 days returns</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="single-service">
                    <i class="ti-lock"></i>
                    <h4>Secure Payment</h4>
                    <p>100% secure payment</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="single-service">
                    <i class="ti-tag"></i>
                    <h4>Best Price</h4>
                    <p>Guaranteed price</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Shop Services Area -->

@include('frontend.layouts.newsletter')

@endsection
