@extends('layouts.backend')

@section('title', 'Data Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            <!-- HEADER -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Pengguna</h5>
                <a href="{{ route('admin.pengguna.create') }}" class="btn btn-success btn-sm">
                    Tambah +
                </a>
            </div>

            <div class="card-body">

                <!-- FILTER & SEARCH -->
                <form method="GET" action="{{ route('admin.pengguna.index') }}" id="filterForm">
    <div class="d-flex justify-content-end align-items-center gap-2 mb-3 flex-wrap">

        <!-- ROLE -->
        <select name="role" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
            <option value="kaproli" {{ request('role') == 'kaproli' ? 'selected' : '' }}>Kaproli</option>
            <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
        </select>

        <!-- STATUS -->
        <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <!-- SEARCH -->
        <div class="position-relative" style="width: 230px;">
            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control form-control-sm ps-5"
                placeholder="Cari nama / username"
            >
        </div>

        @if(request()->hasAny(['role','status','search']))
            <a href="{{ route('admin.pengguna.index') }}" class="btn btn-secondary btn-sm">
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
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Role / Hak Akses</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + $users->firstItem() }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>

                                    <!-- ROLE -->
                                    <td>
                                        @php
                                            $badgeClass = match($user->role) {
                                                'admin'     => 'bg-danger-subtle text-danger',
                                                'pimpinan'  => 'bg-primary-subtle text-primary',
                                                'kaproli'   => 'bg-warning-subtle text-warning',
                                                'pengguna'  => 'bg-info-subtle text-info',
                                                default     => 'bg-light text-dark',
                                            };

                                            $label = $user->role === 'pengguna'
                                                ? 'Pengguna (' . ucfirst($user->tipe_pengguna) . ')'
                                                : ucfirst($user->role);
                                        @endphp

                                        <span class="badge rounded-pill px-3 {{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>

                                    <!-- JURUSAN -->
                                    <td>
                                        {{ $user->jurusan?->nama_jurusan ?? '-' }}
                                    </td>

                                    <!-- STATUS -->
                                    <td>
                                        @if ($user->status === 'aktif')
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- ACTION -->
                                    <td class="text-center">
                                        <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="text-warning me-2">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.pengguna.destroy', $user->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin hapus pengguna?')">
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
                                    <td colspan="7" class="text-center text-muted">
                                        Data pengguna belum ada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="mt-3">
                    {{ $users->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
<script>
    let timer;
    const searchInput = document.querySelector('input[name="search"]');

    searchInput.addEventListener('keyup', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
</script>

