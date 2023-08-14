
<div class="container">
    <div class="row justify-content-center">
        <div id="button1-form" style="display: none;" class="form-overlay">

            <div class="form-container">
                <div class="text-center">
                    <h4>Formularz</h4>
                    <form method="POST" id="tripForm" action="{{ route('trip.store') }}">
                        @csrf
                        <div class="form-group">
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
                </div>
                </div>

            <div class="row">
                <div class="col">
                    <div class="form-container-sugestion" >
                        <div id="suggestions" ></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



{{-----------------------------------------------------------------}}

<div id="button1-form" style="display: none;" class="form-overlay">
    <div class="form-container">
        <h4>Formularz</h4>
        <form>
            <div style="margin-top: 20%;">
                <label for="location">Lokalizacja:</label>
                <input type="text" id="location" name="location" class="form-control">
                <div id="suggestions"></div>
            </div>
            <button type="button" class="btn btn-primary" onclick="saveForm()">Zapisz</button>
            <button type="button" class="btn btn-secondary" onclick="hideForm('button1-form')">Anuluj</button>
        </form>
    </div>
</div>

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





