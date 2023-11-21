<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center">
    <div id="{{$name}}" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
            <div class="form-group">
                <label for="title">Podaj email Taktyka:</label>
                <input type="text" id="email" class="form-control" maxlength="30" placeholder="Taktyk@tactics.tcs" required>
                <br />
                <button type="button" class="btn btn-primary" onclick="addTactic()">Dodaj</button>
                <button type="button" class="btn btn-secondary" onclick="cancel()">Anuluj</button>
            </div>

            <div id="infoMessage" style="display: none;"></div>
        </div>
    </div>
</div>

<script>
    function cancel(){
        const infoMessage = document.getElementById('infoMessage');
        const emailInput = document.getElementById('email');
        infoMessage.style.display = 'none';
        emailInput.value = ''; // Ustawianie wartości na pustą
        hideForms('{{$name}}');
    }
    function addTactic() {
        const emailInput = document.getElementById('email');
        const email = emailInput.value;
        const infoMessage = document.getElementById('infoMessage');
        infoMessage.style.display = 'none';
        axios.post("{{ route('addTactic') }}", {
            user_id: {{$trip->owner_id}},
            trip_id: {{$trip->trip_id}},
            email: email,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",

            }
        })
            .then(response => {
                const existingInvite = response.data.existingInvite;
                document.getElementById('infoMessage').innerHTML = '<br/><div class="alert alert-success">' + response.data.success + '</div>';
                document.getElementById('infoMessage').style.display = 'block';
                emailInput.value = '';

            })
            .catch(error => {
                document.getElementById('infoMessage').innerHTML = '<br/><div class="alert alert-danger">' + error.response.data.error + '</div>';
                document.getElementById('infoMessage').style.display = 'block';
                emailInput.value = '';

            });
    }
</script>
