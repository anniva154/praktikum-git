@extends('layouts.backend')

@section('title', 'Data Barang')

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Header Card --}}
            <div class="card mb-4" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            {{-- Ukuran font disamakan: calc(1rem + 0.3vw) dan letter-spacing --}}
                            <h3 class="fw-bold text-dark mb-0"
                                style="font-size: calc(1rem + 0.3vw); letter-spacing: -0.5px;">
                                Data Barang - {{ $lab->nama_lab }}
                            </h3>
                            {{-- Ukuran font muted disamakan ke 11px dengan margin mb-2 --}}
                            <p class="text-muted mb-2" style="font-size: 11px;">
                                Lihat daftar inventaris barang laboratorium pada SIMLAB.
                            </p>
                            {{-- Warna gradien tetap biru untuk barang, tapi dimensi identik --}}
                            <div
                                style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;">
                            </div>
                        </div>

                        {{-- Slot kanan jika ingin ditambahkan tombol di masa depan, saat ini dikosongkan agar jarak tetap
                        konsisten --}}
                        <div>
                            {{-- Jika ada tombol tambah barang khusus pengguna, letakkan di sini --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">

                        <div class="row g-2 mb-4 align-items-center">

                            <div class="col-7 col-md-4">

                                <div class="position-relative">

                                    {{-- Icon diletakkan secara absolute agar berada di dalam input --}}

                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>



                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control border-light-subtle shadow-none"
                                        placeholder="Cari nama barang..."
                                        style="border-radius: 12px; height: 42px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">



                                    {{-- Tombol submit disembunyikan tapi tetap berfungsi jika user tekan 'Enter' --}}

                                    <button type="submit" class="d-none"></button>

                                </div>

                            </div>





                            {{-- KONDISI --}}
                            <div class="col-5 col-md-3">
                                <select name="kondisi" class="form-select border-light-subtle shadow-none"
                                    style="border-radius: 12px; height: 42px; font-size: 14px; background-color: #f8f9fa;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kondisi</option>
                                    @foreach (['Baik', 'Dipinjam', 'Rusak', 'Dalam Perbaikan'] as $k)
                                        <option value="{{ $k }}" {{ request('kondisi') == $k ? 'selected' : '' }}>{{ $k }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                    </form>

                    {{-- TABLE SECTION (DESKTOP) --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table align-middle">
                            <thead class="bg-light-subtle">
                                <tr>
                                    <th class="ps-3" style="width: 50px;">No.</th>
                                    <th>Nama Barang</th>
                                    <th>Kode</th>
                                    <th>Jumlah</th>
                                    <th>Kondisi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang as $index => $item)
                                    <tr>
                                        <td class="ps-3 text-muted small">{{ $barang->firstItem() + $index }}</td>
                                        <td class="fw-bold text-dark">{{ $item->nama_barang }}</td>
                                        <td class="text-muted">{{ $item->kode_barang }}</td>
                                        <td class="text-muted">{{ $item->jumlah }} Unit</td>
                                        <td>
                                            @php
                                                $kondisiClass = match ($item->kondisi) {
                                                    'Baik' => 'bg-success-subtle text-success',
                                                    'Dipinjam' => 'bg-warning-subtle text-warning',
                                                    'Rusak' => 'bg-danger-subtle text-danger',
                                                    'Dalam Perbaikan' => 'bg-primary-subtle text-primary',
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $kondisiClass }}">
                                                {{ $item->kondisi }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill {{ $item->status == 'aktif' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- CARD SECTION (MOBILE) --}}
                    <div class="d-md-none">
                        @forelse ($barang as $item)
                            <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->nama_barang }}</h6>
                                        <span
                                            class="badge rounded-pill {{ $item->status == 'aktif' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}"
                                            style="font-size: 10px;">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>

                                    <div class="text-muted small mb-3">
                                        <div class="mb-1"><i class="ti ti-barcode me-1"></i> {{ $item->kode_barang }}</div>
                                        <div class="mb-1"><i class="ti ti-package me-1"></i> {{ $item->jumlah }} Unit</div>
                                    </div>

                                    <div class="pt-2 border-top">
                                        @php
                                            $kondisiClassMob = match ($item->kondisi) {
                                                'Baik' => 'bg-success-subtle text-success',
                                                'Dipinjam' => 'bg-warning-subtle text-warning',
                                                'Rusak' => 'bg-danger-subtle text-danger',
                                                'Dalam Perbaikan' => 'bg-primary-subtle text-primary',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $kondisiClassMob }}" style="font-size: 10px;">
                                            {{ $item->kondisi }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">Data tidak ditemukan</div>
                        @endforelse
                    </div>

                    {{-- PAGINATION --}}
                    <div class="card-footer bg-transparent border-0 px-0 pb-0 mt-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <p class="text-muted small mb-0">
                                Menampilkan <strong>{{ $barang->firstItem() ?? 0 }}</strong> -
                                <strong>{{ $barang->lastItem() ?? 0 }}</strong> dari <strong>{{ $barang->total() }}</strong>
                                data
                            </p>
                            <div class="pagination-wrapper">
                                {{ $barang->withQueryString()->links() }}
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
        /* Table Styling */
        .table thead th {
            background-color: #fbfbfb;
            border-bottom: 1px solid #f1f1f1;
            padding: 12px 10px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #f8f9fa;
            font-size: 0.85rem;
        }

        /* Badge subtle effects */
        .badge {
            font-weight: 600 !important;
            padding: 6px 12px !important;
            font-size: 11px;
        }

        .bg-success-subtle {
            background-color: #e6fffa !important;
            color: #00b19d !important;
            border: 1px solid #00b19d20;
        }

        .bg-primary-subtle {
            background-color: #ecf2ff !important;
            color: #5d87ff !important;
            border: 1px solid #5d87ff20;
        }

        .bg-danger-subtle {
            background-color: #fef5f5 !important;
            color: #fa896b !important;
            border: 1px solid #fa896b20;
        }

        .bg-warning-subtle {
            background-color: #fff8ec !important;
            color: #ffae1f !important;
            border: 1px solid #ffae1f20;
        }

        .bg-info-subtle {
            background-color: #e7f1ff !important;
            color: #007bff !important;
            border: 1px solid #007bff20;
        }

        .bg-dark-subtle {
            background-color: #f8f9fa !important;
            color: #343a40 !important;
            border: 1px solid #343a4020;
        }

        /* Form Focus */
        .form-control:focus,
        .form-select:focus {
            border-color: #007bff !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1) !important;
            background-color: #fff !important;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem !important;
            }

            .pagination {
                justify-content: center;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Fitur auto-search saat mengetik
        let timer;
        const searchInput = document.querySelector('input[name="search"]');
        searchInput?.addEventListener('keyup', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 800);
        });
    </script>
@endpush