@extends('layouts.backend')

@section('title', 'Pengajuan Barang')

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- NOTIFIKASI --}}
            <div class="mb-2">
                @include('components.notification')
            </div>

            {{-- Header Card - Tema Teal (Pengajuan Barang) --}}
            <div class="card mb-4"
                style="border-radius: 20px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            {{-- Lingkaran Ikon Teal --}}
                            <div class="me-3 p-3 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px; background-color: #e6fffa !important;">
                                <i class="ti ti-clipboard-list" style="color: #00b19d !important; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold text-dark mb-0"
                                    style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                                    Pengajuan Barang
                                </h3>
                                <p class="text-muted mb-0" style="font-size: 13px;">Daftar usulan pengadaan barang baru</p>
                            </div>
                        </div>

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('kaproli.pengajuan.create', $lab->id_lab) }}"
                            class="btn btn-success d-inline-flex align-items-center shadow-sm px-3 py-2"
                            style="border-radius: 12px; transition: all 0.3s;">
                            <i class="ti ti-plus fs-5 me-1"></i>
                            <span class="fw-bold" style="font-size: 14px;">Tambah</span>
                        </a>
                    </div>

                    {{-- Garis Gradien Teal ke Mint --}}
                    <div class="mt-3"
                        style="height: 4px; width: 100px; background: linear-gradient(to right, #00b19d, #20e4d0); border-radius: 2px;">
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
                                        class="form-control border-0 shadow-none bg-light" placeholder="Cari nama barang..."
                                        style="border-radius: 12px; height: 45px; padding-left: 45px;">
                                </div>
                            </div>

                            <div class="col-5 col-md-3">
                                <select name="status" class="form-select border-0 shadow-none bg-light"
                                    style="border-radius: 12px; height: 45px; cursor: pointer;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>
                                        Disetujui</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                   {{-- DESKTOP TABLE --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center">Tgl Pengajuan</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Estimasi Harga</th>
                                <th class="text-center">Urgensi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pengajuan as $index => $item)
                            <tr>
                                <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                <td class="text-center text-muted small">{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                                    <small class="text-muted d-block" style="font-size: 11px;">
                                        Alasan: {{ Str::limit($item->alasan_kebutuhan, 35) }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-semibold">{{ $item->jumlah }}</span>
                                    <small class="text-muted">{{ $item->satuan }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="text-dark fw-medium">Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $urgensiClass = match ($item->urgensi) {
                                            'Penting Sekali' => 'bg-danger',
                                            'Biasa' => 'bg-info',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $urgensiClass }} text-white">{{ $item->urgensi }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusRaw = strtolower($item->status_persetujuan);
                                        $statusClass = match ($statusRaw) {
                                            'disetujui' => 'bg-success-subtle text-success',
                                            'ditolak' => 'bg-danger-subtle text-danger',
                                            default => 'bg-warning-subtle text-warning'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($item->status_persetujuan) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Tombol Detail --}}
                                        <button type="button" class="btn btn-sm btn-light-info p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;" title="Detail" onclick="showDetail({{ $item->id_pengajuan }})">
                                            <i class="ti ti-eye fs-5"></i>
                                        </button>

                                        @if(strtolower($item->status_persetujuan) == 'pending')
                                            <a href="{{ route('kaproli.pengajuan.edit', [$lab->id_lab, $item->id_pengajuan]) }}" class="btn btn-sm btn-light-primary p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;" title="Edit">
                                                <i class="ti ti-pencil fs-5"></i>
                                            </a>
                                            <form action="{{ route('kaproli.pengajuan.destroy', [$lab->id_lab, $item->id_pengajuan]) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger p-0 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;" onclick="return confirm('Hapus pengajuan ini?')" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted small">Belum ada data pengajuan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE CARD SECTION --}}
                <div class="d-md-none">
                    @forelse ($pengajuan as $item)
                        <div class="card border border-light-subtle mb-3 shadow-none bg-light-subtle" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold text-dark mb-0">{{ $item->nama_barang }}</h6>
                                    <span class="badge {{ $statusClass }}" style="font-size: 9px;">{{ strtoupper($item->status_persetujuan) }}</span>
                                </div>
                                <div class="row g-0 mb-3 small">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Harga Satuan</small>
                                        <span class="fw-medium text-primary">Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted d-block">Jumlah</small>
                                        <span class="fw-medium">{{ $item->jumlah }} {{ $item->satuan }}</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-1 mt-2">
                                    <button class="btn btn-info btn-sm w-100" style="border-radius: 8px;" onclick="showDetail({{ $item->id_pengajuan }})">Detail</button>
                                    @if(strtolower($item->status_persetujuan) == 'pending')
                                        <a href="{{ route('kaproli.pengajuan.edit', [$lab->id_lab, $item->id_pengajuan]) }}" class="btn btn-light-primary btn-sm w-100" style="border-radius: 8px;">Edit</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">Data tidak ditemukan</div>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0">Menampilkan {{ $pengajuan->count() }} data</p>
                    <div class="pagination-sm">{{ $pengajuan->withQueryString()->links() }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
{{-- MODAL DETAIL UNTUK KAPROLI --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-bottom-0 p-4 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="ti ti-info-circle me-2 text-primary"></i>Detail Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    {{-- Status Banner --}}
                    <div class="col-12">
                        <div id="det-status-banner" class="p-3 rounded-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="d-block opacity-75">Status Saat Ini:</small>
                                <span id="det-status-text" class="fw-bold fs-5"></span>
                            </div>
                            <i id="det-status-icon" class="fs-1 opacity-25"></i>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px;">Nama Barang</label>
                        <span id="det-nama" class="fw-bold fs-5 text-dark"></span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px;">Total Estimasi</label>
                        <span id="det-harga" class="fw-bold fs-5 text-success"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px;">Jumlah</label>
                        <span id="det-jumlah" class="fw-semibold text-dark"></span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="small text-muted d-block text-uppercase fw-bold mb-1" style="font-size: 10px;">Urgensi</label>
                        <span id="det-urgensi" class="badge"></span>
                    </div>

                    <div class="col-12">
                        <label class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 10px;">Spesifikasi</label>
                        <div id="det-spesifikasi" class="p-3 bg-light rounded-3 small border-0" style="white-space: pre-wrap; min-height: 50px; color: #444;"></div>
                    </div>

                    <div class="col-12">
                        <label class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 10px;">Catatan Pimpinan</label>
                        <div class="p-3 rounded-3 border-2 border-dashed" style="background-color: #fff9f0; border: 2px dashed #ffe8cc;">
                            <p id="det-catatan" class="mb-0 text-dark fw-medium" style="font-style: italic;"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 p-4 pt-0">
                <button type="button" class="btn btn-secondary w-100" style="border-radius: 12px; height: 45px;" data-bs-dismiss="modal">Tutup</button>
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
      function showDetail(id) {
    // Ambil ID Lab dari variabel yang dilempar controller ke view (contoh: $lab->id_lab)
    const labId = "{{ $lab->id_lab ?? $lab->id }}"; 

    // Pastikan URL mengarah ke: /kaproli/lab/{lab}/pengajuan/detail/{id}
    fetch(`/kaproli/lab/${labId}/pengajuan/detail/${id}`)
        .then(response => {
            if (!response.ok) throw new Error('Akses dilarang atau data tidak ada');
            return response.json();
        })
        .then(data => {
            // Mapping data ke Modal
            document.getElementById('det-nama').innerText = data.nama_barang;
            document.getElementById('det-jumlah').innerText = `${data.jumlah} ${data.satuan}`;
            document.getElementById('det-harga').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.estimasi_harga);
            document.getElementById('det-spesifikasi').innerText = data.spesifikasi || '-';
            document.getElementById('det-catatan').innerText = data.catatan_pimpinan || 'Belum ada catatan.';
            document.getElementById('det-urgensi').innerText = data.urgensi;

            // Logika Warna Status
            const banner = document.getElementById('det-status-banner');
            const statusText = document.getElementById('det-status-text');
            const icon = document.getElementById('det-status-icon');
            const status = (data.status_persetujuan || 'pending').toLowerCase();

            statusText.innerText = status.toUpperCase();

            // Reset class sebelum apply yang baru
            banner.className = 'p-3 rounded-3 d-flex align-items-center justify-content-between ';
            
            if (status === 'disetujui') {
                banner.classList.add('bg-success-subtle', 'text-success');
                icon.className = 'ti ti-circle-check fs-1';
            } else if (status === 'ditolak') {
                banner.classList.add('bg-danger-subtle', 'text-danger');
                icon.className = 'ti ti-circle-x fs-1';
            } else {
                banner.classList.add('bg-warning-subtle', 'text-warning');
                icon.className = 'ti ti-clock fs-1';
            }

            const myModal = new bootstrap.Modal(document.getElementById('modalDetail'));
            myModal.show();
        })
        .catch(error => alert(error.message));
}
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