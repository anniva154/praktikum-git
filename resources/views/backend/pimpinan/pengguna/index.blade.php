@extends('layouts.backend')

@section('title', 'Data Pengguna')

@section('content')
    <div class="row">
        <div class="col-12">

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
                                
                                <p class="text-muted mb-0 small">Ringkasan informasi data pengguna</p>
                            </div>
                        </div>

                        <a href="{{ route('pimpinan.export.pengguna', 'pengguna') }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-download me-1"></i>
                            Unduh
                        </a>

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
                                    <i
                                        class="ti ti-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control border-0 shadow-none" placeholder="Cari nama atau email..."
                                        style="border-radius: 12px; height: 42px; font-size: 14px; background-color: #f8f9fa; padding-left: 45px;">
                                </div>
                            </div>

                            <div class="col-5 col-md-2">
                                <select name="role" class="form-select border-0 shadow-none"
                                    style="border-radius: 12px; height: 42px; font-size: 14px; background-color: #f8f9fa;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan
                                    </option>
                                    <option value="kaproli" {{ request('role') == 'kaproli' ? 'selected' : '' }}>Kaproli
                                    </option>
                                    <option value="pengguna" {{ request('role') == 'pengguna' ? 'selected' : '' }}>Pengguna
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    {{-- DESKTOP VIEW: TABLE --}}
                    <div class="table-responsive d-none d-md-block">
                        <table class="table align-middle table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center ps-3" style="width: 60px;">No.</th>
                                    <th>Pengguna</th>
                                    <th class="text-center" style="width: 150px;">Role</th>
                                    <th class="text-center" style="width: 180px;">No. WhatsApp</th>
                                    <th class="text-center" style="width: 150px;">Jurusan</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    @php
                                        $badgeRole = match ($user->role) {
                                            'admin' => 'bg-purple-subtle text-purple',
                                            'pimpinan' => 'bg-primary-subtle text-primary',
                                            'kaproli' => 'bg-warning-subtle text-warning',
                                            'pengguna' => 'bg-info-subtle text-info',
                                            default => 'bg-light text-dark',
                                        };
                                        $roleLabel = $user->role === 'pengguna' ? 'Pengguna (' . ucfirst($user->tipe_pengguna) . ')' : ucfirst($user->role);

                                        $photoPath = $user->foto && file_exists(public_path('uploads/profile/' . $user->foto))
                                            ? asset('uploads/profile/' . $user->foto)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff';
                                    @endphp
                                    <tr>
                                        <td class="text-center ps-3 text-muted small">
                                            {{ $users->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $photoPath }}"
                                                    class="rounded-circle me-3 border border-2 border-white shadow-sm"
                                                    style="width: 40px; height: 40px; object-fit: cover;" alt="avatar">
                                                <div>
                                                    <div class="fw-bold text-dark mb-0" style="line-height: 1.2;">
                                                        {{ $user->name }}</div>
                                                    <small class="text-muted"
                                                        style="font-size: 11px;">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ $badgeRole }} px-3">{{ $roleLabel }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($user->no_wa)
                                                <a href="https://wa.me/{{ $user->no_wa }}" target="_blank"
                                                    class="text-success text-decoration-none small fw-bold d-flex align-items-center justify-content-center">
                                                    <i class="ti ti-brand-whatsapp fs-5 me-1"></i>{{ $user->no_wa }}
                                                </a>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ $user->jurusan?->nama_jurusan ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill {{ $user->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted small">Data pengguna tidak ditemukan
                                        </td>
                                    </tr>
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
                                        <img src="{{ $photoPath }}" class="rounded-circle me-3 shadow-sm"
                                            style="width: 45px; height: 45px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $user->name }}</h6>
                                            <small class="text-muted small">{{ $user->email }}</small>
                                        </div>
                                        <span
                                            class="badge rounded-pill {{ $user->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}"
                                            style="font-size: 10px;">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </div>
                                    <div class="text-muted small mb-3 bg-light p-2 rounded">
                                        <div class="mb-1"><i class="ti ti-user-circle me-1"></i> {{ ucfirst($user->role) }}
                                        </div>
                                        <div class="mb-1"><i class="ti ti-school me-1"></i>
                                            {{ $user->jurusan?->nama_jurusan ?? 'Semua Jurusan' }}</div>
                                        <div><i class="ti ti-brand-whatsapp me-1 text-success"></i> {{ $user->no_wa ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">Data tidak ditemukan</div>
                        @endforelse
                    </div>
                    {{-- PAGINATION --}}
                    <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <p class="text-muted small mb-0">Menampilkan {{ $users->count() }} data dari {{ $users->total() }}
                        </p>
                        <div class="pagination-sm">{{ $users->withQueryString()->links() }}</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        
        .table thead th {
            background-color: #fbfbfb;
            border-bottom: 2px solid #ebeef2;
            /* Garis bawah header lebih tebal */
            padding: 15px 10px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            color: #7c8fac;
            vertical-align: middle;
            /* Memaksa teks ke tengah */
            text-align: center;
        }

        .table thead th.text-start-important {
            text-align: left !important;
        }

        .table tbody tr {
            border-bottom: 1px solid #f1f1f1;
            /* Garis horizontal antar baris */
            transition: all 0.2s ease;
        }

        .table tbody td {
            padding: 16px 10px;
            vertical-align: middle;
            border: none;
            /* Kita gunakan border di TR saja agar lebih clean */
        }

     
        .badge {
            font-weight: 600 !important;
            padding: 5px 12px !important;
            border-radius: 8px !important;
            font-size: 11px;
        }

        .bg-purple-subtle {
            background-color: #f3e8ff !important;
            color: #7e22ce !important;
        }

        .bg-success-subtle {
            background-color: #e6f9f1 !important;
            color: #1f9462 !important;
        }

        .bg-primary-subtle {
            background-color: #ecf2ff !important;
            color: #5d87ff !important;
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

        .btn-light-primary {
            background-color: #ecf2ff;
            color: #5d87ff;
            border: none;
            transition: all 0.2s;
        }

        .btn-light-primary:hover {
            background-color: #5d87ff !important;
            color: #fff !important;
        }

        .btn-light-danger {
            background-color: #fef5f5;
            color: #fa896b;
            border: none;
            transition: all 0.2s;
        }

        .btn-light-danger:hover {
            background-color: #fa896b !important;
            color: #fff !important;
        }

        .form-control:focus,
        .form-select:focus {
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
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 700);
            });
        }
    </script>
@endpush