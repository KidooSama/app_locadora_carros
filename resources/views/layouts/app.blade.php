<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Locadora de Veículos</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts & Icons -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-dark app-navbar">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                        <span class="app-brand-icon"><i class="bi bi-car-front-fill"></i></span>
                        <span>Locadora</span>
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            @auth
                                <li class="nav-item">
                                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                        <i class="bi bi-grid-1x2-fill nav-icon"></i> Painel
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('clientes') }}" class="nav-link {{ request()->routeIs('clientes') ? 'active' : '' }}">
                                        <i class="bi bi-people-fill nav-icon"></i> Clientes
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('locacoes') }}" class="nav-link {{ request()->routeIs('locacoes') ? 'active' : '' }}">
                                        <i class="bi bi-calendar2-check-fill nav-icon"></i> Locações
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('carros', 'marcas', 'modelos') ? 'active' : '' }}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="bi bi-truck nav-icon"></i> Veículos
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('carros') }}" class="dropdown-item {{ request()->routeIs('carros') ? 'active' : '' }}">
                                            <i class="bi bi-car-front dropdown-icon"></i> Carros
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('marcas') }}" class="dropdown-item {{ request()->routeIs('marcas') ? 'active' : '' }}">
                                            <i class="bi bi-award dropdown-icon"></i> Marcas
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('modelos') }}" class="dropdown-item {{ request()->routeIs('modelos') ? 'active' : '' }}">
                                            <i class="bi bi-tags dropdown-icon"></i> Modelos
                                        </a>
                                    </div>
                                </li>
                            @endauth
                        </ul>

                        <ul class="navbar-nav ml-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">
                                            <i class="bi bi-box-arrow-in-right nav-icon"></i> {{ __('Login') }}
                                        </a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="bi bi-person-circle nav-icon mr-1"></i> {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right dropdown-icon"></i> {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            @auth
                @php
                    $routeLabels = [
                        'home' => 'Painel',
                        'clientes' => 'Clientes',
                        'locacoes' => 'Locações',
                        'carros' => 'Carros',
                        'marcas' => 'Marcas',
                        'modelos' => 'Modelos',
                    ];
                    $currentRoute = request()->route()->getName();
                    $pageTitle = $routeLabels[$currentRoute] ?? ucfirst($currentRoute);
                @endphp
                @if ($currentRoute !== 'home')
                    <div class="app-page-header">
                        <div class="container">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-grid-1x2"></i> Painel</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                                </ol>
                            </nav>
                            <h1 class="page-title">{{ $pageTitle }}</h1>
                        </div>
                    </div>
                @endif
            @endauth

            <main class="app-main">
                @yield('content')
            </main>
        </div>
    </body>
</html>
