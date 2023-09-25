@extends('layouts.app')
    @section('styles')
        <link href="{{ asset('css/buttons.css') }}" rel="stylesheet">
    @endsection
@section('content')
    <style>             .pagination {
            display: flex;
            justify-content: center; /* Wyśrodkowanie w poziomie */
            align-items: center; /* Wyśrodkowanie w pionie */
        }


        .pagination a,
        .pagination .current {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        /*MOTION FOR ROW*/
        .pow {
    background-color: white;
            padding-top:5vh;
            padding-bottom: 5vh;
            min-height: 80vh;
            max-height: 120vh;
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
            border-top-width: 5vh;
            display: grid;
            row-gap: 2rem;
            min-height: 80vh;
            max-height: 120vh;

            margin: 0 auto;



            overflow: auto;

        }

        #frame{

        }
    </style>

    <div class=" framed container_content" style="padding: 0;">
        <div class="text-center pow"  >

            <!-- resources/views/mytrips.blade.php -->
            <h1>Moje podróże</h1>
<br> <br>
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Opis</th>
                    <th>Data wyjazdu</th>
                    <th>Data przyjazdu</th>
                    <th>AKCJE</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($trips as $trip)
<form action="map/{{ $trip->trip_id }}?" method="get">
                    <tr >
                        <td>{{ $trip->title }}</td>
                        <td>{{ $trip->desc }}</td>
                        <td>{{ $trip->start_date }}</td>
                        <td>{{ $trip->end_date }}</td>
                        <td class="panel green"><button type="submit" >Wejdź</button></td>
                        <td class="panel pink"><button>Usuń</button></td>

                    </tr>
</form>
                @endforeach
                </tbody>
            </table>

            <!-- Paginacja -->
            <div class="pagination">
                @if ($trips->currentPage() > 1)
                    <a href="{{ $trips->previousPageUrl() }}">Previous</a>
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
                    <a href="{{ $trips->nextPageUrl() }}">Next</a>
                @endif
            </div>
        </div>

    </div>



@endsection
