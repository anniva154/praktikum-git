<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Dashboard Admin')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ asset('backend/dist/assets/images/favicon.svg') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">

    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/fonts/material.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/dist/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/assets/css/style-preset.css') }}">
</head>

<body data-pc-preset="preset-1" data-pc-theme="light">

    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <div class="pc-wrapper">

        {{-- SIDEBAR --}}
        <x-admin.sidebar />
        <x-kaproli.sidebar />
        <x-pimpinan.sidebar />
        {{-- TOPBAR --}}
        <x-admin.topbar />
        <x-kaproli.topbar />
        <x-pimpinan.topbar />
        {{-- CONTENT --}}
        <div class="pc-container">
            <div class="pc-content">
                @yield('content')
            </div>
        </div>
        <x-footer />
    </div>

    {{-- JS --}}
    <script src="{{ asset('backend/dist/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('backend/dist/assets/js/pcoded.js') }}"></script>

    <script>feather.replace();</script>

</body>

</html>