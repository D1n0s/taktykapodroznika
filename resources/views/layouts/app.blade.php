<!doctype html data-bs-theme="dark" >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <img >

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    @yield('styles')


    <!-- Scripts -->
    @vite([
    'resources/sass/app.scss',
     'resources/js/app.js',
     'public/css/layout.css',
     'public/css/map_creator.css',
     'public/leaflet/dist/leaflet.js',
     'public/leaflet-routing-machine/dist/leaflet-routing-machine.js',
      'public/leaflet-control-geocoder/dist/Control.Geocoder.js',


     ])

</head>
<body>
    <div id="app">
        <nav id="menu-bar" data-toggle="collapse"  class="navbar dropdown-menu navbar-expand-md shadow-sm">
            <div class="container menu_bar" style="height:7em;">

            <img id="logo" src={{ asset('storage/logo.png') }}>
                <a class="navbar-brand logo_tekst" style="" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse navbar " id="navbarSupportedContent" >
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav nav-item me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto bg-white" >
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item dropdown-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item dropdown-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
        <div class="d-inline" style="float:left;position:relative;">
                <a class=" nav-item dropdown-item" href="{{ url('/profile') }}">
                    {{ Auth::user()->login }}
                </a>
                            <li class="">
                                <!-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a> -->

                                <!-- tutaj są logouty -->

                            <div class="nav-link dropdown ms-auto" aria-labelledby="navbarDropdown">

                                    <a class="dropdown-item " href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        </div>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>



    </div>

    @yield('scripts')
 <!-- <div style="height:100em;background-color: red;"> -->
</body>
</html>
