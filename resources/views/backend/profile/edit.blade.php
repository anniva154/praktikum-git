@extends('layouts.backend')

@section('title', 'Edit Profil')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Profil</h5>
                            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

               <div class="card-body">

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        {{-- KOLOM FOTO --}}
        <div class="col-md-4 text-center border-end">

            <label class="form-label fw-semibold d-block mb-3">
                Foto Profil
            </label>

            <img
                src="{{ $user->foto
                    ? asset('uploads/profile/'.$user->foto)
                    : asset('assets/img/default-user.png') }}"
                class="rounded-circle shadow-sm mb-3"
                width="140"
                height="140"
                style="object-fit: cover;"
            >

            <input type="file"
                   name="foto"
                   class="form-control mt-2">

            <small class="text-muted d-block mt-2">
                Format JPG/PNG • Maks 2MB
            </small>

        </div>

        {{-- KOLOM FORM --}}
        <div class="col-md-8 ps-md-4">

            {{-- NAMA --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Nama
                </label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $user->name) }}">
            </div>

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Email
                </label>
                <input type="email"
                       class="form-control"
                       value="{{ $user->email }}"
                       disabled>
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
@endsection
