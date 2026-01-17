<nav class="pc-sidebar">
  <div class="navbar-wrapper">

    <!-- BRAND -->
    <div class="m-header">
      <a href="{{ route('admin.dashboard') }}" class="b-brand d-flex align-items-center gap-2">
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
          <a href="{{ route('admin.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-home"></i></span>
            <span class="pc-mtext">Beranda</span>
          </a>
        </li>

        <!-- MENU -->
        <li class="pc-item pc-caption">
          <label>MENU</label>
          <i class="ti ti-database"></i>
        </li>

        <li class="pc-item">
          <a href="{{ route('admin.jurusan.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-building"></i></span>
            <span class="pc-mtext">Jurusan</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="{{ route('admin.lab.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-flask"></i></span>
            <span class="pc-mtext">Laboratorium</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
            <span class="pc-mtext">Jadwal Lab</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box"></i></span>
            <span class="pc-mtext">Barang Lab</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
            <span class="pc-mtext">Pengajuan Barang</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="" class="pc-link">
            <span class="pc-micon"><i class="ti ti-repeat"></i></span>
            <span class="pc-mtext">Peminjaman</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
            <span class="pc-mtext">Laporan</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="{{ route('admin.pengguna.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Data Pengguna</span>
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
</style>