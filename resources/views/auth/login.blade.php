@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="auth-overlay">
        <div class="auth-card text-center">

            {{-- Logo --}}
            <img src="{{ asset('assets/img/logo.png') }}" width="90" class="mb-3">

            <h5 class="mb-4 fw-bold">Silahkan Masuk Akun Anda</h5>

            <form action="{{ route('login.post') }}" method="POST">

                @csrf

                {{-- Email --}}
                <div class="mb-3 text-start">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autofocus>

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Password --}}
                <div class="mb-2 text-start">
                    <label class="form-label">Password</label>

                    <div class="input-group">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required>

                    </div>

                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Lupa Password --}}
                <div class="text-start mb-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small">
                            Lupa password?
                        </a>
                    @endif
                </div>

                {{-- Button --}}
                <button type="submit" class="btn btn-primary w-100 mb-2">
                    Login
                </button>


                {{-- Register --}}
                <small>
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar disini</a>
                </small>
                <hr class="my-4">

            </form>
        </div>
    </div>
@endsection
<script>
    function togglePassword(el) {
        const input = el.closest('.input-group').querySelector('input');
        const icon = el.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>