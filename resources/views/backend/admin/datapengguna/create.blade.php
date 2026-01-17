@extends('layouts.backend')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Tambah Pengguna</h5>
                    <a href="{{ route('admin.pengguna.index') }}" class="text-dark me-3 fs-3 fw-bold">
                        <i class="ti ti-arrow-left"></i>
                    </a>

                </div>

                <form action="{{ route('admin.pengguna.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Username</label>
                                <input type="text" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Role / Hak Akses</label>
                                <select name="role" class="form-select" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin">Admin</option>
                                    <option value="pimpinan">Pimpinan</option>
                                    <option value="kaproli">Kaproli</option>
                                    <option value="pengguna">Pengguna</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jurusan</label>
                                <select name="id_jurusan" class="form-select">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($jurusan as $j)
                                        <option value="{{ $j->id_jurusan }}">
                                            {{ $j->nama_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection