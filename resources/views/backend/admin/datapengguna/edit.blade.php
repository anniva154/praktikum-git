@extends('layouts.backend')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <!-- HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pengguna</h5>
                    <a href="{{ route('admin.pengguna.index') }}" class="text-dark fs-3 fw-bold">
                        <i class="ti ti-arrow-left"></i>
                    </a>
                </div>

                <!-- FORM -->
                <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row g-3">

                            <!-- NAMA -->
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                    required>
                            </div>

                            <!-- USERNAME / EMAIL -->
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <!-- PASSWORD -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Password
                                    <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                                </label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <!-- ROLE -->
                            <div class="col-md-6">
                                <label class="form-label">Role / Hak Akses</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>
                                        Pimpinan</option>
                                    <option value="kaproli" {{ old('role', $user->role) == 'kaproli' ? 'selected' : '' }}>
                                        Kaproli</option>
                                    <option value="pengguna" {{ old('role', $user->role) == 'pengguna' ? 'selected' : '' }}>
                                        Pengguna</option>
                                </select>
                            </div>

                            <!-- JURUSAN -->
                            <div class="col-md-6">
                                <label class="form-label">Jurusan</label>
                                <select name="id_jurusan" class="form-select">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($jurusan as $j)
                                        <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $user->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                                            {{ $j->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <!-- STATUS -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif
                                    </option>
                                    <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="card-footer text-end">
                        <button type="reset" class="btn btn-secondary">
                            Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection