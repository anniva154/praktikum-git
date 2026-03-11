@extends('layouts.backend')

@section('title', 'Data Barang')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- NOTIFIKASI --}}
            <div class="mb-2">
                @include('components.notification')
            </div>


            {{-- Header Card --}}
            <div class="card mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            {{-- Lingkaran Ikon Biru untuk Barang --}}
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

                        {{-- Tombol Tambah di Sisi Kanan (Seperti Data Lab) --}}
                        <a href="{{ route('kaproli.barang.create', $lab->id_lab) }}"
                            class="btn btn-success d-inline-flex align-items-center shadow-sm px-3 py-2"
                            style="border-radius: 12px; transition: all 0.3s;">
                            <i class="ti ti-plus fs-5 me-1"></i>
                            <span class="fw-bold" style="font-size: 14px;">Tambah</span>
                        </a>
                    </div>

                    {{-- Garis Gradien Biru --}}
                    <div class="mt-3"
                        style="height: 4px; width: 100px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;">
                    </div>
                </div>
            </div>

            <div class="card" style="border-radius: 20px; border: none;">
                <div class="card-body p-3 p-md-4">

                    {{-- TOOLBAR: Search & Filters --}}
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">
                        <div class="row g-2 mb-4 align-items-center">
                            <div class="col-7 col-md-4">
                                <div class="position-relative">
                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control border-0 shadow-none bg-light"
                                        placeholder="Cari nama atau kode..."
                                        style="border-radius: 12px; height: 45px; padding-left: 45px;">
                                </div>
                            </div>

                            <div class="col-5 col-md-3">
                                <select name="kondisi" class="form-select border-0 shadow-none bg-light"
                                    style="border-radius: 12px; height: 45px; cursor: pointer;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kondisi</option>
                                    <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak" {{ request('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    {{-- DESKTOP TABLE --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">No.</th>
                                    <th>Nama Barang</th>
                                    <th>Kode</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Kondisi</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
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
                                        <td class="text-center"><span class="fw-semibold">{{ $item->jumlah }}</span> <small
                                                class="text-muted">Unit</small></td>
                                        {{-- Kolom Kondisi --}}
                                        <td class="text-center">
                                            @php
                                                // Menggunakan class solid Bootstrap agar background pekat dan teks putih
                                                $isBaik = strtolower($item->kondisi) == 'baik';
                                                $kondisiClass = $isBaik ? 'bg-success' : 'bg-danger';
                                            @endphp
                                            <span class="badge {{ $kondisiClass }} text-white border-0 shadow-sm">
                                                {{ ucfirst($item->kondisi) }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            @php
                                                $statusRaw = strtolower($item->status);
                                                $statusClass = match ($statusRaw) {
                                                    'tersedia' => 'bg-primary-subtle text-primary',
                                                    'dipinjam' => 'bg-info-subtle text-info',
                                                    'perbaikan' => 'bg-warning-subtle text-warning',
                                                    'hilang' => 'bg-danger-subtle text-danger',
                                                    default => 'bg-light text-dark'
                                                };
                                                $statusLabel = $statusRaw == 'perbaikan' ? 'Perbaikan' : ucfirst($statusRaw);
                                            @endphp
                                            <span class="badge {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('kaproli.barang.edit', [$lab->id_lab, $item->id_barang]) }}"
                                                    class="btn btn-sm btn-light-primary p-0 d-flex align-items-center justify-content-center"
                                                    style="width: 35px; height: 35px; border-radius: 10px;">
                                                    <i class="ti ti-pencil fs-5"></i>
                                                </a>
                                                <form
                                                    action="{{ route('kaproli.barang.destroy', [$lab->id_lab, $item->id_barang]) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-light-danger p-0 d-flex align-items-center justify-content-center"
                                                        style="width: 35px; height: 35px; border-radius: 10px;"
                                                        onclick="return confirm('Hapus barang ini?')">
                                                        <i class="ti ti-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">Data barang tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE CARD SECTION --}}
                    <div class="d-md-none">
                        @forelse ($barang as $item)
                            @php
                                // Logika Warna Status (Subtle dari CSS)
                                $statusRaw = strtolower($item->status);
                                $statusClass = match ($statusRaw) {
                                    'tersedia' => 'bg-primary-subtle',
                                    'dipinjam' => 'bg-info-subtle',
                                    'perbaikan' => 'bg-warning-subtle',
                                    'hilang' => 'bg-danger-subtle',
                                    default => 'bg-dark-subtle'
                                };
                                $statusLabel = $statusRaw == 'perbaikan' ? 'Perbaikan' : ucfirst($statusRaw);

                                // Logika Warna Kondisi (Solid sesuai permintaan)
                                $isBaik = strtolower($item->kondisi) == 'baik';
                                $kondisiClass = $isBaik ? 'bg-success' : 'bg-danger';
                            @endphp

                            <div class="card border border-light-subtle mb-3 shadow-none bg-light-subtle"
                                style="border-radius: 15px;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold text-dark mb-0">{{ $item->nama_barang }}</h6>
                                        {{-- Status: Pakai warna subtle --}}
                                        <span class="badge {{ $statusClass }}" style="font-size: 9px;">
                                            {{ strtoupper($statusLabel) }}
                                        </span>
                                    </div>

                                    <div class="row g-0 mb-3 small">
                                        <div class="col-6">
                                            <small class="text-muted d-block">Kode</small>
                                            <span class="fw-medium text-primary">{{ $item->kode_barang }}</span>
                                        </div>
                                        <div class="col-6 text-end">
                                            <small class="text-muted d-block">Jumlah</small>
                                            <span class="fw-medium">{{ $item->jumlah }} Unit</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mb-3">
                                        <small class="text-muted">Kondisi:</small>
                                        {{-- Kondisi: Pakai warna Solid & Teks Putih --}}
                                        <span class="badge {{ $kondisiClass }} text-white border-0"
                                            style="font-size: 10px; padding: 5px 10px;">
                                            {{ ucfirst($item->kondisi) }}
                                        </span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('kaproli.barang.edit', [$lab->id_lab, $item->id_barang]) }}"
                                            class="btn btn-light-primary btn-sm w-100" style="border-radius: 8px;">Edit</a>
                                        <form action="{{ route('kaproli.barang.destroy', [$lab->id_lab, $item->id_barang]) }}"
                                            method="POST" class="w-100">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-light-danger btn-sm w-100" style="border-radius: 8px;"
                                                onclick="return confirm('Hapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">Data tidak ditemukan</div>
                        @endforelse
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <p class="text-muted small mb-0">Menampilkan {{ $barang->count() }} dari {{ $barang->total() }} data
                        </p>
                        <div class="pagination-sm">{{ $barang->withQueryString()->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
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


        .badge {
            font-weight: 600 !important;
            padding: 6px 14px !important;
            border-radius: 8px !important;
            font-size: 11px;
        }


        .badge {
            font-weight: 700 !important;
            font-size: 10px !important;
            letter-spacing: 0.3px;
        }


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