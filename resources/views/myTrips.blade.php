@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center" style="background-color: white;">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.js" defer></script>

{{--                <style>--}}
{{--                .pagination {--}}
{{--                    text-align: center;--}}
{{--                }--}}

{{--                .pagination a,--}}
{{--                .pagination .current {--}}
{{--                    display: inline-block;--}}
{{--                    padding: 5px 10px;--}}
{{--                    margin-right: 5px;--}}
{{--                    background-color: #007bff;--}}
{{--                    color: #fff;--}}
{{--                    text-decoration: none;--}}
{{--                    border-radius: 3px;--}}
{{--                }--}}

{{--                .pagination a:hover {--}}
{{--                    background-color: #0056b3;--}}
{{--                }--}}
{{--            </style>--}}




{{--            <!-- resources/views/mytrips.blade.php -->--}}
{{--            <h1>Moje podróże</h1>--}}


{{--            <!-- Wyświetlanie danych podróży -->--}}
{{--            <table class="table">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>Tytuł</th>--}}
{{--                    <th>Opis</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach ($trips as $trip)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $trip->title }}</td>--}}
{{--                        <td>{{ $trip->desc }}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}

{{--            <!-- Paginacja -->--}}
{{--            <div class="pagination">--}}
{{--                @if ($trips->currentPage() > 1)--}}
{{--                    <a href="{{ $trips->previousPageUrl() }}">Previous</a>--}}
{{--                @endif--}}

{{--                @if ($trips->currentPage() > 3)--}}
{{--                    <a href="{{ $trips->url(1) }}">1</a>--}}
{{--                    @if ($trips->currentPage() > 4)--}}
{{--                        <span>...</span>--}}
{{--                    @endif--}}
{{--                @endif--}}

{{--                @for ($i = max(1, $trips->currentPage() - 2); $i <= min($trips->currentPage() + 2, $trips->lastPage()); $i++)--}}
{{--                    @if ($i == $trips->currentPage())--}}
{{--                        <span class="current">{{ $i }}</span>--}}
{{--                    @else--}}
{{--                        <a href="{{ $trips->url($i) }}">{{ $i }}</a>--}}
{{--                    @endif--}}
{{--                @endfor--}}

{{--                @if ($trips->currentPage() < $trips->lastPage() - 2)--}}
{{--                    @if ($trips->currentPage() < $trips->lastPage() - 3)--}}
{{--                        <span>...</span>--}}
{{--                    @endif--}}
{{--                    <a href="{{ $trips->url($trips->lastPage()) }}">{{ $trips->lastPage() }}</a>--}}
{{--                @endif--}}

{{--                @if ($trips->currentPage() < $trips->lastPage())--}}
{{--                    <a href="{{ $trips->nextPageUrl() }}">Next</a>--}}
{{--                @endif--}}
{{--            </div>--}}


            <div class="mt-4" style="background-color: white;">
                @include('components/pagination', ['paginator' => $trips])
            </div>


@endsection
