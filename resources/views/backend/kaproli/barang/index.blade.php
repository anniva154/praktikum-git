@extends('layouts.backend')

@section('title', 'Data Barang')

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
                <h3 class="fw-bold text-dark">Data Barang - {{ $lab->nama_lab }} </h3>
                <p class="text-muted mb-3">Lihat, tambah, dan kelola data barang laboratorium pada SIMLAB.</p>
                <div style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="card" style="border-radius: 20px;">
            <div class="card-body p-3 p-md-4">
                
          
              {{-- TOOLBAR: Search & Filters --}}
<form method="GET" action="{{ route('kaproli.barang.index', $lab->id_lab) }}" id="filterForm">
    <div class="row g-2 mb-4 align-items-center">
        
        {{-- SEARCH --}}
        <div class="col-7 col-md-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control border-light-subtle shadow-none" 
                       placeholder="Cari barang..." 
                       style="border-radius: 10px 0 0 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;">
                <button class="btn btn-primary px-3" type="submit" style="border-radius: 0 10px 10px 0;">
                    <i class="ti ti-search"></i>
                </button>
            </div>
        </div>

        {{-- FILTER KONDISI (Menggantikan Role) --}}
        <div class="col-5 col-md-3">
            <select name="kondisi" class="form-select border-light-subtle shadow-none" 
                    style="border-radius: 10px; height: 42px; font-size: 14px; background-color: #f8f9fa;" 
                    onchange="this.form.submit()">
                <option value="">Kondisi</option>
                <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                <option value="Rusak Ringan" {{ request('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="Rusak Berat" {{ request('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>


        {{-- ACTION BUTTONS --}}
        <div class="col-12 col-md-auto ms-auto d-flex gap-2">
            @if(request()->hasAny(['kondisi', 'search']))
                <a href="{{ route('kaproli.barang.index', $lab->id_lab) }}"
                   class="btn btn-light-danger d-flex align-items-center justify-content-center" 
                   style="border-radius: 10px; height: 42px; min-width: 42px;">
                    <i class="ti ti-refresh"></i> <span class="d-none d-lg-inline ms-1">Reset</span>
                </a>
            @endif
            
            <a href=" {{ route('kaproli.barang.create', $lab->id_lab) }}"
               class="btn btn-success d-flex align-items-center justify-content-center px-4" 
               style="border-radius: 10px; height: 42px; font-weight: 600; min-width: 120px;">
                <i class="ti ti-plus me-1"></i> Tambah
            </a>
        </div>
    </div>
</form>

{{-- DESKTOP VIEW: TABLE --}}
                <div class="table-responsive d-none d-md-block">
                    <table class="table align-middle">
                        <thead class="bg-light-subtle">
                            <tr class="text-muted small text-uppercase">
                                <th class="ps-3" style="width: 50px;">No.</th>
                                <th>Nama Barang / Kode</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-center">Kondisi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($barang as $index => $item)
                                @php
                                    $badgeKondisi = match (strtolower($item->kondisi)) {
                                        'baik'         => 'bg-info-subtle text-info',
                                        'rusak ringan' => 'bg-warning-subtle text-warning',
                                        'rusak berat'  => 'bg-dark-subtle text-dark',
                                        default        => 'bg-secondary-subtle text-secondary',
                                    };
                                    $badgeStatus = match($item->status) {
                                        'aktif'       => 'bg-success-subtle text-success',
                                        'tidak layak' => 'bg-danger-subtle text-danger',
                                        default       => 'bg-secondary-subtle text-secondary',
                                    };
                                @endphp
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $barang->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                                        <small class="text-muted d-block" style="font-size: 11px;">{{ $item->kode_barang }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border fw-semibold">{{ $item->jumlah }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $badgeKondisi }}">{{ ucfirst($item->kondisi) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill {{ $badgeStatus }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('kaproli.barang.edit', [$lab->id_lab, $item->id_barang]) }}" 
                                               class="btn btn-sm btn-light-primary p-0 d-flex align-items-center justify-content-center shadow-none" 
                                               style="width: 32px; height: 32px; border-radius: 8px;" title="Edit">
                                                <i class="ti ti-pencil fs-5"></i>
                                            </a>
                                            <form action="{{ route('kaproli.barang.destroy', [$lab->id_lab, $item->id_barang]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-light-danger p-0 d-flex align-items-center justify-content-center shadow-none" 
                                                        style="width: 32px; height: 32px; border-radius: 8px;" 
                                                        onclick="return confirm('Hapus barang ini?')" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-5 text-muted">Data barang tidak ditemukan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE VIEW: CARDS --}}
                <div class="d-md-none">
                    @forelse ($barang as $item)
                        @php
                            $badgeKondisiMob = match (strtolower($item->kondisi)) {
                                'baik'         => 'bg-info-subtle text-info',
                                'rusak ringan' => 'bg-warning-subtle text-warning',
                                'rusak berat'  => 'bg-dark-subtle text-dark',
                                default        => 'bg-secondary-subtle text-secondary',
                            };
                        @endphp
                        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div style="max-width: 70%;">
                                        <h6 class="fw-bold mb-0 text-dark text-truncate">{{ $item->nama_barang }}</h6>
                                        <small class="text-muted" style="font-size: 11px;">{{ $item->kode_barang }}</small>
                                    </div>
                                    <span class="badge rounded-pill {{ $item->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" 
                                          style="font-size: 10px;">{{ ucfirst($item->status) }}</span>
                                </div>
                                
                                <div class="text-muted small mb-3">
                                    <div class="mb-1">
                                        <i class="ti ti-box me-1"></i> Jumlah: <span class="fw-bold text-dark">{{ $item->jumlah }}</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-activity me-1"></i> Kondisi: 
                                        <span class="badge rounded-pill {{ $badgeKondisiMob }}" style="font-size: 10px;">{{ ucfirst($item->kondisi) }}</span>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-2 border-top">
                                    <a href="{{ route('kaproli.barang.edit', [$lab->id_lab, $item->id_barang]) }}" 
                                       class="btn btn-light-primary btn-sm d-flex align-items-center justify-content-center gap-1" 
                                       style="border-radius: 8px; flex: 1; height: 35px;">
                                        <i class="ti ti-pencil fs-5"></i> Edit
                                    </a>
                                    <form action="{{ route('kaproli.barang.destroy', [$lab->id_lab, $item->id_barang]) }}" 
                                          method="POST" style="flex: 1;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-light-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1" 
                                                style="border-radius: 8px; height: 35px;" 
                                                onclick="return confirm('Hapus?')">
                                            <i class="ti ti-trash fs-5"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">Data barang tidak ditemukan</div>
                    @endforelse
                </div>

{{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0 text-center text-md-start">
                        Menampilkan {{ $barang->count() }} dari {{ $barang->total() }} data
                    </p>
                    <div class="pagination-sm">
                        {{ $barang->withQueryString()->links() }}
                    </div>
                </div>

      </div>
    </div>
  </div>
</div>
@endsection


{{-- SEARCH DELAY --}}
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
