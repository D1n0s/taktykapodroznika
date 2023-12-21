<div class="row justify-content-center">
    <div id="addVehicle" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
                <div class="form-group items-center mx-auto" style="width: 25vh;">
                    <label for="vehicle_name">Nazwa pojazdu*:</label>
                    <input type="text" id="vehicle_name" name="vehicle_name" class="form-control text-center" maxlength="30"
                           placeholder="Pojazd 1" required>
                    <br>
                    <label for="consumption">Spalanie (l/100km)</label>
                    <input  type="number" id="consumption" name="consumption"  class="form-control text-center rounded" min="0" value="5.0" step="0.1" oninput="validity.valid||(value='');">
                    <br>
                    <select id="fuel" class="form-select" aria-label="Default select example">
                        <option selected>Wybierz rodzaj paliwa</option>
                        <option value="diesel">diesel</option>
                        <option value="benzyna">benzyna</option>
                        <option value="gaz">gaz</option>
                    </select>
                </div>
            <br />
            <button type="button" onclick="AddVehicle()" class="btn btn-primary">zapisz</button>
            <button type="button" class="btn btn-secondary" onclick="hideForm('addVehicle')">Anuluj</button>
        </div>
    </div>
</div>
<script>

function AddVehicle(){
          const vehicle_name =  document.getElementById('vehicle_name').value;
          const consumption = document.getElementById('consumption').value;
          const  fuel = document.getElementById('fuel').value;
        axios.post("{{ route('AddVehicle') }}", {
            'vehicle_name' : vehicle_name,
            'consumption' : consumption,
            'fuel' : fuel,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })
            .then(response => {
                alert( response.data.success + " " +  vehicle_name);
                console.log(response.data.success);
                document.getElementById('vehicle_name').value = '';
                document.getElementById('consumption').value = '5.0';
                document.getElementById('fuel').selectedIndex = 0;
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

</script>

