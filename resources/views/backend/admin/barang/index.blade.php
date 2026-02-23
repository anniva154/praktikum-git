@extends('layouts.backend')

@section('title', 'Data Barang')

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Header Card --}}
        <div class="card mb-4" style="border-radius: 20px;">
            <div class="card-body p-4">
                <h3 class="fw-bold text-dark">Data Barang - {{ $lab->nama_lab }}</h3>
                <p class="text-muted mb-3">Lihat daftar inventaris barang laboratorium pada SIMLAB.</p>
                <div style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="card" style="border-radius: 20px;">
            <div class="card-body p-3 p-md-4">
                
                {{-- TOOLBAR: Search & Filters --}}
                <form method="GET" action="{{ route('admin.barang.lab', $lab) }}" id="filterForm">
                    <div class="row g-2 mb-4 align-items-center">
                        {{-- SEARCH --}}
                        <div class="col-7 col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control border-light-subtle shadow-none" 
                                       placeholder="Cari barang..." 
                                       style="border-radius: 10px 0 0 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;">
                                <button class="btn btn-primary px-3" type="submit" style="border-radius: 0 10px 10px 0;">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- KONDISI --}}
                        <div class="col-5 col-md-3">
                            <select name="kondisi" class="form-select border-light-subtle shadow-none" 
                                    style="border-radius: 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;" 
                                    onchange="this.form.submit()">
                                <option value="">Kondisi</option>
                                <option value="baik" {{ request('kondisi')=='baik'?'selected':'' }}>Baik</option>
                                <option value="rusak ringan" {{ request('kondisi')=='rusak ringan'?'selected':'' }}>Rusak Ringan</option>
                                <option value="rusak berat" {{ request('kondisi')=='rusak berat'?'selected':'' }}>Rusak Berat</option>
                            </select>
                        </div>

                        {{-- RESET BUTTON --}}
                        <div class="col-12 col-md-auto ms-auto d-flex">
                            @if(request()->hasAny(['kondisi','search']))
                                <a href="{{ url()->current() }}" 
                                   class="btn btn-light-danger d-flex align-items-center justify-content-center flex-grow-1 flex-md-grow-0" 
                                   style="border-radius: 10px; height: 42px; min-width: 100px;">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- TABLE SECTION (DESKTOP) --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table align-middle">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th class="ps-3">No.</th>
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
                                            $kondisiClass = match($item->kondisi) {
                                                'baik' => 'bg-success-subtle text-success',
                                                'rusak ringan' => 'bg-warning-subtle text-warning',
                                                'rusak berat' => 'bg-danger-subtle text-danger',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp
                                        <span class="badge rounded-pill {{ $kondisiClass }}">
                                            {{ ucfirst($item->kondisi) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $item->status == 'aktif' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Data tidak ditemukan</td></tr>
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
                                    <span class="badge rounded-pill {{ $item->status == 'aktif' ? 'bg-info-subtle text-info' : 'bg-dark-subtle text-dark' }}" style="font-size: 10px;">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                
                                <div class="text-muted small mb-3">
                                    <div class="mb-1"><i class="ti ti-barcode me-1"></i> {{ $item->kode_barang }}</div>
                                    <div class="mb-1"><i class="ti ti-package me-1"></i> {{ $item->jumlah }} Unit</div>
                                </div>

                                <div class="pt-2 border-top">
                                    @php
                                        $kondisiClassMob = match($item->kondisi) {
                                            'baik' => 'bg-success-subtle text-success',
                                            'rusak ringan' => 'bg-warning-subtle text-warning',
                                            'rusak berat' => 'bg-danger-subtle text-danger',
                                            default => 'bg-light text-dark'
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $kondisiClassMob }}" style="font-size: 10px;">
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
<div class="card-footer bg-transparent border-0 px-4 pb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        {{-- Info Data --}}
        <p class="text-muted small mb-0">
            Menampilkan <strong>{{ $barang->firstItem() }}</strong> - <strong>{{ $barang->lastItem() }}</strong> dari <strong>{{ $barang->total() }}</strong> data
        </p>
        
        {{-- Tombol Paginasi --}}
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
    /* Table Styling agar sama dengan Data Lab */
    .table thead th {
        background-color: #fbfbfb;
        border-bottom: 1px solid #f1f1f1;
        padding: 12px 10px;
        font-size: 0.7rem;
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

    /* Badge subtle effects (UK & Warna disamakan) */
    .badge {
        font-weight: 600 !important;
        padding: 5px 12px !important;
        font-size: 11px; /* Ukuran Desktop */
    }

    .bg-success-subtle { background-color: #e6fffa !important; color: #00b19d !important; }
    .bg-primary-subtle { background-color: #ecf2ff !important; color: #5d87ff !important; }
    .bg-danger-subtle { background-color: #fef5f5 !important; color: #fa896b !important; }
    .bg-warning-subtle { background-color: #fff8ec !important; color: #ffae1f !important; }
    .bg-info-subtle { background-color: #e7f1ff !important; color: #007bff !important; }
    .bg-dark-subtle { background-color: #f8f9fa !important; color: #343a40 !important; }

    /* Form Focus */
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: none;
        background-color: #fff !important;
    }

    @media (max-width: 768px) {
        .card-body { padding: 1rem !important; }
        .pagination { justify-content: center; }
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
        }, 700);
    });
</script>
@endpush