@extends('layouts.backend')

@section('title', 'Data Peminjaman Admin')

@section('content')
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
                        <span id="det-status" class="badge"></span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border border-dashed">
                                <small class="text-muted d-block mb-1">Informasi Barang</small>
                                <h6 id="det-barang" class="fw-bold text-primary mb-1"></h6>
                                <p class="mb-0 small text-dark"><i class="ti ti-package me-1"></i> <span
                                        id="det-jumlah"></span> Unit</p>
                                <p class="mb-0 small text-dark"><i class="ti ti-building me-1"></i> Lab: <span
                                        id="det-lab"></span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border border-dashed">
                                <small class="text-muted d-block mb-1">Informasi Peminjam</small>
                                <h6 id="det-peminjam" class="fw-bold text-dark mb-1"></h6>
                                <p class="mb-0 small text-dark"><i class="ti ti-mail me-1"></i> <span id="det-email"></span>
                                </p>
                                <p class="mb-0 small text-dark"><i class="ti ti-brand-whatsapp me-1 text-success"></i> <span
                                        id="det-wa"></span></p>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 small text-uppercase" style="letter-spacing: 1px;">Log Waktu</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered custom-log-table mb-0">
                            <tbody>
                                <tr>
                                    <th class="bg-light-subtle text-muted" width="40%">Waktu Pengajuan</th>
                                    <td id="det-pengajuan" class="fw-medium"></td>
                                </tr>
                                <tr>
                                    <th class="bg-light-subtle text-muted">Waktu Pinjam (Jadwal)</th>
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
                    <div id="det-keterangan" class="p-3 bg-light rounded-3 border small text-muted"
                        style="min-height: 60px;"></div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            {{-- NOTIFIKASI --}}
            <div class="mb-2">
                @include('components.notification')
            </div>

            {{-- Header Card Monitoring Peminjaman --}}
<div class="card mb-4" style="border-radius: 20px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center">
            <div class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                style="width: 55px; height: 55px; background-color: #fff8e1;">
                <i class="ti ti-refresh text-warning fs-7" style="color: #ffb300 !important;"></i>
            </div>
            
            <div>
                <h3 class="fw-bold text-dark mb-0"
                    style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                    Monitoring Peminjaman
                </h3>
                <p class="text-muted mb-0" style="font-size: 13px;">{{ $lab->nama_lab }}</p>
            </div>
        </div>

        <div class="mt-3"
            style="height: 4px; width: 150px; background: linear-gradient(to right, #ffb300, #ffe082); border-radius: 2px;">
        </div>
    </div>
</div>

            <div class="card" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">

                    {{-- TOOLBAR: Search & Filters --}}
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">
                        <div class="row g-2 mb-4 align-items-center">
                            <div class="col-7 col-md-4">
                                <div class="position-relative">
                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control border-0 shadow-none" placeholder="Cari barang atau peminjam..."
                                        style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                                </div>
                            </div>

                            <div class="col-5 col-md-3">
                                <select name="status" class="form-select border-0 shadow-none"
                                    style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; cursor: pointer;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Menunggu
                                    </option>
                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                        Disetujui</option>
                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                    </option>
                                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                                        Dikembalikan</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    {{-- TABLE SECTION (DESKTOP) --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3" width="5%">No.</th>
                                    <th>Barang & Peminjam</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Waktu Pinjam</th>
                                    <th class="text-center">Waktu Kembali</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjaman as $index => $item)
                                    @php
                                        $statusClass = match ($item->status) {
                                            'disetujui' => 'bg-success-subtle text-success',
                                            'ditolak' => 'bg-danger-subtle text-danger',
                                            'dikembalikan' => 'bg-info-subtle text-info',
                                            'diajukan' => 'bg-warning-subtle text-warning',
                                            default => 'bg-light text-dark'
                                        };
                                    @endphp
                                    <tr>
                                        <td class="ps-3 text-muted small">{{ $peminjaman->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 14px;">
                                                {{ $item->barang->nama_barang }}
                                            </div>
                                            <div class="text-primary fw-medium" style="font-size: 11px;">
                                                <i class="ti ti-user me-1"></i>{{ $item->user->name }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold text-dark">{{ $item->jumlah_pinjam }}</span>
                                            <small class="text-muted small">Unit</small>
                                        </td>
                                        <td class="text-center small">
                                            {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            @if($item->waktu_kembali)
                                                <span class="text-info small fw-medium">
                                                    {{ \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') }}
                                                </span>
                                            @else
                                                <span class="text-muted small italic">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ $statusClass }}">
                                                {{ $item->status == 'diajukan' ? 'Menunggu' : ucfirst($item->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-flex justify-content-end gap-1">
                                                <button type="button" class="btn btn-sm btn-light-primary p-2 btn-detail"
                                                    style="border-radius: 8px;" data-bs-toggle="modal"
                                                    data-bs-target="#modalDetail" data-id="{{ $item->id_peminjaman }}"
                                                    data-barang="{{ $item->barang->nama_barang }}"
                                                    data-peminjam="{{ $item->user->name }}"
                                                    data-email="{{ $item->user->email }}"
                                                    data-wa="{{ $item->user->no_wa ?? '-' }}"
                                                    data-jumlah="{{ $item->jumlah_pinjam }}"
                                                    data-lab="{{ $item->barang->laboratorium->nama_lab }}"
                                                    data-pengajuan="{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y - H:i') }}"
                                                    data-pinjam="{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d F Y - H:i') }}"
                                                    data-kembali="{{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d F Y - H:i') : 'Belum Dikembalikan' }}"
                                                    data-status="{{ $item->status == 'diajukan' ? 'Menunggu' : $item->status }}"
                                                    data-statusclass="{{ $statusClass }}"
                                                    data-keterangan="{{ $item->keterangan ?? 'Tidak ada keterangan tambahan.' }}">
                                                    <i class="ti ti-eye fs-5"></i>
                                                </button>

                                                <form
                                                    action="{{ route('admin.peminjaman.destroy', [$lab->id_lab, $item->id_peminjaman]) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger p-2"
                                                        style="border-radius: 8px;"
                                                        onclick="return confirm('Hapus record ini?')">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted small">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE VIEW --}}
                    <div class="d-md-none">
                        @foreach ($peminjaman as $item)
                            <div class="card border border-secondary-subtle bg-light-subtle mb-3" style="border-radius: 15px;">

                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0 small">{{ $item->barang->nama_barang }}</h6>
                                        <span
                                            class="badge {{ $item->status == 'dikembalikan' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }}"
                                            style="font-size: 8px;">
                                            {{ strtoupper($item->status) }}
                                        </span>
                                    </div>
                                    <div class="row g-0 py-2 border-top border-bottom mb-2">
                                        <div class="col-6 border-end">
                                            <small class="text-muted d-block" style="font-size: 9px;">Pinjam:</small>
                                            <span class="fw-medium small"
                                                style="font-size: 10px;">{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}</span>
                                        </div>
                                        <div class="col-6 ps-2">
                                            <small class="text-muted d-block" style="font-size: 9px;">Kembali:</small>
                                            <span class="fw-medium small text-info" style="font-size: 10px;">
                                                {{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') : 'Belum' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-primary fw-bold">{{ $item->user->name }}</small>
                                        {{-- PERBAIKAN: Ganti a menjadi button untuk Modal Mobile --}}
                                        <button type="button"
                                            class="btn btn-sm btn-link text-decoration-none p-0 fw-bold btn-detail"
                                            style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#modalDetail"
                                            data-id="{{ $item->id_peminjaman }}" data-barang="{{ $item->barang->nama_barang }}"
                                            data-peminjam="{{ $item->user->name }}" data-email="{{ $item->user->email }}"
                                            data-wa="{{ $item->user->no_wa ?? '-' }}" data-jumlah="{{ $item->jumlah_pinjam }}"
                                            data-lab="{{ $item->barang->laboratorium->nama_lab }}"
                                            data-pengajuan="{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/y H:i') }}"
                                            data-pinjam="{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}"
                                            data-kembali="{{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') : 'Belum Kembali' }}"
                                            data-status="{{ $item->status == 'diajukan' ? 'Menunggu' : $item->status }}"
                                            data-statusclass="{{ $statusClass }}"
                                            data-keterangan="{{ $item->keterangan ?? 'Tidak ada keterangan tambahan.' }}">
                                            Detail >
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <p class="text-muted small mb-0">
                                Menampilkan <strong>{{ $peminjaman->firstItem() ?? 0 }}</strong> -
                                <strong>{{ $peminjaman->lastItem() ?? 0 }}</strong> dari
                                <strong>{{ $peminjaman->total() }}</strong> data
                            </p>
                            <div>
                                {{ $peminjaman->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Tambahan untuk Modal */
        .border-dashed {
            border-style: dashed !important;
        }

        .w-40 {
            width: 40%;
        }

        .modal-content {
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Warna teks khusus untuk pembeda */
        #det-kembali {
            color: #007bff !important;
            /* Warna info untuk tgl kembali */
        }

        /* Perbaikan style tabel dalam modal */
        .table-sm th {
            font-weight: 600;
            color: #64748b;
        }

        /* Membuat border tabel log terlihat jelas */
        .custom-log-table {
            border: 1px solid #e0e6ed !important;
            border-collapse: collapse !important;
        }

        .custom-log-table th,
        .custom-log-table td {
            border: 1px solid #e0e6ed !important;
            /* Warna abu-abu border yang lebih tegas */
            padding: 12px 15px !important;
            vertical-align: middle;
            font-size: 14px;
        }

        .custom-log-table th {
            background-color: #f8f9fa !important;
            /* Warna background header (kiri) */
            font-weight: 600;
            color: #526484 !important;
        }

        /* Warna biru khusus untuk Waktu Kembali sesuai gambar */
        #det-kembali {
            color: #007bff !important;
            /* Biru cerah */
        }

        /* Tambahan agar modal terlihat lebih premium */
        .modal-content {
            border: none !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* 1. Table Styling (Identik dengan Lab) */
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
            color: #2d3436;
            /* Memastikan teks (termasuk waktu) berwarna gelap standar */
        }

        /* 2. Badge Modern Style (Disederhanakan agar tidak bentrok) */
        .badge {
            font-weight: 700 !important;
            padding: 6px 14px !important;
            border-radius: 8px !important;
            font-size: 10px !important;
            letter-spacing: 0.3px;
            text-transform: capitalize !important;
            /* Agar tulisan status tidak huruf besar semua */
        }

        /* 3. Palette Warna Soft (Konsisten) */
        .bg-success-subtle {
            background-color: #e6fffa !important;
            color: #00b19d !important;
        }

        .bg-danger-subtle {
            background-color: #fef5f5 !important;
            color: #fa896b !important;
        }

        .bg-warning-subtle {
            background-color: #fff8ec !important;
            color: #ffae1f !important;
        }

        .bg-info-subtle {
            background-color: #e7f1ff !important;
            color: #007bff !important;
        }

        .bg-primary-subtle {
            background-color: #ecf2ff !important;
            color: #5d87ff !important;
        }

        .bg-dark-subtle {
            background-color: #f8f9fa !important;
            color: #343a40 !important;
        }

        /* 4. Custom scrollbar untuk table responsive */
        .table-responsive::-webkit-scrollbar {
            height: 5px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #e0e0e0;
            border-radius: 10px;
        }

        /* 5. Mobile Adjustments */
        @media (max-width: 768px) {
            .badge {
                padding: 4px 10px !important;
                font-size: 9px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto-search dengan debounce (seperti di data barang)
        let timer;
        const searchInput = document.querySelector('input[name="search"]');
        searchInput?.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 800);
        });
        $(document).ready(function () {
            $('.btn-detail').on('click', function () {
                const data = $(this).data();

                // Mapping data ke elemen modal
                $('#det-id').text(data.id);
                $('#det-barang').text(data.barang);
                $('#det-peminjam').text(data.peminjam);
                $('#det-email').text(data.email);
                $('#det-wa').text(data.wa);
                $('#det-jumlah').text(data.jumlah);
                $('#det-lab').text(data.lab);
                $('#det-pengajuan').text(data.pengajuan);
                $('#det-pinjam').text(data.pinjam);
                $('#det-kembali').text(data.kembali);
                $('#det-keterangan').text(data.keterangan);

                // Atur Status Badge (Warna mengikuti statusClass dari PHP)
                $('#det-status').text(data.status)
                    .removeClass()
                    .addClass('badge px-3 py-2 ' + data.statusclass);
            });
        });
    </script>
@endpush