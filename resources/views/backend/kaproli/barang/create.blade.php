@extends('layouts.backend')

@section('title', 'Tambah Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Tambah Barang - {{ $lab->nama_lab }}
                </h5>

                <a href="{{ route('kaproli.barang.index', $lab->id_lab) }}"
                   class="text-dark fs-3 fw-bold">
                    <i class="ti ti-arrow-left"></i>
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('kaproli.barang.store', $lab->id_lab) }}" method="POST">
                @csrf

                {{-- id lab dari URL --}}
                <input type="hidden" name="id_lab" value="{{ $lab->id_lab }}">

                <div class="card-body">
                    <div class="row g-3">

                        {{-- NAMA BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Barang</label>
                            <input type="text"
                                   name="nama_barang"
                                   class="form-control"
                                   placeholder="Contoh: Komputer PC"
                                   required>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Barang</label>
                            <input type="text"
                                   name="kode_barang"
                                   class="form-control"
                                   placeholder="Contoh: LAB-001"
                                   required>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number"
                                   name="jumlah"
                                   class="form-control"
                                   min="1"
                                   required>
                        </div>

                        {{-- KONDISI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Baik">Baik</option>
                                <option value="Dipinjam">Dipinjam</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                            </select>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-end">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
