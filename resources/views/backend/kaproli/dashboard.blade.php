@extends('layouts.backend')

@section('title', 'Dashboard Kaproli')

@section('content')

    <div class="container-fluid py-3">

        {{-- WELCOME SECTION --}}
        <div class="welcome-card mb-4">
            <h2 class="welcome-title">Selamat datang di SIMLAB, Kaproli {{ Auth::user()->name }} 👋</h2>
            <p class="welcome-subtitle">
               Kelola inventaris, pantau aktivitas laboratorium, dan pastikan seluruh fasilitas laboratorium berjalan optimal hari ini.
            </p>
            <div class="welcome-line"></div>
        </div>


        {{-- CARD GRID (SELALU 3 KOLOM) --}}
        <div class="card-grid">

            {{-- CARD 1: PENGGUNA --}}
            <div class="grid-item">
                <div class="modern-card">
                    <div class="icon-box bg-primary-soft">
                        <i class="bi bi-people-fill stat-icon text-primary"></i>
                    </div>
                    <div class="card-text">
                        <span>Pengguna</span>
                        <h3></h3>
                    </div>
                </div>
            </div>

            {{-- CARD 2: BARANG LAB --}}
            <div class="grid-item">
                <div class="modern-card">
                    <div class="icon-box bg-success-soft">
                        <i class="bi bi-box-seam-fill text-success"></i>
                    </div>
                    <div class="card-text">
                        <span>Barang Lab</span>
                        <h3>{{ $barang }}</h3>
                    </div>
                </div>
            </div>

            {{-- CARD 3: PENGAJUAN --}}
            <div class="grid-item">
                <div class="modern-card">
                    <div class="icon-box bg-warning-soft">
                        <i class="bi bi-file-earmark-text-fill text-warning"></i>
                    </div>
                    <div class="card-text">
                        <span>Pengajuan</span>
                        <h3>{{ $pengajuan ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- CARD 4: PEMINJAMAN --}}
            <div class="grid-item">
                <div class="modern-card">
                    <div class="icon-box bg-info-soft">
                        <i class="bi bi-arrow-left-right text-info"></i>
                    </div>
                    <div class="card-text">
                        <span>Peminjaman</span>
                        <h3>{{ $peminjaman ?? 15 }}</h3>
                    </div>
                </div>
            </div>

            {{-- CARD 5: KERUSAKAN --}}
            <div class="grid-item">
                <div class="modern-card">
                    <div class="icon-box bg-danger-soft">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    </div>
                    <div class="card-text">
                        <span>Kerusakan</span>
                        <h3>{{ $kerusakan ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            {{-- CARD 6: KALENDER (SEGARIS & KOTAK ABU-ABU) --}}
            <div class="grid-item">
                <div class="modern-card">
                    <div class="icon-box bg-secondary-soft">
                        <i class="bi bi-calendar3 text-secondary"></i>
                    </div>
                    <div class="card-text">
                        <span id="current-day"
                            style="font-weight: 600;  font-size: 13px; display: block; margin-bottom: -2px;">Memuat...</span>

                        <div style="display: flex; align-items: baseline; gap: 6px;">
                            <h3 id="current-date" style="font-weight: 800; font-size: 22px; margin: 0; color: #333;">-</h3>
                            <span id="current-month-year"
                                style="font-size: 14px; color: #6c757d; font-weight: 500;">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- CHART GRID --}}
        <div class="chart-grid-layout mt-4">
            <div class="chart-card">
                <h6>Laporan Kerusakan</h6>
                <canvas id="chartKerusakan"></canvas>
            </div>
            <div class="chart-card">
                <h6>Data Barang</h6>
                <canvas id="chartBarang"></canvas>
            </div>
            <div class="chart-card">
                <h6>Riwayat Peminjaman</h6>
                <canvas id="chartPeminjaman"></canvas>
            </div>
        </div>

    </div>

@endsection

@push('styles')
    <style>
        /* === KOTAK IKON (ICON BOX) === */
        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-box i {
            font-size: 20px !important;
            line-height: 1;
        }

        .icon-box {

            font-size: 0 !important;

        }



        .icon-box i {

            font-size: 24px !important;
            /* atau ukuran lain */

        }

        /* === GRID UTAMA === */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        /* CARD BASE */
        .modern-card {
            background: #fff;
            border-radius: 15px;
            padding: 15px 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 10px;
            transition: .3s;
            height: 100%;
            border: 1px solid #f0f0f0;
        }

        @media (min-width: 768px) {
            .modern-card {
                flex-direction: row;
                text-align: left;
                padding: 20px;
                gap: 15px;
            }

            .card-grid {
                gap: 20px;
            }

            .icon-box {
                width: 55px;
                height: 55px;
            }

            .icon-box i {
                font-size: 24px !important;
            }
        }

        /* Card Text Styling */
        .card-text span {
            font-size: 11px;
            color: #6c757d;
            display: block;
        }

        .card-text h3 {
            font-size: 18px;
            margin: 2px 0 0;
            font-weight: 700;
        }

        @media (min-width: 768px) {
            .card-text span {
                font-size: 14px;
            }

            .card-text h3 {
                font-size: 24px;
            }
        }

        /* Warna Soft Background */
        .bg-primary-soft {
            background-color: #e7f1ff !important;
        }

        .bg-success-soft {
            background-color: #eafbe7 !important;
        }

        .bg-warning-soft {
            background-color: #fff9e6 !important;
        }

        .bg-info-soft {
            background-color: #e1faff !important;
        }

        .bg-danger-soft {
            background-color: #ffeef0 !important;
        }

        .bg-secondary-soft {
            background-color: #f0f2f5 !important;
        }

        /* Abu-abu untuk kalender */

        .text-secondary {
            color: #6c757d !important;
        }

        /* Welcome Card & Charts */
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

        .chart-grid-layout {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .chart-card {
            background: #fff;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
        }
    </style>
@endpush

@push('scripts')
    <script>
        function updateCalendar() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            const dayElement = document.getElementById('current-day');
            const dateElement = document.getElementById('current-date');
            const monthYearElement = document.getElementById('current-month-year');

            if (dayElement) dayElement.innerText = days[now.getDay()];
            if (dateElement) dateElement.innerText = now.getDate();
            if (monthYearElement) monthYearElement.innerText = months[now.getMonth()] + ' ' + now.getFullYear();
        }

        document.addEventListener('DOMContentLoaded', updateCalendar);
    </script>
@endpush