<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

   <style>

body {
    font-family: 'Montserrat', sans-serif;
    background: url('{{ asset('assets/img/a.png') }}') no-repeat center center / cover;
    min-height: 100vh;
    margin: 0;
}

.overlay {
    background: rgba(0, 0, 0, 0.45);
    min-height: 100vh;

    display: flex;
    justify-content: center;      
    
    padding: 80px 20px;           
    box-sizing: border-box;
}

.auth-card {
    background: rgba(255, 255, 255, 0.55); /* putih tetap putih */

    backdrop-filter: blur(0px);
   

    border-radius: 14px;
    padding: 36px;

    max-width: 800px;
    width: 100%;

    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25);
}


.auth-card input {
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 10px;
    padding: 12px 16px;
}

.auth-card input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 174, 214, 0.35);
}

.auth-card .btn {
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
}
</style>

</head>
<body>

<div class="overlay">
    @yield('content')
</div>

</body>
</html>
