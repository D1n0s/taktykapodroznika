<!doctype html  >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>{{ config('app.name', 'Taktyka Podróżnika') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
      'node_modules/axios/dist/axios.js',

     ])

</head>
<body>
    <div id="app">
        <nav id="menu-bar" data-toggle="collapse"  class="navbar dropdown-menu navbar-expand-md shadow-sm">
            <div class="container menu_bar" style="height:7em;">

            <img id="logo" src={{ asset('storage/logo.png') }}>
                <a class="navbar-brand logo_tekst" style="" href="{{ url('/') }}">
                    {{ config('app.name', 'Taktyka Podróżnika') }}
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

                            <div class="btn-group dropup">
                                <button type="button" class="btn btn-primary" id="notificationButton">NOTIFICATION</button>
                                <div class="dropdown" id="test">
                                    <div class="dropdown-menu"  aria-labelledby="notificationButton" id="notificationDropdown">
                                        @foreach(Auth::user()->invitesReceived as $invite)
                                        <div class="dropdown-item" id="{{$invite->invite_id}}">
                                            <p class="mb-0">Dostałeś zaproszenie od {{$invite->invitedBy->name}}</p>
                                            <p class="mb-0">do podróży {{$invite->invitedTrip->title}}</p>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" onclick="inviteaccept('{{$invite->invite_id}}')" class="btn btn-secondary btn-success mx-2">Akceptuj</button>
                                                <button type="button" onclick="invitedecline('{{$invite->invite_id}}')" class="btn btn-secondary btn-danger">Anuluj</button>
                                            </div>
                                        </div>
                                        @endforeach

                                </div>
                            </div>
                            </div>

                            <script>
                                function inviteaccept(invite_id){

                                    axios.post("{{ route('AcceptInvite') }}", {
                                            'invite_id' : invite_id,
                                    }, {
                                        headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                        }
                                    })
                                        .then(response => {
                                            document.getElementById(response.data.id).innerHTML = '<div class="alert alert-success">' + response.data.success + '</div>';
                                        })
                                        .catch(error => {
                                            console.error(error); // Wyświetlanie błędu w konsoli
                                            if (error.response && error.response.data) {
                                                console.log(error.response.data);
                                            } else if (error.request) {
                                                console.log(error.request);
                                            } else {
                                                console.log('Error', error.message);
                                            }
                                        });

                                }
                                function invitedecline(invite_id){

                                    alert('Czy na pewno chcesz usunąć zaproszenie ? ');
                                    axios.post("{{ route('DeclineInvite') }}", {
                                            'invite_id' : invite_id,
                                    }, {
                                        headers: {
                                            'Content-Type': 'application/json;charset=utf-8',
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                        }
                                    })
                                        .then(response => {
                                            // console.log(response.data);
                                            // console.log(response.data.id);
                                            document.getElementById(response.data.id).innerHTML = '<div class="alert alert-danger">' + response.data.success + '</div>';
                                        })
                                        .catch(error => {
                                            console.error(error); // Wyświetlanie błędu w konsoli
                                            if (error.response && error.response.data) {
                                                console.log(error.response.data);
                                            } else if (error.request) {
                                                console.log(error.request);
                                            } else {
                                                console.log('Error', error.message);
                                            }
                                        });

                                }


                                $(document).ready(function () {
                                    $('#notificationButton').on('click', function (event) {
                                        $('#notificationDropdown').toggle();
                                        event.stopPropagation();
                                    });

                                    // Dodaj event mousedown, aby sprawdzić kliknięcie wewnątrz #notificationDropdown
                                    $('#notificationDropdown').on('mousedown', function (event) {
                                        event.stopPropagation();
                                    });

                                    $(document).on('mousedown', function () {
                                        $('#notificationDropdown').hide();
                                    });
                                });


                            </script>

            <li class="nav-item dropdown-item">
                <a class="nav-link"   href="{{ url('/profile') }}">{{ Auth::user()->name }}</a>
            </li>
                                <!-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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
