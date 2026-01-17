@extends('layouts.backend')

@section('title', 'Tambah Laboratorium')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Laboratorium</h5>
                <a href="{{ route('admin.lab.index') }}" class="text-dark fs-3">
                    <i class="ti ti-arrow-left"></i>
                </a>
            </div>

            <form action="{{ route('admin.lab.store') }}" method="POST">
                @csrf

                <div class="card-body">

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

                    {{-- NAMA LAB --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nama Lab</label>
                        <input type="text" 
                               name="nama_lab" 
                               class="form-control"
                               placeholder="Contoh: Lab RPL 1"
                               value="{{ old('nama_lab') }}"
                               required>
                    </div>

                    {{-- JURUSAN & STATUS --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jurusan</label>
                            <select name="id_jurusan" class="form-select" required>
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}"
                                        {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="kosong">Kosong</option>
                                <option value="dipakai">Dipakai</option>
                                <option value="perbaikan">Perbaikan</option>
                            </select>
                        </div>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Opsional: Praktikum, ujian, dll">{{ old('keterangan') }}</textarea>
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
