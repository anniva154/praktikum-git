@extends('layouts.backend')

@section('title', 'Manajemen Laporan Kerusakan')

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
                    <div class="d-flex align-items-center">
                        <div class="me-3 bg-danger-subtle p-3 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 55px; height: 55px;">
                            <i class="ti ti-tool text-danger fs-7"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-dark mb-0"
                                style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                                Manajemen Laporan Kerusakan
                            </h3>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{ $lab->nama_lab }}</p>
                        </div>
                    </div>
                    <div class="mt-3"
                        style="height: 4px; width: 150px; background: linear-gradient(to right, #dc3545, #ff4d5a); border-radius: 2px;">
                    </div>
                </div>
            </div>

            <div class="card" style="border-radius: 20px;">
                <div class="card-body p-3 p-md-4">
                    {{-- TOOLBAR --}}
                    <form method="GET" action="{{ url()->current() }}" id="filterForm">
                        <div class="row g-2 mb-4 align-items-center">
                            <div class="col-7 col-md-4">
                                <div class="position-relative">
                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control border-0 shadow-none" placeholder="Cari barang atau pelapor..."
                                        style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                                </div>
                            </div>
                            <div class="col-5 col-md-3">
                                <select name="status" class="form-select border-0 shadow-none"
                                    style="border-radius: 12px; height: 45px; font-size: 14px; background-color: #f8f9fa; cursor: pointer;"
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

                   {{-- DESKTOP VIEW --}}
<div class="table-responsive d-none d-md-block">
    <table class="table table-hover align-middle">
        <thead>
            <tr>
                <th class="ps-3" style="width: 60px;">No.</th>
                <th class="text-center" style="width: 100px;">Foto</th>
                <th>Barang & Pelapor</th>
                <th>Keterangan Kerusakan</th>
                <th class="text-center" style="width: 150px;">Status Laporan</th>
                <th class="text-center" style="width: 80px;">Aksi</th> {{-- JUDUL TABEL AKSI --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $index => $item)
                <tr>
                    <td class="ps-3 text-muted small">{{ $laporan->firstItem() + $index }}</td>
                    <td class="text-center">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" class="rounded shadow-sm border"
                                style="width: 48px; height: 48px; object-fit: cover; cursor: pointer;"
                                onclick="zoomFoto('{{ asset('storage/' . $item->foto) }}')">
                        @else
                            <div class="mx-auto bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 45px; height: 45px;">
                                <i class="ti ti-photo-off text-muted fs-5"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold text-dark d-block mb-1">{{ $item->barang->nama_barang ?? '-' }}</span>
                        <div class="d-flex align-items-center">
                            <i class="ti ti-user text-muted me-1" style="font-size: 12px;"></i>
                            <span class="text-muted small">Pelapor: {{ $item->user->name ?? 'Anonim' }}</span>
                        </div>
                    </td>
                    <td>
                        <p class="mb-1 text-dark fw-normal" style="max-width: 320px; line-height: 1.5; font-size: 15px;">
                            {{ $item->keterangan }}
                        </p>
                        <div class="d-flex align-items-center text-muted" style="font-size: 11px;">
                            <i class="ti ti-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($item->tgl_laporan)->translatedFormat('d F Y') }}
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button
                                class="btn btn-sm dropdown-toggle badge {{ $item->status == 'diajukan' ? 'bg-danger-subtle' : ($item->status == 'diproses' ? 'bg-primary-subtle' : 'bg-success-subtle') }}"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $item->status == 'diajukan' ? 'Menunggu' : ($item->status == 'diproses' ? 'Perbaikan' : 'Selesai') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li>
                                    <form action="{{ route('admin.laporan-kerusakan.update-status', $item->id_laporan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="diajukan">
                                        <button type="submit" class="dropdown-item small">Menunggu</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.laporan-kerusakan.update-status', $item->id_laporan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="dropdown-item small text-primary">Perbaikan</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.laporan-kerusakan.update-status', $item->id_laporan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="dropdown-item small text-success">Selesai</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td class="text-center ps-0"> {{-- ISI TABEL AKSI --}}
                        <form action="{{ route('admin.laporan-kerusakan.destroy', $item->id_laporan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn p-0 rounded-3 bg-danger-subtle text-danger border-0 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5"> {{-- Colspan diubah jadi 6 --}}
                        <img src="{{ asset('assets/images/backgrounds/empty-data.svg') }}" alt="" style="width: 150px;" class="mb-3 d-block mx-auto">
                        <span class="text-muted">Belum ada laporan kerusakan yang masuk.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

                   {{-- MOBILE VIEW --}}
<div class="d-md-none">
    @foreach ($laporan as $item)
        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex gap-2">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" class="rounded shadow-sm"
                                style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                onclick="zoomFoto('{{ asset('storage/' . $item->foto) }}')">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="ti ti-photo-off text-muted"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 13px;">
                                {{ $item->barang->nama_barang ?? '-' }}
                            </h6>
                            <small class="text-muted">Pelapor: {{ $item->user->name ?? 'Anonim' }}</small>
                        </div>
                    </div>

                    {{-- GROUPING STATUS & ACTION --}}
                    <div class="d-flex align-items-center gap-2">
                        {{-- Dropdown Status --}}
                        <div class="dropdown">
                            <button
                                class="btn btn-sm dropdown-toggle badge {{ $item->status == 'diajukan' ? 'bg-danger-subtle' : ($item->status == 'diproses' ? 'bg-primary-subtle' : 'bg-success-subtle') }}"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $item->status == 'diajukan' ? 'Menunggu' : ($item->status == 'diproses' ? 'Perbaikan' : 'Selesai') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li>
                                    <form action="{{ route('admin.laporan-kerusakan.update-status', $item->id_laporan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="diajukan">
                                        <button type="submit" class="dropdown-item small">Menunggu</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.laporan-kerusakan.update-status', $item->id_laporan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="diproses">
                                        <button type="submit" class="dropdown-item small text-primary">Perbaikan</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.laporan-kerusakan.update-status', $item->id_laporan) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" class="dropdown-item small text-success">Selesai</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        {{-- Tombol Hapus (Gaya sesuai gambar) --}}
                        <form action="{{ route('admin.laporan-kerusakan.destroy', $item->id_laporan) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn p-0 d-flex align-items-center justify-content-center rounded-3 bg-danger-subtle text-danger" 
                                    style="width: 32px; height: 32px; border: none;">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-light p-2 rounded mb-2" style="border-left: 3px solid #dc3545;">
                    <p class="mb-0 small text-dark italic">"{{ $item->keterangan }}"</p>
                </div>

                <div class="d-flex justify-content-between align-items-center small text-muted">
                    <span>
                        <i class="ti ti-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($item->tgl_laporan)->translatedFormat('d M Y') }}
                    </span>
                    <span class="badge bg-light text-dark fw-normal">ID: {{ $item->id_laporan }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

                    {{-- PAGINATION --}}
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <p class="text-muted small mb-0">Menampilkan {{ $laporan->count() }} laporan kerusakan</p>
                        <div>{{ $laporan->withQueryString()->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ZOOM --}}
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                    <img src="" id="fullImage" class="img-fluid rounded shadow-lg" style="max-height: 80vh;">
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
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #7c8fac;
        }

        .table tbody td {
            padding: 15px 10px;
            border-bottom: 1px solid #f8f9fa;
            font-size: 0.85rem;
            color: #2d3436;
        }

        .badge {
            font-weight: 700 !important;
            padding: 6px 14px !important;
            border-radius: 8px !important;
            font-size: 10px !important;
            text-transform: capitalize !important;
        }

        .bg-success-subtle {
            background-color: #e6fffa !important;
            color: #00b19d !important;
        }

        .bg-danger-subtle {
            background-color: #fef5f5 !important;
            color: #fa896b !important;
        }

        .bg-primary-subtle {
            background-color: #ecf2ff !important;
            color: #5d87ff !important;
        }

        .dropdown-toggle.badge::after {
            display: inline-block;
            margin-left: 0.5em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
        }

        .dropdown-item {
            font-size: 13px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            .badge {
                padding: 4px 10px !important;
                font-size: 9px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function zoomFoto(url) {
            document.getElementById('fullImage').src = url;
            new bootstrap.Modal(document.getElementById('fotoModal')).show();
        }
    </script>
@endpush