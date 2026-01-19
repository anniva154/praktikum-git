<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Dashboard Admin')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF (WAJIB untuk ajax / form js) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('backend/dist/assets/images/favicon.svg') }}">

    {{-- GOOGLE FONT --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">

    {{-- ICONS --}}
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/material.css') }}">

    {{-- MAIN STYLE --}}
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/css/style-preset.css') }}">

    {{-- BOOTSTRAP ICON --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- STYLE TAMBAHAN PER HALAMAN --}}
    @stack('styles')
</head>

<body data-pc-preset="preset-1" data-pc-theme="light">

    {{-- LOADER --}}
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="pc-wrapper">

        {{-- SIDEBAR --}}
        @auth
            @if(auth()->user()->role === 'admin')
                <x-admin.sidebar />
            @elseif(auth()->user()->role === 'kaproli')
                <x-kaproli.sidebar />
            @elseif(auth()->user()->role === 'pimpinan')
                <x-pimpinan.sidebar />
            @endif
        @endauth

        {{-- TOPBAR --}}
        @auth
            @if(auth()->user()->role === 'admin')
                <x-admin.topbar />
            @elseif(auth()->user()->role === 'kaproli')
                <x-kaproli.topbar />
            @elseif(auth()->user()->role === 'pimpinan')
                <x-pimpinan.topbar />
            @endif
        @endauth

        {{-- CONTENT --}}
        <div class="pc-container">
            <div class="pc-content">
                @yield('content')
            </div>
        </div>

        {{-- FOOTER --}}
        <x-footer />

    </div>

    {{-- CORE JS --}}
    <script src="{{ asset('backend/dist/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/pcoded.js') }}"></script>

    {{-- CHART JS (GLOBAL) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- SCRIPT TAMBAHAN PER HALAMAN --}}
    @stack('scripts')

    {{-- ICON INIT --}}
    <script>
        feather.replace();
    </script>

</body>
</html>
