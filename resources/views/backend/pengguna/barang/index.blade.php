@extends('layouts.backend')

@section('title', 'Data Barang')

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- Header Card --}}
            <div class="card mb-4 border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        {{-- Lingkaran Ikon Biru --}}
                        <div class="me-3 p-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px; background-color: #e7f1ff !important; flex-shrink: 0;">
                                {{-- Menggunakan warna biru #007bff agar nyambung dengan gradien --}}
                                <i class="ti ti-package" style="color: #007bff !important; font-size: 1.8rem;"></i>
                            </div>
                        <div>
                            <h3 class="fw-bold text-dark mb-0"
                                style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                                Data Barang
                            </h3>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{ $lab->nama_lab }}</p>
                        </div>
                    </div>
                     <div class="mt-3"
                        style="height: 4px; width: 100px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">

                    {{-- TOOLBAR: Search & Filters --}}
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">
                        <div class="row g-2 mb-4 align-items-center">
                            <div class="col-7 col-md-4">
                                <div class="position-relative">
                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control border-0 shadow-none"
                                        placeholder="Cari nama atau kode barang..."
                                        style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                                </div>
                            </div>

                            <div class="col-5 col-md-3">
                                <select name="kondisi" class="form-select border-0 shadow-none"
                                    style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; cursor: pointer;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kondisi</option>
                                    @foreach (['Baik', 'Rusak', 'Dipinjam', 'Dalam Perbaikan'] as $k)
                                        <option value="{{ $k }}" {{ request('kondisi') == $k ? 'selected' : '' }}>{{ $k }}
                                        </option>
                                    @endforeach
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
                                    <th width="30%">Nama Barang</th>
                                    <th width="15%">Kode</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="15%">Kondisi</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang as $index => $item)
                                    <tr>
                                        <td class="ps-3 text-muted">{{ $barang->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                                        </td>
                                        <td><code class="text-primary fw-medium">{{ $item->kode_barang }}</code></td>
                                        <td>
                                            <span class="fw-semibold">{{ $item->jumlah }}</span>
                                            <small class="text-muted">Unit</small>
                                        </td>
                                        <td>
                                            @php
                                                $kondisiClass = match ($item->kondisi) {
                                                    'baik' => 'bg-success-subtle text-success',
                                                    'rusak ringan' => 'bg-warning-subtle text-warning',
                                                    'rusak berat' => 'bg-danger-subtle text-danger',
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $kondisiClass }}">
                                                {{ ucfirst($item->kondisi) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge rounded-pill px-3 py-2  {{ $item->status == 'aktif' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}">
                                                {{ ucfirst($item->status) }}
                                            </span>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Data barang tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- CARD SECTION (MOBILE) --}}
                    <div class="d-md-none">
                        @forelse ($barang as $item)
                            <div class="card border-0 bg-light-subtle mb-3" style="border-radius: 15px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->nama_barang }}</h6>
                                        <span
                                            class="badge {{ $item->status == 'aktif' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}"
                                            style="font-size: 9px;">
                                            {{ strtoupper($item->status) }}
                                        </span>
                                    </div>

                                    <div class="row g-0 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Kode</small>
                                            <span class="fw-medium small text-primary">{{ $item->kode_barang }}</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted d-block">Jumlah</small>
                                            <span class="fw-medium small">{{ $item->jumlah }} Unit</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <small class="text-muted">Kondisi:</small>
                                        @php
                                            $kondisiClassMob = match ($item->kondisi) {
                                                'baik' => 'bg-success-subtle text-success',
                                                'rusak ringan' => 'bg-warning-subtle text-warning',
                                                'rusak berat' => 'bg-danger-subtle text-danger',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp
                                        <span class="badge {{ $kondisiClassMob }}">
                                            {{ ucfirst($item->kondisi) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">Data tidak ditemukan</div>
                        @endforelse
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <p class="text-muted small mb-0">
                                Menampilkan <strong>{{ $barang->firstItem() }}</strong> -
                                <strong>{{ $barang->lastItem() }}</strong> dari <strong>{{ $barang->total() }}</strong> data
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

        /* Badge modern style (Disamakan dengan pengguna) */
        .badge {
            font-weight: 600 !important;
            padding: 6px 14px !important;
            border-radius: 8px !important;
            font-size: 11px;
        }

        /* Badge Style */
        .badge {
            font-weight: 700 !important;
            font-size: 10px !important;
            letter-spacing: 0.3px;
        }

        /* Palette Warna Soft */
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
    </style>
@endpush

@push('scripts')
    <script>
        // Fitur auto-search dengan debounce
        let timer;
        const searchInput = document.querySelector('input[name="search"]');
        searchInput?.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 800);
        });
    </script>
@endpush