@extends('layouts.backend')

@section('title', 'Laporan Kerusakan')

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
                <h3 class="fw-bold text-dark">Laporan Kerusakan - {{ $lab->nama_lab }}</h3>
                <p class="text-muted mb-3">Pantau dan kelola laporan kondisi barang yang rusak di laboratorium ini.</p>
                <div style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-3 p-md-4">

                {{-- TOOLBAR: Search & Filters --}}
                <form method="GET" action="{{ url()->current() }}" id="filterForm">
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

                        {{-- FILTER STATUS --}}
                        <div class="col-5 col-md-3">
                            <select name="status" class="form-select border-light-subtle shadow-none" 
                                    style="border-radius: 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;" 
                                    onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="dilaporkan" {{ request('status') == 'dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="col-12 col-md-auto ms-auto d-flex gap-2 mt-2 mt-md-0">
                            @if(request()->hasAny(['status', 'search']))
                                <a href="{{ url()->current() }}"
                                   class="btn btn-light-danger d-flex align-items-center justify-content-center" 
                   style="border-radius: 10px; height: 42px; min-width: 42px;">
                    <i class="ti ti-refresh"></i> <span class="d-none d-lg-inline ms-1">Reset</span>
                </a>
                                
                            @endif
                        </div>
                    </div>
                </form>

                {{-- DESKTOP VIEW: TABLE --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table align-middle">
                        <thead class="bg-light-subtle">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-3" style="width: 50px;">No.</th>
                                <th>Informasi Barang</th>
                                <th class="text-center">Tanggal Laporan</th>
                                <th>Keterangan Kerusakan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporan as $index => $item)
                                @php
                                    $badgeStatus = match($item->status) {
                                        'dilaporkan' => 'bg-warning-subtle text-warning',
                                        'diproses'   => 'bg-info-subtle text-info',
                                        'selesai'    => 'bg-success-subtle text-success',
                                        'ditolak'    => 'bg-danger-subtle text-danger',
                                        default      => 'bg-light text-dark',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $laporan->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->barang->nama_barang ?? '-' }}</div>
                                        <small class="text-muted" style="font-size: 11px;">
                                            ID: {{ $item->barang->kode_barang ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td class="text-center small text-dark">
                                        {{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <span class="small text-muted d-inline-block text-truncate" style="max-width: 250px;">
                                            {{ $item->keterangan ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $badgeStatus }} px-3">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada laporan kerusakan yang ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW: CARDS --}}
                <div class="d-md-none">
                    @forelse ($laporan as $item)
                        @php
                            $badgeMob = match($item->status) {
                                'dilaporkan' => 'bg-warning-subtle text-warning',
                                'diproses'   => 'bg-info-subtle text-info',
                                'selesai'    => 'bg-success-subtle text-success',
                                'ditolak'    => 'bg-danger-subtle text-danger',
                                default      => 'bg-light text-dark',
                            };
                        @endphp
                        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div style="max-width: 70%;">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate">{{ $item->barang->nama_barang ?? '-' }}</h6>
                                        <small class="text-muted" style="font-size: 11px;">Tgl: {{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d/m/Y') }}</small>
                                    </div>
                                    <span class="badge rounded-pill {{ $badgeMob }}" style="font-size: 10px;">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                
                                <div class="pt-2 border-top">
                                    <small class="text-muted d-block" style="font-size: 10px;">Keterangan Kerusakan:</small>
                                    <p class="small text-dark mb-0 mt-1" style="line-height: 1.4;">
                                        {{ $item->keterangan ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Data laporan tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0">Menampilkan {{ $laporan->count() }} dari {{ $laporan->total() }} laporan</p>
                    <div class="pagination-sm">{{ $laporan->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let timer;
        const searchInput = document.querySelector('input[name="search"]');

        searchInput?.addEventListener('keyup', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 800);
        });
    });
</script>
@endpush