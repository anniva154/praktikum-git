@extends('layouts.backend')

@section('title', 'Edit Laboratorium')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Edit Laboratorium</h5>
                <a href="{{ route('admin.lab.index') }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            <form action="{{ route('admin.lab.update', $lab->id_lab) }}" method="POST">
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

                    {{-- NAMA LAB --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Nama Lab</label>
                        <input type="text" 
                               name="nama_lab" 
                               class="form-control custom-input"
                               placeholder="Contoh: Lab RPL 1"
                               value="{{ old('nama_lab', $lab->nama_lab) }}"
                               required>
                    </div>

                    {{-- JURUSAN & STATUS --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Jurusan</label>
                            <select name="id_jurusan" class="form-select custom-input" required>
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
                            <label class="form-label fw-bold text-dark">Status</label>
                            <select name="status" class="form-select custom-input" required>
                                <option value="kosong" {{ old('status', $lab->status) == 'kosong' ? 'selected' : '' }}>Kosong</option>
                                <option value="dipakai" {{ old('status', $lab->status) == 'dipakai' ? 'selected' : '' }}>Dipakai</option>
                                <option value="perbaikan" {{ old('status', $lab->status) == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                            </select>
                        </div>
                    </div>

                    {{-- KETERANGAN --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark">Keterangan</label>
                        <textarea name="keterangan"
                                  class="form-control custom-input"
                                  rows="4"
                                  placeholder="Opsional: Praktikum, ujian, dll">{{ old('keterangan', $lab->keterangan) }}</textarea>
                    </div>

                </div>

                {{-- FOOTER: BUTTONS AT BOTTOM RIGHT --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('admin.lab.index') }}" class="btn btn-action btn-reset text-center">
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
    /* Menyamakan styling dengan halaman Tambah */
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

    /* Action Buttons Base */
    .btn-action {
        border-radius: 10px !important;
        font-weight: 600;
        padding: 10px 30px; 
        min-width: 120px;   
        border: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    /* Reset/Batal Button Style */
    .btn-reset {
        background-color: #fff5f5;
        color: #ff5c5c;
    }

    .btn-reset:hover {
        background-color: #ffe0e0;
        color: #ff3333;
    }

    /* Update Button Style */
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