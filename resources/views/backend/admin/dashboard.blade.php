@extends('layouts.backend')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid py-4">

    {{-- WELCOME SECTION --}}
    <div class="welcome-card mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="welcome-title">Selamat datang, Admin SIMLAB 👋</h2>
                <p class="welcome-subtitle">
                    Kelola dan pantau aktivitas laboratorium Anda melalui sistem SIMLAB secara real-time.
                </p>
            </div>
            
        </div>
        <div class="welcome-line"></div>
    </div>

    {{-- STATS GRID - 2 Kolom Mobile, 3 Kolom Desktop --}}
    <div class="card-grid">
    {{-- PENGGUNA --}}
    <a href="{{ route('admin.pengguna.index') }}" class="modern-card">
        <div class="icon-box bg-primary-soft"><i class="bi bi-people-fill text-primary"></i></div>
        <div class="card-info">
            <span class="card-label">Total Pengguna</span>
            <h3 class="card-value">{{ $users }}</h3>
        </div>
    </a>

    {{-- BARANG --}}
    <a href="#" class="modern-card">
        <div class="icon-box bg-success-soft"><i class="bi bi-box-seam-fill text-success"></i></div>
        <div class="card-info">
            <span class="card-label">Barang Lab</span>
            <h3 class="card-value">{{ $barang }}</h3>
        </div>
    </a>

    {{-- PENGAJUAN --}}
    <a href="#" class="modern-card">
        <div class="icon-box bg-warning-soft"><i class="bi bi-file-earmark-text-fill text-warning"></i></div>
        <div class="card-info">
            <span class="card-label">Pengajuan</span>
            <h3 class="card-value">{{ $pengajuan ?? 0 }}</h3>
        </div>
    </a>

    {{-- PEMINJAMAN --}}
    <a href="#" class="modern-card">
        <div class="icon-box bg-info-soft"><i class="bi bi-arrow-left-right text-info"></i></div>
        <div class="card-info">
            <span class="card-label">Peminjaman</span>
            <h3 class="card-value">{{ $peminjaman ?? 0 }}</h3>
        </div>
    </a>

    {{-- KERUSAKAN --}}
    <a href="#" class="modern-card">
        <div class="icon-box bg-danger-soft"><i class="bi bi-exclamation-triangle-fill text-danger"></i></div>
        <div class="card-info">
            <span class="card-label">Kerusakan</span>
            <h3 class="card-value">{{ $kerusakan ?? 0 }}</h3>
        </div>
    </a>

    {{-- KALENDER --}}
    <div class="modern-card">
        <div class="icon-box bg-secondary-soft"><i class="bi bi-calendar3 text-secondary"></i></div>
        <div class="card-info">
            <span id="current-day" class="day-label-top">-</span>
            <div class="calendar-flex">
                <h3 id="current-date" class="card-value">-</h3>
                <div class="calendar-meta">
                    <span id="current-month" class="month-txt">-</span>
                    <span id="current-year" class="year-txt">-</span>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- CHART SECTION --}}
    <div class="row g-4 mt-2">
        <div class="col-lg-4 col-md-6">
            <div class="chart-card">
                <h6 class="chart-title">Laporan Kerusakan</h6>
                <div class="chart-wrapper">
                    <canvas id="chartKerusakan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="chart-card">
                <h6 class="chart-title">Status Barang</h6>
                <div class="chart-wrapper">
                    <canvas id="chartBarang"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="chart-card">
                <h6 class="chart-title">Tren Peminjaman</h6>
                <div class="chart-wrapper">
                    <canvas id="chartPeminjaman"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
    :root {
        --card-radius: 16px;
        --soft-blue: #e7f1ff;
        --soft-green: #eafbe7;
        --soft-yellow: #fff9e6;
        --soft-cyan: #e1faff;
        --soft-red: #ffeef0;
        --soft-gray: #f8f9fa;
        --text-main: #1a202c;
        --text-muted: #718096;
    }

    /* === Grid Stats === */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr); /* Tetap 2 kolom di HP */
        gap: 12px;
        margin-bottom: 24px;
    }

    /* === Modern Card Base === */
    .modern-card {
        background: #fff;
        padding: 15px;
        border-radius: var(--card-radius);
        border: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
        transition: all 0.3s ease;
        min-height: 90px;
        position: relative;
    }

    .modern-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -5px rgba(0,0,0,0.08);
        border-color: #e2e8f0;
    }

    /* FIX KOTAK DISAMPING ICON */
    .icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0 !important; /* MENGHILANGKAN KOTAK/WHITESPACE */
        line-height: 0 !important;
        overflow: hidden;
    }

    .icon-box i { 
        font-size: 22px !important; 
        line-height: 1 !important;
        display: inline-block;
    }

    /* === Card Info & Text === */
    .card-info { 
        display: flex; 
        flex-direction: column; 
        overflow: hidden; 
        line-height: 1.2;
    }
    .card-label { 
        font-size: 11px; 
        color: var(--text-muted); 
        font-weight: 600; 
        white-space: nowrap;
        margin-bottom: 2px;
    }
    .card-value { 
        font-size: 20px !important; 
        font-weight: 800; 
        color: var(--text-main); 
        margin: 0 !important; 
    }

    /* === Khusus Kalender === */
    .day-label-top { font-size: 9px; font-weight: 800; text-transform: uppercase; color: #a0aec0; margin-bottom: 2px; }
    .calendar-flex { display: flex; align-items: center; gap: 6px; }
    .calendar-meta { display: flex; flex-direction: column; line-height: 1; }
    .month-txt { font-size: 11px; font-weight: 700; color: #2d3748; }
    .year-txt { font-size: 9px; color: #cbd5e0; }

    /* === Chart & Welcome === */
    .chart-card { background: #fff; padding: 20px; border-radius: var(--card-radius); border: 1px solid #f0f0f0; }
    .chart-title { font-weight: 700; font-size: 14px; color: #2d3748; margin-bottom: 15px; border-left: 4px solid #3182ce; padding-left: 10px; }
    .chart-wrapper { height: 240px; }

    .welcome-card { background: #fff; padding: 25px; border-radius: var(--card-radius); border: 1px solid #f0f0f0; }
    .welcome-title { font-size: 20px; font-weight: 800; color: var(--text-main); }
    .welcome-line { width: 60px; height: 4px; background: linear-gradient(90deg, #3182ce, #38b2ac); border-radius: 10px; margin-top: 10px; }

    /* === Responsive Desktop === */
    @media (min-width: 992px) {
        .card-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .modern-card { padding: 20px; gap: 15px; }
        .icon-box { width: 56px; height: 56px; }
        .icon-box i { font-size: 26px !important; }
        .card-value { font-size: 26px !important; }
        .card-label { font-size: 13px; }
    }

    /* Soft Colors */
    .bg-primary-soft { background: var(--soft-blue) !important; color: #3182ce; }
    .bg-success-soft { background: var(--soft-green) !important; color: #38a169; }
    .bg-warning-soft { background: var(--soft-yellow) !important; color: #d69e2e; }
    .bg-info-soft    { background: var(--soft-cyan) !important; color: #00b5ad; }
    .bg-danger-soft  { background: var(--soft-red) !important; color: #e53e3e; }
    .bg-secondary-soft { background: var(--soft-gray) !important; color: #718096; }
</style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        /**
         * 1. UPDATE CALENDAR ENGINE
         * Mengisi data waktu ke dalam kartu kalender secara real-time
         */
        function updateCalendar() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                           'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            // Mapping ID sesuai dengan struktur HTML terbaru
            const elements = {
                'current-day': days[now.getDay()],
                'current-date': now.getDate(),
                'current-month': months[now.getMonth()],
                'current-year': now.getFullYear()
            };

            // Loop untuk mengisi teks jika elemen ditemukan
            for (const [id, value] of Object.entries(elements)) {
                const el = document.getElementById(id);
                if (el) el.innerText = value;
            }
        }

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            updateCalendar();
            
            // Opsional: Update tiap menit agar hari berganti otomatis jika tab dibiarkan terbuka
            setInterval(updateCalendar, 60000);
        });

        /**
         * 2. CHART GLOBAL CONFIGURATION
         * Mengatur font dan gaya default untuk semua chart
         */
        Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
        Chart.defaults.color = '#718096';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.borderRadius = 10;

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false } 
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { drawBorder: false, color: '#f0f0f0', borderDash: [5, 5] },
                    ticks: { padding: 10 }
                },
                x: { 
                    grid: { display: false },
                    ticks: { padding: 10 }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            }
        };

        /**
         * 3. CHART IMPLEMENTATION
         */

        // --- Chart Kerusakan (Line Chart) ---
        const ctxKerusakan = document.getElementById('chartKerusakan');
        if (ctxKerusakan) {
            new Chart(ctxKerusakan, {
                type: 'line',
                data: {
                    labels: ['M1', 'M2', 'M3', 'M4'],
                    datasets: [{
                        label: 'Kerusakan',
                        data: [2, 5, 3, 7],
                        borderColor: '#f56565',
                        backgroundColor: 'rgba(245, 101, 101, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f56565',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: commonOptions
            });
        }

        // --- Chart Status Barang (Doughnut Chart) ---
        const ctxBarang = document.getElementById('chartBarang');
        if (ctxBarang) {
            new Chart(ctxBarang, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Dipinjam', 'Rusak'],
                    datasets: [{
                        data: [80, 15, 5],
                        backgroundColor: ['#48bb78', '#4299e1', '#f56565'],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { 
                        legend: { 
                            display: true, 
                            position: 'bottom', 
                            labels: { 
                                usePointStyle: true, 
                                padding: 25,
                                font: { size: 12, weight: '600' }
                            } 
                        } 
                    }
                }
            });
        }

        // --- Chart Tren Peminjaman (Bar Chart) ---
        const ctxPeminjaman = document.getElementById('chartPeminjaman');
        if (ctxPeminjaman) {
            new Chart(ctxPeminjaman, {
                type: 'bar',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
                    datasets: [{
                        label: 'Peminjaman',
                        data: [12, 19, 15, 8, 22],
                        backgroundColor: '#4299e1',
                        hoverBackgroundColor: '#3182ce',
                        borderRadius: 8,
                        barThickness: 20
                    }]
                },
                options: commonOptions
            });
        }
    </script>
@endpush