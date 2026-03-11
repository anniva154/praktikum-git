@extends('layouts.backend')

@section('title', 'Tambah Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm" style="border-radius: 20px; border: none; overflow: hidden;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    Tambah Barang - {{ $lab->nama_lab }}
                </h5>
                <a href="{{ route('kaproli.barang.index', $lab->id_lab) }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

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
                            <input type="text" name="nama_barang" class="form-control custom-input"
                                   value="{{ old('nama_barang') }}" placeholder="Contoh: Komputer PC" required>
                        </div>

                        {{-- KODE BARANG --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kode Barang</label>
                            <input type="text" name="kode_barang" class="form-control custom-input"
                                   value="{{ old('kode_barang') }}" placeholder="Contoh: TKJ-LK1-PC01" required>
                        </div>

                        {{-- JUMLAH --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Jumlah</label>
                            <input type="number" name="jumlah" class="form-control custom-input"
                                   min="1" value="{{ old('jumlah', 1) }}" required>
                        </div>

                        {{-- KONDISI --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Kondisi</label>
                            <select name="kondisi" id="kondisi" class="form-select custom-input" required>
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                            <small class="text-muted d-block mt-1">Kondisi fisik saat ini</small>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select custom-input" required>
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="diperbaiki" {{ old('status') == 'diperbaiki' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="hilang" {{ old('status') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            <small class="text-muted d-block mt-1">Ketersediaan untuk sirkulasi</small>
                        </div>
                    </div>
                </div> {{-- Akhir Card Body --}}

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent  border-0 p-4">
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
        border-radius: 12px !important;
        border: 1.5px solid #eceef1 !important;
        padding: 12px 15px;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
    }

    .custom-input:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1) !important;
    }

    .btn-action {
        border-radius: 10px !important;
        font-weight: 600;
        padding: 10px 25px;
        min-width: 120px;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-reset {
        background-color: #fff5f5;
        color: #ff5c5c;
        text-align: center;
        line-height: 24px;
        text-decoration: none;
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kondisiSelect = document.getElementById('kondisi');
        const statusSelect = document.getElementById('status');

        function sinkronisasiStatus() {
            const kondisi = kondisiSelect.value;
            const opsiTersedia = statusSelect.querySelector('option[value="tersedia"]');

            if (kondisi === 'rusak') {
                // 1. Paksa status ke 'diperbaiki'
                statusSelect.value = 'diperbaiki';
                // 2. Disable opsi 'Tersedia'
                if (opsiTersedia) opsiTersedia.disabled = true;
            } else {
                // 3. JIKA BAIK: Aktifkan kembali opsi 'Tersedia'
                if (opsiTersedia) opsiTersedia.disabled = false;
                
                // 4. Jika sebelumnya statusnya tertahan di 'diperbaiki', balikkan ke 'tersedia'
                if (statusSelect.value === 'diperbaiki') {
                    statusSelect.value = 'tersedia';
                }
            }
        }

        kondisiSelect.addEventListener('change', sinkronisasiStatus);
        sinkronisasiStatus(); // Jalankan saat load
    });
</script>
@endpush