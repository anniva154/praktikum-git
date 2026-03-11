@extends('layouts.backend')

@section('title', 'Dashboard Kaproli')

@section('content')
    <div class="container-fluid py-3">

        {{-- 1. WELCOME SECTION --}}
        <div class="welcome-card mb-4">
            <h2 class="welcome-title">Selamat datang di SIMLAB, Kaproli {{ Auth::user()->name }} 👋</h2>
            <p class="welcome-subtitle text-muted">Kelola inventaris dan pantau aktivitas laboratorium hari ini.</p>
            <div class="welcome-line"></div>
        </div>

        {{-- 2. STATS GRID (3 Kolom Desktop, 2-1 Mobile) --}}
        <div class="card-grid mb-4">
            {{-- CARD 1: DISTRIBUSI BARANG (Responsive Split) --}}
            <div class="modern-card">
                <div class="icon-box bg-success-soft">
                    <i class="bi bi-box-seam-fill text-success"></i>
                </div>
                <div class="card-info w-100">
                    <span class="card-label text-nowrap">Distribusi Barang</span>
                    <div class="stats-split-wrapper mt-1">
                        <div class="stat-item">
                            <small class="stat-label">LAB 1</small>
                            <h3 class="stat-value-large text-dark">{{ $barang_lab1 }}</h3>
                        </div>
                        <div class="stat-item text-end">
                            <small class="stat-label">LAB 2</small>
                            <h3 class="stat-value-large text-dark">{{ $barang_lab2 }}</h3>
                        </div>
                    </div>
                </div>
            </div>

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

            {{-- CARD 3: STATUS PENGGUNAAN --}}
            <div class="modern-card">
                <div class="icon-box bg-info-soft"><i class="bi bi-display text-info"></i></div>
                <div class="card-info w-100">
                    <span class="card-label">Penggunaan Lab</span>
                    <div class="stats-split-wrapper mt-1">
                        <div class="stat-item">
                            <small class="stat-label">LAB 1</small>
                            <span class="stat-status text-dark">{{ $lab1_user ?? 'Kosong' }}</span>
                        </div>
                        <div class="stat-item text-end">
                            <small class="stat-label">LAB 2</small>
                            <span class="stat-status text-dark">{{ $lab2_user ?? 'XII TKJ 2' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       {{-- 3. MAIN CONTENT (Tabel & Chart dalam SATU Row) --}}
        <div class="row g-4 mb-4">
            {{-- KOLOM TABEL (Kiri) --}}
            <div class="col-lg-8">
                <div class="chart-card shadow-sm h-100 border-0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold m-0">
                            <i class="bi bi-clipboard-check me-2 text-primary"></i>
                            Antrean Validasi
                        </h6>
                    </div>

                    {{-- DESKTOP TABLE --}}
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr style="font-size:11px;color:#718096;text-transform:uppercase;letter-spacing:.5px">
                                        <th class="border-0 ps-3">Laboratorium</th>
                                        <th class="border-0">Peminjam</th>
                                        <th class="border-0">Tanggal</th>
                                        <th class="border-0 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($antreanValidasi as $item)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark">
                                                {{ $item->barang->laboratorium->nama_lab ?? 'Lab Tidak Terdata' }}
                                            </td>
                                            <td>
                                                <div class="fw-medium text-dark">{{ $item->user->name ?? 'User' }}</div>
                                                <small class="text-primary fw-bold text-uppercase" style="font-size:10px">
                                                    {{ $item->user->tipe_pengguna ?? '-' }}
                                                    @if(($item->user->tipe_pengguna ?? '') == 'siswa' && $item->user->kelas)
                                                        - {{ $item->user->kelas }}
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="text-muted small">
                                                {{ $item->waktu_pinjam->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-light btn-sm rounded-circle border shadow-sm btn-detail" 
                                                        data-bs-toggle="modal" data-bs-target="#modalDetail"
                                                        data-id="{{ $item->id_peminjaman }}"
                                                        data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                                        data-jumlah="{{ $item->jumlah_pinjam }}"
                                                        data-lab="{{ $item->barang->laboratorium->nama_lab ?? '-' }}"
                                                        data-peminjam="{{ $item->user->name }}"
                                                        data-email="{{ $item->user->email }}"
                                                        data-wa="{{ $item->user->no_wa ?? '-' }}"
                                                        data-pengajuan="{{ $item->created_at->format('d M Y, H:i') }}"
                                                        data-pinjam="{{ $item->waktu_pinjam->format('d M Y, H:i') }}"
                                                        data-kembali="{{ $item->waktu_kembali->format('d M Y, H:i') }}"
                                                        data-keterangan="{{ $item->keterangan ?? '-' }}"
                                                        data-status="{{ ucfirst($item->status) }}">
                                                        <i class="bi bi-eye text-primary"></i>
                                                    </button>

                                                    <div class="dropdown">
                                                        <button class="btn btn-primary btn-sm rounded-pill px-3 dropdown-toggle" data-bs-toggle="dropdown" style="font-size:11px">
                                                            Validasi
                                                        </button>
                                                        <ul class="dropdown-menu shadow border-0 p-2" style="border-radius:12px">
                                                            @foreach(['disetujui' => ['Setujui', 'success', 'bi-check-circle'], 'ditolak' => ['Tolak', 'danger', 'bi-x-circle']] as $val => $label)
                                                                <li>
                                                                    <form action="{{ route('kaproli.peminjaman.updateStatus', ['lab' => $item->barang->id_lab ?? 0, 'peminjaman' => $item->id_peminjaman]) }}" method="POST">
                                                                        @csrf @method('PATCH')
                                                                        <input type="hidden" name="status" value="{{ $val }}">
                                                                        <button class="dropdown-item text-{{ $label[1] }} small fw-bold">
                                                                            <i class="bi {{ $label[2] }}"></i> {{ $label[0] }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox d-block mb-2 fs-2"></i> Tidak ada antrean validasi
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- MOBILE CARD --}}
                    <div class="d-md-none">
                        @forelse($antreanValidasi as $item)
                            <div class="mobile-item-card mb-3 p-3 border rounded shadow-sm">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $item->barang->laboratorium->nama_lab ?? 'Lab' }}</div>
                                        <div class="small text-muted">{{ $item->user->name ?? 'User' }}</div>
                                    </div>
                                    <div class="small text-muted">{{ $item->waktu_pinjam->format('d M Y') }}</div>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn btn-light btn-sm btn-detail" data-bs-toggle="modal" data-bs-target="#modalDetail" data-id="{{ $item->id_peminjaman }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    {{-- Dropdown validasi mobile disingkat untuk menghemat ruang --}}
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Status</button>
                                        <ul class="dropdown-menu shadow">
                                            <li><button class="dropdown-item">Setujui</button></li>
                                            <li><button class="dropdown-item">Tolak</button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Tidak ada antrean.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KOLOM CHART (Kanan) --}}
            <div class="col-lg-4">
                <div class="chart-card shadow-sm h-100 border-0">
                    <h6 class="fw-bold mb-4 text-dark">
                        <i class="bi bi-pie-chart-fill me-2 text-success"></i>Status Barang
                    </h6>
                    <div class="chart-wrapper">
                        <canvas id="chartBarang"></canvas>
                    </div>
                </div>
            </div>
        </div> {{-- End Row Utama --}}

        {{-- MODAL DETAIL --}}
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                    <div class="modal-header border-0 pt-4 px-4">
                        <h5 class="fw-bold text-dark mb-0">Detail Transaksi Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <small class="text-muted">ID Peminjaman: #<span id="det-id"></span></small>
                            <span id="det-status" class="badge px-3 py-2 rounded-pill"></span>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border border-dashed">
                                    <small class="text-muted d-block mb-1">Informasi Barang</small>
                                    <h6 id="det-barang" class="fw-bold text-primary mb-1"></h6>
                                    <p class="mb-0 small text-dark"><i class="bi bi-box-seam me-1"></i> <span id="det-jumlah"></span> Unit</p>
                                    <p class="mb-0 small text-dark"><i class="bi bi-building me-1"></i> Lab: <span id="det-lab"></span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border border-dashed">
                                    <small class="text-muted d-block mb-1">Informasi Peminjam</small>
                                    <h6 id="det-peminjam" class="fw-bold text-dark mb-1"></h6>
                                    <p class="mb-0 small text-dark"><i class="bi bi-envelope me-1"></i> <span id="det-email"></span></p>
                                    <p class="mb-0 small text-dark"><i class="bi bi-whatsapp me-1 text-success"></i> <span id="det-wa"></span></p>
                                </div>
                            </div>
                        </div>
                        <h6 class="fw-bold mb-3 small text-uppercase">Log Waktu</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered custom-log-table mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light-subtle text-muted" width="40%">Waktu Pengajuan</th>
                                        <td id="det-pengajuan" class="fw-medium"></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light-subtle text-muted">Waktu Pinjam</th>
                                        <td id="det-pinjam" class="fw-medium"></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light-subtle text-muted">Waktu Kembali</th>
                                        <td id="det-kembali" class="fw-bold"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <h6 class="fw-bold mb-2 small text-uppercase">Keperluan:</h6>
                        <div id="det-keterangan" class="p-3 bg-light rounded-3 border small text-muted" style="min-height: 60px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- End Container --}}
@endsection

@push('styles')
    <style>
        :root {
            --card-radius: 16px;
            --soft-blue: #e7f1ff;
            --soft-green: #eafbe7;
            --soft-gray: #f8f9fa;
            --soft-cyan: #e1faff;
            --text-main: #1a202c;
            --text-muted: #718096;
        }

        /* === GRID STATS === */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .card-grid .modern-card:nth-child(3) {
            grid-column: span 2;
        }

        @media (min-width: 992px) {
            .card-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
            }

            .card-grid .modern-card:nth-child(3) {
                grid-column: span 1;
            }

            .row.d-flex {
                display: flex !important;
            }

            .chart-card {
                height: 100%;
                display: flex;
                flex-direction: column;
            }
        }

        /* === CARDS BASE === */
        .modern-card,
        .chart-card,
        .welcome-card {
            background: #fff;
            border-radius: var(--card-radius);
            border: 1px solid #f0f0f0;
        }

        .modern-card {
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none !important;
            transition: transform 0.3s;
            min-height: 95px;
        }

        /* === SPLIT LOGIC (Perbaikan Ukuran Agar Sama) === */
        .stats-split-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
        }

        .stat-item {
            flex: 1;
        }

        .stat-label {
            font-size: 9px;
            color: var(--text-muted);
            font-weight: 800;
            display: block;
            text-transform: uppercase;
            margin-bottom: 2px;
            white-space: nowrap;
            /* Agar LAB 1 tidak turun baris */
        }

        /* DISINI KUNCINYA: Samakan stat-value dengan card-value */
        .stat-value {
            font-size: 20px !important;
            /* Disamakan dengan kalender */
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
        }

        .stat-status {
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Garis Pemisah */
        .stat-item:first-child {
            border-right: 1px solid #f0f0f0;
            padding-right: 10px;
        }

        @media (max-width: 576px) {
            .stat-value {
                font-size: 18px !important;
            }

            /* Sedikit mengecil di HP agar muat */
            .modern-card {
                padding: 12px;
                gap: 8px;
            }

            .stat-item:first-child {
                padding-right: 8px;
            }
        }

        /* === ICONS & UI === */
        .icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0 !important;
        }

        .icon-box i {
            font-size: 20px;
        }

        .card-label {
            font-size: 10px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            /* Paksa satu baris */
        }

        .card-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0;
        }

        .bg-success-soft {
            background: var(--soft-green);
            color: #38a169;
        }

        .bg-secondary-soft {
            background: var(--soft-gray);
            color: #718096;
        }

        .bg-info-soft {
            background: var(--soft-cyan);
            color: #00b5ad;
        }

        .bg-primary-soft {
            background: var(--soft-blue);
            color: #0d6efd;
        }

        /* === Khusus Kalender === */
        .day-label-top {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #a0aec0;
            margin-bottom: 2px;
        }

        .calendar-flex {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .calendar-meta {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }

        .month-txt {
            font-size: 11px;
            font-weight: 700;
            color: #2d3748;
        }

        .year-txt {
            font-size: 9px;
            color: #cbd5e0;
        }

        /* WELCOME */
        .welcome-card {
            padding: 25px;
        }

        .welcome-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-main);
        }

        .welcome-line {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #3182ce, #38b2ac);
            border-radius: 10px;
            margin-top: 10px;
        }

        /* CHART & TABLE */
        .chart-card {
            padding: 20px;
        }

        .chart-wrapper {
            position: relative;
            height: 260px;
            width: 100%;
            margin-top: auto;
        }

        .view-all-link {
            font-size: 13px;
            font-weight: 700;
            color: #0d6efd;
            text-decoration: none;
        }

        .mobile-item-card {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            padding: 16px;
            border-radius: 15px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const detailButtons = document.querySelectorAll('.btn-detail');

            detailButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Ambil data dari atribut tombol
                    const data = this.dataset;

                    // Isi elemen modal
                    document.getElementById('det-id').innerText = data.id;
                    document.getElementById('det-barang').innerText = data.barang;
                    document.getElementById('det-jumlah').innerText = data.jumlah;
                    document.getElementById('det-lab').innerText = data.lab;
                    document.getElementById('det-peminjam').innerText = data.peminjam;
                    document.getElementById('det-email').innerText = data.email;
                    document.getElementById('det-wa').innerText = data.wa;
                    document.getElementById('det-pengajuan').innerText = data.pengajuan;
                    document.getElementById('det-pinjam').innerText = data.pinjam;
                    document.getElementById('det-kembali').innerText = data.kembali;
                    document.getElementById('det-keterangan').innerText = data.keterangan;

                    // Atur Badge Status
                    const statusBadge = document.getElementById('det-status');
                    statusBadge.innerText = data.status;
                    statusBadge.className = 'badge bg-warning-subtle text-warning px-3 py-2 rounded-pill';
                });
            });
        });
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
        document.addEventListener('DOMContentLoaded', function () {
            updateCalendar();

            // Opsional: Update tiap menit agar hari berganti otomatis jika tab dibiarkan terbuka
            setInterval(updateCalendar, 60000);
        });

        // Chart Kondisi Barang (Status)
        const ctxBarang = document.getElementById('chartBarang').getContext('2d');
        const statusData = @json($statusCounts); // Mengambil data dari Laravel

        new Chart(ctxBarang, {
            type: 'doughnut',
            data: {
                labels: ['Tersedia', 'Hilang', 'Diperbaiki', 'Dipinjam'],
                datasets: [{
                    data: [
                        statusData.tersedia,
                        statusData.hilang,
                        statusData.diperbaiki,
                        statusData.dipinjam
                    ],
                    backgroundColor: [
                        '#48bb78', // Hijau (Tersedia)
                        '#f56565', // Merah (Hilang)
                        '#ed8936', // Orange (Diperbaiki)
                        '#4299e1'  // Biru (Dipinjam)
                    ],
                    borderWidth: 0,
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: '600' }
                        }
                    }
                }
            }
        });
    </script>
@endpush