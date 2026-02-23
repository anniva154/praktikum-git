@extends('layouts.backend')

@section('title', 'Data Barang')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"> Data Barang - {{ $lab->nama_lab }} </h5>

                               <a href="{{ route('pimpinan.export.barang', $lab->id_lab) }}"
 class="btn btn-sm btn-outline-primary">
                        
                        <i class="ti ti-download me-1"></i>
                        Unduh Data
                    </a>
                </div>


      <div class="card-body">

    {{-- ======================== --}}
    {{--        FILTER BAR        --}}
    {{-- ======================== --}}
    <form method="GET" id="filterForm">
        <div class="row g-2 mb-3">

            {{-- KONDISI --}}
            <div class="col-12 col-md-auto">
                <select name="kondisi"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi')=='baik'?'selected':'' }}>Baik</option>
                    <option value="rusak ringan" {{ request('kondisi')=='rusak ringan'?'selected':'' }}>Rusak Ringan</option>
                    <option value="rusak berat" {{ request('kondisi')=='rusak berat'?'selected':'' }}>Rusak Berat</option>
                </select>
            </div>

            {{-- STATUS --}}
            <div class="col-12 col-md-auto">
                <select name="status"
                        class="form-select form-select-sm"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="tidak layak" {{ request('status')=='tidak layak'?'selected':'' }}>Tidak Layak</option>
                </select>
            </div>

            {{-- SEARCH --}}
            <div class="col-12 col-md-3">
                <div class="position-relative">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control form-control-sm ps-5"
                           placeholder="Cari barang...">
                </div>
            </div>

            {{-- RESET --}}
            @if(request()->hasAny(['kondisi','status','search']))
                <div class="col-12 col-md-auto">
                    <a href="{{ url()->current() }}"
                       class="btn btn-secondary btn-sm w-100">
                        Reset
                    </a>
                </div>
            @endif

        </div>
    </form>


    {{-- ======================== --}}
    {{--       RESPONSIVE TABLE   --}}
    {{-- ======================== --}}
    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead class="text-muted border-bottom">
                <tr>
                    <th style="min-width: 60px">No</th>
                    <th style="min-width: 180px">Nama Barang</th>
                    <th style="min-width: 140px">Kode</th>
                    <th style="min-width: 80px">Jumlah</th>
                    <th style="min-width: 160px">Kondisi</th>
                    <th style="min-width: 150px">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($barang as $index => $item)
                    <tr>
                        <td>{{ $index + $barang->firstItem() }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->jumlah }}</td>

                        {{-- KONDISI --}}
                        <td>
                            @php
                                $kondisiBadge = match($item->kondisi) {
                                    'baik' => 'bg-success-subtle text-success',
                                    'rusak ringan' => 'bg-warning-subtle text-warning',
                                    'rusak berat' => 'bg-danger-subtle text-danger',
                                    default => 'bg-secondary-subtle text-secondary',
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 {{ $kondisiBadge }}">
                                {{ ucfirst($item->kondisi) }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @php
                                $statusBadge = match($item->status) {
                                    'aktif' => 'bg-primary-subtle text-primary',
                                    'tidak layak' => 'bg-dark-subtle text-dark',
                                    default => 'bg-secondary-subtle text-secondary',
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 {{ $statusBadge }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Data barang belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

 {{-- PAGINATION --}}
        <div class="mt-3">
          {{ $barang->links('pagination::simple-bootstrap-4') }}
        </div>
    

</div>

    </div>
  </div>
</div>
@endsection

{{-- SEARCH DELAY --}}
@push('scripts')
<script>
let timer;
const searchInput = document.querySelector('input[name="search"]');

searchInput?.addEventListener('keyup', function () {
  clearTimeout(timer);
  timer = setTimeout(() => {
    document.getElementById('filterForm').submit();
  }, 500);
});
</script>
@endpush
