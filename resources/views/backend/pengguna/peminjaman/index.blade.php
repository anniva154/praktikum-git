@extends('layouts.backend')

@section('title', 'Data Peminjaman')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      <div class="card-header d-flex justify-content-between align-items-center">
  <h5 class="mb-0">Data Barang Dipinjam</h5>

  <a href="{{ route('pengguna.peminjaman.create') }}"
     class="btn btn-primary btn-sm">
      <i class="ti ti-plus"></i> Ajukan Peminjaman
  </a>
</div>


      <div class="card-body">

        {{-- TABLE --}}
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead class="text-muted border-bottom">
              <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Lab</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($peminjaman as $index => $item)
                <tr>
                  <td>{{ $index + $peminjaman->firstItem() }}</td>
                  <td>{{ $item->barang->nama_barang }}</td>
                  <td>{{ $item->barang->laboratorium->nama_lab ?? '-' }}</td>
                  <td>{{ $item->jumlah_pinjam }}</td>
                  <td>{{ \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y') }}</td>
                  <td>
                    {{ $item->tgl_kembali
                        ? \Carbon\Carbon::parse($item->tgl_kembali)->format('d/m/Y')
                        : '-' }}
                  </td>

                  {{-- STATUS --}}
                  <td>
                    @php
                      $badge = match($item->status) {
                        'diajukan'     => 'bg-warning-subtle text-warning',
                        'disetujui'    => 'bg-success-subtle text-success',
                        'ditolak'      => 'bg-danger-subtle text-danger',
                        'dikembalikan' => 'bg-secondary-subtle text-secondary',
                        default        => 'bg-light text-dark',
                      };
                    @endphp

                    <span class="badge rounded-pill px-3 {{ $badge }}">
                      {{ ucfirst($item->status) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted">
                    Belum ada data peminjaman
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
          {{ $peminjaman->links() }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
