@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center" style="background-color: red;">
                <button class="button-perspective" onclick="showForm('start')">Utwórz podróż</button>
            <a href="/mytrips"><button class="button-perspective">Moje podróże</button></a>
            </div>

            @extends('components/modalWindow')
            @section('modal.name', 'start')
            @section('modal.title')Utwórz podróż @endsection
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
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                        <label for="title">Tytuł:</label>
                        <input type="text" id="ttile" name="title" class="form-control"  maxlength="30">
                        <label for="desc">Opis:</label>
                        <input type="text" id="desc" name="desc" class="form-control" maxlength="250">

                        <label for="startdate">Data startu</label>
                        <input class="form-control" type="date" name="startdate" id="startdate" min="{{ date('Y-m-d') }}" onchange="updateEndDateMin()">

                        <label for="enddate">Data zakończenia</label>
                        <input class="form-control" type="date" name="enddate" id="enddate" min="{{ date('Y-m-d') }}">
                    </div>

                        @section('buttonText', 'Inny tekst')



                    <script>
                        function updateEndDateMin() {
                            const startDateInput = document.getElementById('startdate');
                            const endDateInput = document.getElementById('enddate');

                            endDateInput.min = startDateInput.value;

                            // Odśwież pole "Data zakończenia" po zmianie daty rozpoczęcia
                            endDateInput.value = ""; // Wyczyść pole
                        }
                    </script>
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
