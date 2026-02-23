<nav class="pc-sidebar">
  <div class="navbar-wrapper">

    <!-- BRAND -->
    <div class="m-header">
      <a href="{{ route('pengguna.dashboard') }}" class="b-brand d-flex align-items-center gap-2">
        <img src="{{ asset('assets/img/logo.png') }}" class="logo-img" alt="logo">
        <div class="brand-text">
          <span class="brand-subtitle">SIM LAB</span>
        </div>
      </a>
    </div>

    <div class="navbar-content">
      <ul class="pc-navbar">

        <!-- BERANDA -->
        <li class="pc-item">
          <a href="{{ route('pengguna.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-home"></i></span>
            <span class="pc-mtext">Beranda</span>
          </a>
        </li>

        <!-- MENU -->
        <li class="pc-item pc-caption">
          <label>MENU</label>
          <i class="ti ti-database"></i>
        </li>
        <li class="pc-item pc-hasmenu
    {{ request()->routeIs('pengguna.jadwal.*') ? 'active open' : '' }}">

          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
            <span class="pc-mtext">Jadwal Lab</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a href="{{ route('pengguna.jadwal.lab', $lab->id_lab) }}" class="submenu-link
                        {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>


        <li class="pc-item pc-hasmenu
    {{ request()->routeIs('pengguna.barang.*') ? 'active open' : '' }}">

          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box"></i></span>
            <span class="pc-mtext">Barang Lab</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a href="{{ route('pengguna.barang.index', $lab->id_lab) }}" class="submenu-link
                {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <li class="pc-item">
          <a href="{{ route('pengguna.peminjaman.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-repeat"></i></span>
            <span class="pc-mtext">Peminjaman</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="{{ route('pengguna.laporan.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
            <span class="pc-mtext">Laporan</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<style>
  /* ==== BRAND SIDEBAR ==== */
  /* BACKGROUND SIDEBAR */
  .pc-sidebar {
    background-color: #d7eef5 !important;
    /* ganti sesuai warna */
  }

  .pc-sidebar .m-header {
    padding: 30px 20px;
  }

  .pc-sidebar .b-brand {
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .pc-sidebar .logo-img {
    width: 42px;
    height: auto;
  }

  .pc-sidebar .brand-text {
    display: flex;
    flex-direction: column;
    font-family: 'Poppins', sans-serif;
    line-height: 1.1;
  }



  .pc-sidebar .brand-subtitle {
    font-size: 20px;
    font-weight: 800;
    color: #000000;
    letter-spacing: 1px;
  }

  .pc-submenu {
    list-style: none;
    padding-left: 12px;
    margin-top: 6px;
  }

  .submenu-link {
    display: block;
    padding: 8px 16px;
    margin: 4px 12px;
    border-radius: 6px;
    color: #555;
    text-decoration: none;
    transition: 0.2s;
  }

  .submenu-link:hover {
    background: #eef3f7;
    color: #000;
  }

  /* ACTIVE LAB */
  .submenu-link.active {
    background: #e7f1ff;
    color: #0d6efd;
    font-weight: 600;
    border-left: 4px solid #0d6efd;
    padding-left: 12px;
  }
</style>