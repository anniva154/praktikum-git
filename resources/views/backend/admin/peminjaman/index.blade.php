@extends('layouts.backend')

@section('title', 'Data Peminjaman Per Lab')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      {{-- HEADER --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          Data Barang Dipinjam - {{ $lab->nama_lab }}
        </h5>
      </div>

      <div class="card-body">

        {{-- TABLE --}}
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead class="text-muted border-bottom">
              <tr>
                <th>No</th>
                <th>Barang</th>
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
                  <td colspan="6" class="text-center text-muted">
                    Belum ada peminjaman di lab ini
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $peminjaman->links() }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

<script>
setTimeout(() => {
    window.location.reload();
}, 5000);
</script>
