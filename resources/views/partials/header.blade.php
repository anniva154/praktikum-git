<header class="header-lab">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="container d-flex justify-content-end align-items-center">
    <div class="top-info">
        <span class="top-item">
            <i class="fa-solid fa-phone"></i> Telp. (031) 3062126
        </span>
        <span class="divider">|</span>
        <span class="top-item">
            <i class="fa-solid fa-envelope"></i> smkn3bangkalan.adm@gmail.com
        </span>
    </div>
</div>

    </div>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-lab">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/logo.png') }}" class="logo-img me-2">
                <div class="brand-text">
                    <span class="brand-title">SISTEM INFORMASI MANAJEMEN</span>
                    <span class="brand-subtitle">LABORATORIUM</span>
                </div>
            </a>

            <!-- TOGGLER -->
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarLab">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarLab">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active">Beranda</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Profil</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item">Tentang Laboratorium</a></li>
                            <li><a class="dropdown-item">Tentang Sistem</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link">Kontak</a></li>
                </ul>
            </div>

            <!-- AUTH -->
            <div class="auth-menu d-flex align-items-center">
                <a href="{{ route('login') }}" class="auth-link">Masuk</a>
                <a href="{{ route('register') }}" class="auth-btn">Daftar</a>
            </div>

        </div>
    </nav>

</header>

<style>
/* ===============================
   GLOBAL
================================ */
body {
    font-family: 'Montserrat', sans-serif;
    margin: 0;
}

/* ===============================
   HEADER STICKY
================================ */
.header-lab {
    position: sticky;
    top: 0;
    z-index: 1050;
}

/* ===============================
   TOPBAR
================================ */
.topbar {
    background-color: #0d6efd;
    padding: 6px 0;
    font-size: 14px;
}

.top-info {
    display: flex;
    align-items: center;
}

.top-item {
    color: #ffffff;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
}


.top-item i {
    color: #ffffff;
    margin-right: 6px;
}

.divider {
    margin: 0 12px;
    color: rgba(255,255,255,.6);
}

/* ===============================
   NAVBAR
================================ */
.navbar-lab {
    background: linear-gradient(90deg, #0d6efd, #084298);
}

/* ===============================
   LOGO & BRAND
================================ */
.logo-img {
    width: 48px;
}

.brand-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
    font-family: 'Poppins', sans-serif;
}

.brand-title {
    font-size: 13px;
    font-weight: 600;
    color: #dbeafe;
}

.brand-subtitle {
    font-size: 22px;
    font-weight: 800;
    color: #ffffff;
}

/* ===============================
   NAV MENU
================================ */
.navbar-lab .nav-link {
    color: #ffffff !important;
    font-weight: 500;
    margin: 0 10px;
    position: relative;
}

.navbar-lab .nav-link:hover {
    color: #ffd000 !important;
}

.navbar-lab .nav-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -8px;
    width: 100%;
    height: 3px;
    background: #ffd000;
    border-radius: 10px;
}

/* ===============================
   DROPDOWN
================================ */
.dropdown-menu {
    border-radius: 10px;
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,.15);
}

/* ===============================
   AUTH
================================ */
.auth-menu {
    gap: 10px;
}

.auth-link {
    color: #ffffff;
    text-decoration: none;
}

.auth-link:hover {
    color: #ffd000;
}

.auth-btn {
    background: #ffd000;
    color: #084298;
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    text-decoration: none;
}

.auth-btn:hover {
    background: #ffc107;
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 768px) {
    .topbar {
        display: none;
    }

    .logo-img {
        width: 40px;
    }

    .brand-title {
        font-size: 11px;
    }

    .brand-subtitle {
        font-size: 18px;
    }
}

</style>