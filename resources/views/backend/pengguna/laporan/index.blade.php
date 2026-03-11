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
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                {{-- Lingkaran Ikon Merah untuk Laporan --}}
                <div class="me-3 p-3 rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 55px; height: 55px; background-color: #fff5f5 !important; flex-shrink: 0;">
                    {{-- Ukuran ikon disamakan (1.8rem - 1.9rem) --}}
                    <i class="ti ti-tool" style="color: #ff5c5c !important; font-size: 1.8rem !important;"></i>
                </div>
                <div>
                    <h3 class="fw-bold text-dark mb-0" style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                        Laporan Kerusakan Saya
                    </h3>
                    <p class="text-muted mb-0" style="font-size: 13px;">Pantau status perbaikan barang yang Anda laporkan</p>
                </div>
            </div>

            {{-- Tombol di Sisi Kanan --}}
            <div>
                <a href="{{ route('pengguna.laporan.create') }}"
                    class="btn btn-success d-inline-flex align-items-center shadow-sm"
                    style="border-radius: 12px; padding: 10px 20px; transition: all 0.3s;">
                    <i class="ti ti-plus fs-5 me-2"></i>
                    <span class="fw-bold" style="font-size: 14px;">Laporan</span>
                </a>
            </div>
        </div>

        {{-- Garis Gradien di Bawah dengan Jarak mt-3 --}}
        <div class="mt-3"
            style="height: 4px; width: 100px; background: linear-gradient(to right, #dc3545, #ff4d5a); border-radius: 2px;">
        </div>
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
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                    </div>
                </form>

                {{-- DESKTOP VIEW --}}
                <div class="table-responsive d-none d-md-block">
                   <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 50px;">No.</th>
                                <th style="width: 100px;">Foto</th>
                                <th>Barang / Lab</th>
                                <th>Keterangan Kerusakan</th>
                                <th class="text-center">Tgl Laporan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporan as $index => $item)
                                @php
                                    $statusClass = match($item->status) {
                                        'selesai'  => 'status-baik',
                                        'diajukan' => 'status-rusak',
                                        default    => 'status-proses'
                                    };
                                    $statusLabel = match($item->status) {
                                        'diajukan' => 'Menunggu',
                                        'diproses' => 'Perbaikan',
                                        'selesai'  => 'Selesai',
                                        default    => ucfirst($item->status)
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $laporan->firstItem() + $index }}</td>
                                    <td>
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                                 class="rounded shadow-sm img-thumbnail-zoom" 
                                                 style="width: 60px; height: 45px; object-fit: cover; cursor: pointer;"
                                                 onclick="zoomFoto('{{ asset('storage/' . $item->foto) }}')">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" 
                                                 style="width: 60px; height: 45px; border: 1px dashed #ddd;">
                                                <i class="ti ti-photo-off fs-5"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block" style="font-size: 14px;">{{ $item->barang->nama_barang ?? '-' }}</span>
                                        <span class="text-primary fw-medium" style="font-size: 12px;">{{ $item->laboratorium->nama_lab ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block" style="max-width: 250px; line-height: 1.4; font-size: 12px;">
                                            {{ Str::limit($item->keterangan ?? '-', 100) }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-medium text-dark" style="font-size: 13px;">
                                            {{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="custom-badge {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada data laporan kerusakan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW --}}
                <div class="d-md-none">
                    @foreach ($laporan as $item)
                        @php
                            $statusClass = match($item->status) {
                                'selesai'  => 'status-baik',
                                'diajukan' => 'status-rusak',
                                default    => 'status-proses'
                            };
                        @endphp
                        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex gap-3">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" class="rounded shadow-sm" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onclick="zoomFoto('{{ asset('storage/' . $item->foto) }}')">
                                        @endif
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">{{ $item->barang->nama_barang ?? '-' }}</h6>
                                            <small class="text-primary fw-medium" style="font-size: 11px;">{{ $item->laboratorium->nama_lab ?? '-' }}</small>
                                        </div>
                                    </div>
                                    <span class="badge-custom {{ $statusClass }}">
                                        {{ $item->status == 'diajukan' ? 'Menunggu' : ucfirst($item->status) }}
                                    </span>
                                </div>
                                <div class="bg-light p-2 rounded-3 mb-3 border-start border-3 border-danger-subtle">
                                    <p class="mb-0 text-dark" style="font-size: 12px; line-height: 1.4;">{{ $item->keterangan ?? '-' }}</p>
                                </div>
                                <div class="pt-2 border-top">
                                    <small class="text-muted" style="font-size: 10px;">Tanggal Laporan: </small>
                                    <span class="fw-bold text-dark" style="font-size: 11px;">{{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0">Menampilkan {{ $laporan->count() }} data</p>
                    <div>{{ $laporan->withQueryString()->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ZOOM --}}
<div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" 
                        data-bs-dismiss="modal" aria-label="Close"></button>
                <img src="" id="fullImage" class="img-fluid rounded shadow-lg" style="max-height: 90vh;">
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

    .img-thumbnail-zoom {
        transition: transform .3s ease;
    }
    .img-thumbnail-zoom:hover {
        transform: scale(1.1);
    }

    .custom-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
        min-width: 85px;
        line-height: 1.2;
    }

    /* Badge Style Matching Peminjaman */
    .status-baik { background-color: #e7f1ff; color: #007bff; } /* Selesai */
    .status-rusak { background-color: #fff0f0; color: #ff8567; border: 1px solid #ffe5e5; } /* Menunggu/Diajukan */
    .status-proses { background-color: #fcf4e6; color: #ffa500; } /* Perbaikan */

    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar { height: 5px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }
</style>
@endpush

@push('scripts')
<script>
    function zoomFoto(url) {
        const img = document.getElementById('fullImage');
        img.src = url;
        const modalElement = document.getElementById('fotoModal');
        const myModal = new bootstrap.Modal(modalElement);
        myModal.show();
    }
</script>
@endpush