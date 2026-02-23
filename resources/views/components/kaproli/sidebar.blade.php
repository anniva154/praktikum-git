<nav class="pc-sidebar">
  <div class="navbar-wrapper">

    <div class="m-header">
      <a href="{{ route('kaproli.dashboard') }}" class="b-brand d-flex align-items-center gap-2">
        <img src="{{ asset('assets/img/logo.png') }}" class="logo-img" alt="logo">
        <div class="brand-text">
          <span class="brand-subtitle">SIM LAB</span>
        </div>
      </a>
    </div>

    <div class="navbar-content">
      <ul class="pc-navbar">

        <li class="pc-item">
          <a href="{{ route('kaproli.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-home"></i></span>
            <span class="pc-mtext">Beranda</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>MENU</label>
        </li>

        {{-- BARANG LAB --}}
        <li class="pc-item pc-hasmenu {{ request()->routeIs('kaproli.barang.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box"></i></span>
            <span class="pc-mtext">Barang Lab</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a href="{{ route('kaproli.barang.index', $lab->id_lab) }}"
                   class="submenu-link {{ (request()->segment(3) == $lab->id_lab && request()->routeIs('kaproli.barang.*')) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        {{-- PENGAJUAN BARANG --}}
        <li class="pc-item pc-hasmenu {{ request()->routeIs('kaproli.pengajuan.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
            <span class="pc-mtext">Pengajuan Barang</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a href="{{ route('kaproli.pengajuan.index', $lab->id_lab) }}"
                   class="submenu-link {{ (request()->segment(3) == $lab->id_lab && request()->routeIs('kaproli.pengajuan.*')) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        {{-- PEMINJAMAN --}}
        <li class="pc-item pc-hasmenu {{ request()->routeIs('kaproli.peminjaman.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-repeat"></i></span>
            <span class="pc-mtext">Peminjaman</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a href="{{ route('kaproli.peminjaman.index', $lab->id_lab) }}"
                   class="submenu-link {{ (request()->segment(3) == $lab->id_lab && request()->routeIs('kaproli.peminjaman.*')) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        {{-- LAPORAN KERUSAKAN --}}
        <li class="pc-item pc-hasmenu {{ request()->routeIs('kaproli.laporan.*') ? 'active open' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
            <span class="pc-mtext">Laporan Kerusakan</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li>
                <a href="{{ route('kaproli.laporan.index', $lab->id_lab) }}" 
                   class="submenu-link {{ (request()->segment(3) == $lab->id_lab && request()->routeIs('kaproli.laporan.*')) ? 'active' : '' }}">
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