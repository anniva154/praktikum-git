@extends('layouts.backend')

@section('title', 'Dashboard Admin')

@section('content')
  <div class="row g-2">

    {{-- PENGGUNA --}}
    <div class="col">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-1">
            <h2 class="fw-bold text-primary mb-0">{{ $users }}</h2>
            <i class="bi bi-people dashboard-icon text-primary"></i>
          </div>
          <p class="dashboard-label text-secondary">Pengguna</p>
        </div>
      </div>
    </div>

    {{-- BARANG LAB --}}
    <div class="col">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-1">
            <h2 class="fw-bold text-success mb-0">{{ $barang }}</h2>
            <i class="bi bi-box-seam dashboard-icon text-success"></i>
          </div>
          <p class="dashboard-label text-secondary">Barang Lab</p>
        </div>
      </div>
    </div>

    {{-- PENGAJUAN --}}
    <div class="col">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-1">
            <h2 class="fw-bold text-warning mb-0"></h2>
            <i class="bi bi-file-earmark-text dashboard-icon text-warning"></i>
          </div>
          <p class="dashboard-label text-secondary">Pengajuan Barang</p>
        </div>
      </div>
    </div>

    {{-- PEMINJAMAN --}}
    <div class="col">
      <div class="card dashboard-card">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-1">
            <h2 class="fw-bold text-info mb-0"></h2>
            <i class="bi bi-arrow-left-right dashboard-icon text-info"></i>
          </div>
          <p class="dashboard-label text-secondary">Peminjaman</p>
        </div>
      </div>
    </div>

    {{-- KERUSAKAN --}}
    <div class="col">
      <div class="card dashboard-card">
        <div class="card-body">

          <div class="d-flex align-items-center justify-content-between mb-1">
            <h1 class="fw-bold text-danger mb-0"></h1>
            <i class="bi bi-exclamation-triangle dashboard-icon text-danger"></i>
          </div>
          <p class="dashboard-label text-secondary">Laporan Kerusakan</p>
        </div>
      </div>
    </div>

  </div>
  {{-- ================= GRAFIK SECTION ================= --}}
<div class="row mt-2 g-3">


    {{-- LAPORAN KERUSAKAN --}}
    <div class="col-md-4">
        <div class="card chart-card chart-warning">
            <h6 class="chart-title">Laporan Kerusakan</h6>
            <canvas id="chartKerusakan"></canvas>
        </div>
    </div>

    {{-- DATA BARANG --}}
    <div class="col-md-4">
        <div class="card chart-card chart-info">
            <h6 class="chart-title">Data Barang</h6>
            <canvas id="chartBarang"></canvas>
        </div>
    </div>

    {{-- RIWAYAT PEMINJAMAN --}}
    <div class="col-md-4">
        <div class="card chart-card chart-danger">
            <h6 class="chart-title">Riwayat Peminjaman</h6>
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

