@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-card" style="max-width: 600px;">
    {{-- Logo --}}
    <div class="text-center mb-2">
        <img src="{{ asset('assets/img/logo.png') }}" width="60" alt="Logo">
    </div>

    <div class="text-center mb-3">
        <h6 class="fw-bold" style="color: #333;">Buat Akun Anda</h6>
    </div>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        
        @if(session('google_id'))
            <input type="hidden" name="google_id" value="{{ session('google_id') }}">
        @endif

        <div class="row">
            <div class="col-md-6 mb-3 text-start">
                <label class="form-label fw-bold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ session('google_name', old('name')) }}" 
                    {{ session('google_name') ? 'readonly' : '' }} required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3 text-start">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ session('google_email', old('email')) }}" 
                    {{ session('google_email') ? 'readonly' : '' }} required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3 text-start">
                <label class="form-label fw-bold">Daftar Sebagai</label>
                <select name="tipe_pengguna" class="form-select @error('tipe_pengguna') is-invalid @enderror" required>
                    <option value="">-- Pilih --</option>
                    <option value="siswa" {{ old('tipe_pengguna') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ old('tipe_pengguna') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
                @error('tipe_pengguna')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6 mb-3 text-start">
                <label class="form-label fw-bold">Jurusan</label>
                <select name="id_jurusan" class="form-select @error('id_jurusan') is-invalid @enderror" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach ($jurusan as $j)
                        <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
                @error('id_jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row">
            {{-- Password --}}
            <div class="col-md-6 mb-3 text-start">
                <label class="form-label fw-bold">Password</label>
                <div style="position: relative;">
                    <input type="password" id="regPassword" name="password" class="form-control @error('password') is-invalid @enderror" required style="padding-right: 45px;">
                    <div onclick="toggleSecret('regPassword', 'eyeIcon1')" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 999; color: #666; font-size: 1.2rem;">
                        <i id="eyeIcon1" class="bi bi-eye"></i>
                    </div>
                </div>
                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="col-md-6 mb-3 text-start">
                <label class="form-label fw-bold">Konfirmasi Password</label>
                <div style="position: relative;">
                    <input type="password" id="confirmPassword" name="password_confirmation" class="form-control" required style="padding-right: 45px;">
                    <div onclick="toggleSecret('confirmPassword', 'eyeIcon')" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 999; color: #666; font-size: 1.2rem;">
                        <i id="eyeIcon" class="bi bi-eye"></i>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-main mt-2">DAFTAR</button>

        <a href="{{ route('google.login') }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center mt-3" style="border: 1px solid #ddd;">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" width="16" class="me-2">
            <span>Daftar dengan Google</span>
        </a>

        <div class="text-center mt-3">
            <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Login</a></small>
        </div>
    </form>
</div>
@endsection

<style>
    /* Hilangkan mata bawaan Edge */
    input::-ms-reveal,
    input::-ms-clear {
        display: none;
    }

    .form-control, .form-select {
        border-radius: 12px !important;
    }
</style>

@push('scripts')
<script>
    function toggleSecret(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
@endpush