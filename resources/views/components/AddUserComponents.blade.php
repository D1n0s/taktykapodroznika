<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center">
    <div id="{{$name}}" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
            <div class="form-group">
                <label for="title">Podaj email Taktyka:</label>
                <input type="text" id="email" class="form-control" maxlength="30" placeholder="Taktyk@tactics.tcs" required>
                <div class="form-group mb-1">
                    <label for="permissionsDropdown">Uprawnienia:</label>

                    <select class="form-control" id="permission">
                        <option value="1" >odczyt/zapis</option>
                        <option value="2" >odczyt</option>
                    </select>

                </div>
                <br />
                <button type="button" class="btn btn-primary" onclick="addTactic()">Dodaj</button>

                <button type="button" class="btn btn-secondary" onclick="cancel();hideForm('{{$name}}');">Anuluj</button>
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
        emailInput.value = '';
    }
    function hideForm(formId) {
        var form = document.getElementById(formId);
        form.style.display = "none";
    }
    function addTactic() {
        const emailInput = document.getElementById('email');
        const email = emailInput.value;
        const permission = document.getElementById('permission').value;
        const infoMessage = document.getElementById('infoMessage');
        infoMessage.style.display = 'none';
        axios.post("{{ route('addTactic') }}", {
            user_id: {{$trip->owner_id}},
            trip_id: {{$trip->trip_id}},
            email: email,
            permission: permission,
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
                if (error.response) {
                    // Błąd odpowiedzi z serwera
                    if (error.response.status === 422) {
                        // Przypadek błędnych danych wejściowych (Unprocessable Entity)
                        const validationErrors = error.response.data.errors;
                        let errorMessage = '<ul>';
                        Object.values(validationErrors).forEach(errors => {
                            errors.forEach(error => {
                                errorMessage += '<li>' + error + '</li>';
                            });
                        });
                        errorMessage += '</ul>';
                        document.getElementById('infoMessage').innerHTML = '<br/><div class="alert alert-danger">' + errorMessage + '</div>';
                    } else {
                        // Inne błędy odpowiedzi z serwera
                        document.getElementById('infoMessage').innerHTML = '<br/><div class="alert alert-danger">' + error.response.data.error + '</div>';
                    }
                } else if (error.request) {
                    // Błąd requestu (np. brak odpowiedzi od serwera)
                    document.getElementById('infoMessage').innerHTML = '<br/><div class="alert alert-danger">Request error</div>';
                } else {
                    // Inne błędy
                    document.getElementById('infoMessage').innerHTML = '<br/><div class="alert alert-danger">Error: ' + error.message + '</div>';
                }
                document.getElementById('infoMessage').style.display = 'block';
                emailInput.value = '';
            });
    }
</script>
