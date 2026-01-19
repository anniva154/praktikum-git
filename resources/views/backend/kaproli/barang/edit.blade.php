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

{{-- ERROR VALIDASI --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Edit Barang - {{ $lab->nama_lab }}
                </h5>

                <a href="{{ $routeBack }}" class="text-dark fs-3 fw-bold">
                    <i class="ti ti-arrow-left"></i>
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ $routeUpdate }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id_lab" value="{{ $lab->id_lab }}">

                <div class="card-body">
                    <div class="row g-3">

                        {{-- NAMA BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang</label>
                            <input type="text"
                                   name="nama_barang"
                                   class="form-control"
                                   value="{{ old('nama_barang', $barang->nama_barang) }}"
                                   required>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Barang</label>
                            <input type="text"
                                   name="kode_barang"
                                   class="form-control"
                                   value="{{ old('kode_barang', $barang->kode_barang) }}"
                                   required>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number"
                                   name="jumlah"
                                   class="form-control"
                                   min="1"
                                   value="{{ old('jumlah', $barang->jumlah) }}"
                                   required>
                        </div>

                        {{-- KONDISI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>

                                @foreach (['Baik', 'Rusak', 'Dipinjam', 'Dalam Perbaikan'] as $kondisi)
                                    <option value="{{ $kondisi }}"
                                        {{ old('kondisi', $barang->kondisi) === $kondisi ? 'selected' : '' }}>
                                        {{ $kondisi }}
                                    </option>
                                @endforeach
                            </select>

                            @error('kondisi')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-end">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
