@php
    use App\Http\Helpers\Helper;
@endphp

<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="social-links">
                        <span>Ikuti kami di</span>
                        <a href="#"><i class="fa fa-whatsapp"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-map-marker"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12 text-end">
                    <div class="contact-links">
                        <a href="#" class="contact-link"><i class="fa fa-phone"></i> Hubungi Kami</a>
                        <a href="#" class="contact-link"><i class="fa fa-question-circle"></i> Bantuan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Inner -->
    <div class="middle-inner">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/logo.png') }}" alt="logo" class="logo-img">
                </a>
            </div>

            <!-- Search Bar -->
            <div class="search-bar">
                <form method="POST" action="{{ route('product.search') }}">
                    @csrf
                    <input name="search" placeholder="Cari produk di sini..." type="search">
                    <button class="btnn" type="submit"><i class="ti-search"></i></button>
                </form>
            </div>

            <!-- User Options -->
            <div class="user-options d-flex align-items-center">
                <!-- Wishlist -->
                <div class="sinlge-bar wishlist-bar">
    <a href="{{ route('wishlist') }}" class="icon-link">
        <i class="fa fa-heart-o"></i>
        <span class="badge">{{ Helper::wishlistCount() }}</span>
    </a>
    
    <!-- Wishlist Dropdown -->
    <div class="wishlist-dropdown">
        <ul class="shopping-list">
            @foreach(Helper::getAllProductFromWishlist() as $data)
                @php
                    $photo = explode(',', $data->product['photo']);
                @endphp
                <li>
                    <a href="{{ route('wishlist-delete', $data->id) }}" class="remove" title="Remove this item">
                        <i class="fa fa-remove"></i>
                    </a>
                    <a class="cart-img" href="#">
                        <img src="{{ $photo[0] }}" alt="{{ $data->product['title'] }}">
                    </a>
                    <h4>
                        <a href="{{ route('product-detail', $data->product['slug']) }}" target="_blank">
                            {{ $data->product['title'] }}
                        </a>
                    </h4>
                    <p class="quantity">
                        {{ $data->quantity }} x - 
                        <span class="amount">Rp. {{ number_format($data->price, 0, ',', '.') }}</span>
                    </p>
                </li>
            @endforeach
        </ul>
        <div class="bottom">
            <div class="total">
                <span>Total</span>
                <span class="total-amount">Rp. {{ number_format(Helper::totalWishlistPrice(),0, ',', '.') }}</span>
            </div>
            <a href="{{ route('cart') }}" class="btn animate">Go to Cart</a>
        </div>
    </div>
</div>

<style>
/* Wishlist Dropdown Styling */
.wishlist-bar {
    position: relative;
}

/* Wishlist Dropdown Styling */
.wishlist-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    width: 400px; /* Lebar dropdown diperbesar */
    background: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    z-index: 1000;
    padding: 15px;
    transition: all 0.3s ease;
}


.wishlist-dropdown .shopping-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.wishlist-dropdown .shopping-list li {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 10px;
}

.wishlist-dropdown .shopping-list li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.wishlist-dropdown .cart-img img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 10px;
}

.wishlist-dropdown .remove {
    color: #ff6347;
    margin-right: 10px;
}

.wishlist-dropdown .quantity {
    font-size: 14px;
    color: #555;
}

.wishlist-dropdown .amount {
    font-weight: bold;
    color: #333;
}

/* Total and Button Styling */
.wishlist-dropdown .bottom {
    display: flex;
    justify-content: space-between; /* Sejajarkan tombol dengan total */
    align-items: center;
    margin-top: 10px;
}

.wishlist-dropdown .total {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.wishlist-dropdown .btn {
    background: #004080;
    color: #fff;
    padding: 10px 20px;
    border-radius: 25px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease, color 0.3s ease;
}

.wishlist-dropdown .btn:hover {
    background: #ff6347; /* Merah pada hover */
    color: #fff; /* Tetap putih pada hover */
    border: none; /* Menghilangkan border */
}

/* Show Dropdown on Hover */
.wishlist-bar:hover .wishlist-dropdown {
    display: block;
}
</style>


                <!-- Cart -->
                <div class="sinlge-bar">
                    <a href="{{ route('cart') }}" class="icon-link">
                        <i class="ti-bag"></i>
                        <span class="badge">{{ Helper::cartCount() }}</span>
                    </a>
                </div>

                <!-- User -->
                @auth
                    <a href="{{ Auth::user()->role == 'admin' ? route('admin') : route('user') }}" class="icon-link">
                        <i class="fa fa-user-o"></i>
                    </a>
                @else
                    <a href="{{ route('login.form') }}" class="btn-outline-light">Masuk</a>
                    <a href="{{ route('register.form') }}" class="btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">	
                                    <div class="nav-inner">	
                                        <ul class="nav main-menu menu navbar-nav">
                                            <li class="{{ Request::path() == 'home' ? 'active' : '' }}">
                                                <a href="{{ route('home') }}">Home</a>
                                            </li>
                                            <li class="{{ Request::path() == 'about-us' ? 'active' : '' }}">
                                                <a href="{{ route('about-us') }}">About Us</a>
                                            </li>
                                            <li class="{{ Request::is('product-grids', 'product-lists') ? 'active' : '' }}">
                                                <a href="{{ route('product-grids') }}">Products</a>
                                            </li>
                                            {{ Helper::getHeaderCategory() }}
                                            <li class="{{ Request::path() == 'contact' ? 'active' : '' }}">
                                                <a href="{{ route('contact') }}">Contact</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* General Styling */
.header.shop { background-color: #004080; }
.header .topbar, .header-inner { color: #fff; }
.header .logo-img { width: 180px; }

/* Topbar */
.topbar {
    background-color: #004080;
    color: #fff;
    padding: 10px 15px;
    font-size: 14px;
}

.topbar .social-links a {
    color: #fff;
    margin-left: 10px;
    transition: color 0.3s ease-in-out;
}

.topbar .social-links a:hover {
    color: #ff6347;
}

.topbar .contact-links {
    text-align: right;
}

.topbar .contact-links a {
    color: #fff;
    margin-left: 15px;
    transition: color 0.3s ease-in-out;
}

.topbar .contact-links a:hover {
    color: #ff6347;
}

/* Header Inner */
.header-inner { box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
.header-inner .menu-area { background: none; }
.header-inner .nav-link { color: #fff; text-transform: uppercase; margin: 0 15px; transition: color 0.3s; }
.header-inner .nav-link:hover, .nav .active a { color: #ff6347; font-weight: bold; }

/* Search Bar */
.search-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border-radius: 50px;
    border: 1px solid #ddd;
    padding: 5px 10px;
    width: 60%;
    margin: 0 auto;
    position: relative;
}

.search-bar form {
    display: flex;
    align-items: center;
    width: 100%;
}

.search-bar select {
    width: 30%;
    border: none;
    padding: 8px 10px;
    border-radius: 50px 0 0 50px;
    background: #f5f5f5;
    outline: none;
}

.search-bar input {
    width: 60%;
    border: none;
    padding: 8px 10px;
    background: #f9f9f9;
    outline: none;
}

.search-bar button {
    width: 10%;
    background: #004080;
    color: #fff;
    border: none;
    border-radius: 0 50px 50px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.search-bar button i {
    font-size: 16px;
}

/* Responsiveness */
@media (max-width: 768px) {
    .search-bar {
        width: 100%;
        margin: 10px 0;
    }

    .search-bar select,
    .search-bar input,
    .search-bar button {
        width: 100%;
        margin-bottom: 5px;
        border-radius: 8px;
    }

    .user-options {
        flex-direction: row;
        align-items: center;
        gap: 10px;
    }

    .header-inner .menu-area .nav .nav-link {
        font-size: 12px;
        margin: 0 5px;
    }
}
/* User Options */
.user-options {
    display: flex;
    gap: 15px;
}

.user-options .icon-link {
    font-size: 18px;
    color: #fff;
    position: relative;
    margin-right: 15px;
    transition: color 0.3s ease-in-out;
}

.user-options .icon-link:hover {
    color: #ff6347;
}

.user-options .badge {
    position: absolute;
    top: -5px;
    right: -10px;
    background: #ff6347;
    color: #fff;
    border-radius: 50%;
    font-size: 12px;
    padding: 2px 5px;
}

/* Buttons */
.btn-outline-light, .btn-primary { border-radius: 25px; padding: 8px 20px; }
.btn-outline-light { background: #28a745; color: #fff; }
.btn-outline-light:hover { background: #fff; color: #28a745; }
.btn-primary { background: #ff6347; color: #fff; }
.btn-primary:hover { background: #fff; color: #ff6347; }
</style>
