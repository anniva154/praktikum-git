@extends('layouts.backend')

@section('title', 'Edit Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 20px;">

            {{-- HEADER --}}
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">Edit Pengguna</h5>
                <a href="{{ route('admin.pengguna.index') }}" class="text-dark">
                    <i class="ti ti-arrow-left fs-5"></i>
                </a>
            </div>

            <form action="{{ route('admin.pengguna.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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

                        {{-- NAMA --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control custom-input"
                                   value="{{ old('name', $user->name) }}" required placeholder="Nama sesuai identitas">
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Email</label>
                            <input type="email" name="email" class="form-control custom-input"
                                   value="{{ old('email', $user->email) }}" required placeholder="email@example.com">
                        </div>

                        {{-- NO WHATSAPP --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">No. WhatsApp</label>
                            <input type="number" name="no_wa" class="form-control custom-input" 
                                   value="{{ old('no_wa', $user->no_wa) }}" placeholder="08xxxxxxx">
                        </div>

                        {{-- PASSWORD --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                Password <small class="text-muted fw-normal">(Kosongkan jika tidak diubah)</small>
                            </label>
                            <input type="password" name="password" class="form-control custom-input" placeholder="********">
                        </div>

                        {{-- ROLE UTAMA --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Role / Hak Akses</label>
                            <select name="role" id="roleSelect" class="form-select custom-input" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                <option value="kaproli" {{ old('role', $user->role) == 'kaproli' ? 'selected' : '' }}>Kaproli</option>
                                <option value="pengguna" {{ old('role', $user->role) == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                            </select>
                        </div>

                        {{-- JURUSAN --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Jurusan</label>
                            <select name="id_jurusan" class="form-select custom-input">
                                <option value="">-- Pilih Jurusan --</option>
                                @foreach ($jurusan as $j)
                                    <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $user->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                                        {{ $j->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TIPE PENGGUNA (Dinamis muncul jika role 'pengguna') --}}
                        <div class="col-md-6 {{ old('role', $user->role) == 'pengguna' ? '' : 'd-none' }}" id="tipePenggunaWrapper">
                            <label class="form-label fw-bold text-primary">Tipe Data Pengguna</label>
                            <select name="tipe_pengguna" id="tipe_pengguna" class="form-select custom-input border-primary">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="guru" {{ old('tipe_pengguna', $user->tipe_pengguna) == 'guru' ? 'selected' : '' }}>Guru</option>
                                <option value="siswa" {{ old('tipe_pengguna', $user->tipe_pengguna) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Status Akun</label>
                            <select name="status" class="form-select custom-input" required>
                                <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                        {{-- FOTO --}}
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-dark">Foto Profil</label>
                            <div class="d-flex align-items-start gap-3">
                                @php
                                    $photoPath = $user->foto && file_exists(public_path('uploads/profile/' . $user->foto)) 
                                        ? asset('uploads/profile/' . $user->foto) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff';
                                @endphp
                                <img src="{{ $photoPath }}" class="rounded-3 border shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" id="previewFoto">
                                <div class="flex-grow-1">
                                    <input type="file" name="foto" class="form-control custom-input" accept="image/*" onchange="previewImage(this)">
                                    <small class="text-muted mt-1 d-block">Format: JPG, PNG, JPEG. Maks: 2MB. Biarkan kosong jika tidak ingin mengubah foto.</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 p-4 pt-0">
                    <div class="d-flex justify-content-end align-items-center gap-3">
                        <a href="{{ route('admin.pengguna.index') }}" class="btn btn-action btn-reset">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-action btn-primary shadow-sm">
                            Update
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
    /* Input Styling */
    .custom-input {
        border-radius: 10px !important;
        border: 1.5px solid #eceef1 !important;
        padding: 12px 15px;
        background-color: #ffffff;
        transition: all 0.2s ease-in-out;
    }

    .custom-input:focus {
        border-color: #007bff !important;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1) !important;
    }

    /* Base Button Action */
    .btn-action {
        border-radius: 15px !important; 
        font-weight: 600;
        padding: 12px 35px;
        min-width: 130px;
        border: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    /* Tombol Batal (Merah Muda sesuai Gambar Referensi) */
    .btn-reset { 
        background-color: #fff5f5 !important; 
        color: #ff5c5c !important; 
    }

    .btn-reset:hover { 
        background-color: #ffe0e0 !important; 
        color: #ff3333 !important; 
    }

    /* Tombol Update (Biru sesuai Gambar Referensi) */
    .btn-primary { 
        background-color: #007bff !important; 
        color: #ffffff !important; 
    }

    .btn-primary:hover { 
        background-color: #0069d9 !important; 
        transform: translateY(-1px);
        color: #ffffff !important;
    }
</style>
@endpush

@push('scripts')
<script>
    // Preview Gambar saat upload
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewFoto').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Logic Tipe Pengguna (Guru/Siswa)
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('roleSelect');
        const tipeWrapper = document.getElementById('tipePenggunaWrapper');
        const tipeSelect = document.getElementById('tipe_pengguna');

        function toggleTipe() {
            if (roleSelect.value === 'pengguna') {
                tipeWrapper.classList.remove('d-none');
                tipeSelect.setAttribute('required', 'required');
            } else {
                tipeWrapper.classList.add('d-none');
                tipeSelect.removeAttribute('required');
                tipeSelect.value = "";
            }
        }

        roleSelect.addEventListener('change', toggleTipe);
    });
</script>
@endpush