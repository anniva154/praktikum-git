@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<div class="auth-card">
    {{-- Alert Status --}}
    @if (session('status'))
        <div id="status-alert" class="alert alert-success alert-dismissible fade show d-flex justify-content-between align-items-center mb-3" role="alert">
            <span>{{ session('status') }}</span>
            <button type="button" class="btn-close" onclick="dismissAlert()" aria-label="Close"></button>
        </div>
    @endif

    <div class="text-center mb-2">
        <img src="{{ asset('assets/img/logo.png') }}" width="60" alt="Logo">
    </div>

    <div class="text-center mb-3">
        <h6 class="fw-bold" style="color: #333;">Silahkan Masuk Akun Anda</h6>
    </div>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control shadow-none" name="email" placeholder="Contoh: user@gmail.com" required>
        </div>

        <div class="mb-3 text-start">
    <label class="form-label fw-bold">Password</label>
    <div style="position: relative;">
        <input type="password" id="loginPassword" class="form-control" name="password" placeholder="Masukkan kata sandi" required style="padding-right: 45px;">
        
        <div id="toggleBtn" onclick="handleToggle()" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; z-index: 999; color: #666; font-size: 1.2rem;">
            <i id="eyeIcon" class="bi bi-eye"></i>
        </div>
    </div>
</div>
        <div class="auth-options d-flex justify-content-between mb-3">
            <div class="form-check text-start">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-bold">Lupa sandi?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-main mb-2">MASUK</button>

        <a href="{{ route('google.login') }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center mb-3" style="border: 1px solid #ddd;">
            <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" width="16" class="me-2">
            <span>Masuk dengan Google</span>
        </a>

        <div class="text-center mt-2">
            <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold">Daftar</a></small>
        </div>
    </form>
</div>
@endsection
<style>
    /* Menghilangkan icon mata bawaan Edge / Internet Explorer */
    input::-ms-reveal,
    input::-ms-clear {
        display: none;
    }

    /* CSS agar icon mata kita posisinya pas di dalam input */
    .input-group-auth {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .btn-toggle-pw {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        z-index: 10;
        cursor: pointer;
        color: #6c757d;
        display: flex;
        align-items: center;
        height: 100%;
    }

    /* Styling alert agar rapi di dalam card */
    #status-alert {
        border-radius: 8px;
        font-size: 0.85rem;
    }
</style>
@push('scripts')
<script>
    function handleToggle() {
        const passwordInput = document.getElementById('loginPassword');
        const eyeIcon = document.getElementById('eyeIcon');
        
        console.log("Tombol diklik!"); // Cek di console browser

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            // Jika pakai Bootstrap Icons
            if(eyeIcon.classList.contains('bi')) {
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                eyeIcon.innerText = '🙈'; // Fallback jika icon tidak muncul
            }
        } else {
            passwordInput.type = 'password';
            if(eyeIcon.classList.contains('bi')) {
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                eyeIcon.innerText = '👁️'; // Fallback
            }
        }
    }

    // Perbaikan untuk Alert yang tidak hilang
    function dismissAlert() {
        const alertBox = document.getElementById('status-alert');
        if (alertBox) {
            alertBox.style.display = 'none';
        }
    }

    // Auto close alert setelah 5 detik
    setTimeout(() => {
        dismissAlert();
    }, 5000);
</script>
@endpush