@extends('layouts.backend')

@section('title', 'Form Peminjaman Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Form Peminjaman Barang</h5>

                <a href="{{ route('pengguna.peminjaman.index') }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('pengguna.peminjaman.store') }}" method="POST">
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

                        {{-- LAB --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Laboratorium</label>
                            <select name="id_lab" id="id_lab" class="form-select custom-input" required>
                                <option value="">-- Pilih Lab --</option>
                                @foreach ($laboratorium as $lab)
                                    <option value="{{ $lab->id_lab }}">
                                        {{ $lab->nama_lab }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Barang</label>
                            <select name="id_barang" id="id_barang" class="form-select custom-input" required disabled>
                                <option value="">-- Pilih Lab terlebih dahulu --</option>
                            </select>
                            <small class="text-muted mt-1 d-block">
                                <i class="ti ti-info-circle"></i> Barang muncul sesuai lab yang dipilih
                            </small>
                        </div>

                        {{-- JUMLAH PINJAM --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Jumlah Pinjam</label>
                            <input type="number" name="jumlah_pinjam" class="form-control custom-input" 
                                   min="1" value="{{ old('jumlah_pinjam', 1) }}" required>
                        </div>

                        {{-- TANGGAL PINJAM --}} 
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Waktu Pinjam</label>
                            <input type="datetime-local" name="waktu_pinjam" class="form-control custom-input" 
                                   value="{{ old('waktu_pinjam') }}" required>
                        </div>

                        {{-- TANGGAL KEMBALI --}} 
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Waktu Kembali (Opsional)</label>
                            <input type="datetime-local" name="waktu_kembali" class="form-control custom-input" 
                                   value="{{ old('waktu_kembali') }}">
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control custom-input"
                                      placeholder="Contoh: Untuk keperluan praktikum mandiri">{{ old('keterangan') }}</textarea>
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
                            Ajukan Peminjaman
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

@push('scripts')
<script>
$(document).ready(function () {
    $('#id_lab').on('change', function () {
        let idLab = $(this).val();
        let barangSelect = $('#id_barang');

        barangSelect.prop('disabled', true)
            .html('<option>Loading...</option>');

        if (!idLab) {
            barangSelect.html('<option>-- Pilih Lab terlebih dahulu --</option>');
            return;
        }

       // Cari baris ini di script Blade kamu:
let url = "{{ route('pengguna.barang.by-lab', ':id') }}" // Sesuaikan namanya di sini
    .replace(':id', idLab);
        $.get(url, function (data) {
            barangSelect.empty();

            if (data.length === 0) {
                barangSelect.append('<option>Barang tidak tersedia</option>');
            } else {
                barangSelect.append('<option value="">-- Pilih Barang --</option>');
                data.forEach(function (item) {
                    barangSelect.append(
                        `<option value="${item.id_barang}">
                            ${item.nama_barang} (Tersedia: ${item.jumlah})
                        </option>`
                    );
                });
            }
            barangSelect.prop('disabled', false);
        });
    });
});
</script>
@endpush