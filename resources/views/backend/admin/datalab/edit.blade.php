@extends('layouts.backend')

@section('title', 'Edit Laboratorium')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Laboratorium</h5>
                <a href="{{ route('admin.lab.index') }}" class="text-dark fs-3">
                    <i class="ti ti-arrow-left"></i>
                </a>
            </div>

            <form action="{{ route('admin.lab.update', $lab->id_lab) }}" method="POST">
                @csrf
                @method('PUT')

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
                               value="{{ old('nama_lab', $lab->nama_lab) }}"
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
                                        {{ old('id_jurusan', $lab->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="kosong"
                                    {{ old('status', $lab->status) == 'kosong' ? 'selected' : '' }}>
                                    Kosong
                                </option>
                                <option value="dipakai"
                                    {{ old('status', $lab->status) == 'dipakai' ? 'selected' : '' }}>
                                    Dipakai
                                </option>
                                <option value="perbaikan"
                                    {{ old('status', $lab->status) == 'perbaikan' ? 'selected' : '' }}>
                                    Perbaikan
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Opsional: Praktikum, ujian, dll">{{ old('keterangan', $lab->keterangan) }}</textarea>
                    </div>

                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('admin.lab.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
