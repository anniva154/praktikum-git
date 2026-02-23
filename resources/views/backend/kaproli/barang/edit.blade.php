@extends('layouts.backend')

@section('title', 'Edit Barang')

@section('content')

@php
    $isAdmin = auth()->user()->role === 'admin';

    $routeUpdate = $isAdmin
        ? route('admin.barang.update', [$lab->id_lab, $barang->id_barang])
        : route('kaproli.barang.update', [$lab->id_lab, $barang->id_barang]);

    $routeBack = $isAdmin
        ? route('admin.barang.lab', $lab->id_lab)
        : route('kaproli.barang.index', $lab->id_lab);
@endphp

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    Edit Barang - {{ $lab->nama_lab }}
                </h5>
                <a href="{{ $routeBack }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ $routeUpdate }}" method="POST">
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

                        {{-- NAMA BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Nama Barang</label>
                            <input type="text" 
                                   name="nama_barang" 
                                   class="form-control custom-input"
                                   value="{{ old('nama_barang', $barang->nama_barang) }}" 
                                   required>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Kode Barang</label>
                            <input type="text" 
                                   name="kode_barang" 
                                   class="form-control custom-input"
                                   value="{{ old('kode_barang', $barang->kode_barang) }}" 
                                   required>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Jumlah</label>
                            <input type="number" 
                                   name="jumlah" 
                                   class="form-control custom-input"
                                   min="1"
                                   value="{{ old('jumlah', $barang->jumlah) }}" 
                                   required>
                        </div>

                        {{-- KONDISI --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Kondisi</label>
                            <select name="kondisi" class="form-select custom-input" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="baik" {{ old('kondisi', $barang->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak ringan" {{ old('kondisi', $barang->kondisi) == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="rusak berat" {{ old('kondisi', $barang->kondisi) == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark">Status</label>
                            <select name="status" class="form-select custom-input" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="aktif" {{ old('status', $barang->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak layak" {{ old('status', $barang->status) == 'tidak layak' ? 'selected' : '' }}>Tidak Layak</option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ $routeBack }}" class="btn btn-action btn-reset">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-action btn-primary shadow-sm">
                            Update Barang
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