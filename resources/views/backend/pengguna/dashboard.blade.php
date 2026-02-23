@extends('layouts.backend')

@section('title', 'Dashboard Pengguna')

@section('content')
  <div class="container-fluid py-3">
    {{-- WELCOME SECTION --}}
    <div class="welcome-card mb-4">
      <h2 class="welcome-title">Selamat datang,{{ Auth::user()->name }} 👋</h2>
      <p class="welcome-subtitle">
        Kelola dan pantau aktivitas laboratorium Anda melalui sistem SIMLAB.
      </p>
      <div class="welcome-line"></div>
    </div>

    <div class="row g-4">
      {{-- LEFT COLUMN: STATISTICS --}}
      <div class="col-lg-8">
        <div class="card-grid">
    {{-- CARD 1: TOTAL PEMINJAMAN USER --}}
    <div class="grid-item">
        <a href="{{ route('pengguna.peminjaman.index') }}" class="modern-card">
            <div class="icon-box bg-info-soft">
                <i class="bi bi-arrow-left-right text-info"></i>
            </div>
            <div class="card-text">
                <span>Peminjaman</span>
                <h3>{{ $totalPeminjamanUser ?? 0 }}</h3>
            </div>
        </a>
    </div>

    {{-- CARD 2: LAPORAN KERUSAKAN USER --}}
    <div class="grid-item">
        <a href="{{ route('pengguna.laporan.index') }}" class="modern-card">
            <div class="icon-box bg-danger-soft">
                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
            </div>
            <div class="card-text">
                <span>Laporan Kerusakan</span>
                <h3>{{ $kerusakanUser ?? 0 }}</h3>
            </div>
        </a>
    </div>
</div>


        {{-- QUICK ACTIONS --}}
        <h5 class="mt-4 mb-3 fw-bold">Akses Cepat</h5>
        <div class="row g-3">
          <div class="col-6 col-md-3">
            <a href="/peminjaman/create" class="action-card">
              <i class="bi bi-plus-circle text-primary"></i>
              <span>Pinjam Alat</span>
            </a>
          </div>
          <div class="col-6 col-md-3">
            <a href="/barang" class="action-card">
              <i class="bi bi-search text-success"></i>
              <span>Cari Alat</span>
            </a>
          </div>
          <div class="col-6 col-md-3">
            <a href="/laporan/kerusakan" class="action-card">
              <i class="bi bi-tools text-warning"></i>
              <span>Lapor Rusak</span>
            </a>
          </div>
          <div class="col-6 col-md-3">
            <a href="/profile" class="action-card">
              <i class="bi bi-person-gear text-secondary"></i>
              <span>Profil Saya</span>
            </a>
          </div>
        </div>
      </div>

      {{-- RIGHT COLUMN: CALENDAR --}}
      <div class="col-lg-4">
        <div class="calendar-card">
          <div class="calendar-header">
            <button id="calPrev" class="btn-cal"><i class="bi bi-chevron-left"></i></button>
            <h6 id="calTitle" class="mb-0 fw-bold text-uppercase"></h6>
            <button id="calNext" class="btn-cal"><i class="bi bi-chevron-right"></i></button>
          </div>
          <div id="calendarGrid" class="calendar-grid">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Global Styling */
    .bg-primary-soft {
      background-color: #e7f1ff !important;
    }

    .bg-info-soft {
      background-color: #e1faff !important;
    }

    .bg-danger-soft {
      background-color: #ffeef0 !important;
    }

    /* Welcome Card */
    .welcome-card {
      background: #fff;
      padding: 20px;
      border-radius: 15px;
      border: 1px solid #e9ecef;
    }

    .welcome-title {
      font-size: 18px;
      font-weight: 700;
    }

    .welcome-subtitle {
      font-size: 12px;
      color: #6c757d;
    }

    .welcome-line {
      width: 60px;
      height: 4px;
      margin-top: 10px;
      border-radius: 10px;
      background: linear-gradient(90deg, #0d6efd, #20c997);
    }

    /* === Grid Stats (FIX: Selalu 2 Kolom Sejajar) === */
    .card-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      /* Mengunci jadi 2 kolom di semua layar */
      gap: 12px;
      margin-bottom: 20px;
    }

    .modern-card {
      background: #fff;
      padding: 15px;
      border-radius: 18px;
      border: 1px solid #f0f0f0;
      display: flex;
      flex-direction: column;
      /* Ikon di atas (untuk mobile-first) */
      align-items: center;
      text-align: center;
      gap: 10px;
      transition: transform 0.2s;
    }

    .modern-card:hover {
      transform: translateY(-5px);
    }

    /* Fix Icon Box */
    .modern-card .icon-box {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex !important;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 0 !important;
    }

    .modern-card .icon-box i {
      font-size: 20px !important;
      line-height: 1;
      display: block;
    }

    .card-text span {
      font-size: 11px;
      color: #718096;
      display: block;
      font-weight: 500;
    }

    .card-text h3 {
      font-size: 20px;
      font-weight: 800;
      margin: 0;
      color: #2d3748;
    }

    /* Action Cards (Akses Cepat) */
    .action-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      /* Default 2 kolom di HP */
      gap: 10px;
    }

    .action-card {
      background: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 15px;
      border-radius: 15px;
      border: 1px solid #f0f0f0;
      text-decoration: none;
      color: #4a5568;
      transition: all 0.2s;
      min-height: 90px;
      justify-content: center;
    }

    .action-card:hover {
      background: #f8faff;
      border-color: #3182ce;
      color: #3182ce;
    }

    .action-card i {
      font-size: 22px !important;
      margin-bottom: 8px;
    }

    .action-card span {
      font-size: 12px;
      font-weight: 600;
      text-align: center;
    }

    /* Calendar Styling */
    .calendar-card {
      background: #fff;
      padding: 20px;
      border-radius: 20px;
      border: 1px solid #edf2f7;
    }

    .calendar-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 15px;
    }

    .btn-cal {
      background: #f8f9fa;
      border: none !important;
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .calendar-title {
      font-size: 14px;
      font-weight: 700;
      color: #2d3748;
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 2px;
      text-align: center;
    }

    .day-name {
      font-size: 10px;
      font-weight: 700;
      color: #a0aec0;
      padding-bottom: 5px;
    }

    .calendar-grid div {
      padding: 8px 0;
      font-size: 11px;
      border-radius: 8px;
    }

    .today {
      background: #0d6efd !important;
      color: #fff !important;
      font-weight: bold;
    }

    /* === Perubahan saat Layar Desktop (min-width: 992px) === */
    @media (min-width: 992px) {

      /* Kembalikan ke samping (horizontal) di desktop agar lebih profesional */
      .modern-card {
        flex-direction: row;
        text-align: left;
        padding: 20px;
      }

      .card-text h3 {
        font-size: 24px;
      }

      .action-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }
  </style>
@endpush


@push('scripts')
  <script>
    // Calendar Logic
    let date = new Date();
    function renderCalendar() {
      const calTitle = document.getElementById('calTitle');
      const calGrid = document.getElementById('calendarGrid');
      calGrid.innerHTML = '';

      const year = date.getFullYear();
      const month = date.getMonth();
      const firstDay = new Date(year, month, 1).getDay();
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      calTitle.innerText = date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });

      const dayNames = ['M', 'S', 'S', 'R', 'K', 'J', 'S'];
      dayNames.forEach(d => {
        const div = document.createElement('div');
        div.classList.add('day-name');
        div.innerText = d;
        calGrid.appendChild(div);
      });

      for (let i = 0; i < firstDay; i++) {
        calGrid.appendChild(document.createElement('div'));
      }

      for (let d = 1; d <= daysInMonth; d++) {
        const div = document.createElement('div');
        div.innerText = d;
        const today = new Date();
        if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
          div.classList.add('today');
        }
        calGrid.appendChild(div);
      }
    }

    renderCalendar();
    document.getElementById('calPrev').onclick = () => { date.setMonth(date.getMonth() - 1); renderCalendar(); }
    document.getElementById('calNext').onclick = () => { date.setMonth(date.getMonth() + 1); renderCalendar(); }
  </script>
@endpush