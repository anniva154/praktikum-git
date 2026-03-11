@extends('layouts.backend')

@section('title', 'Buat Pengajuan')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card shadow-sm" style="border-radius: 20px; border: none; overflow: hidden;">

        {{-- Header Form --}}
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold text-dark">Buat Pengajuan - {{ $lab->nama_lab }}</h5>
          <a href="{{ route('kaproli.pengajuan.index', $lab->id_lab) }}" class="text-dark">
            <i class="ti ti-arrow-left fs-5"></i>
          </a>
        </div>

        <form action="{{ route('kaproli.pengajuan.store', $lab->id_lab) }}" method="POST">
          @csrf
          <div class="card-body p-4">
            <div class="row g-4">

              {{-- Nama Barang --}}
              <div class="col-md-6">
                <label class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                <input type="text" name="nama_barang" class="form-control custom-input"
                  placeholder="Contoh: Proyektor Epson" required>
              </div>

              <div class="col-md-6">
                <label class="form-label fw-bold">Estimasi Harga (Satuan)</label>
                <div class="input-group">
                  {{-- Span dengan class khusus untuk memastikan tidak ada border radius di kanan --}}
                  <span class="input-group-text bg-light border-1"
                    style="border-radius: 12px 0 0 12px; border-right: none;">Rp</span>

                  {{-- Input dengan class khusus untuk memastikan tidak ada border radius di kiri --}}
                  <input type="number" name="estimasi_harga" class="form-control custom-input"
                    style="border-radius: 0 12px 12px 0 !important; border-left: 1.5px solid #eceef1;" placeholder="0">
                </div>
              </div>

              {{-- Jumlah & Satuan --}}
              <div class="col-md-3">
                <label class="form-label fw-bold">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="jumlah" class="form-control custom-input" min="1" value="1" required>
              </div>

              {{-- SATUAN --}}
              <div class="col-md-3">
                <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                <input type="text" name="satuan" class="form-control custom-input" value="{{ old('satuan') }}"
                  placeholder="Contoh: Unit, Pcs, Box" required>
              </div>

              {{-- Urgensi --}}
              <div class="col-md-6">
                <label class="form-label fw-bold">Urgensi <span class="text-danger">*</span></label>
                <select name="urgensi" class="form-select custom-input" required>
                  <option value="Biasa">Biasa</option>
                  <option value="Penting Sekali">Penting Sekali</option>
                  <option value="Persediaan">Persediaan</option>
                </select>
              </div>

              {{-- Spesifikasi --}}
              <div class="col-md-12">
                <label class="form-label fw-bold">Spesifikasi</label>
                <textarea name="spesifikasi" class="form-control custom-input" rows="2"></textarea>
              </div>

              {{-- Alasan --}}
              <div class="col-md-12">
                <label class="form-label fw-bold">Alasan Kebutuhan <span class="text-danger">*</span></label>
                <textarea name="alasan_kebutuhan" class="form-control custom-input" rows="2" required></textarea>
              </div>

            </div>
          </div>

          {{-- FOOTER - Disesuaikan dengan styling btn-action --}}
          <div class="card-footer bg-transparent border-0 p-4">
            <div class="d-flex justify-content-end align-items-center gap-3">
              <button type="reset" class="btn btn-action btn-reset">
                Batal
              </button>
              <button type="submit" class="btn btn-action btn-primary shadow-sm">
                Kirim Pengajuan
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

