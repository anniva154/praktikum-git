@extends('layouts.backend')

@section('title', 'Edit Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Edit Pengguna</h5>
                <a href="{{ route('admin.pengguna.index') }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body p-4">

                    {{-- ERROR HANDLING --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-4">

                        {{-- NAMA --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Nama</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control custom-input"
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                        </div>

                        {{-- USERNAME --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Username / Email</label>
                            <input type="text" 
                                   name="email" 
                                   class="form-control custom-input"
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                Password <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                            </label>
                            <input type="password" name="password" class="form-control custom-input">
                        </div>

                        {{-- ROLE --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Role / Hak Akses</label>
                            <select name="role" class="form-select custom-input" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                <option value="kaproli" {{ old('role', $user->role) == 'kaproli' ? 'selected' : '' }}>Kaproli</option>
                                <option value="pengguna" {{ old('role', $user->role) == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                            </select>
                        </div>

                        {{-- JURUSAN --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Jurusan</label>
                            <select name="id_jurusan" class="form-select custom-input">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $user->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Status</label>
                            <select name="status" class="form-select custom-input" required>
                                <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-action btn-reset">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-action btn-primary shadow-sm">
                            Update
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .custom-input {
        border-radius: 10px !important;
        border: 1px solid #e0e6ed !important;
        padding: 12px 15px;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
    }

    .custom-input:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.05) !important;
    }

    .btn-action {
        border-radius: 10px !important;
        font-weight: 600;
        padding: 10px 30px;
        min-width: 120px;
        border: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-reset {
        background-color: #fff5f5;
        color: #ff5c5c;
    }

    .btn-reset:hover {
        background-color: #ffe0e0;
        color: #ff3333;
    }

    .btn-primary {
        background-color: #007bff;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        transform: translateY(-1px);
    }
</style>
@endpush
