@extends('layouts.app')
@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/map_creator.css')}}" rel="stylesheet">
    <!-- Stylizacja Leaflet -->
    <link rel="stylesheet" href="{{ asset('leaflet/dist/leaflet.css') }}"/>
    <!-- Stylizacja Leaflet Control Geocoder -->
    <link rel="stylesheet" href="{{ asset('leaflet-control-geocoder/dist/Control.Geocoder.css') }}"/>
    <!-- Stylizacja Leaflet Routing Machine -->
    <link rel="stylesheet" href="{{ asset('leaflet-routing-machine/dist/leaflet-routing-machine.css') }}"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@endsection
@section('scripts')
    @parent
    <!-- Skrypt Leaflet -->
    <script src="{{ asset('leaflet/dist/leaflet.js') }}"></script>
    {{--        <!-- Skrypt Leaflet Control Geocoder -->--}}
    <script src="{{ asset('leaflet-control-geocoder/dist/Control.Geocoder.js') }}"></script>
    {{--        <!-- Skrypt Leaflet Routing Machine -->--}}
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <!-- MAPA -->
    <script>
        var map = L.map('map', {
            zoomControl: false // Wyłącz domyślną kontrolę przybliżania
        }).setView([52.237049, 21.017532], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            zoomControl: false,
        }).addTo(map);

        // MARKERY Z BAZY DANYCH
        var markersData = {!! json_encode($markerData) !!}; // Konwertuje dane PHP na dane JavaScript
        var waypoints = [];

        markersData.forEach(function (marker) {
            if(marker.queue != null){
                var waypoint = L.latLng(marker.latitude, marker.longitude);
                waypoints.push(waypoint);
            }
        });
        var routingControl =  L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: false,
            addWaypointsMode: 'Disabled',
            show: false,

        }).addTo(map);

        for(i=0; i < waypoints.length + 1;i++){
            console.log(waypoints[i] + " POZYCJA " + i );
        }

        function addWaypoint(mark) {
            var latLng = L.latLng(mark.latitude, mark.longitude);
            waypoints.push(latLng);
            routingControl.setWaypoints(waypoints);
        }

        function delWaypoint(mark) {
            var indexToRemove = -1;
            for (var i = 0; i < waypoints.length; i++) {
                var waypoint = waypoints[i];

                if (waypoints[i]['lat'] == mark.latitude && waypoints[i]['lng'] == mark.longitude) {
                    indexToRemove = i;
                    break;
                }
            }
            if (indexToRemove !== -1) {
                waypoints.splice(indexToRemove, 1); // Usuń waypoint z tablicy
                routingControl.setWaypoints(waypoints); // Zaktualizuj trasę
                console.info('AKTUALIZACJA TRASY <3 ');
            }
        }

        //dodawnaie markerów do mapy <3
        var markers = markersData.map(function(marker) {
            var newMarker = L.marker([marker.latitude, marker.longitude]).addTo(map);
            newMarker.bindPopup("<b>" + marker.name + "</b><br>" + marker.address);
            return newMarker;
        });

        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('TripEvent', (e) => {
                addMarker(e.mark);
            });

        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('AddQueueEvent', (e) => {
                const mark = e.mark;
                addWaypoint(mark);
            });
        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('DelQueueEvent', (e) => {
                const mark = e.mark;
                delWaypoint(mark)
            });
    </script>

    <script src="{{ asset('js/map_creator.js') }}" ></script>

@endsection
@section('content')

    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
{{--    NIE DOTYKAĆ BO TO JEST MOJA MAPA <!---->--}}
    <div id="map"></div>
@if($trip->owner_id === Auth::user()->user_id)
        <div class="map_bar container">

                        <div id="" >
                            <button class="button-perspective"  onclick="showForm('tactic')">Dodaj Taktyka</button>
                            <button class="button-perspective"  onclick="showForm('tactic_list');reload()">Lista Taktyków</button>
                        </div>
            @include('components.AddUserComponents', ['name' => 'tactic','title' => 'powpow'])
            @include('components.UserListComponents', ['name' => 'tactic_list','title' => 'Lista Taktyków!'])


        </div>
@endif
    @include('components.AddPostComponents')
    @include('components/creatorComponents')

    <div class="dashboard">
        <div class="dash_menu">
            <div class="outer">
            <button class="dash_bttn tablinks active"  data-tab="markers" onclick="change(event, 'markers')"><i class="material-icons">location_on</i> Markery</button>
            <button class="dash_bttn tablinks active" data-tab="routes" onclick="change(event, 'routes')"><i class="material-icons">location_on</i> Trasowanie</button>
            <button class="dash_bttn tablinks active" data-tab="posts" onclick="change(event, 'posts')"><i class="material-icons">location_on</i> Wpisy</button>

            </div>
        </div>

        <div class="dash_content tabcontent" id="markers" >

                @include('components.markerComponents')
        </div>
        <div class="dash_content tabcontent" id="routes" >
                @include('components.routeComponents')
        </div>
        <div class="dash_content tabcontent" id="posts"  >
            @include('components.postComponents')
        </div>

    </div>

<script>
        function RefreshDivs(divIds) {
            divIds.forEach(divId => {
                const divToRefresh = document.getElementById(divId);
                if (divToRefresh) {
                    fetch(location.href)
                        .then(response => response.text())
                        .then(data => {
                            const parser = new DOMParser();
                            const newDocument = parser.parseFromString(data, 'text/html');
                            const newContent = newDocument.getElementById(divId);
                            if (newContent) {
                                divToRefresh.innerHTML = '';
                                divToRefresh.innerHTML = newContent.innerHTML;
                                start();
                                console.info(`udał się zaktualizować zawartość ${divId}`);
                            } else {
                                console.error(`Brak div o id "${divId}" w nowej zawartości.`);
                            }
                        })
                        .catch(error => {
                            console.error('Błąd podczas pobierania zawartości: ', error);
                        });
                } else {
                    console.error(`Brak div o id "${divId}" na obecnej stronie.`);
                }
            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            // Po załadowaniu strony wywołaj funkcję change
            var urlParams = new URLSearchParams(window.location.search);
            var tabType = urlParams.get('tab') || 'markers';
            change(null, tabType);
        });

        function change(evt, tabType) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active"); // Usuń klasę "active" ze wszystkich przycisków
            }
            var activeButton = document.querySelector('button[data-tab="' + tabType + '"]');
            if (activeButton) {
                activeButton.classList.add("active");
            }
            document.getElementById(tabType).style.display = "block";
            if (evt) {
                evt.currentTarget.classList.add("active");
            }

            history.pushState(null, null, '?tab=' + tabType);
        }
</script>



@endsection

