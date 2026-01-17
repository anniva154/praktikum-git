@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-overlay">
    <div class="auth-card text-center">

        <img src="{{ asset('assets/img/logo.png') }}" width="90" class="mb-3">

        <h5 class="mb-4 fw-bold">Buat Akun Anda</h5>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            {{-- Nama --}}
            <div class="mb-3 text-start">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Email --}}
            <div class="mb-3 text-start">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Tipe Pengguna --}}
            <div class="mb-3 text-start">
                <label class="form-label">Daftar Sebagai</label>
                <select name="tipe_pengguna" id="tipe_pengguna"
                        class="form-select @error('tipe_pengguna') is-invalid @enderror"
                        required>
                    <option value="">-- Pilih --</option>
                    <option value="siswa" {{ old('tipe_pengguna') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ old('tipe_pengguna') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
                @error('tipe_pengguna')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

         {{-- Jurusan --}}
<div class="mb-3 text-start">
    <label for="id_jurusan" class="form-label">Jurusan</label>
    <select name="id_jurusan"
            id="id_jurusan"
            class="form-select @error('id_jurusan') is-invalid @enderror"
            required>
        <option value="">-- Pilih Jurusan --</option>
        @foreach ($jurusan as $j)
            <option value="{{ $j->id_jurusan }}"
                {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                {{ $j->nama_jurusan }}
            </option>
        @endforeach
    </select>

    @error('id_jurusan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


            {{-- Password --}}
            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Konfirmasi --}}
            <div class="mb-3 text-start">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>

            <small class="d-block mt-3">
                Sudah punya akun? <a href="{{ route('login') }}">Login</a>
            </small>
        </form>
    </div>
</div>

@endsection
