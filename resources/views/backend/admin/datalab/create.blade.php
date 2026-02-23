@extends('layouts.backend')

@section('title', 'Tambah Laboratorium')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Tambah Laboratorium</h5>
                <a href="{{ route('admin.lab.index') }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            <form action="{{ route('admin.lab.store') }}" method="POST">
                @csrf
                <div class="card-body p-4">

                    {{-- ERROR HANDLING --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- NAMA LAB --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nama Lab</label>
                        <input type="text" 
                               name="nama_lab" 
                               class="form-control custom-input"
                               placeholder="Contoh: Lab RPL 1"
                               value="{{ old('nama_lab') }}"
                               required>
                    </div>

                    {{-- JURUSAN & STATUS --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jurusan</label>
                            <select name="id_jurusan" class="form-select custom-input" required>
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
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select custom-input" required>
                                <option value="kosong">Kosong</option>
                                <option value="dipakai">Dipakai</option>
                                <option value="perbaikan">Perbaikan</option>
                            </select>
                        </div>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="keterangan"
                                  class="form-control custom-input"
                                  rows="4"
                                  placeholder="Opsional: Praktikum, ujian, dll">{{ old('keterangan') }}</textarea>
                    </div>

                </div>

                {{-- FOOTER / ACTION BUTTONS --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <button type="reset" class="btn btn-action btn-reset">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-action btn-primary shadow-sm">
                            Simpan
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
    /* Styling Input agar sesuai gambar (rounded & subtle border) */
    .custom-input {
        border-radius: 10px !important;
        border: 1px solid #dee2e6 !important;
        padding: 10px 15px;
        background-color: #fff;
    }

    .custom-input:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1) !important;
    }

    /* Styling Tombol */
    .btn-reset {
        background-color: #fff5f5; /* Merah sangat muda seperti contoh */
        color: #ff5c5c;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        padding: 10px 25px;
        transition: all 0.3s;
    }

    .btn-reset:hover {
        background-color: #ffe0e0;
        color: #ff3333;
    }

    .btn-primary {
        background-color: #2196f3; /* Biru cerah seperti contoh */
        border: none;
        border-radius: 10px;
        font-weight: 500;
        padding: 10px 25px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        background-color: #1976d2;
        transform: translateY(-1px);
    }

    /* Menyamakan ukuran tombol */
    .btn {
        min-width: 100px;
        font-size: 14px;
    }
</style>
@endpush