@extends('layouts.backend')

@section('title', 'Laporan Kerusakan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Form Laporan Kerusakan</h5>
                <a href="{{ route('pengguna.laporan.index') }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            {{-- FORM --}}
            <form action="{{ route('pengguna.laporan.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data">
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
                                <i class="ti ti-info-circle"></i> Semua barang (baik/rusak) dapat dilaporkan
                            </small>
                        </div>

                        {{-- TANGGAL --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Laporan</label>
                            <input type="date" name="tgl_laporan" class="form-control custom-input" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Foto Kerusakan <small class="text-muted fw-normal">(Opsional)</small>
                            </label>
                            <input type="file" name="foto" class="form-control custom-input" accept="image/*">
                        </div>

                        {{-- KETERANGAN --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Keterangan Kerusakan</label>
                            <textarea name="keterangan" rows="4" class="form-control custom-input" 
                                      placeholder="Jelaskan detail kerusakan barang tersebut..." required>{{ old('keterangan') }}</textarea>
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
                            Kirim Laporan
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
        font-size: 14px;
    }

    .custom-input:focus {
        border-color: #2196f3 !important;
        box-shadow: 0 0 0 0.25rem rgba(33, 150, 243, 0.1) !important;
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
        min-width: 120px;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    $('#id_lab').on('change', function () {
        let labId = $(this).val();
        let barangSelect = $('#id_barang');

        barangSelect.prop('disabled', true).html('<option>Loading...</option>');

        if (!labId) {
            barangSelect.html('<option value="">-- Pilih Lab terlebih dahulu --</option>');
            return;
        }

        // Pastikan route ini sesuai dengan route get-barang Anda
        let url = "{{ route('pengguna.laporan.get-barang', ['lab' => ':id']) }}";
        url = url.replace(':id', labId);

        $.get(url, function (data) {
            barangSelect.empty();

            if (data.length === 0) {
                barangSelect.append('<option value="">Tidak ada barang di lab ini</option>');
            } else {
                barangSelect.append('<option value="">-- Pilih Barang --</option>');
                data.forEach(function (item) {
                    barangSelect.append(
                        `<option value="${item.id_barang}">
                            ${item.nama_barang} (${item.kondisi} | ${item.status})
                        </option>`
                    );
                });
                barangSelect.prop('disabled', false);
            }
        }).fail(function() {
            barangSelect.html('<option value="">Gagal memuat data</option>');
        });
    });
});
</script>
@endpush