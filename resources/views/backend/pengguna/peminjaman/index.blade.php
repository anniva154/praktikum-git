@extends('layouts.backend')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="row">
    <div class="col-12">
        {{-- NOTIFIKASI --}}
        <div class="mb-2">
            @include('components.notification')
        </div>

        {{-- Header Card --}}
<div class="card mb-4 border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                {{-- Lingkaran Ikon Kuning --}}
                <div class="me-3 p-3 rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 55px; height: 55px; background-color: #fff8e1 !important; flex-shrink: 0;">
                    <i class="ti ti-repeat" style="color: #ffa000 !important; font-size: 1.8rem !important;"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                        Riwayat Peminjaman
                    </h3>
                    <p class="text-muted mb-0" style="font-size: 13px;">Kelola pengembalian dan pantau status barang</p>
                </div>
            </div>
            <div>
                <a href="{{ route('pengguna.peminjaman.create') }}"
                   class="btn btn-success d-inline-flex align-items-center shadow-sm"
                   style="border-radius: 12px; padding: 10px 20px; transition: all 0.3s;">
                    <i class="ti ti-plus fs-5 me-2"></i>
                    <span class="fw-bold" style="font-size: 14px;">Ajukan Pinjaman</span>
                </a>
            </div>
        </div>

        {{-- Garis Gradien dengan Jarak (Margin Top) --}}
        <div style="height: 4px; width: 100px; background: linear-gradient(to right,  #ffae1f, #ffd88d); border-radius: 2px; margin-top: 20px;"></div>
    </div>
</div>

        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-3 p-md-4">
                {{-- TOOLBAR --}}
                <form method="GET" action="{{ url()->current() }}" id="filterForm">
                    <div class="row g-2 mb-4 align-items-center">
                        <div class="col-7 col-md-4">
                            <div class="position-relative">
                                <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="form-control border-0 shadow-none"
                                    placeholder="Cari nama barang..."
                                    style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                            </div>
                        </div>
                        <div class="col-5 col-md-3">
                            <select name="status" class="form-select border-0 shadow-none"
                                style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; cursor: pointer;"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Menunggu</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui (Dipinjam)</option>
                                <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Sedang Dicek</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                    </div>
                </form>

               {{-- DESKTOP VIEW --}}
<div class="table-responsive d-none d-md-block">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th class="ps-3">No.</th>
                <th>Nama Barang / LAB</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Jadwal Pinjam & Kembali</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi / Info</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjaman as $index => $item)
                @php
                    // Pemetaan Class CSS sesuai status database
                    $statusClass = match($item->status) {
                        'disetujui'           => 'status-baik',
                        'ditolak'             => 'status-rusak',
                        'menunggu_konfirmasi' => 'status-cek',
                        'dikembalikan'        => 'status-kembali',
                        default               => 'status-proses' // untuk status 'diajukan'
                    };

                    $statusLabel = match($item->status) {
                        'diajukan'            => 'Menunggu',
                        'disetujui'           => 'Dipinjam',
                        'menunggu_konfirmasi' => 'Sedang Dicek',
                        'dikembalikan'        => 'Selesai',
                        default               => ucfirst($item->status)
                    };
                @endphp
                <tr>
                    <td class="ps-3 text-muted small">{{ $peminjaman->firstItem() + $index }}</td>
                    <td>
                        <span class="fw-bold text-dark d-block" style="font-size: 14px;">{{ $item->barang->nama_barang }}</span>
                        <span class="text-primary fw-medium" style="font-size: 12px;">{{ $item->barang->laboratorium->nama_lab ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        <span class="fw-bold text-dark">{{ $item->jumlah_pinjam }}</span> <span class="text-muted small">Unit</span>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1 text-center" style="font-size: 12px;">
                            <div class="fw-medium text-dark"><i class="ti ti-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}</div>
                            <div class="text-muted small"><i class="ti ti-calendar-check me-1"></i>{{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') : '-' }}</div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="custom-badge {{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($item->status == 'disetujui')
                            <form action="{{ route('pengguna.peminjaman.ajukanKembali', $item->id_peminjaman) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary shadow-sm px-3" style="border-radius: 8px;" onclick="return confirm('Apakah Anda yakin sudah mengembalikan barang ini?')">
                                    <i class="ti ti-package-import me-1"></i> Kembalikan
                                </button>
                            </form>
                        @elseif($item->status == 'menunggu_konfirmasi')
                            <small class="text-info fw-bold"><i class="ti ti-loader me-1"></i>Menunggu Verifikasi</small>
                        @elseif($item->status == 'dikembalikan')
                            <div class="text-success small fw-bold">
                                <i class="ti ti-circle-check me-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d/m/y H:i') }}
                            </div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

                {{-- MOBILE VIEW --}}
                <div class="d-md-none">
                    @foreach ($peminjaman as $item)
                        @php
                             $statusConfig = match($item->status) {
                                'disetujui' => ['class' => 'bg-primary-subtle text-primary', 'label' => 'Dipinjam'],
                                'dikembalikan' => ['class' => 'bg-success text-white', 'label' => 'Selesai'],
                                'menunggu_konfirmasi' => ['class' => 'bg-info text-white', 'label' => 'Sedang Dicek'],
                                'ditolak' => ['class' => 'bg-danger text-white', 'label' => 'Ditolak'],
                                default => ['class' => 'bg-warning text-dark', 'label' => 'Menunggu']
                            };
                        @endphp
                        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">{{ $item->barang->nama_barang }}</h6>
                                        <small class="text-primary fw-medium" style="font-size: 11px;">{{ $item->barang->laboratorium->nama_lab ?? '-' }}</small>
                                    </div>
                                    <span class="badge {{ $statusConfig['class'] }}" style="font-size: 10px; border-radius: 8px; padding: 5px 10px;">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </div>

                                {{-- Info Tanggal Pengembalian (Jika sudah selesai) --}}
                                @if($item->status == 'dikembalikan' && $item->tanggal_dikembalikan)
                                    <div class="bg-success-subtle p-2 rounded-3 mb-2 border-start border-3 border-success">
                                        <small class="text-success d-block fw-bold" style="font-size: 9px; text-transform: uppercase;">Sudah Dikembalikan Pada:</small>
                                        <span class="fw-bold text-success" style="font-size: 12px;">{{ \Carbon\Carbon::parse($item->tanggal_dikembalikan)->format('d/m/y H:i') }}</span>
                                    </div>
                                @endif

                                <div class="bg-light p-2 rounded-3 mb-3">
                                    <small class="text-muted d-block" style="font-size: 9px; font-weight: 700; text-transform: uppercase;">Keperluan:</small>
                                    <p class="mb-0 text-dark" style="font-size: 12px; line-height: 1.4;">{{ $item->keterangan ?? '-' }}</p>
                                </div>

                                <div class="row g-0 pt-2 border-top align-items-center">
                                    <div class="col-4 border-end text-center">
                                        <small class="text-muted d-block" style="font-size: 10px;">Jumlah</small>
                                        <span class="fw-bold text-dark" style="font-size: 13px;">{{ $item->jumlah_pinjam }}</span>
                                    </div>
                                    <div class="col-8 ps-3">
                                        @if($item->status == 'disetujui')
                                            <form action="{{ route('pengguna.peminjaman.ajukanKembali', $item->id_peminjaman) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary w-100" style="border-radius: 8px;">Kembalikan Barang</button>
                                            </form>
                                        @elseif($item->status == 'menunggu_konfirmasi')
                                            <div class="text-info fw-bold text-center" style="font-size: 11px;">
                                                <i class="ti ti-loader me-1"></i>Menunggu Verifikasi
                                            </div>
                                        @else
                                            <small class="text-muted d-block" style="font-size: 10px;">Waktu Pinjam</small>
                                            <span class="fw-bold text-dark" style="font-size: 11px;">{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0">Menampilkan {{ $peminjaman->count() }} data</p>
                    <div>{{ $peminjaman->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
      /* Table Styling */
        .table thead th {
            background-color: #fbfbfb;
            border-bottom: 1px solid #f1f1f1;
            padding: 15px 10px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #7c8fac;
        }

        .table tbody td {
            padding: 15px 10px;
            border-bottom: 1px solid #f8f9fa;
            font-size: 0.9rem;
        }

        /* Custom scrollbar untuk table responsive */
        .table-responsive::-webkit-scrollbar {
            height: 5px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #e0e0e0;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .badge {
                padding: 4px 10px !important;
                font-size: 10px;
            }
        }

    .custom-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
        min-width: 70px;
        line-height: 1.2;
    }

    /* Warna Biru Soft (Disetujui/Selesai) */
    .status-baik {
        background-color: #e7f1ff;
        color: #007bff;
    }

    /* Warna Merah/Oranye Soft (Ditolak/Dibatalkan) */
    .status-rusak {
        background-color: #fff0f0;
        color: #c32802;
        border: 1px solid #ffe5e5;
    }

    /* Warna Abu-abu Soft (Pending/Menunggu) */
   /* Warna Kuning Soft (Pending/Menunggu) */
.status-proses {
    background-color: #fff9db; /* Latar belakang kuning muda */
    color: #f59f00;            /* Teks oranye/kuning gelap agar kontras & terbaca */
    border: 1px solid #ffec99;  /* Border tipis agar lebih tegas */
}
/* Warna Hijau Soft (Sudah Dikembalikan) */
    .status-kembali {
        background-color: #e6fffa;
        color: #088974;
        border: 1px solid #b2f5ea;
    }

    /* Warna Biru Muda/Cyan (Proses Verifikasi) */
    .status-cek {
        background-color: #e0f7fa;
        color: #00acc1;
        border: 1px solid #b2ebf2;
    }
</style>
@endpush