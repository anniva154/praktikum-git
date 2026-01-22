@extends('layouts.backend')

@section('title', 'Dashboard Pengguna')

@section('content')
  <div class="row g-2">
{{-- WELCOME CARD --}}
<div class="row mb-3">
  <div class="col-lg-8">
    <div class="card welcome-card">
      <div class="card-body">
        <h4 class="fw-bold mb-1">
          👋 Hallo {{ Auth::user()->name }}, Selamat Datang di <span class="text-primary">EduLab</span>
        </h4>
        <p class="mb-0 text-muted">
          Setiap percobaan di laboratorium adalah langkah menuju penemuan baru.
          Tetap semangat, fokus, dan jadilah ilmuwan hebat di EduLab!
        </p>
      </div>
    </div>
  </div>

  {{-- KALENDER MINI --}}
  <div class="col-lg-4">
    <div class="card calendar-card">
      <div class="card-body">
        <h6 class="fw-semibold mb-2">📅 Kalender</h6>
        <div id="calendarMini" class="text-center text-muted small">
          {{ now()->translatedFormat('l, d F Y') }}
        </div>
      </div>
    </div>
  </div>
</div>


   <div class="row g-3">

  {{-- PEMINJAMAN --}}
  <div class="col-md-6 col-lg-3">
    <div class="card dashboard-card border-start border-info border-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="fw-bold text-info mb-0">{{ $totalPeminjaman ?? 0 }}</h3>
            <span class="text-muted small">Peminjaman</span>
          </div>
          <i class="bi bi-arrow-left-right dashboard-icon text-info"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- KERUSAKAN --}}
  <div class="col-md-6 col-lg-3">
    <div class="card dashboard-card border-start border-danger border-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h3 class="fw-bold text-danger mb-0">{{ $totalKerusakan ?? 0 }}</h3>
            <span class="text-muted small">Laporan Kerusakan</span>
          </div>
          <i class="bi bi-exclamation-triangle dashboard-icon text-danger"></i>
        </div>
      </div>
    </div>
  </div>

</div>


  </div>
  {{-- ================= GRAFIK SECTION ================= --}}
<div class="row mt-3 g-3">

  <div class="col-md-4">
    <div class="card chart-card">
      <h6 class="chart-title">📊 Laporan Kerusakan</h6>
      <canvas id="chartKerusakan"></canvas>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card chart-card">
      <h6 class="chart-title">📦 Data Barang</h6>
      <canvas id="chartBarang"></canvas>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card chart-card">
      <h6 class="chart-title">🕘 Riwayat Peminjaman</h6>
      <canvas id="chartPeminjaman"></canvas>
    </div>
  </div>

</div>


@endsection
@push('styles')
<style>
.dashboard-card { border-radius:12px; min-height:115px; }
.dashboard-icon { font-size:3rem; opacity:.7 }
.dashboard-label { font-weight:600 }
.chart-card { padding:18px; border-radius:16px }
.chart-warning { background:#fdeccf }
.chart-info { background:#6ec8db }
.chart-danger { background:#f4c6a9 }

.welcome-card {
  background: linear-gradient(135deg, #e3f2fd, #ffffff);
  border-radius: 16px;
}

.calendar-card {
  border-radius: 16px;
  text-align: center;
}

.dashboard-card {
  border-radius: 14px;
  transition: .3s;
}

.dashboard-card:hover {
  transform: translateY(-4px);
}

.dashboard-icon {
  font-size: 2.8rem;
  opacity: .6;
}

.chart-card {
  padding: 18px;
  border-radius: 16px;
  background: #fff;
}

.chart-title {
  font-weight: 600;
  margin-bottom: 10px;
}

</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const barangData = @json($barangByKondisi ?? []);

    new Chart(document.getElementById('chartBarang'), {
        type: 'line',
        data: {
            labels: Object.keys(barangData),
            datasets: [{
                data: Object.values(barangData),
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

});
</script>
@endpush

