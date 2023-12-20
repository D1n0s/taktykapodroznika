<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center">
    <div id="{{$name}}" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
            <div class="form-group">

                <br />
                <form method="post" action="{{ route('CopyPublicTrip', ['trip_id' => $trip->trip_id]) }}">
                    @csrf
                    <label for="title">Podaj nazwę podróży</label>
                    <input type="text" name="title" class="form-control" maxlength="30"  required value="{{$trip->title}}">

                    <label for="startdate">Data startu</label>
                    <input class="form-control" type="date" name="startdate" id="startdate" value="{{$trip->start_date}}" min="{{ date('Y-m-d') }}" onchange="updateEndDateMin()">

                    <label for="enddate">Data zakończenia</label>
                    <input class="form-control" type="date" name="enddate" id="enddate" value="{{$trip->end_date}}" min="{{ date('Y-m-d') }}">
                    <br>
                    <button type="submit" class="btn btn-primary" >SKOPIUJ PODRÓŻ</button>
                </form>
                <br>
                <button type="button" class="btn btn-secondary" onclick="hideForm('{{$name}}')">Anuluj</button>
            </div>

            <div id="infoMessage" style="display: none;"></div>
        </div>
    </div>
</div>

<script>
    function updateEndDateMin() {
        const startDateInput = document.getElementById('startdate');
        const endDateInput = document.getElementById('enddate');

        endDateInput.min = startDateInput.value;

        endDateInput.value = "";
    }
</script>
