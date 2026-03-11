@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-card">
    {{-- Logo --}}
    <div class="text-center mb-2">
        <img src="{{ asset('assets/img/logo.png') }}" width="60" alt="Logo">
    </div>

    <div class="text-center mb-3">
        <h6 class="fw-bold" style="color: #333;">Lupa Kata Sandi?</h6>
        <p class="text-muted small">Masukan email anda untuk menerima link reset password.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small border-0 mb-3" style="background-color: #e6fffa; color: #00b19d;">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3 text-start">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Contoh: user@gmail.com" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-main">KIRIM LINK RESET</button>

        <div class="text-center mt-3">
            <small class="text-muted">Kembali ke <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">Login</a></small>
        </div>
    </form>
</div>
@endsection