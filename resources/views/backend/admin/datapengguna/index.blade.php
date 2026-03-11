@extends('layouts.backend')

@section('title', 'Data Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        {{-- NOTIFIKASI --}}
        <div class="mb-2">
            @include('components.notification')
        </div>

        {{-- Header Card Data Pengguna --}}
<div class="card mb-4" style="border-radius: 20px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="me-3 bg-primary-subtle p-3 rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 55px; height: 55px;">
                    <i class="ti ti-users text-primary fs-7"></i>
                </div>
                
                <div>
                    <h3 class="fw-bold text-dark mb-0"
                        style="font-size: calc(1.1rem + 0.3vw); letter-spacing: -0.5px;">
                        Data Pengguna
                    </h3>
                    <p class="text-muted mb-0" style="font-size: 13px;">
                        Lihat, tambah, dan kelola data pengguna pada SIMLAB.
                    </p>
                </div>
            </div>

            <div>
                <a href="{{ route('admin.pengguna.create') }}"
                   class="btn btn-success d-inline-flex align-items-center shadow-sm"
                   style="border-radius: 12px; padding: 8px 16px; transition: all 0.3s;">
                    <i class="ti ti-plus fs-6 me-1"></i>
                    <span class="fw-bold" style="font-size: 13px;">Tambah</span>
                </a>
            </div>
        </div>

        <div class="mt-3"
            style="height: 4px; width: 150px; background: linear-gradient(to right, #007bff, #00d4ff); border-radius: 2px;">
        </div>
    </div>
</div>

        <div class="card" style="border-radius: 20px;">
            <div class="card-body p-3 p-md-4">
                
                {{-- TOOLBAR: Search & Filters --}}
                <form method="GET" action="{{ route('admin.pengguna.index') }}" id="filterForm">
                    <div class="row g-2 mb-4 align-items-center">
                        <div class="col-7 col-md-4">
                            <div class="position-relative">
                                <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                      class="form-control border-0 shadow-none"
                                       placeholder="Cari nama atau email..." 
                                       style="border-radius: 12px; height: 42px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                            </div>
                        </div>

                        <div class="col-5 col-md-2">
                            <select name="role" class="form-select border-0 shadow-none" 
                                    style="border-radius: 12px; height: 42px; font-size: 14px; background-color: #f8f9fa;"
                                    onchange="this.form.submit()">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                <option value="kaproli" {{ request('role') == 'kaproli' ? 'selected' : '' }}>Kaproli</option>
                                <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                            </select>
                        </div>
                    </div>
                </form>

               {{-- DESKTOP VIEW: TABLE --}}
<div class="table-responsive d-none d-md-block">
    <table class="table align-middle">
        <thead>
            <tr>
                <th class="ps-3" style="width: 50px;">No.</th>
                <th>Pengguna</th>
                <th class="text-center">Role</th>
                <th class="text-center">No. WhatsApp</th> {{-- Tambah Header --}}
                <th class="text-center">Jurusan</th>
                <th class="text-center">Status</th>
                <th class="text-end pe-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                @php
                    $badgeRole = match($user->role) {
                        'admin'    => 'bg-purple-subtle text-purple',
                        'pimpinan' => 'bg-primary-subtle text-primary',
                        'kaproli'  => 'bg-warning-subtle text-warning',
                        'pengguna' => 'bg-info-subtle text-info',
                        default    => 'bg-light text-dark',
                    };
                    $roleLabel = $user->role === 'pengguna' ? 'Pengguna (' . ucfirst($user->tipe_pengguna) . ')' : ucfirst($user->role);
                    
                    $photoPath = $user->foto && file_exists(public_path('uploads/profile/' . $user->foto)) 
                        ? asset('uploads/profile/' . $user->foto) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff';
                @endphp
                <tr>
                    <td class="ps-3 text-muted small">{{ $users->firstItem() + $index }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $photoPath }}" 
                                 class="rounded-circle me-3 border border-2 border-white shadow-sm" 
                                 style="width: 42px; height: 42px; object-fit: cover;"
                                 alt="avatar">
                            <div>
                                <div class="fw-bold text-dark mb-0">{{ $user->name }}</div>
                                <small class="text-muted" style="font-size: 11px;">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge rounded-pill {{ $badgeRole }}">{{ $roleLabel }}</span>
                    </td>
                    {{-- Tambah Kolom No WA --}}
                    <td class="text-center">
                        @if($user->no_wa)
                            <a href="https://wa.me/{{ $user->no_wa }}" target="_blank" class="text-success text-decoration-none small fw-bold">
                                <i class="ti ti-brand-whatsapp fs-5 me-1"></i>{{ $user->no_wa }}
                            </a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-center text-muted small">{{ $user->jurusan?->nama_jurusan ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge rounded-pill {{ $user->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="text-end pe-3">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn btn-sm btn-light-primary p-2" style="border-radius: 8px;"><i class="ti ti-pencil fs-5"></i></a>
                            <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light-danger p-2" style="border-radius: 8px;" onclick="return confirm('Hapus pengguna?')"><i class="ti ti-trash fs-5"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center py-5 text-muted small">Data pengguna tidak ditemukan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- MOBILE VIEW: CARDS --}}
<div class="d-md-none">
    @forelse ($users as $user)
        @php
            $photoPath = $user->foto && file_exists(public_path('uploads/profile/' . $user->foto)) 
                ? asset('uploads/profile/' . $user->foto) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff';
        @endphp
        <div class="card border border-light-subtle shadow-none mb-3" style="border-radius: 15px;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $photoPath }}" class="rounded-circle me-3 shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0 text-dark">{{ $user->name }}</h6>
                        <small class="text-muted small">{{ $user->email }}</small>
                    </div>
                    <span class="badge rounded-pill {{ $user->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}" style="font-size: 10px;">{{ ucfirst($user->status) }}</span>
                </div>
                <div class="text-muted small mb-3 bg-light p-2 rounded">
                    <div class="mb-1"><i class="ti ti-user-circle me-1"></i> {{ ucfirst($user->role) }}</div>
                    <div class="mb-1"><i class="ti ti-school me-1"></i> {{ $user->jurusan?->nama_jurusan ?? 'Semua Jurusan' }}</div>
                    {{-- Tambah Info WA di Mobile --}}
                    <div><i class="ti ti-brand-whatsapp me-1 text-success"></i> {{ $user->no_wa ?? '-' }}</div>
                </div>
                <div class="d-flex gap-2 pt-2 border-top">
                    <a href="{{ route('admin.pengguna.edit', $user->id) }}" class="btn btn-light-primary btn-sm flex-grow-1" style="border-radius: 8px;">Edit</a>
                    <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST" class="flex-grow-1">
                        @csrf @method('DELETE')
                        <button class="btn btn-light-danger btn-sm w-100" style="border-radius: 8px;" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-4 text-muted small">Data tidak ditemukan</div>
    @endforelse
</div>

                {{-- PAGINATION --}}
                <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-muted small mb-0">Menampilkan {{ $users->count() }} data dari {{ $users->total() }}</p>
                    <div class="pagination-sm">{{ $users->withQueryString()->links() }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* --- Table Styling --- */
    .table thead th {
        background-color: #fbfbfb;
        border-bottom: 1px solid #f1f1f1;
        padding: 15px 10px;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700 !important;
        letter-spacing: 0.5px;
        color: #7c8fac;
    }

    .table tbody tr:hover {
        background-color: #f0f5ff !important;
    }

    .table tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #f8f9fa;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    /* --- Badge & Colors --- */
    .badge {
        font-weight: 600 !important;
        padding: 5px 12px !important;
        border-radius: 8px !important;
        font-size: 11px;
    }

    .bg-purple-subtle { background-color: #f3e8ff !important; color: #7e22ce !important; }
    .bg-success-subtle { background-color: #e6f9f1 !important; color: #1f9462 !important; }
    .bg-primary-subtle { background-color: #ecf2ff !important; color: #5d87ff !important; }
    .bg-danger-subtle { background-color: #fef5f5 !important; color: #fa896b !important; }
    .bg-warning-subtle { background-color: #fff8ec !important; color: #ffae1f !important; }
    .bg-info-subtle { background-color: #e7f1ff !important; color: #007bff !important; }

    /* --- Tombol Aksi --- */
    .btn-light-primary { background-color: #ecf2ff; color: #5d87ff; border: none; transition: all 0.2s; }
    .btn-light-primary:hover { background-color: #5d87ff !important; color: #fff !important; }

    .btn-light-danger { background-color: #fef5f5; color: #fa896b; border: none; transition: all 0.2s; }
    .btn-light-danger:hover { background-color: #fa896b !important; color: #fff !important; }

    .form-control:focus, .form-select:focus {
        border-color: #007bff !important;
        box-shadow: none !important;
        background-color: #fff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    let timer;
    const searchInput = document.querySelector('input[name="search"]');
    if(searchInput) {
        searchInput.addEventListener('keyup', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 700);
        });
    }
</script>
@endpush