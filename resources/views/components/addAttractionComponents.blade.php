@extends('../layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700;900&display=swap');

        *, body {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -moz-osx-font-smoothing: grayscale;
        }

        html, body {
            height: 100%;
            overflow-x: hidden;
        }


        .form-holder {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .form-holder .form-content {
            position: relative;
            text-align: center;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-align-items: center;
            align-items: center;
            padding: 60px;
        }

        .form-content .form-items {
            border: 3px solid #fff;
            padding: 40px;
            display: inline-block;
            width: 100%;
            min-width: 540px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            text-align: left;
            -webkit-transition: all 0.4s ease;
            transition: all 0.4s ease;
            backdrop-filter: blur(3px);


        }

        .form-content h3 {
            color: #fff;
            text-align: left;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-content h3.form-title {
            margin-bottom: 30px;
        }

        .form-content p {
            color: #fff;
            text-align: left;
            font-size: 17px;
            font-weight: 300;
            line-height: 20px;
            margin-bottom: 30px;
        }


        .form-content label, .was-validated .form-check-input:invalid~.form-check-label, .was-validated .form-check-input:valid~.form-check-label{
            color: #fff;
        }

        .form-content input[type=text], .form-content input[type=password], .form-content textarea, .form-content select {
            width: 100%;
            /*float:left;*/
            padding: 9px 20px;
            text-align: left;
            border: 0;
            outline: 0;
            border-radius: 6px;
            background-color: #fff;
            font-size: 15px;
            font-weight: 300;
            color: #000000;
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
            margin-top: 16px;
        }


        .btn-primary{
            background-color: #6C757D;
            outline: none;
            border: 0px;
            box-shadow: none;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active{
            background-color: #495056;
            outline: none !important;
            border: none !important;
            box-shadow: none;
        }

        .form-content textarea {
            position: static !important;
            width: 100%;
            padding: 8px 20px;
            border-radius: 6px;
            text-align: left;
            background-color: #fff;
            border: 0;
            font-size: 15px;
            font-weight: 300;
            color: #8D8D8D;
            outline: none;
            resize: none;
            height: 120px;
            -webkit-transition: none;
            transition: none;
            margin-bottom: 14px;
        }

        .form-content textarea:hover, .form-content textarea:focus {
            border: 0;
            background-color: #ebeff8;
            color: #8D8D8D;
        }

        .mv-up{
            margin-top: -9px !important;
            margin-bottom: 8px !important;
        }

        .invalid-feedback{
            color: #ff606e;
        }

        .valid-feedback{
            color: #2acc80;
        }
        select {
            font-family: 'FontAwesome', 'Second Font name';
        }
    </style>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet"/>
    <div class="form-body">
        <div class="row">
            <div class="form-holder" >
                <div class="form-content">
                    <div class="form-items">
                        <h3>Nowe wydarzenie na dziś jest :</h3>
                        <p>Dodajesz wydarzenie do {{$post->title}} </p>

                        <form class="requires-validation" method="POST" action="{{route('addAttraction')}}">
                            @csrf
                            <input type="hidden" name="post" value="{{$post->post_id}}">
                            @if($att != null)
                            <input type="hidden" name="attraction_id" value="{{$att->attraction_id}}">
                            @endif
                            <div class="col-md-12">
                                <input class="form-control" type="text" name="name" placeholder="Nazwa atrakcji" value="{{ $att ? $att->title : '' }}" maxlength="37" required>
                            </div>
                            <div class="col-md-12">
                                <textarea name="desc"  style="color:black;" placeholder="Opisz co będziemy robić" class="form-control text-base" maxlength="254">{{$att ? $att->desc : '' }}</textarea>
                            </div>

                            <div class="col-md-12 mt-3" style="float: none;">
                                <label for="category">Kategoria wydarzenia </label>
                                <select id="markerSelect" name="category" class="form-select " style="margin:auto;">
                                    @if($att == null)
                                        @foreach($attractions_category as $att_categorie)
                                            <option value="{{$att_categorie['category_attraction_id']}}" >
                                                {{$att_categorie['name']}}
                                            </option>
                                            @endforeach
                                    @endif

                                    @if($att != null)
                                            @foreach($attractions_category as $att_categorie)
                                                <option value="{{$att_categorie['category_attraction_id']}}" @if($att->category_id == $att_categorie['category_attraction_id'] ) selected @endif>

                                                    <i style='font-size:24px' class='fas' value="{{$att_categorie['icon']}}">

                                                    </i>{{$att_categorie['name']}}
                                                </option>
                                            @endforeach
                                    @endif

                                </select>
                            </div>

                                    <div class="col-md-12 mt-4">
                                        <div class="row">
                                            <div class="col-md-5 mx-auto ">
                                                <label for="start_time">Godzina rozpoczęcia</label>
                                                <input type="time" name="start_time" value="{{$att ? $att->getTime('time_start') : '' }}" class="form-control text-center">
                                            </div>
                                            <div class="col-md-5 mx-auto">
                                                <label for="end_time">Godzina zakończenia</label>
                                                <input type="time"  name="end_time" value="{{$att ? $att->getTime('time_end') : '' }}" class="form-control text-center">
                                            </div>
                                        </div>
                                    </div>


                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var startTimeInput = document.querySelector('input[name="start_time"]');
                                    var endTimeInput = document.querySelector('input[name="end_time"]');

                                    function validateEndTime() {
                                        var startTimeParts = startTimeInput.value.split(':');
                                        var endTimeParts = endTimeInput.value.split(':');

                                        var startHour = parseInt(startTimeParts[0]);
                                        var startMinute = parseInt(startTimeParts[1]);
                                        var endHour = parseInt(endTimeParts[0]);
                                        var endMinute = parseInt(endTimeParts[1]);

                                        if (endHour < startHour || (endHour === startHour && endMinute < startMinute)) {
                                            alert('Godzina zakończenia nie może być wcześniejsza niż godzina rozpoczęcia.');
                                            endTimeInput.value = null;
                                        }
                                    }
                                    endTimeInput.addEventListener('change', validateEndTime);
                                });
                            </script>
                            <br>

                            <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5 mx-auto">
                                    <div class="input-group">
                                            <label style="margin-right: 55px;" for="price" class="bg-transparent  ">Całkowity koszt  </label>
                                        <br><input  type="number"  name="price" value="{{$att ? $att->cost : '' }}" class="form-control  rounded " min="0"  step="0.01" oninput="validity.valid||(value='');">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-transparent border-0" style="color: white; font-weight: bold;">zł</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-12" style="float: none;">
                                <select id="markerSelect" name="location" class="form-select mt-3">

                                    @if($att == null)
                                        <option  value="" selected>Lokalizacja (nie wymagane)</option>
                                        <span id="refresh_markers">
                                        @foreach($markers as $marker)
                                            <option value="{{$marker['id']}}" >
                                                {{$marker['name']}} || {{ substr($marker['desc'], 0, 23) }}
                                            </option>
                                        @endforeach
                                        </span>
                                    @endif

                                    @if($att != null)
                                    <option  value="">Lokalizacja (nie wymagane)</option>
                                    @foreach($markers as $marker)
                                        <option value="{{$marker['id']}}" @if($att->mark && $att->mark->mark_id == $marker['id']) selected @endif>
                                            {{$marker['name']}} || {{ substr($marker['desc'], 0, 23) }}
                                        </option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>


                            <div class="col-md-12 mt-2 d-flex justify-content-center">
                                <br>
                                <button type="submit"  class="btn btn-primary " style="background-color:green;margin-right: 1vh;">Utwórz</button>
                                <button type="button" class="btn btn-primary" style="background-color:red;" onclick="history.back()">Anuluj</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
