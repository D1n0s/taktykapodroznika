@extends('layouts.app')
@section('styles')
    <link href="{{ asset('css/buttons.css') }}" rel="stylesheet">
@endsection
@section('content')
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&display=swap");

        .pagination {
            padding: 2vh;
            border-radius: 8px;
            background-color: rgba(21, 33, 72, 0.85);
            justify-content: center; /* Wyśrodkowanie w poziomie */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
            width: 30%;
            position: absolute;
            bottom: 1vh;
        }


        .pagination a,
        .pagination .current {
            font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #000000;
            color: #fff;
            text-decoration: #0cde17;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
        }

        .pagination a:hover {
            background-color: #0056b3;
        }
        .center{
            display: flex;
            justify-content: center; /* Wyśrodkowanie w poziomie */
            align-items: center; /* Wyśrodkowanie w pionie */
        }
        /*MOTION FOR ROW*/
        .pow {
            background-image: linear-gradient(135deg, rgba(74, 205, 222, 0.25), rgba(29, 123, 219, 0.2) 20%, #152148 40%, rgba(21, 33, 72, 0.8) 100%);
            padding-top:5vh;
            padding-bottom: 5vh;
            min-height: 80vh;
            max-height: 120vh;
            color:white;
            padding: 1vh;


        }

        .framed {
        {{--border-image-source: url({{asset('storage/path31.png')}});--}}
/*border-image-source: url("https://raw.githubusercontent.com/robole/artifice/main/framed-content/img/frame.png");*/
            border-image:url({{asset('storage/path31.png')}}) 1;
            border-image-slice: 85 140 140 310;
            border-image-repeat: stretch;
            border-style: inset;
            border-width: 8vh;
            border-left-width: 20vh;
            border-right-width: 8vh;
            border-top-width: 5vh;
            display: grid;
            min-height: 80vh;
            max-height: 120vh;

            margin: 0 auto;
            margin-left: 27vh;



            overflow: auto;

        }

    </style>
    @if(!empty($trips))
        <div class=" framed container_content " style="padding: 0;position: relative; ">
            <div class="text-center pow"   >
                <h1>WSPÓŁDZIELONE PODRÓŻE </h1>
                <br> <br><BR>
                <table class="table  text-center text-white" style="vertical-align: middle;">
                    <thead>
                    <tr>
                        <th>Tytuł</th>
                        <th>Data wyjazdu</th>
                        <th>Data przyjazdu</th>
                        <th>AKCJE</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($trips as $trip)
                        <tr >
                            <td>{{ $trip->trip->title }}</td>

                            <td>{{ $trip->trip->start_date }}</td>
                            <td>{{ $trip->trip->end_date }}</td>
                            <td class="panel green">
                                <form action="map/{{ $trip->trip->trip_id }}?" method="get">
                                    <button type="submit"  >Wejdź</button>
                                </form>
                            </td >
                            <td class="panel pink">
                                <form action="{{route('leaveTrip',['trip_id'=>$trip->trip_id])}}" method="post">
                                    @csrf
                                    <span class="panel pink">  <button type="submit" class="panel pink">Usuń</button> </span>
                                </form>
                            </td>



                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <!-- Paginacja -->
                <div class="center ">
                    <div class="pagination ">
                        @if ($trips->currentPage() > 1)
                            <a href="{{ $trips->previousPageUrl() }}">POPRZEDNIE</a>
                        @endif

                        @if ($trips->currentPage() > 3)
                            <a href="{{ $trips->url(1) }}">1</a>
                            @if ($trips->currentPage() > 4)
                                <span>...</span>
                            @endif
                        @endif

                        @for ($i = max(1, $trips->currentPage() - 2); $i <= min($trips->currentPage() + 2, $trips->lastPage()); $i++)
                            @if ($i == $trips->currentPage())
                                <span class="current">{{ $i }}</span>
                            @else
                                <a href="{{ $trips->url($i) }}">{{ $i }}</a>
                            @endif
                        @endfor

                        @if ($trips->currentPage() < $trips->lastPage() - 2)
                            @if ($trips->currentPage() < $trips->lastPage() - 3)
                                <span>...</span>
                            @endif
                            <a href="{{ $trips->url($trips->lastPage()) }}">{{ $trips->lastPage() }}</a>
                        @endif

                        @if ($trips->currentPage() < $trips->lastPage())
                            <a href="{{ $trips->nextPageUrl() }}">DALEJ</a>
                        @endif
                    </div></div>

            </div>


        </div>

    @else
    @endif







@endsection
