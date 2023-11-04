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
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    main{
        height: 10500px;
    }
</style>
@endsection

@section('content')






    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif



    <div id="map"></div>
        <div class="map_bar">
                        <div>
                            <button class="button-perspective" onclick="showForm('button1-form')">DODAJ PUNKT</button>
                        </div>
                        <div id="koniec_" >
                            <button class="button-perspective"  onclick="showForm()">Punkt Końcowy</button>
                        </div>

        </div>

    @include('components/creatorComponents')



    <div class="dashboard">
        <div class="dash_menu">
            <div class="outer">
            <button class="dash_bttn tablinks" onclick="change(event, 'markers')"><i class="material-icons">location_on</i> Markery</button>
            <button class="dash_bttn tablinks" onclick="change(event, 'routes')"><i class="material-icons">location_on</i> Trasowanie</button>
            <button class="dash_bttn tablinks" onclick="change(event, 'test')"><i class="material-icons">location_on</i> Markery</button>
            <button class="dash_bttn tablinks" onclick="change(event, 'test1')"><i class="material-icons">location_on</i> Markery</button>

            </div>
        </div>
        <div class="dash_content tabcontent" id="markers" >
                @include('components.markerComponents')
        </div>
        <div class="dash_content tabcontent" id="routes" style="float:left;" >
                @include('components.routeComponents')
        </div>
        <div class="dash_content tabcontent" id="test" >
        cOŚ TAM
        </div>
        <div class="dash_content tabcontent" id="test1" >
         POW POW POW
        </div>
    </div>

    <script>




        document.addEventListener("DOMContentLoaded", function() {
            // Po załadowaniu strony wywołaj funkcję change z odpowiednim argumentem
            change(null, 'routes');
        });
        function change(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>


    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        @parent
        <!-- Skrypt Leaflet -->
        <script src="{{ asset('leaflet/dist/leaflet.js') }}"></script>
{{--        <!-- Skrypt Leaflet Control Geocoder -->--}}
        <script src="{{ asset('leaflet-control-geocoder/dist/Control.Geocoder.js') }}"></script>
{{--        <!-- Skrypt Leaflet Routing Machine -->--}}
        <script src="{{ asset('leaflet-routing-machine/dist/leaflet-routing-machine.js') }}"></script>
        <!-- MAPA -->
        <script>
            var map = L.map('map').setView([52.237049, 21.017532], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            }).addTo(map);

                // MARKERY Z BAZY DANYCH
                var markersData = {!! json_encode($markerData) !!}; // Konwertuje dane PHP na dane JavaScript
                var markers = markersData.map(function(marker) {
                    var newMarker = L.marker([marker.latitude, marker.longitude]).addTo(map);
                    newMarker.bindPopup("<b>" + marker.name + "</b><br>" + marker.address);
                    return newMarker;
                });


                Echo.private('privateTrip.{{$trip->trip_id}}')
                .listen('TripEvent', (e) => {
                addMarker(e.mark);
            });




        </script>
        <script src="{{ asset('js/map_creator.js') }}" ></script>

    @endsection
@endsection

