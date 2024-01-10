<div class="row justify-content-center">
    <div id="PeronsNumberForm" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
                <div id="PeronsNum" class="form-group items-center mx-auto" style="width: 25vh;">
                    <br>
                    <label for="personsNumber">Liczba podróżujących</label>
                    <input type="number" id="personsNumber" name="personsNumber" value="{{ $trip->persons }}" class="form-control text-center rounded" min="1" oninput="validity.valid||(value='');">
                    <br>
                </div>
            <br />
            <button type="button" onclick="ADDPeronsNumber()" class="btn btn-primary">zapisz</button>
            <button type="button" class="btn btn-secondary" onclick="hideForm('PeronsNumberForm')">Anuluj</button>
        </div>
    </div>
</div>
<script>

function refreshPersons(){
    $("#PeronsNum").load(location.href + " #PeronsNum");
}

function ADDPeronsNumber(){

          const persons =  document.getElementById('personsNumber').value;
        axios.post("{{ route('PersonNumber') }}", {
            'persons' : persons,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })
            .then(response => {
                alert( response.data.success );
                hideForm('PeronsNumberForm');
            })
            .catch(error => {
                if (error.response && error.response.data) {
                    alert(error.response.data.message);
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
            });

        }

    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('PersonsUpdateEvent', (e) => {
            $("#PeronsNum").load(location.href + " #PeronsNum");
        });

</script>

