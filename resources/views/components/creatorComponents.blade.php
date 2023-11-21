
    <div class="row justify-content-center">
        <div id="button1-form" style="display: none;" class="form-overlay">
            <div class="form-container" >
                <div class="text-center">
                    <h4>Formularz</h4>
                    <form method="POST" id="tripForm" action="{{ route('addMarker') }}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="trip_id" id="trip_id" value="{{ $trip->trip_id }}">
                            <label for="name">Nazwa:</label>
                            <input type="text" id="name" name="name" class="form-control">
                            <label for="opis">Opis:</label>
                            <input type="text" id="desc" name="desc" class="form-control">
                            <label for="address">Lokalizacja:</label>
                            <input type="text" id="address" name="address" class="form-control">
                            <div class="form-row">
                                <div class="col">
                                    <br>
                                    <input type="text" style="width: 30%; float:left;margin-right: 30%;margin-left: 5%;" id="latitude" name="latitude" class="form-control form-control-sm" readonly>
                                    <input type="text" style="width: 30%; float:left;" id="longitude" name="longitude" class="form-control form-control-sm" readonly>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <br>
                        {{--                                             onclick="addMarker('location','button1-form'),submitForm();">                                 pop--}}
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                        <button type="button" class="btn btn-secondary" onclick="hideForm('button1-form')">Anuluj</button>
                    </form>
                    <div class="form-container-sugestion" style="position: relative;">
                        <div id="suggestions" ></div>
                    </div>
                </div>

                </div>


        </div>
    </div>



{{-----------------------------------------------------------------}}



@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif





