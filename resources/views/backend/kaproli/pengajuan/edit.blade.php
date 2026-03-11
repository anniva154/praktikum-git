@extends('layouts.backend')

@section('title', 'Edit Pengajuan Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm" style="border-radius: 20px; border: none; overflow: hidden;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    Edit Pengajuan - {{ $lab->nama_lab }}
                </h5>
                <a href="{{ route('kaproli.pengajuan.index', $lab->id_lab) }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            {{-- Perhatikan method spoofing @method('PUT') untuk proses update --}}
            <form action="{{ route('kaproli.pengajuan.update', [$lab->id_lab, $pengajuan->id_pengajuan]) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                            <label class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama_barang" class="form-control custom-input"
                                   value="{{ old('nama_barang', $pengajuan->nama_barang) }}" required>
                        </div>

                       {{-- ESTIMASI HARGA - Edit Version --}}
<div class="col-md-6">
    <label class="form-label fw-bold">Estimasi Harga (Satuan)</label>
    <div class="input-group">
        {{-- Span dengan border-radius hanya di sisi kiri agar menyatu --}}
        <span class="input-group-text bg-light border-1" 
              style="border-radius: 12px 0 0 12px; border-right: none; border: 1.5px solid #eceef1;">
            Rp
        </span>

        {{-- Input dengan border-radius hanya di sisi kanan dan mengambil data lama --}}
        <input type="number" name="estimasi_harga" 
               class="form-control custom-input" 
               style="border-radius: 0 12px 12px 0 !important; border-left: 1.5px solid #eceef1; border: 1.5px solid #eceef1;" 
               placeholder="0"
               value="{{ old('estimasi_harga', number_format($pengajuan->estimasi_harga, 0, '', '')) }}">
    </div>
</div>

                        {{-- JUMLAH --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" class="form-control custom-input"
                                   min="1" value="{{ old('jumlah', $pengajuan->jumlah) }}" required>
                        </div>

                        {{-- SATUAN --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                            <input type="text" name="satuan" class="form-control custom-input"
                                   value="{{ old('satuan', $pengajuan->satuan) }}" placeholder="Contoh: Unit" required>
                        </div>

                        {{-- URGENSI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tingkat Urgensi <span class="text-danger">*</span></label>
                            <select name="urgensi" class="form-select custom-input" required>
                                <option value="Biasa" {{ old('urgensi', $pengajuan->urgensi) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="Penting Sekali" {{ old('urgensi', $pengajuan->urgensi) == 'Penting Sekali' ? 'selected' : '' }}>Penting Sekali</option>
                                <option value="Persediaan" {{ old('urgensi', $pengajuan->urgensi) == 'Persediaan' ? 'selected' : '' }}>Persediaan</option>
                            </select>
                        </div>

                        {{-- SPESIFIKASI --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Spesifikasi Barang</label>
                            <textarea name="spesifikasi" class="form-control custom-input" rows="3">{{ old('spesifikasi', $pengajuan->spesifikasi) }}</textarea>
                        </div>

                        {{-- ALASAN KEBUTUHAN --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Alasan Kebutuhan <span class="text-danger">*</span></label>
                            <textarea name="alasan_kebutuhan" class="form-control custom-input" rows="3" required>{{ old('alasan_kebutuhan', $pengajuan->alasan_kebutuhan) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 p-4">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('kaproli.pengajuan.index', $lab->id_lab) }}" class="btn btn-action btn-reset text-decoration-none d-flex align-items-center justify-content-center">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-action btn-primary shadow-sm">
                            Update Pengajuan
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
