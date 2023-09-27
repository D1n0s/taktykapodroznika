@extends('layouts.app')

@section('styles')

<link href="{{ asset('css/map_creator.css')}}" rel="stylesheet">
    <!-- Stylizacja Leaflet -->
    <link rel="stylesheet" href="{{ asset('leaflet/dist/leaflet.css') }}"/>
    <!-- Stylizacja Leaflet Control Geocoder -->
    <link rel="stylesheet" href="{{ asset('leaflet-control-geocoder/dist/Control.Geocoder.css') }}"/>
    <!-- Stylizacja Leaflet Routing Machine -->
    <link rel="stylesheet" href="{{ asset('leaflet-routing-machine/dist/leaflet-routing-machine.css') }}"/>
<script src="{{ asset('js/app.js') }}"></script>

@endsection

@section('content')



{{--PRIV--}}

<div style="background-color: white">
    <h3> TO JEST WIADOMOŚĆ PRYWATNA ! </h3>
    <input id="txt_priv" type="text" value="nothing">
    <button class="button-perspective" onclick="sendpriv()">WYŚLIJ PRYWATNIE</button>
    <div id="response_priv" class="p-6 bg-white border-b border-gray-200">
        ----------NOTHING----------
    </div>
</div>
<script>


    window.onload = sendpriv;

        function sendpriv() {
        let messagepriv = document.getElementById('txt_priv')

        axios.post("{{route('fire.private.event')}}", {
                message: messagepriv.value
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        })

    }

    Echo.private('private.{{auth()->user()->user_id}}')
        .listen('PrivateEvent', (e) => {
            document.getElementById('response_priv').innerText = e.message;

        });
</script>

<br><br><br>

{{--public --}}

<div style="background-color: white">
    <h3> TO JEST WIADOMOŚĆ PUBLICZNA ! </h3>
    <input id="txt_pub" type="text">
    <button class="button-perspective" onclick="sendpub()">WYŚLIJ PRYWATNIE</button>
    <div id="response_pub" class="p-6 bg-white border-b border-gray-200">
        ----------NOTHING----------
    </div>
</div>
<script>
    function sendpub() {
        let messagepub = document.getElementById('txt_pub');

        axios.post("{{route('fire.public.event')}}", {
            color: messagepub.value
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        })



    }

    Echo.channel('public')
        .listen('PublicEvent', (e) => {
            document.getElementById('response_pub').innerText = e.color;

        });
</script>

{{--<script>--}}
{{--    async function upload() {--}}

{{--        this.loading = true--}}
{{--        await axios.get("{{route('fire.private.event')}}", {--}}
{{--            headers: {--}}
{{--                'Content-Type': 'application/json;charset=utf-8',--}}
{{--                'X-CSRF-TOKEN': "{{csrf_token()}}"--}}
{{--            }--}}
{{--        }).then(res => this.loading = false)--}}
{{--    }--}}
{{--    Echo.private('private.{{auth()->user()->user_id}}')--}}
{{--        .listen('PrivateEvent', (e) => document.getElementById('message').innerText = e.message);--}}
{{--</script>--}}


<br><br><br><br>
<div style="background-color: white;">
    <p id="message">NIC TU NIE MA</p>
    <p>{{auth()->user()->user_id}}</p>
</div>

<script>
    Echo.private('private.')
        .listen('PrivateEvent', (e) => document.getElementById('message').innerText = e.message);
</script>








    @if(session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

        <p id="title">Tytuł: {{ $trip->title}}</p>
        <p  id="title">Opis: {{ $trip->desc }}</p>

    <div id="map"></div>
        <div class="map_bar">
                        <div>
                            <button class="button-perspective" onclick="showForm('button1-form')">Punkt Startowy</button>
                        </div>
                        <div id="koniec_" >
                            <button class="button-perspective"  onclick="showForm()">Punkt Końcowy</button>
                        </div>

        </div>

    @include('components/creatorComponents')



    <div id="button1-form" style="display: none;" class="form-overlay">
        <div class="form-container">
            <h4>Formularz</h4>
            <form>
                <div class="mb-3">
                    <label for="start">Miejsce Startowe:</label>
                    <input type="text" class="form-control" id="start" name="start">
                </div>
                <button type="button" class="btn btn-primary" onclick="saveForm()">Zapisz</button>
                <button type="button" class="btn btn-secondary" onclick="hideForm('button1-form')">Anuluj</button>
            </form>
        </div>
    </div>

    @if(isset($data))
        <h1>Dane:</h1>
        <p>Name: {{ $data['name'] }}</p>
        <p>Description: {{ $data['desc'] }}</p>
        <p>Address: {{ $data['address'] }}</p>
        <p>Latitude: {{ $data['latitude'] }}</p>
        <p>Longitude: {{ $data['longitude'] }}</p>
    @endif



    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        @parent
        <!-- Skrypt Leaflet -->
        <script src="{{ asset('leaflet/dist/leaflet.js') }}"></script>
        <!-- Skrypt Leaflet Control Geocoder -->
        <script src="{{ asset('leaflet-control-geocoder/dist/Control.Geocoder.js') }}"></script>
        <!-- Skrypt Leaflet Routing Machine -->
        <script src="{{ asset('leaflet-routing-machine/dist/leaflet-routing-machine.js') }}"></script>
        <script src="{{ asset('js/map_creator.js') }}" ></script>
        <!-- MAPA -->
        <script>
            var map = L.map('map').setView([52.237049, 21.017532], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            }).addTo(map);


            // MARKERY Z BAZY DANYCH
            @foreach($markerData as $marker)
            var marker = L.marker([{{ $marker['latitude'] }}, {{ $marker['longitude'] }}]).addTo(map);
            marker.bindPopup("<b>{{ $marker['name'] }}</b><br>{{ $marker['address'] }}");
            @endforeach




        </script>

    @endsection
@endsection

