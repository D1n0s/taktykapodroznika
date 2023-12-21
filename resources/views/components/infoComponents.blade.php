<style>
    * {

    }

    #paper {
        color:#FFF;
        font-size:20px;
    }
    #margin {
        margin-left:12px;
        margin-bottom:20px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
    #text {
        width: 100%;
        overflow:hidden;
        background-color:#FFF;
        color:#222;
        font-family:Courier, monospace;
        font-weight:bold;
        font-size:24px;
        line-height:40px;
        padding-left:100px;
        padding-right:50px;
        padding-top:40px;
        padding-bottom:34px;
        background-image:url({{@asset('storage/lines.png')}}),url({{@asset('storage/lines2.png')}}), url({{@asset('storage/paper.png')}});
        background-repeat:repeat-y ,repeat, repeat;
        -webkit-border-radius:12px;
        border-radius:12px;
        -webkit-box-shadow: 0px 2px 14px #000;
        box-shadow: 0px 2px 14px #000;
        border-top:1px solid #FFF;
        border-bottom:1px solid #FFF;
    }

    #wrapper {
        width: 60%;
        /*max-width:47%;*/
        /*min-width:47%;*/
        height:auto;
        margin-left:auto;
        margin-right:auto;
        margin-top:24px;
        outline:none;
        border:none;
        padding:0px;
        font-family:Courier, monospace;
    }
    .line {
        width: 100%;
        height: 0;
        border-top: 3px dashed black;
        display:inline-block;
    }
    .btn_info {
        line-height: 40px;
        position: relative;
        display: inline-block;
        transition: box-shadow 0.3s ease; /* Dodane przejście dla płynniejszego efektu */
        cursor:pointer;
    }

    .btn_info::before {
        content: "";
        position: absolute;
        top: -2px;
        left: -2px;
        width: calc(100% + 4px);
        height: calc(100% + 4px);
        box-shadow: 0 0 0 2px #000;
        border-radius: 30px 2px 30% 3px / 4px 10px 3px 30px;
        box-sizing: border-box;
        transition: box-shadow 0.3s ease; /* Dodane przejście dla płynniejszego efektu */
    }

    .btn_info:hover::after {
        content: "";
        position: absolute;
        top: -4px;
        left: -4px;
        width: calc(100% + 8px);
        height: calc(100% + 8px);
        box-shadow:
            0 0 0 2px #000,
            2px 2px 0 2px #000, /* Zmniejszone wartości cienia */
            4px 4px 0 2px #000, /* Zmniejszone wartości cienia */
            4px 4px 0 2px #000; /* Zmniejszone wartości cienia */
        border-radius: 30px 2px 30% 3px / 4px 10px 3px 30px;
        box-sizing: border-box;
        transition: box-shadow 0.3s ease; /* Dodane przejście dla płynniejszego efektu */
    }

    ul{
        margin:0px;
    }
    li{
        margin:0px;
    }

    .expandable-list-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .expandable-list div {
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease-out;
    }

    .expandable-list .expanded {
        max-height: 1000px;
    }

</style>
<div class="box">
    @if($permission == 1)
<div class="first">
    <button class="button-perspective" onclick="showForm('addVehicle')">Dodaj Pojazd</button>
    <button class="button-perspective" onclick="refreshFuel(),showForm('addFuelPrice')">Ceny Paliwa</button>
    <button class="button-perspective" onclick="refreshPersons(),showForm('PeronsNumberForm')">Liczba Osób</button>
</div>
    @endif

<div class="secound text-center " style="background-color: white;" >

</div>

    <div id="wrapper">
        <div id="text" name="text"  style="overflow: hidden; word-wrap: break-word; resize: none; ">
                <div class="btn_info">PODSUMOWANIE</div>
                <div class="btn_info">POJAZDY</div>
                <div class="btn_info">KOSZTY</div>

                <div id="UpdateInfo">
                    <br>
                    <div id="podsumowanie">
                    <h2 class="text-center" style="font-weight: bolder;line-height:40px;margin-bottom: 0px;"> PODSUMOWANIE</h2>
                    <br>
                    <span style="float:left;">CZAS PODRÓŻY</span><span style="float:right;">{{$trip->travel_time}}</span><br>
                    <span style="float:left;">DYSTANS</span><span style="float:right;">{{$trip->distance}} km</span><br>
                    <span style="float:left;">ŚREDNIA PRĘDKOŚĆ</span><span style="float:right;">{{$trip->avg_speed}} km/h</span><br>
                    <span style="float:left;">SPALONE PALIWO</span><span style="float:right;">{{$trip->fuel_consumed}} l</span><br>
                    <span style="float:left;">Ilość pojazdów</span><span style="float:right;">{{count($trip->vehicles)}}</span><br>
                    <span style="float:left;">Ilość podróżujących</span><span style="float:right;">{{$trip->persons}}</span><br>

                        <ul class="expandable-list" data-list-id="list3">
                            <li class="expandable-list-item" onclick="toggleList('list3')"><span style="">CENY PALIWA </span></li>
                            <div id="list3" class="collapsed">
                                <ul>
                                    <li>
                                        <span style="float: left;">Diesel</span>
                                        <span style="float: right;">{{$trip->diesel_cost}} zł/l</span>
                                    </li>
                                    <li>
                                        <span style="float: left;">Benzyna</span>
                                        <span style="float: right;">{{$trip->petrol_cost}} zł/l</span>
                                    </li>
                                    <li>
                                        <span style="float: left;">Gaz</span>
                                        <span style="float: right;">{{$trip->gas_cost}} zł/l</span>
                                    </li>
                                </ul>
                            </div>
                        </ul>

</div>
                    <div id="pojazdy">
                        <h2 class="text-center" style="font-weight: bolder;line-height:40px;margin-bottom: 0px;"> POJAZDY</h2>
                        @foreach($trip->vehicles as $car)
                        <br>
                        <span style="float:left;">NAZWA POJAZDU</span><span style="float:right;">{{$car->name}}</span><br>
                        <span style="float:left;">ZUŻYCIE PALIWA</span><span style="float:right;">{{$car->consumption}} l/100km</span><br>
                        <span style="float:left;">RODZAJ PALIWA</span><span style="float:right;">{{$car->fuel}} </span><br>
                            @if($permission == 1)
                            <div class="btn_info  mx-auto" onclick="delVehicle({{$car->vehicle_id}})" >Usuń pojazd</div>
                            @endif
                        @endforeach
                    </div>

                    <div id="koszty">
                        <h2 class="text-center" style="font-weight: bolder;line-height:40px;margin-bottom: 0px;"> KOSZTY</h2>
                        <br>
                        <ul class="expandable-list" data-list-id="list1">
                            <li class="expandable-list-item" onclick="toggleList('list1')"><span >ATRAKCJE:</span> <span style="float:right;">{{ number_format($attractions->sum('cost'), 2, '.', ' ') }} zł</span></li>
                            <div id="list1" class="collapsed">
                                <ul>
                                    @foreach($attractions as $att)
                                        <li style="margin:0px;">
                                            <span style="float: left;">{{$att->title}}</span><span style="float: right;">{{number_format($att->cost,2,'.',' ')}} zł</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </ul>
                                                    {{-- ******************SUMA ZA PALIWO ***************************************************--}}
                        @php
                            $totalFuelCost = $trip->vehicles->sum(function ($car) use ($trip) {
                                $fuelCost = 0;

                                if ($car->fuel == "benzyna") {
                                    $fuelCost = $trip->petrol_cost;
                                } elseif ($car->fuel == "gaz") {
                                    $fuelCost = $trip->gas_cost;
                                } elseif ($car->fuel == "diesel") {
                                    $fuelCost = $trip->diesel_cost;
                                }

                                return round(($trip->distance / 100) * $car->consumption * $fuelCost, 2);
                            });
                        @endphp
                        <ul class="expandable-list" data-list-id="list2">
                            <li class="expandable-list-item" onclick="toggleList('list2')">
                                <span>PALIWO:</span>
                                <span style="float:right;">{{ number_format($totalFuelCost, 2, '.', ' ') }} zł</span>
                            </li>
                            <div id="list2" class="collapsed">
                                <ul>
                                    @foreach($trip->vehicles as $car)
                                        <li style="margin:0px;">
                                            <span style="float: left;">{{ $car->name }}</span>
                                            <span style="float: right;">
                        {{
                            number_format(
                                ($trip->distance / 100) * $car->consumption *
                                (
                                    $car->fuel == "benzyna" ? $trip->petrol_cost : (
                                        $car->fuel == "gaz" ? $trip->gas_cost : (
                                            $car->fuel == "diesel" ? $trip->diesel_cost : 0
                                        )
                                    )
                                ),2, '.',' ') }} zł
                    </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </ul>


                </div>




            </div>
    </div>
</div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('js/info_script.js') }}"></script>
<script>
    function toggleList(listId) {
        var list = $("#" + listId);
        list.toggleClass('expanded collapsed');

        var isExpanded = list.hasClass('expanded');
        localStorage.setItem(listId, isExpanded);

        // Dodaj animację dla rozwijania/zwijania
        var maxHeight = isExpanded ? list.prop("scrollHeight") + "px" : "0";
        list.css("max-height", maxHeight);
    }

    $(document).ready(function () {
        var expandableLists = $('.expandable-list div');
        expandableLists.each(function () {
            var list = $(this);
            var listId = list.attr('id');
            var isExpanded = localStorage.getItem(listId) === 'true';

            if (isExpanded) {
                list.addClass('expanded');
            } else {
                list.addClass('collapsed');
            }

            // Przywróć animację po odświeżeniu
            var maxHeight = isExpanded ? list.prop("scrollHeight") + "px" : "0";
            list.css("max-height", maxHeight);
        });
    });

    function delVehicle(vehicle_id){
        var confirmation = confirm('Czy na pewno chcesz usunać pojazd ? ');
        if(confirmation){
            axios.post("{{ route('DelVehicle') }}", {
                'vehicle_id' : vehicle_id,
            }, {
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
                .then(response => {
                    alert( response.data.success );
                })
                .catch(error => {
                    if (error.response && error.response.data) {
                        if (error.response.status === 404) {
                            alert('Nie znaleziono pojazdu');
                        } else {
                            alert(error.response.data.message);
                        }
                    } else if (error.request) {
                        console.log(error.request);
                    } else {
                        console.log('Error', error.message);
                    }
                });
        }


    }
    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('InfoUpdateEvent', (e) => {
            $("#UpdateInfo").load(location.href + " #UpdateInfo", function () {
                // Przywróć stan list po odświeżeniu
                $('.expandable-list').each(function () {
                    var list = $(this);
                    var listId = list.data('list-id');
                    var isExpanded = localStorage.getItem(listId) === 'true';

                    if (isExpanded) {
                        list.find('div').addClass('expanded');
                    } else {
                        list.find('div').addClass('collapsed');
                    }


                });
            });
        });

    {{--Echo.private('privateTrip.{{$trip->trip_id}}')--}}
    {{--    .listen('InfoUpdateEvent', (e) => {--}}
    {{--        $("#UpdateInfo").load(location.href + " #UpdateInfo");--}}
    {{--    });--}}
</script>

