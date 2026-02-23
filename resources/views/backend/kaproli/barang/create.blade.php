@extends('layouts.backend')

@section('title', 'Tambah Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    Tambah Barang - {{ $lab->nama_lab }}
                </h5>
                <a href="{{ route('kaproli.barang.index', $lab->id_lab) }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('kaproli.barang.store', $lab->id_lab) }}" method="POST">
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

                    <div class="row g-4">

                        {{-- NAMA BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Barang</label>
                            <input type="text" 
                                   name="nama_barang" 
                                   class="form-control custom-input"
                                   value="{{ old('nama_barang') }}" 
                                   placeholder="Contoh: Komputer PC"
                                   required>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kode Barang</label>
                            <input type="text" 
                                   name="kode_barang" 
                                   class="form-control custom-input"
                                   value="{{ old('kode_barang') }}" 
                                   placeholder="Contoh: TKJ-LK1-PC01"
                                   required>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Jumlah</label>
                            <input type="number" 
                                   name="jumlah" 
                                   class="form-control custom-input"
                                   min="1"
                                   value="{{ old('jumlah', 1) }}" 
                                   required>
                        </div>

                        {{-- KONDISI --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kondisi</label>
                            <select name="kondisi" class="form-select custom-input" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak ringan" {{ old('kondisi') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak berat" {{ old('kondisi') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select custom-input" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak layak" {{ old('status') == 'tidak layak' ? 'selected' : '' }}>Tidak Layak</option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <button type="reset" class="btn btn-action btn-reset">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-action btn-primary shadow-sm">
                            Simpan Barang
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
        border: 1px solid #dee2e6 !important;
        padding: 10px 15px;
        background-color: #fff;
    }

    .custom-input:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1) !important;
    }

    .btn-reset {
        background-color: #fff5f5;
        color: #ff5c5c;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        padding: 10px 25px;
        transition: 0.3s;
    }

    .btn-reset:hover {
        background-color: #ffe0e0;
        color: #ff3333;
    }

    .btn-primary {
        background-color: #2196f3;
        border: none;
        border-radius: 10px;
        font-weight: 500;
        padding: 10px 25px;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #1976d2;
        transform: translateY(-1px);
    }

    .btn {
        min-width: 100px;
        font-size: 14px;
    }
</style>
@endpush