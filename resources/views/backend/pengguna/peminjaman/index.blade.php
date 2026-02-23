@extends('layouts.backend')



@section('title', 'Data Peminjaman')



@section('content')

    <div class="row">

        <div class="col-12">



            {{-- NOTIFIKASI --}}

            <div class="mb-2">

                @include('components.notification')

            </div>



            <div class="card mb-4" style="border-radius: 20px;">

                <div class="card-body p-3 p-md-4">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <h3 class="fw-bold text-dark mb-0"
                                style="font-size: calc(1rem + 0.3vw); letter-spacing: -0.5px;">

                                Riwayat Peminjaman

                            </h3>

                            <p class="text-muted mb-2" style="font-size: 11px;">

                                Silakan pantau status pengajuan peminjaman Anda di sini.

                            </p>

                            <div
                                style="height: 4px; width: 150px; background: linear-gradient(to right, #198754, #20c997); border-radius: 2px;">

                            </div>

                        </div>



                        <div>

                            <a href="{{ route('pengguna.peminjaman.create') }}"
                                class="btn btn-success d-inline-flex align-items-center shadow-sm"
                                style="border-radius: 12px; padding: 8px 16px; transition: all 0.3s;">

                                <i class="ti ti-plus fs-6 me-1"></i>

                                <span class="fw-bold" style="font-size: 13px;">Ajukan</span>

                            </a>

                        </div>

                    </div>

                </div>

            </div>



            <div class="card" style="border-radius: 20px;">

                <div class="card-body p-3 p-md-4">



                    {{-- TOOLBAR: Search & Filters --}}

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

                                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu

                                    </option>

                                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>

                                        Disetujui</option>

                                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak

                                    </option>

                                </select>

                            </div>

                        </div>

                    </form>

                    {{-- DESKTOP VIEW: TABLE --}}

                    <div class="table-responsive d-none d-md-block">

                        <table class="table align-middle">

                            <thead class="bg-light-subtle">

                                <tr>

                                    <th class="ps-3 text-center" style="width: 50px;">No.</th>

                                    <th class="text-center">Barang / Lab</th>

                                    <th class="text-center">Keterangan</th> {{-- KOLOM BARU --}}

                                    <th class="text-center">Jumlah</th>

                                    <th class="text-center">Waktu Pinjam & Kembali</th>

                                    <th class="text-center">Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse ($peminjaman as $index => $item)

                                    @php

                                        $bg = '#f8f9fa';

                                        $color = '#333';

                                        $label = 'Menunggu';

                                        if ($item->status == 'disetujui') {

                                            $bg = '#e6f9f1';

                                            $color = '#1f9462';

                                            $label = 'Disetujui';

                                        } elseif ($item->status == 'ditolak') {

                                            $bg = '#ffe5e5';

                                            $color = '#f15b5b';

                                            $label = 'Ditolak';

                                        } elseif ($item->status == 'diajukan') {

                                            $bg = '#fff9e6';

                                            $color = '#d4a017';

                                            $label = 'Menunggu';

                                        }

                                    @endphp

                                    <tr>

                                        <td class="ps-3 text-muted small">{{ $peminjaman->firstItem() + $index }}</td>

                                        <td>

                                            <div class="fw-bold text-dark">{{ $item->barang->nama_barang }}</div>

                                            <small class="text-muted"><i class="ti ti-building fs-7"></i>

                                                {{ $item->barang->laboratorium->nama_lab ?? '-' }}</small>

                                        </td>

                                        {{-- ISI KETERANGAN DESKTOP --}}

                                        <td>

                                            <small class="text-muted"
                                                style="font-size: 12px; display: block; max-width: 200px; line-height: 1.4;">

                                                {{ $item->keterangan ?? '-' }}

                                            </small>

                                        </td>

                                        <td class="text-center">

                                            <span class="badge bg-light text-dark border fw-semibold">{{ $item->jumlah_pinjam }}

                                                Unit</span>

                                        </td>

                                        <td class="small">

                                            <div class="d-flex flex-column">

                                                <div class="d-flex align-items-center mb-1">

                                                    <i class="ti ti-calendar-event text-success me-2" style="width: 16px;"></i>

                                                    <span
                                                        class="fw-medium">{{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}</span>

                                                </div>

                                                <div class="d-flex align-items-center">

                                                    <i class="ti ti-calendar-check text-primary me-2" style="width: 16px;"></i>

                                                    <span class="text-muted">

                                                        {{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') : 'Belum Kembali' }}

                                                    </span>

                                                </div>

                                            </div>

                                        </td>

                                        <td class="text-center">

                                            <span class="badge rounded-pill px-3 py-2"
                                                style="font-size: 11px; min-width: 90px; background-color: {{ $bg }}; color: {{ $color }};">

                                                {{ $label }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center py-5 text-muted small">Data peminjaman tidak

                                            ditemukan.</td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>



                    {{-- MOBILE VIEW: CARDS --}}

                    <div class="d-md-none">

                        @forelse ($peminjaman as $item)

                            @php

                                $bg = '#f8f9fa';

                                $color = '#333';

                                $label = 'Menunggu';

                                if ($item->status == 'disetujui') {

                                    $bg = '#e6f9f1';

                                    $color = '#1f9462';

                                    $label = 'Disetujui';

                                } elseif ($item->status == 'ditolak') {

                                    $bg = '#ffe5e5';

                                    $color = '#f15b5b';

                                    $label = 'Ditolak';

                                } elseif ($item->status == 'diajukan') {

                                    $bg = '#fff9e6';

                                    $color = '#d4a017';

                                    $label = 'Menunggu';

                                }

                            @endphp

                            <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">

                                <div class="card-body p-3">

                                    <div class="d-flex justify-content-between align-items-start mb-2">

                                        <div style="max-width: 65%;">

                                            <h6 class="fw-bold mb-0 text-dark">{{ $item->barang->nama_barang }}</h6>

                                            <small class="text-muted"
                                                style="font-size: 11px;">{{ $item->barang->laboratorium->nama_lab ?? '-' }}</small>

                                        </div>

                                        <span class="badge rounded-pill px-2 py-1"
                                            style="font-size: 10px; background-color: {{ $bg }}; color: {{ $color }};">

                                            {{ $label }}

                                        </span>

                                    </div>



                                    {{-- KETERANGAN BOX MOBILE --}}

                                    <div class="mb-3 p-2 rounded-3 bg-light border-start border-3 border-primary-subtle">

                                        <small class="text-muted d-block mb-1"
                                            style="font-size: 10px; font-weight: 600; text-uppercase;">Keperluan:</small>

                                        <p class="mb-0 text-dark" style="font-size: 12px; line-height: 1.4;">

                                            {{ $item->keterangan ?? '-' }}
                                        </p>

                                    </div>



                                    <div class="row g-0 pt-2 border-top">

                                        <div class="col-4">

                                            <small class="text-muted d-block" style="font-size: 10px;">Jumlah</small>

                                            <span class="fw-bold text-dark" style="font-size: 13px;">{{ $item->jumlah_pinjam }}

                                                Unit</span>

                                        </div>

                                        <div class="col-8 border-start ps-3">

                                            <small class="text-muted d-block mb-1" style="font-size: 10px;">Durasi

                                                Peminjaman</small>

                                            <div style="font-size: 11px; line-height: 1.5;">

                                                <div class="d-flex">

                                                    <span class="text-muted" style="width: 45px;">Mulai</span>

                                                    <span class="fw-medium text-dark">:

                                                        {{ \Carbon\Carbon::parse($item->waktu_pinjam)->format('d/m/y H:i') }}</span>

                                                </div>

                                                <div class="d-flex">

                                                    <span class="text-muted" style="width: 45px;">Selesai</span>

                                                    <span class="fw-medium text-dark">:

                                                        {{ $item->waktu_kembali ? \Carbon\Carbon::parse($item->waktu_kembali)->format('d/m/y H:i') : '-' }}</span>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-4 text-muted small">Data tidak ditemukan.</div>

                        @endforelse

                    </div>



                    {{-- PAGINATION --}}

                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                        <p class="text-muted small mb-0">Menampilkan {{ $peminjaman->count() }} data</p>

                        <div class="pagination-sm">{{ $peminjaman->withQueryString()->links() }}</div>

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