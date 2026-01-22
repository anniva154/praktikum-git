@extends('layouts.backend')

@section('title', 'Peminjaman Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">
                <h5 class="mb-0">Form Peminjaman Barang</h5>
            </div>

            {{-- FORM --}}
            <form action="{{ route('pengguna.peminjaman.store') }}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="row g-4">

                        {{-- PILIH LAB --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Lab</label>
                            <select name="id_lab" class="form-select" required>
                                <option value="">-- Pilih Lab --</option>
                                @foreach ($laboratorium as $lab)
                                    <option value="{{ $lab->id_lab }}">
                                        {{ $lab->nama_lab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PILIH BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Pilih Barang</label>
                            <select name="id_barang" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barang as $item)
                                    <option value="{{ $item->id_barang }}">
                                        {{ $item->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TANGGAL PINJAM --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Peminjaman</label>
                            <input type="date"
                                   name="tgl_pinjam"
                                   class="form-control"
                                   required>
                        </div>

                        {{-- TANGGAL KEMBALI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Kembali</label>
                            <input type="date"
                                   name="tgl_kembali"
                                   class="form-control">
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jumlah</label>
                            <input type="number"
                                   name="jumlah_pinjam"
                                   class="form-control"
                                   min="1"
                                   required>
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Keterangan</label>
                            <input type="text"
                                   name="keterangan"
                                   class="form-control"
                                   placeholder="Tuliskan keterangan peminjaman">
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-end">
                    <button type="reset" class="btn btn-secondary">
                        Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Ajukan Peminjaman
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
