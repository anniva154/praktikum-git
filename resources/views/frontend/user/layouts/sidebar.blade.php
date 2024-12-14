<div class="container">
    <div class="card custom-card">
        <!-- Profil Pengguna -->
        <div class="profile text-center">
            @if(Auth()->user()->photo)
                <img class="img-profile rounded-circle" src="{{ Auth()->user()->photo }}" alt="Profile Photo">
            @else
                <img class="img-profile rounded-circle" src="{{ asset('backend/img/avatar.png') }}" alt="Default Avatar">
            @endif
            <h5 class="text-dark mt-3">
                Hi, {{ Auth()->user()->name ?? 'Guest' }}
            </h5>
            <p class="text-muted">{{ Auth()->user()->email }}</p>
        </div>

        <!-- Menu Sidebar -->
        <ul class="menu list-unstyled">
            <!-- Dashboard -->
            <li class="nav-item {{ request()->is('user/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">Shop</div>

            <!-- Orders -->
            <li class="nav-item {{ request()->is('user/order') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user.transaction-history') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Transaction</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">User Settings</div>

            <!-- Manage Address -->
            <li class="nav-item {{ request()->is('user/address') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('address.index') }}">
                    <i class="fa fa-map-marker"></i>
                    <span>Manage Address</span>
                </a>
            </li>

            <!-- Profile Settings -->
            <li class="nav-item {{ request()->is('user/profile') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('user-profile') }}">
                    <i class="fa fa-user"></i>
                    <span>Profile Settings</span>
                </a>
            </li>

            <li class="nav-item">
    <a class="nav-link logout" href="{{ route('logout') }}"
       onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">
        <i class="fa fa-sign-out"></i>
        <span>Logout</span>
    </a>
</li>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

        </ul>
    </div>
</div>

<style>
    /* Card Styling */
    .custom-card {
        color: #adb5bd;
        padding: 20px;
        border: 1px solid #6c757d;
        border-radius: 10px;
        max-width: 250px;
        margin: 20px auto;
    }

    /* Profil */
    .profile {
        text-align: center;
        margin-bottom: 30px;
    }

    .img-profile {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin: 0 auto;
        border: 2px solid #fff;
    }

    .profile h5 {
        margin-top: 15px;
        font-size: 18px;
        font-weight: bold;
        color: #fff;
    }

    .profile p {
        font-size: 14px;
        color: #adb5bd;
    }

    /* Menu items */
    .menu {
        padding: 0;
    }

    .nav-item {
        list-style: none;
    }

    .nav-link {
        display: flex;
        align-items: center;
        color: #adb5bd;
        text-decoration: none;
        padding: 10px 15px;
        margin: 5px 0;
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
    }

    .nav-link i {
        margin-right: 10px;
        font-size: 18px;
    }

    .nav-item:hover .nav-link,
    .nav-item.active .nav-link {
        background: #495057;
        color: #fff;
    }

    .logout {
        color: #e74c3c;
        font-weight: bold;
    }

    .logout:hover {
        background: #c0392b;
    }

    .sidebar-divider {
        border-top: 1px solid #6c757d;
        margin: 10px 0;
    }

    .sidebar-heading {
        font-size: 12px;
        text-transform: uppercase;
        color: #adb5bd;
        margin: 10px 0;
    }
</style>
