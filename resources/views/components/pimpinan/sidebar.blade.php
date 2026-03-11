<nav class="pc-sidebar">
  <div class="navbar-wrapper">

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

        <li class="pc-item {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}">
          <a href="{{ route('pimpinan.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-home"></i></span>
            <span class="pc-mtext">Beranda</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>MENU</label>
          <i class="ti ti-database"></i>
        </li>

        <li class="pc-item pc-hasmenu {{ request()->is('pimpinan/jadwal*') ? 'active pc-trigger' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
            <span class="pc-mtext">Jadwal</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li class="pc-item">
                <a href="{{ route('pimpinan.jadwal.lab', $lab->id_lab) }}" 
                   class="pc-link {{ (request()->is('pimpinan/jadwal*') && request()->segment(3) == $lab->id_lab) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <li class="pc-item {{ request()->is('pimpinan/pengguna*') ? 'active' : '' }}">
          <a href="{{ route('pimpinan.pengguna.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Data Pengguna</span>
          </a>
        </li>

        <li class="pc-item pc-hasmenu {{ request()->is('pimpinan/barang*') ? 'active pc-trigger' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-box"></i></span>
            <span class="pc-mtext">Data Barang</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li class="pc-item">
                <a href="{{ route('pimpinan.barang.lab.index', $lab->id_lab) }}"
                   class="pc-link {{ (request()->is('pimpinan/barang*') && request()->segment(3) == $lab->id_lab) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <li class="pc-item pc-hasmenu {{ request()->is('pimpinan/pengajuan*') ? 'active pc-trigger' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-clipboard-list"></i></span>
            <span class="pc-mtext">Pengajuan Barang</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li class="pc-item">
                <a href="{{ route('pimpinan.pengajuan.index', $lab->id_lab) }}"
                   class="pc-link {{ (request()->is('pimpinan/pengajuan*') && request()->segment(3) == $lab->id_lab) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <li class="pc-item pc-hasmenu {{ request()->is('pimpinan/peminjaman-lab*') ? 'active pc-trigger' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-repeat"></i></span>
            <span class="pc-mtext">Peminjaman</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li class="pc-item">
                <a href="{{ route('pimpinan.peminjaman.lab', $lab->id_lab) }}" 
                   class="pc-link {{ (request()->is('pimpinan/peminjaman-lab*') && request()->segment(3) == $lab->id_lab) ? 'active' : '' }}">
                  {{ $lab->nama_lab }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>

        <li class="pc-item pc-hasmenu {{ request()->is('pimpinan/laporan-lab*') ? 'active pc-trigger' : '' }}">
          <a href="javascript:void(0)" class="pc-link">
            <span class="pc-micon"><i class="ti ti-file-text"></i></span>
            <span class="pc-mtext">Laporan Kerusakan</span>
            <span class="pc-arrow"><i class="ti ti-chevron-right"></i></span>
          </a>
          <ul class="pc-submenu">
            @foreach ($laboratorium as $lab)
              <li class="pc-item">
                <a href="{{ route('pimpinan.laporan-lab', $lab->id_lab) }}" 
                   class="pc-link {{ (request()->is('pimpinan/laporan-lab*') && request()->segment(3) == $lab->id_lab) ? 'active' : '' }}">
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
  .pc-sidebar {
    background-color: #d7eef5 !important;

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

  .submenu-link.active {
    background: #e7f1ff;
    color: #0d6efd;
    font-weight: 600;
    border-left: 4px solid #0d6efd;
  }
</style>