@extends('layouts.backend')

@section('title', 'Laporan Kerusakan')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      {{-- HEADER --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          Data Laporan Kerusakan - {{ $lab->nama_lab }}
        </h5>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead class="text-muted border-bottom">
              <tr>
                <th>No</th>
                <th>Barang</th>
                <th>Lab</th>
                <th>Tgl Laporan</th>
                <th>Status</th>
                <th>Keterangan</th>
              </tr>
            </thead>

            <tbody>
              @forelse ($laporan as $index => $item)
                <tr>
                  {{-- No --}}
                  <td>{{ $laporan->firstItem() + $index }}</td>

                  {{-- Barang --}}
                  <td>{{ $item->barang->nama_barang ?? '-' }}</td>

                  {{-- Lab --}}
                  <td>{{ $item->laboratorium->nama_lab ?? '-' }}</td>

                  {{-- Tanggal --}}
                  <td>{{ \Carbon\Carbon::parse($item->tgl_laporan)->format('d/m/Y') }}</td>

                  {{-- Status --}}
                  <td>
                    @php
                      $badge = match($item->status) {
                        'dilaporkan'  => 'bg-warning-subtle text-warning',
                        'diproses'    => 'bg-info-subtle text-info',
                        'selesai'     => 'bg-success-subtle text-success',
                        'ditolak'     => 'bg-danger-subtle text-danger',
                        default       => 'bg-light text-dark',
                      };
                    @endphp

                    <span class="badge rounded-pill px-3 {{ $badge }}">
                      {{ ucfirst($item->status) }}
                    </span>
                  </td>

                  {{-- Keterangan --}}
                  <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">
                    Belum ada laporan kerusakan
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
          {{ $laporan->links() }}
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
