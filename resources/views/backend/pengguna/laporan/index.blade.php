@extends('layouts.backend')

@section('title', 'Laporan Kerusakan')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- NOTIFIKASI --}}
            <div class="mb-2">
                @include('components.notification')
            </div>

            {{-- HEADER CARD --}}
            <div class="card mb-4" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bold text-dark mb-0"
                                style="font-size: calc(1rem + 0.3vw); letter-spacing: -0.5px;">
                                Laporan Kerusakan Saya
                            </h3>
                            <p class="text-muted mb-2" style="font-size: 11px;">
                                Pantau status perbaikan barang yang Anda laporkan di sini.
                            </p>
                            <div
                                style="height: 4px; width: 150px; background: linear-gradient(to right, #dc3545, #ff4d5a); border-radius: 2px;">
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('pengguna.laporan.create') }}"
                                class="btn btn-success d-inline-flex align-items-center shadow-sm"
                                style="border-radius: 12px; padding: 8px 16px; transition: all 0.3s;">
                                <i class="ti ti-plus fs-6 me-1"></i>
                                <span class="fw-bold" style="font-size: 13px;">Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MAIN CONTENT CARD --}}
            <div class="card" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">

                    {{-- TOOLBAR --}}
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

                            <div class="col-5 col-md-3">
                                <select name="status" class="form-select border-light-subtle shadow-none"
                                    style="border-radius: 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Menunggu
                                    </option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Perbaikan
                                    </option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                    {{-- DESKTOP VIEW: TABLE --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table align-middle">
                            <thead class="bg-light-subtle">
                                <tr class="text-muted small text-uppercase text-center">
                                    <th style="width: 50px;">No.</th>
                                    <th style="width: 120px;">Foto</th>
                                    <th class="text-start" style="width: 250px;">Barang / Lab</th>
                                    <th class="text-start">Keterangan Kerusakan</th>
                                    <th style="width: 130px;">Tgl Laporan</th>
                                    <th style="width: 130px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($laporan as $index => $item)
                                    @php
                                        $statusStyle = match ($item->status) {
                                            'diajukan' => ['bg' => '#ffe5e5', 'color' => '#dc3545', 'label' => 'Menunggu'],
                                            'diproses' => ['bg' => '#fff4e5', 'color' => '#ff9800', 'label' => 'Perbaikan'],
                                            'selesai' => ['bg' => '#e6f9f1', 'color' => '#1f9462', 'label' => 'Selesai'],
                                            default => ['bg' => '#f8f9fa', 'color' => '#333', 'label' => 'Unknown'],
                                        };
                                    @endphp
                                    <tr class="text-center">
                                        <td class="text-muted small">{{ $laporan->firstItem() + $index }}</td>
                                        <td class="text-center">
                                            @if($item->foto)
                                                {{-- Gunakan 'storage/' karena isi database Anda adalah path relatif dari
                                                storage/app/public --}}
                                                <img src="{{ asset('storage/' . $item->foto) }}"
                                                    class="rounded img-thumbnail-zoom shadow-sm"
                                                    style="width: 85px; height: 60px; object-fit: cover; border: 1px solid #dee2e6; cursor: pointer;"
                                                    onclick="zoomFoto('{{ asset('storage/' . $item->foto) }}')"
                                                    title="Klik untuk memperbesar">
                                            @else
                                                {{-- Tampilan jika tidak ada foto --}}
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted mx-auto"
                                                    style="width: 85px; height: 60px; border: 1px dashed #dee2e6;">
                                                    <i class="ti ti-photo-off fs-5"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-start">
                                            <div class="fw-bold text-dark">{{ $item->barang->nama_barang ?? '-' }}</div>
                                            <small class="text-muted">
                                                <i class="ti ti-building fs-7"></i> {{ $item->laboratorium->nama_lab ?? '-' }}
                                            </small>
                                        </td>
                                        <td class="text-start">
                                            <div class="text-muted"
                                                style="font-size: 12px; line-height: 1.5; max-width: 350px;">
                                                {{ $item->keterangan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="small fw-medium">
                                            {{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d/m/Y') }}
                                        </td>

                                        <td class="text-center">
                                            <span class="badge rounded-pill px-3 py-2"
                                                style="font-size: 11px; min-width: 90px; background-color:  {{ $statusStyle['bg'] }}; color: {{ $statusStyle['color'] }};">
                                                {{ $statusStyle['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted small">Belum ada data laporan
                                            kerusakan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MOBILE VIEW --}}
                    <div class="d-md-none">
                        @forelse ($laporan as $item)
                            {{-- Logika status sama dengan desktop --}}
                            @php
                                $statusStyle = match ($item->status) {
                                    'diajukan' => ['bg' => '#ffe5e5', 'color' => '#dc3545', 'label' => 'Menunggu'],
                                    'diproses' => ['bg' => '#fff4e5', 'color' => '#ff9800', 'label' => 'Perbaikan'],
                                    'selesai' => ['bg' => '#e6f9f1', 'color' => '#1f9462', 'label' => 'Selesai'],
                                    default => ['bg' => '#f8f9fa', 'color' => '#333', 'label' => 'Unknown'],
                                };
                            @endphp
                            <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                                <div class="card-body p-3">
                                    <div class="d-flex gap-3 mb-3">
                                        @if($item->foto)
                                            {{-- Gunakan 'storage/' karena Laravel menyimpan upload otomatis di sana --}}
                                            <img src="{{ asset('storage/' . $item->foto) }}" class="rounded shadow-sm"
                                                style="width: 60px; height: 60px; object-fit: cover; cursor: pointer; border: 1px solid #eee;"
                                                onclick="zoomFoto('{{ asset('storage/' . $item->foto) }}')">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted shadow-sm"
                                                style="width: 60px; height: 60px; border: 1px dashed #ddd;">
                                                <i class="ti ti-photo-off fs-4"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $item->barang->nama_barang ?? '-' }}</h6>
                                                <span class="badge rounded-pill px-2 py-1"
                                                    style="font-size: 10px; background-color: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['color'] }};">
                                                    {{ $statusStyle['label'] }}
                                                </span>
                                            </div>
                                            <small class="text-muted d-block">{{ $item->laboratorium->nama_lab ?? '-' }}</small>
                                            <small class="text-muted"
                                                style="font-size: 10px;">{{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="p-2 rounded-3 bg-light border-start border-3 border-danger-subtle">
                                        <p class="mb-0 text-dark" style="font-size: 12px;">{{ $item->keterangan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted small">Data tidak ditemukan.</div>
                        @endforelse
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <p class="text-muted small mb-0">Menampilkan {{ $laporan->count() }} data</p>
                        <div class="pagination-sm">{{ $laporan->withQueryString()->links() }}</div>
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
        .img-thumbnail-zoom {
            transition: transform .3s ease;
        }

        .img-thumbnail-zoom:hover {
            transform: scale(1.1);
            z-index: 10;
        }

        .table td,
        .table th {
            vertical-align: middle !important;
        }

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
        function zoomFoto(url) {
            const img = document.getElementById('fullImage');
            img.src = url;
            const modalElement = document.getElementById('fotoModal');
            const myModal = new bootstrap.Modal(modalElement);
            myModal.show();
        }

        // Script untuk search otomatis
        document.addEventListener("DOMContentLoaded", function () {
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