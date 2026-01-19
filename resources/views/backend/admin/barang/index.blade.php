@extends('layouts.backend')

@section('title', 'Data Barang')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      {{-- HEADER --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          Data Barang - {{ $lab->nama_lab }}
        </h5>
      </div>

      <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" id="filterForm">
          <div class="d-flex justify-content-end gap-2 mb-3 flex-wrap">

            {{-- KONDISI --}}
            <select name="kondisi"
                    class="form-select form-select-sm w-auto"
                    onchange="this.form.submit()">
              <option value="">Semua Kondisi</option>
              <option value="Baik" {{ request('kondisi')=='Baik'?'selected':'' }}>Baik</option>
              <option value="Dipinjam" {{ request('kondisi')=='Dipinjam'?'selected':'' }}>Dipinjam</option>
              <option value="Rusak" {{ request('kondisi')=='Rusak'?'selected':'' }}>Rusak</option>
              <option value="Dalam Perbaikan" {{ request('kondisi')=='Dalam Perbaikan'?'selected':'' }}>
                Dalam Perbaikan
              </option>
            </select>

            {{-- SEARCH --}}
            <div class="position-relative" style="width:230px">
              <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input type="text"
                     name="search"
                     value="{{ request('search') }}"
                     class="form-control form-control-sm ps-5"
                     placeholder="Cari barang">
            </div>

            @if(request()->hasAny(['kondisi','search']))
              <a href="{{ url()->current() }}" class="btn btn-secondary btn-sm">
                Reset
              </a>
            @endif
          </div>
        </form>

        {{-- TABLE --}}
        <div class="table-responsive">
          <table class="table table-borderless align-middle">
            <thead class="text-muted border-bottom">
              <tr>
                <th width="50">No</th>
                <th>Nama Barang</th>
                <th>Kode</th>
                <th width="80">Jumlah</th>
                <th width="160">Kondisi</th>
                <th width="120" class="text-center">Aksi</th>
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
                      $badge = match($item->kondisi) {
                        'Baik'            => 'bg-success-subtle text-success',
                        'Dipinjam'        => 'bg-warning-subtle text-warning',
                        'Rusak'           => 'bg-danger-subtle text-danger',
                        'Dalam Perbaikan' => 'bg-primary-subtle text-primary',
                        default           => 'bg-secondary-subtle text-secondary',
                      };
                    @endphp

                    <span class="badge rounded-pill px-3 {{ $badge }}">
                      {{ $item->kondisi }}
                    </span>
                  </td>

                  {{-- AKSI --}}
                  <td class="text-center">

                    {{-- EDIT STATUS --}}
                    <a href="{{ route('admin.barang.edit', [$lab->id_lab, $item->id_barang]) }}"
                       class="text-warning me-2"
                       title="Edit Status">
                      <i class="ti ti-pencil"></i>
                    </a>

                    {{-- HAPUS --}}
                    <form action="{{ route('admin.barang.destroy', [$lab->id_lab, $item->id_barang]) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Yakin hapus barang ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn p-0 border-0 bg-transparent text-danger"
                              title="Hapus Barang">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>

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
          {{ $barang->withQueryString()->links() }}
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
