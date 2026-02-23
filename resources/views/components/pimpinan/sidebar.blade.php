<nav class="pc-sidebar">
  <div class="navbar-wrapper">

    <!-- BRAND -->
    <div class="m-header">
      <a href="{{ route('pimpinan.dashboard') }}" class="b-brand d-flex align-items-center gap-2">
        <img src="{{ asset('assets/img/logo.png') }}" class="logo-img" alt="logo">
        <div class="brand-text">
          <span class="brand-subtitle">SIM LAB</span>
        </div>
      </a>
    </div>

    <div class="navbar-content">
      <ul class="pc-navbar">

        <!-- BERANDA -->
        <li class="pc-item {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
          <a href="{{ route('pimpinan.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-home"></i></span>
            <span class="pc-mtext">Beranda</span>
          </a>
        </li>

        <!-- MENU -->
        <li class="pc-item pc-caption">
          <label>MENU</label>
          <i class="ti ti-database"></i>
        </li>

        <!-- JADWAL -->
        <li class="pc-item pc-hasmenu {{ request()->routeIs('pimpinan.jadwal.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
            <span class="pc-mtext">Jadwal</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a
                  href="{{ route('pimpinan.jadwal.lab', $lab->id_lab) }}"
                  class="submenu-link {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <!-- DATA PENGGUNA (TIDAK DROPDOWN) -->
        <li class="pc-item {{ request()->routeIs('pimpinan.pengguna.*') ? 'active' : '' }}">
          <a href="{{ route('pimpinan.pengguna.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Data Pengguna</span>
          </a>
        </li>

        <!-- DATA BARANG -->
        <li class="pc-item pc-hasmenu {{ request()->routeIs('pimpinan.barang.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box"></i></span>
            <span class="pc-mtext">Data Barang</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a
                  href="{{ route('pimpinan.barang.lab.index', $lab->id_lab) }}"

                  class="submenu-link {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <!-- PENGAJUAN BARANG -->
        <li class="pc-item pc-hasmenu {{ request()->routeIs('pimpinan.pengajuan.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
            <span class="pc-mtext">Pengajuan Barang</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a
                  href=""
                  class="submenu-link {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <!-- PEMINJAMAN -->
        <li class="pc-item pc-hasmenu {{ request()->routeIs('pimpinan.peminjaman.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-repeat"></i></span>
            <span class="pc-mtext">Peminjaman</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a
                  href=""
                  class="submenu-link {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <!-- LAPORAN -->
        <li class="pc-item pc-hasmenu {{ request()->routeIs('pimpinan.laporan.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
            <span class="pc-mtext">Laporan</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>

          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a
                  href=""
                  class="submenu-link {{ optional(request()->route('lab'))->id_lab == $lab->id_lab ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
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
  transition: .2s;
}

.submenu-link:hover {
  background: #eef3f7;
  color: #000;
}

/* LAB ACTIVE (PENTING) */
.submenu-link.active {
  background: #e7f1ff;
  color: #0d6efd;
  font-weight: 600;
  border-left: 4px solid #0d6efd;
}
</style>