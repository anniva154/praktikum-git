<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

     <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: url('{{ asset('assets/img/a.png') }}') no-repeat center center / cover;
            margin: 0;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.5);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.45) !important;
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-google {
            background-color: #ffffff !important;
            border: 1px solid #ddd;
            border-radius: 12px;
            color: #333;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 10px;
            margin-top: 15px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-google:active {
            background-color: #f0f0f0 !important;
            transform: translateY(2px);
            border-color: #999;
        }

        .form-label {
            margin-bottom: 4px;
            font-size: 0.85rem;
            color: #444;
            font-weight: 500;
        }

        .auth-card .form-control {
            background: rgba(255, 255, 255, 0.9) !important;
            border: none;
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .auth-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
        }

        .form-check {
            padding-left: 0;
            display: flex;
            align-items: center;
            margin: 0;
        }

        .form-check-input {
            margin: 0 8px 0 0 !important;
            float: none;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: #444;
            cursor: pointer;
        }

        .btn-main {
            background: #0d6efd;
            border: none;
            border-radius: 12px;
            padding: 10px;
            font-weight: 700;
            font-size: 0.9rem;
        }


       .btn-google:hover {
    background-color: #f8f9fa; /* Abu-abu sangat muda */
    border-color: #ccc;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Efek melayang */
    color: #222;
}
    </style>
</head>
<body>
    <div class="overlay">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
 