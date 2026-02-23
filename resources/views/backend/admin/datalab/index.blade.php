@extends('layouts.backend')

@section('title', 'Data Laboratorium')
@include('components.notification')

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Header Card --}}
        <div class="card mb-4" style="border-radius: 20px;">
            <div class="card-body p-4">
                <h3 class="fw-bold text-dark">Data Laboratorium</h3>
                <p class="text-muted mb-3">Lihat, tambah, dan kelola data laboratorium pada SIMLAB.</p>
                <div style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="card" style="border-radius: 20px;">
            <div class="card-body p-3 p-md-4">
                
                {{-- TOOLBAR: Search & Filters --}}
                <form method="GET" action="{{ route('admin.lab.index') }}" id="filterForm">
                    <div class="row g-2 mb-4 align-items-center">
                        {{-- SEARCH --}}
                        <div class="col-7 col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control border-light-subtle shadow-none" 
                                       placeholder="Cari..." 
                                       style="border-radius: 10px 0 0 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;">
                                <button class="btn btn-primary px-3" type="submit" style="border-radius: 0 10px 10px 0;">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </div>

                        {{-- JURUSAN FILTER --}}
                        <div class="col-5 col-md-3">
                            <select name="jurusan" class="form-select border-light-subtle shadow-none" 
                                    style="border-radius: 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;"
                                    onchange="this.form.submit()">
                                <option value="">Jurusan</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}" {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="col-12 col-md-auto ms-auto d-flex gap-2">
                            @if(request()->hasAny(['jurusan', 'search']))
                                <a href="{{ route('admin.lab.index') }}" 
                                   class="btn btn-light-danger d-flex align-items-center justify-content-center" 
                                   style="border-radius: 10px; height: 42px; min-width: 42px;">
                                    <i class="ti ti-refresh"></i> <span class="d-none d-lg-inline ms-1">Reset</span>
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.lab.create') }}" 
                               class="btn btn-success d-flex align-items-center justify-content-center px-4" 
                               style="border-radius: 10px; height: 42px; font-weight: 600; min-width: 120px;">
                                <i class="ti ti-plus me-1"></i> Tambah
                            </a>
                        </div>
                    </div>
                </form>

                {{-- DESKTOP VIEW: TABLE --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table align-middle">
                        <thead class="bg-light-subtle">
                            <tr>
                                <th class="ps-3" style="width: 50px;">No.</th>
                                <th>Nama Laboratorium</th>
                                <th>Jurusan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($labs as $index => $lab)
                                @php
                                    $badgeClass = match ($lab->status) {
                                        'kosong' => 'bg-success-subtle text-success',
                                        'dipakai' => 'bg-primary-subtle text-primary',
                                        'perbaikan' => 'bg-danger-subtle text-danger',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $labs->firstItem() + $index }}</td>
                                    <td class="fw-bold text-dark">{{ $lab->nama_lab }}</td>
                                    <td class="text-muted">{{ $lab->jurusan?->nama_jurusan ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $badgeClass }}">
                                            {{ ucfirst($lab->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.lab.edit', $lab->id_lab) }}" class="btn btn-sm btn-light-primary p-2" style="border-radius: 8px;"><i class="ti ti-pencil fs-5"></i></a>
                                            <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light-danger p-2" style="border-radius: 8px;" onclick="return confirm('Hapus?')"><i class="ti ti-trash fs-5"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW: CARDS --}}
                <div class="d-md-none">
                    @forelse ($labs as $lab)
                        @php
                            $badgeClassMob = match ($lab->status) {
                                'kosong' => 'bg-success-subtle text-success',
                                'dipakai' => 'bg-primary-subtle text-primary',
                                'perbaikan' => 'bg-danger-subtle text-danger',
                                default => 'bg-light text-dark',
                            };
                        @endphp
                        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $lab->nama_lab }}</h6>
                                    <span class="badge rounded-pill {{ $badgeClassMob }}" style="font-size: 10px;">{{ ucfirst($lab->status) }}</span>
                                </div>
                                <div class="text-muted small mb-3">
                                    <div class="mb-1"><i class="ti ti-school me-1"></i> {{ $lab->jurusan?->nama_jurusan ?? '-' }}</div>
                                    <div><i class="ti ti-info-circle me-1"></i> {{ $lab->keterangan ?: 'Tidak ada keterangan' }}</div>
                                </div>
                                <div class="d-flex gap-2 pt-2 border-top">
                                    <a href="{{ route('admin.lab.edit', $lab->id_lab) }}" class="btn btn-light-primary btn-sm flex-grow-1" style="border-radius: 8px;">
                                        <i class="ti ti-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" class="flex-grow-1">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light-danger btn-sm w-100" style="border-radius: 8px;" onclick="return confirm('Hapus?')">
                                            <i class="ti ti-trash"></i> Hapus
                                        </button>
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
                    <p class="text-muted small mb-0 text-center text-md-start">
                        Menampilkan {{ $labs->count() }} data dari {{ $labs->total() }} total
                    </p>
                    <div class="pagination-sm">
                        {{ $labs->withQueryString()->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Table Styling (UK disamakan dengan Data Barang) */
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

    /* Badge Global Styling (UK samakan halaman Pengguna/Barang) */
    .badge {
        font-weight: 600 !important;
        padding: 5px 12px !important;
        font-size: 11px; /* Ukuran Desktop */
    }

    /* Subtle Colors */
    .bg-success-subtle { background-color: #e6fffa !important; color: #00b19d !important; }
    .bg-primary-subtle { background-color: #ecf2ff !important; color: #5d87ff !important; }
    .bg-danger-subtle { background-color: #fef5f5 !important; color: #fa896b !important; }

    /* Input Focus */
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