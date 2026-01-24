@extends('layouts.backend')

@section('title', 'Ganti Password')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">

      {{-- HEADER --}}
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Ganti Password</h5>
      </div>

      <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <div class="card-body">

          <small class="text-muted d-block mb-4">
            Demi keamanan akun, masukkan password lama Anda.
          </small>

          <div class="row justify-content-center">
            <div class="col-md-8">

              {{-- PASSWORD LAMA --}}
              <div class="mb-4">
                <label class="form-label fw-semibold">Password Lama</label>

                <div class="input-group">
                  <input type="password" id="password_lama" name="password_lama" class="form-control">

                  <span class="input-group-text bg-white toggle-password"
                        data-target="password_lama"
                        style="cursor:pointer">
                    <i class="bi bi-eye"></i>
                  </span>
                </div>

                <div class="mt-1">
                  <a href="#" class="text-decoration-none">Lupa password?</a>
                </div>
              </div>

              {{-- PASSWORD BARU --}}
              <div class="mb-4">
                <label class="form-label fw-semibold">Password Baru</label>

                <div class="input-group">
                  <input type="password" id="password_baru" name="password_baru" class="form-control">

                  <span class="input-group-text bg-white toggle-password"
                        data-target="password_baru"
                        style="cursor:pointer">
                    <i class="bi bi-eye"></i>
                  </span>
                </div>
              </div>

              {{-- KONFIRMASI PASSWORD --}}
              <div class="mb-4">
                <label class="form-label fw-semibold">Konfirmasi Password</label>

                <div class="input-group">
                  <input type="password" id="password_konfirmasi" name="password_konfirmasi" class="form-control">

                  <span class="input-group-text bg-white toggle-password"
                        data-target="password_konfirmasi"
                        style="cursor:pointer">
                    <i class="bi bi-eye"></i>
                  </span>
                </div>
              </div>

            </div>
          </div>

        </div>

        {{-- FOOTER --}}
        <div class="card-footer bg-white border-top text-end">
          <button type="reset" class="btn btn-outline-secondary">
            Reset
          </button>
          <button type="submit" class="btn btn-primary ms-2">
            Simpan
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- JS TOGGLE PASSWORD --}}
<script>
document.querySelectorAll('.toggle-password').forEach(function (el) {
  el.addEventListener('click', function () {
    const inputId = this.getAttribute('data-target');
    const input   = document.getElementById(inputId);
    const icon    = this.querySelector('i');

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
    }
  });
});
</script>

<style>
/* HILANGKAN ICON MATA BAWAAN BROWSER */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
  display: none;
}

input[type="password"]::-webkit-credentials-auto-fill-button {
  visibility: hidden;
  position: absolute;
  right: 0;
}
</style>
@endsection
