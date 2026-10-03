<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Facturación e Inventarios')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="{{ asset('css/tema.css') }}" rel="stylesheet">
</head>
<body>
    @auth @include('layouts.elementos.sidebar') @endauth

    <div class="@auth main-wrap @endauth">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                @auth
                <button class="btn btn-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>
                @endauth
                <a href="{{ route('inicio') }}" class="fw-bold text-decoration-none text-dark">@yield('title', 'Inicio')</a>
            </div>
            <div class="d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Ingresar</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registrarse</a>
                @else
                    <span class="d-none d-sm-inline text-muted">{{ Auth::user()->name }}</span>
                    <span class="avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="btn btn-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</button>
                    </form>
                @endguest
            </div>
        </header>

        <main class="py-4">
            <div class="container">
                @if(session('status'))<div class="alert alert-success shadow-sm"><i class="bi bi-check-circle"></i> {{ session('status') }}</div>@endif
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm"><ul class="mb-0">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul></div>
                @endif
            </div>
            @yield('content')
        </main>
        <div class="footer">IESTP "Pedro P. Díaz" · Desarrollo Web Integrado · Proyecto Aplicativo Integrador</div>
    </div>
    @stack('scripts')
</body>
</html>
