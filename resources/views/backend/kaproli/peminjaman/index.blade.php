@extends('layouts.backend')

@section('title', 'Data Peminjaman')

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
                <h3 class="fw-bold text-dark">Data Peminjaman - {{ $lab->nama_lab }}</h3>
                <p class="text-muted mb-3">Lihat dan kelola riwayat peminjaman barang pada laboratorium ini.</p>
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
                                    style="border-radius: 10px; height: 42px; font-size: 14px;  background-color: #f8f9fa;" 
                                    onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
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
                                <th>Barang / Peminjam</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Waktu Pinjam</th>
                                <th class="text-center">Waktu Kembali</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $index => $item)
                                @php
                                    $badgeStatus = match($item->status) {
                                        'diajukan'     => 'bg-warning-subtle text-warning',
                                        'disetujui'    => 'bg-success-subtle text-success',
                                        'ditolak'      => 'bg-danger-subtle text-danger',
                                        'dikembalikan' => 'bg-secondary-subtle text-secondary',
                                        default        => 'bg-light text-dark',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $peminjaman->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->barang->nama_barang }}</div>
                                        <small class="text-muted d-block" style="font-size: 11px;">
                                            <i class="ti ti-user fs-7"></i> {{ $item->user->name ?? 'User' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border fw-semibold">{{ $item->jumlah_pinjam }}</span>
                                    </td>
                                    <td class="text-center small">
                                        {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/Y') }}<br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('H:i') }}</span>
                                    </td>
                                    <td class="text-center small">
                                        @if($item->waktu_kembali)
                                            {{ \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/Y') }}<br>
                                            <span class="text-muted">{{ \Carbon\Carbon::parse($item->waktu_kembali)->format('H:i') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                             <td class="text-center" style="vertical-align: middle;">
    @php
        // Logika penentuan warna background dan teks berdasarkan status
        $bg = '#ffffff'; 
        $color = '#333';
        
        if ($item->status == 'disetujui' || $item->status == 'dikembalikan') {
            $bg = '#e6f9f1'; // Hijau soft
            $color = '#1f9462';
        } elseif ($item->status == 'ditolak') {
            $bg = '#ffe5e5'; // Merah soft
            $color = '#f15b5b';
        } elseif ($item->status == 'diajukan') {
            $bg = '#fff9e6'; // Kuning soft
            $color = '#d4a017';
        }
    @endphp

    @if(auth()->user()->role === 'kaproli' || auth()->user()->role === 'admin')
        <form action="{{ route('kaproli.peminjaman.updateStatus', [$lab->id_lab ?? $item->barang->id_lab, $item->id_peminjaman]) }}" method="POST" class="d-inline-block">
            @csrf
            @method('PATCH')
            <select name="status" class="form-select form-select-sm shadow-sm border-0 custom-select-pill" 
                    onchange="this.form.submit()"
                    style="font-size: 12px; 
                           font-weight: 600;
                           border-radius: 20px; 
                           padding: 4px 20px 4px 20px; /* Padding seimbang kiri kanan */
                           cursor: pointer; 
                           background-color: {{ $bg }}; 
                           color: {{ $color }};
                           width: auto; 
                           min-width: 110px;
                           text-align: center;      /* Untuk Chrome/Firefox */
                           text-align-last: center; /* Untuk alignment teks saat tertutup */
                           appearance: none;
                           -webkit-appearance: none;">
                
                @if(auth()->user()->role === 'kaproli')
                    <option value="diajukan" {{ $item->status == 'diajukan' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>Setujui</option>
                    <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Tolak</option>
                @else
                    <option value="diajukan" {{ $item->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="disetujui" {{ $item->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ $item->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dikembalikan" {{ $item->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                @endif
            </select>
        </form>
    @else
        <span class="badge rounded-pill px-3" style="font-size: 11px; background-color: {{ $bg }}; color: {{ $color }};">
            {{ ucfirst($item->status) }}
        </span>
    @endif
</td>

<style>
/* CSS khusus agar panah 'v' tetap ada di pojok kanan meskipun teks di tengah */
.custom-select-pill {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23666' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 8px 8px;
}
</style>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Data peminjaman tidak ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW: CARDS --}}
                <div class="d-md-none">
                    @forelse ($peminjaman as $item)
                        @php
                            $badgeStatusMob = match($item->status) {
                                'diajukan'     => 'bg-warning-subtle text-warning',
                                'disetujui'    => 'bg-success-subtle text-success',
                                'ditolak'      => 'bg-danger-subtle text-danger',
                                'dikembalikan' => 'bg-secondary-subtle text-secondary',
                                default        => 'bg-light text-dark',
                            };
                        @endphp
                        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div style="max-width: 70%;">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate">{{ $item->barang->nama_barang }}</h6>
                                        <small class="text-muted" style="font-size: 11px;">Peminjam: {{ $item->user->name ?? 'User' }}</small>
                                    </div>
                                    <span class="badge rounded-pill {{ $badgeStatusMob }}" style="font-size: 10px;">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                
                                <div class="row g-0 pt-2 border-top">
                                    <div class="col-6">
                                        <small class="text-muted d-block" style="font-size: 10px;">Jumlah</small>
                                        <span class="fw-bold text-dark">{{ $item->jumlah_pinjam }} Unit</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block" style="font-size: 10px;">Waktu Pinjam</small>
                                        <span class="text-dark small">{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}</span>
                                    </div>
                                </div>
                                
                                @if($item->waktu_kembali)
                                <div class="mt-2 pt-2 border-top small d-flex justify-content-between">
                                    <span class="text-muted">Dikembalikan:</span>
                                    <span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Data peminjaman tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0">Menampilkan {{ $peminjaman->count() }} dari {{ $peminjaman->total() }} riwayat</p>
                    <div class="pagination-sm">{{ $peminjaman->withQueryString()->links() }}</div>
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