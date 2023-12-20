<div class="row justify-content-center">
    <div id="addFuelPrice" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
                <div id="refreshFuel" class="form-group items-center mx-auto" style="width: 25vh;">
                    <br>
                    <label for="diesel">Diesel (zł)</label>
                    <input  type="number" id="diesel" name="diesel" value="{{$trip->diesel_cost ? $trip->diesel_cost: '0.00' }}"  class="form-control text-center rounded" min="0"  step="0.01" oninput="validity.valid||(value='');">
                    <br>
                    <label for="petrol">Benzyna (zł)</label>
                    <input  type="number" id="petrol" name="petrol" value="{{$trip->petrol_cost ? $trip->petrol_cost: '0.00' }}" class="form-control text-center rounded" min="0"  step="0.01" oninput="validity.valid||(value='');">
                    <br>
                    <label for="gas">Gaz (zł)</label>
                    <input  type="number" id="gas" name="gas"  value="{{$trip->gas_cost ? $trip->gas_cost: '0.00' }}" class="form-control text-center rounded" min="0"  step="0.01" oninput="validity.valid||(value='');">
                    <br>

                </div>
            <br />
            <button type="button" onclick="AddFuelPrice()" class="btn btn-primary">zapisz</button>
            <button type="button" class="btn btn-secondary" onclick="hideForm('addFuelPrice')">Anuluj</button>
        </div>
    </div>
</div>
<script>

    function refreshFuel(){
        $("#refreshFuel").load(location.href + " #refreshFuel");
    }


function AddFuelPrice(){
          const diesel =  document.getElementById('diesel').value;
          const petrol =  document.getElementById('petrol').value;
          const gas =  document.getElementById('gas').value;
          console.log(diesel , petrol , gas );
        axios.post("{{ route('FuelPrice') }}", {
            'diesel' : diesel,
            'petrol' : petrol,
            'gas' : gas,
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
                    alert(error.response.data.message);
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
            });

        }
    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('fuelUpdateEvent', (e) => {
            $("#refreshFuel").load(location.href + " #refreshFuel");
        });
</script>

