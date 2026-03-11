@extends('layouts.backend')

@section('title', 'Data Laboratorium')

@section('content')
    <div class="row">
        <div class="col-12">
            
            <div class="mb-2">
                @include('components.notification')
            </div>

   {{-- Header Card Data Laboratorium - Hijau Emerald --}}
<div class="card mb-4" style="border-radius: 20px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="me-3 d-flex align-items-center justify-content-center rounded-circle"
                    style="width: 55px; height: 55px; background-color: #ecfdf5;">
                    <i class="ti ti-flask fs-7" style="color: #10b981 !important;"></i>
                </div>
                
                <div>
                    <h3 class="fw-bold text-dark mb-0"
                        style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                        Data Laboratorium
                    </h3>
                    <p class="text-muted mb-0" style="font-size: 13px;">
                        Kelola informasi rincian dan ketersediaan ruangan laboratorium.
                    </p>
                </div>
            </div>

            <div>
                <a href="{{ route('admin.lab.create') }}"
                    class="btn btn-success d-inline-flex align-items-center shadow-sm"
                    style="border-radius: 12px; padding: 8px 16px; transition: all 0.3s;">
                    <i class="ti ti-plus fs-6 me-1"></i>
                    <span class="fw-bold" style="font-size: 13px;">Tambah</span>
                </a>
            </div>
        </div>

        <div class="mt-3"
            style="height: 4px; width: 150px; background: linear-gradient(to right, #10b981, #34d399); border-radius: 2px;">
        </div>
    </div>
</div>

            <div class="card" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">
 <form method="GET" action="{{ url()->current() }}" id="filterForm">
                    <div class="row g-2 mb-4 align-items-center">
                        <div class="col-7 col-md-4">
                            <div class="position-relative">
                                <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control border-0 shadow-none" 
                                       placeholder="Cari nama atau kode barang..." 
                                       style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                            </div>
                        </div>

                        <div class="col-5 col-md-3">
                                <select name="jurusan" class="form-select border-0 shadow-none" 
                                    style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; cursor: pointer;" 
                                    onchange="this.form.submit()">
                                    <option value="">Semua Jurusan</option>
                                    @foreach ($jurusan as $j)
                                        <option value="{{ $j->id_jurusan }}" {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                            {{ $j->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                </form>
                   
                {{-- DESKTOP VIEW: TABLE --}}
<div class="table-responsive d-none d-md-block">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th class="ps-3" width="5%">No.</th>
                <th>Nama Laboratorium</th>
                <th>Jurusan</th>
                <th class="text-center">Status</th>
                <th class="text-end pe-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($labs as $index => $lab)
                @php
                   $statusClass = match ($lab->status) {
    'kosong'    => 'bg-success-subtle text-success',
    'dipakai'   => 'bg-primary-subtle text-primary',
    'perbaikan' => 'bg-warning-subtle text-warning',
    default     => 'bg-light text-dark',
};
                @endphp
                <tr>
                    {{-- Kolom No --}}
                    <td class="ps-3 text-muted small">{{ $labs->firstItem() + $index }}</td>
                    
                    {{-- Kolom Nama Lab --}}
                    <td>
                        <div class="fw-bold text-dark">{{ $lab->nama_lab }}</div>
                        <small class="text-muted" style="font-size: 11px;">
                            {{ $lab->keterangan ?: 'Tidak ada keterangan' }}
                        </small>
                    </td>
                    
                    {{-- Kolom Jurusan --}}
                    <td class="text-muted small">{{ $lab->jurusan?->nama_jurusan ?? '-' }}</td>
                    
                    {{-- Kolom Status (Badge) --}}
                    
                    <td class="text-center">
    <span class="badge rounded-pill px-3 py-2 {{ $statusClass }}" style="font-size: 12px;">
        {{ ucfirst($lab->status) }}
    </span>
</td>
                    {{-- Kolom Aksi --}}
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.lab.edit', $lab->id_lab) }}"
                                class="btn btn-sm btn-light-primary p-2" style="border-radius: 8px;">
                                <i class="ti ti-pencil fs-5"></i>
                            </a>
                            <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light-danger p-2" style="border-radius: 8px;"
                                    onclick="return confirm('Hapus data ini?')">
                                    <i class="ti ti-trash fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted small">
                        Data laboratorium tidak ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

                   {{-- MOBILE VIEW: CARDS --}}
<div class="d-md-none">
    @forelse ($labs as $lab)
        @php
          
            $statusClassMob = match ($lab->status) {
    'kosong'    => 'bg-success-subtle text-success',
    'dipakai'   => 'bg-primary-subtle text-primary',
    'perbaikan' => 'bg-warning-subtle text-warning',
    default     => 'bg-light text-dark',
};
        @endphp

        <div class="card border border-secondary-subtle bg-light-subtle mb-3" style="border-radius: 15px;">
            <div class="card-body p-3">
                {{-- Header: Nama Lab & Badge Status --}}
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold text-dark mb-0">{{ $lab->nama_lab }}</h6>
                
                    <span class="badge rounded-pill {{ $statusClassMob }}" style="font-size: 10px; padding: 5px 12px;">
    {{ ucfirst($lab->status) }}
</span>
                </div>
                
                {{-- Info Jurusan & Keterangan --}}
                <div class="row g-0 mb-3">
                    <div class="col-12 mb-2">
                        <small class="text-muted d-block">Jurusan</small>
                        <span class="fw-medium small text-primary">
                            <i class="ti ti-school me-1"></i>{{ $lab->jurusan?->nama_jurusan ?? '-' }}
                        </span>
                    </div>
                    <div class="col-12">
                        <div class="p-2 rounded bg-white border border-light-subtle" style="font-size: 11px; color: #666;">
                            {{ $lab->keterangan ?: 'Tidak ada keterangan' }}
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 pt-2 border-top">
                    <a href="{{ route('admin.lab.edit', $lab->id_lab) }}"
                        class="btn btn-light-primary btn-sm flex-grow-1" style="border-radius: 8px;">
                        <i class="ti ti-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}" method="POST" class="flex-grow-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-light-danger btn-sm w-100" style="border-radius: 8px;"
                            onclick="return confirm('Hapus data ini?')">
                            <i class="ti ti-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-4 text-muted small">Data laboratorium tidak ditemukan.</div>
    @endforelse
</div>

                    {{-- PAGINATION --}}
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <p class="text-muted small mb-0">Menampilkan {{ $labs->count() }} data dari {{ $labs->total() }}</p>
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
    .bg-success-subtle { background-color: #e6fffa !important; color: #00b19d !important; }
    .bg-danger-subtle { background-color: #fef5f5 !important; color: #fa896b !important; }
    .bg-warning-subtle { background-color: #fff8ec !important; color: #ffae1f !important; }
    .bg-info-subtle { background-color: #e7f1ff !important; color: #007bff !important; }
    .bg-primary-subtle { background-color: #ecf2ff !important; color: #5d87ff !important; }
    .bg-dark-subtle { background-color: #f8f9fa !important; color: #343a40 !important; }

    /* Custom scrollbar untuk table responsive */
    .table-responsive::-webkit-scrollbar {
        height: 5px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .badge { padding: 4px 10px !important; font-size: 10px; }
    }
</style>
@endpush