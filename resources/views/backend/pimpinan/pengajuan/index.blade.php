@extends('layouts.backend')

@section('title', 'Pengajuan Barang')

@section('content')
  <div class="row">
    <div class="col-12">

      {{-- NOTIFIKASI --}}
      <div class="mb-2">
        @include('components.notification')
      </div>
      <div class="card mb-4"
        style="border-radius: 20px; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
        <div class="card-body p-4">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="me-3 p-3 rounded-circle d-flex align-items-center justify-content-center"
                style="width: 55px; height: 55px; background-color: #e6fffa !important;">
                <i class="ti ti-clipboard-list" style="color: #00b19d !important; font-size: 1.5rem;"></i>
              </div>
              <div>
                <h3 class="fw-bold text-dark mb-0" style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                  Pengajuan Barang
                </h3>
                <p class="text-muted mb-0" style="font-size: 13px;">{{ $lab->nama_lab }}</p>
              </div>
            </div>
            <a href="{{ route('pimpinan.export.pengajuan', $lab->id_lab) }}" class="btn btn-sm btn-outline-primary">
              <i class="ti ti-download me-1"></i>
              Unduh
            </a>

          </div>
          <div class="mt-3"
            style="height: 4px; width: 100px; background: linear-gradient(to right, #00b19d, #20e4d0); border-radius: 2px;">
          </div>
        </div>
      </div>

      <div class="card" style="border-radius: 20px; border: none;">
        <div class="card-body p-3 p-md-4">
          {{-- TOOLBAR --}}
          <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="row g-2 mb-4 align-items-center">
              <div class="col-7 col-md-4">
                <div class="position-relative">
                  <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                  <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control border-0 shadow-none bg-light" placeholder="Cari nama barang..."
                    style="border-radius: 12px; height: 45px; padding-left: 45px;">
                </div>
              </div>
              <div class="col-5 col-md-3">
                <select name="status" class="form-select border-0 shadow-none bg-light"
                  style="border-radius: 12px; height: 45px; cursor: pointer;" onchange="this.form.submit()">
                  <option value="">Semua Status</option>
                  @foreach(['Pending', 'Disetujui', 'Ditolak'] as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </form>

          {{-- DESKTOP TABLE --}}
          <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th class="ps-3">Tgl Pengajuan</th>
                  <th>Nama Barang</th>
                  <th class="text-center">Jumlah</th>
                  <th>Estimasi Harga</th>
                  <th class="text-center">Urgensi</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($pengajuan as $item)
                  <tr>
                    <td class="ps-3 text-muted small">{{ optional($item->created_at)->format('d/m/Y') }}</td>
                    <td>
                      <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                      <small class="text-muted d-block" style="font-size:11px;">
                        {{ $item->laboratorium->nama_lab ?? '-' }}
                      </small>
                    </td>
                    <td class="text-center">
                      <span class="fw-semibold">{{ $item->jumlah }}</span>
                      <small class="text-muted">{{ $item->satuan }}</small>
                    </td>
                    <td><span class="text-dark fw-medium">Rp {{ number_format($item->estimasi_harga, 0, ',', '.') }}</span>
                    </td>
                    <td class="text-center">
                      <span
                        class="badge {{ $item->urgensi == 'Penting Sekali' ? 'bg-danger' : ($item->urgensi == 'Biasa' ? 'bg-info' : 'bg-secondary') }} text-white">
                        {{ $item->urgensi }}
                      </span>
                    </td>
                    <td class="text-center">
                      <span
                        class="badge {{ strtolower($item->status_persetujuan) == 'disetujui' ? 'bg-success-subtle text-success' : (strtolower($item->status_persetujuan) == 'ditolak' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                        {{ ucfirst($item->status_persetujuan) }}
                      </span>
                    </td>
                    <td class="text-center">
                      <button type="button" onclick="showDetail({{ $item->id_pengajuan }})"
                        class="btn btn-sm btn-light-info p-0 mx-auto d-flex align-items-center justify-content-center"
                        style="width:35px;height:35px;border-radius:10px" title="Detail">
                        <i class="ti ti-eye fs-5"></i>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center py-5 text-muted">Belum ada data pengajuan</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- MOBILE CARD --}}
          <div class="d-md-none">
            @forelse ($pengajuan as $item)
              <div class="card border border-light-subtle mb-3 shadow-none bg-light-subtle" style="border-radius:15px">
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="fw-bold text-dark mb-0">{{ $item->nama_barang }}</h6>
                    <span
                      class="badge {{ strtolower($item->status_persetujuan) == 'pending' ? 'bg-warning-subtle' : (strtolower($item->status_persetujuan) == 'disetujui' ? 'bg-success-subtle' : 'bg-danger-subtle') }}"
                      style="font-size:9px">
                      {{ strtoupper($item->status_persetujuan) }}
                    </span>
                  </div>
                  <div class="row g-0 mb-3 small">
                    <div class="col-6">
                      <small class="text-muted d-block">Total</small>
                      <span class="fw-medium text-primary">Rp
                        {{ number_format($item->estimasi_harga * $item->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-6 text-end">
                      <small class="text-muted d-block">Jumlah</small>
                      <span class="fw-medium">{{ $item->jumlah }} {{ $item->satuan }}</span>
                    </div>
                  </div>
                  <button type="button" onclick="showDetail({{ $item->id_pengajuan }})"
                    class="btn btn-sm btn-light-info w-100">
                    <i class="ti ti-eye"></i> Lihat Detail
                  </button>
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-muted">Data tidak ditemukan</div>
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

  {{-- MODAL DETAIL --}}
  <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content" style="border-radius: 15px; border: none;">
        <div class="modal-header border-bottom-0 p-4 pb-2">
          <h5 class="modal-title fw-bold">Detail & Validasi Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formValidasi" method="POST">
          @csrf @method('PATCH')
          <div class="modal-body p-4 pt-0">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="small text-muted d-block">Nama Barang</label>
                <span id="det-nama" class="fw-bold fs-5 text-dark"></span>
              </div>
              <div class="col-md-6 text-md-end">
                <label class="small text-muted d-block">Pengaju (Kaproli)</label>
                <span id="det-user" class="fw-semibold text-primary"></span>
              </div>
              <div class="col-md-4">
                <label class="small text-muted d-block">Laboratorium</label>
                <span id="det-lab" class="badge bg-primary-subtle text-primary"></span>
              </div>
              <div class="col-md-4 text-center">
                <label class="small text-muted d-block">Jumlah</label>
                <span id="det-jumlah" class="fw-bold text-dark"></span>
              </div>
              <div class="col-md-4 text-end">
                <label class="small text-muted d-block">Total Estimasi</label>
                <span id="det-harga" class="fw-bold text-success"></span>
              </div>

              <div class="col-12">
                <label class="small text-muted">Spesifikasi Barang</label>
                <div id="det-spesifikasi" class="p-3 bg-light rounded small border"
                  style="white-space: pre-wrap; min-height: 50px;"></div>
              </div>

              <div class="col-12">
                <label class="small text-muted">Alasan Kebutuhan</label>
                <p id="det-alasan" class="mb-0 border-start border-4 ps-3 py-1 text-dark" style="font-style: italic;"></p>
              </div>

              <hr class="my-3">

              <div class="col-md-6">
                <label class="form-label fw-bold">Status Persetujuan</label>
                <select name="status_persetujuan" id="det-status-input" class="form-select border-2 shadow-none">
                  <option value="Pending">Pending</option>
                  <option value="Disetujui">Setujui</option>
                  <option value="Ditolak">Tolak</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Catatan Pimpinan</label>
                <textarea name="catatan_pimpinan" id="det-catatan-input" class="form-control border-2 shadow-none"
                  rows="2" placeholder="Berikan alasan jika ditolak..."></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer border-top-0 p-4 pt-0">
            <button type="button" class="btn btn-light px-4" style="border-radius: 10px;"
              data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary px-4"
              style="border-radius: 10px; background-color: #00b19d; border: none;">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
   function showDetail(id) {
    // URL disesuaikan dengan Route baru
    const url = `{{ url('pimpinan/pengajuan/detail') }}/${id}`;

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Data tidak ditemukan atau akses dilarang');
            return response.json();
        })
        .then(data => {
            // Tampilkan data ke elemen modal
            document.getElementById('det-nama').innerText = data.nama_barang;
            document.getElementById('det-user').innerText = data.user.name;
            document.getElementById('det-lab').innerText = data.laboratorium.nama_lab;
            document.getElementById('det-jumlah').innerText = `${data.jumlah} ${data.satuan}`;
            document.getElementById('det-harga').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.estimasi_harga);
            document.getElementById('det-spesifikasi').innerText = data.spesifikasi;
            document.getElementById('det-alasan').innerText = data.alasan_kebutuhan;

            // Form Validasi (khusus pimpinan)
            const statusInput = document.getElementById('det-status-input');
            const catatanInput = document.getElementById('det-catatan-input');
            if (statusInput) statusInput.value = data.status_persetujuan;
            if (catatanInput) catatanInput.value = data.catatan_pimpinan || '';
            
            // Set Action Form Validasi
            const formValidasi = document.getElementById('formValidasi');
            if (formValidasi) {
                formValidasi.action = `{{ url('pimpinan/pengajuan/validasi') }}/${id}`;
            }

            const myModal = new bootstrap.Modal(document.getElementById('modalDetail'));
            myModal.show();
        })
        .catch(err => {
            alert('Error: ' + err.message);
        });
}
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
@push('styles')
  <style>
    .table thead th {
      background-color: #fbfbfb;
      border-bottom: 2px solid #ebeef2;
      padding: 15px 10px;
      font-size: 0.75rem;
      text-transform: uppercase;
      font-weight: 700 !important;
      letter-spacing: 0.5px;
      color: #7c8fac;
      vertical-align: middle;
      text-align: center;
    }
    .table thead th.text-start-important {
      text-align: left !important;
    }

    .table tbody tr {
      border-bottom: 1px solid #f1f1f1;
      transition: all 0.2s ease;
    }

    .table tbody td {
      padding: 16px 10px;
      vertical-align: middle;
      border: none;
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