@extends('layouts.backend')

@section('title', 'Data Laboratorium')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Laboratorium</h5>
                <a href="{{ route('admin.lab.create') }}" class="btn btn-success btn-sm">
                    Tambah +
                </a>
            </div>

            <div class="card-body">
 {{-- SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-checks me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                <!-- FILTER & SEARCH -->
                <form method="GET" action="{{ route('admin.lab.index') }}" id="filterForm">
                    <div class="d-flex justify-content-end align-items-center gap-2 mb-3 flex-wrap">

                        <!-- FILTER JURUSAN -->
                        <select name="jurusan"
                                class="form-select form-select-sm w-auto"
                                onchange="this.form.submit()">
                            <option value="">Semua Jurusan</option>
                            @foreach ($jurusan as $j)
                                <option value="{{ $j->id_jurusan }}"
                                    {{ request('jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>

                        <!-- SEARCH -->
                        <div class="position-relative" style="width: 230px;">
                            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control form-control-sm ps-5"
                                placeholder="Cari nama lab"
                            >
                        </div>

                        @if(request()->hasAny(['jurusan','search']))
                            <a href="{{ route('admin.lab.index') }}" class="btn btn-secondary btn-sm">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="text-muted border-bottom">
                            <tr>
                                <th>No</th>
                                <th>Nama Laboratorium</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($labs as $index => $lab)

                                @php
                                    $badgeClass = match($lab->status) {
                                        'kosong'     => 'bg-success-subtle text-success',
                                        'dipakai'    => 'bg-primary-subtle text-primary',
                                        'perbaikan'  => 'bg-danger-subtle text-danger',
                                        default      => 'bg-light text-dark',
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $index + $labs->firstItem() }}</td>

                                    <td class="fw-semibold">
                                        {{ $lab->nama_lab }}
                                    </td>

                                    <td>
                                            {{ $lab->jurusan?->nama_jurusan ?? '-' }}
                                    
                                    </td>

                                    <td>
                                        <span class="badge rounded-pill px-3 {{ $badgeClass }}">
                                            {{ ucfirst($lab->status) }}
                                        </span>
                                    </td>

                                    <td class="text-muted">
                                        {{ $lab->keterangan ?: '-' }}
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.lab.edit', $lab->id_lab) }}" class="text-warning me-2">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.lab.destroy', $lab->id_lab) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus laboratorium?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn p-0 text-danger border-0 bg-transparent">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Data laboratorium belum ada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="mt-3">
                    {{ $labs->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let timer;
const searchInput = document.querySelector('input[name="search"]');

if (searchInput) {
    searchInput.addEventListener('keyup', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
}
        setTimeout(() => {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('hide');
            }
        }, 3000);

</script>
@endpush
