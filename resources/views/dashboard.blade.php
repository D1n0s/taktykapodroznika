@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row m-5">
        <div class="col-12 d-flex justify-content-center">
            <button class="button-perspective mb-4" onclick="showForms('start')">Utwórz podróż</button>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <button class="button-perspective mb-4" onclick="window.location.href='/mytrips'">Moje podróże</button>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <button class="button-perspective mb-4" onclick="window.location.href='/sharedtrips'">Udostępnione podróże</button>
        </div>
        <div class="col-12 d-flex justify-content-center">
            <button class="button-perspective mb-4" onclick="window.location.href='/publictrips'">Publiczne podróże</button>
        </div>
    </div>


            @extends('components/modalWindow', ['name'=>'start' , 'title'=>'Utwórz podróż'])
            @section('modal.content')
                <style>
                    [type="date"] {
                        background:#fff url(https://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/calendar_2.png)  97% 50% no-repeat ;
                    }
                    [type="date"]::-webkit-inner-spin-button {
                        display: none;
                    }
                    [type="date"]::-webkit-calendar-picker-indicator {
                        opacity: 0;
                    }
                </style>
                <form method="post" id="tripstartForm" action="{{ route('init') }}">
                    @csrf
                    <div class="form-group">
                        <label for="title">Tytuł:</label>
                        <input type="text" id="ttile" name="title" class="form-control"  maxlength="30">
                        <label for="startdate">Data startu</label>
                        <input class="form-control" type="date" name="startdate" id="startdate" min="{{ date('Y-m-d') }}" onchange="updateEndDateMin()">

                        <label for="enddate">Data zakończenia</label>
                        <input class="form-control" type="date" name="enddate" id="enddate" min="{{ date('Y-m-d') }}">
                    </div>
<br>
                    <button type="submit" class="btn btn-primary">Utwórz</button>
                    <button type="button" class="btn btn-secondary" onclick="hideForms('start')">Anuluj</button>

                    <script>
                        function updateEndDateMin() {
                            const startDateInput = document.getElementById('startdate');
                            const endDateInput = document.getElementById('enddate');

                            endDateInput.min = startDateInput.value;

                            // Odśwież pole "Data zakończenia" po zmianie daty rozpoczęcia
                            endDateInput.value = ""; // Wyczyść pole
                        }
                    </script>
                </form>

            @endsection

@if (session('success'))

                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
@endif
@if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
            @endif



    </div>
</div>
@endsection
