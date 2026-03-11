@extends('layouts.auth')

@section('content')
<div class="auth-card text-center">
    <img src="{{ asset('assets/img/logo.png') }}" width="60" class="mb-3">
    <h6 class="fw-bold">Atur Ulang Password</h6>

    @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size: 13px;">
            <ul class="mb-0 text-start">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.reset.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-2 text-start">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
        </div>

        <div class="mb-2 text-start">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required autofocus>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">SIMPAN PASSWORD</button>
    </form>
</div>
@endsection