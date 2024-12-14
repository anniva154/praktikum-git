<!DOCTYPE html>
<html lang="zxx">
<head>
	@include('frontend.layouts.head')	
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="js">
	<!-- Preloader -->
	<div class="preloader">
		<div class="preloader-inner">
			<div class="preloader-icon">
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
	<!-- End Preloader -->

	@include('frontend.layouts.notification')
	<!-- Header -->
	@include('frontend.layouts.header')
	<!--/ End Header -->

	<div class="container-fluid mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            @include('frontend.user.layouts.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            @yield('main-content') <!-- Area untuk konten utama -->
        </div>
    </div>
</div>


	@include('frontend.layouts.footer')
</body>
</html>
