<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center" >
    <div id="{{$name}}" style="display: none;" class="form-overlay" >
        <div class="form-container text-center mx-auto" style="width:60%;max-width: none;">
            <h4>{{$title}}</h4>
            <div class="form-group  " style="">
                <h3 for="title">Taktycy i ich uprawnienia:</h3>
    <div style="overflow-y: auto; max-height: 50vh;">
                <ul class="list-group" id="listoftactics">
                    @foreach($sharedusers as $suser)
                    <li id="tactic_{{$loop->index}}" class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">E-mail użytkownika</h5>
                            <p id="emailtactic_{{$loop->index}}" class="mb-1">{{$suser->email}}</p>
                        </div>
                        <div class="form-group mb-1">
                            <label for="permissionsDropdown">Uprawnienia</label>

                            <select class="form-control" id="tactic_perm_{{$loop->index}}">
                                <option value="1" {{ $suser->pivot->permission_id == 1 ? 'selected' : '' }}>odczyt/zapis</option>
                                <option value="2" {{ $suser->pivot->permission_id == 2 ? 'selected' : '' }}>odczyt</option>
                            </select>

                        </div>
                        <div>
                            <button type="button" class="btn btn-success mr-2" onclick="saveperm({{$loop->index}})" >Zapisz uprawnienia</button>
                            <button type="button" class="btn btn-danger" onclick="deltactic({{$loop->index}})">Usuń użytkownika</button>
                        </div>
                    </li>
                        <div id="Message_{{$loop->index}}" ></div>

                    @endforeach
                </ul>
    </div>
                <br>
                <div id="Message"></div>
<br>
                <button type="button" class="btn btn-secondary btn-info" onclick="reload()">Odśwież</button>
                <button type="button" class="btn btn-secondary" onclick="hideForms('{{$name}}')">Wyjdź</button>
            </div>
        </div>
    </div>
</div>

<script>



function reload(){
    $("#listoftactics").load(location.href + " #listoftactics");
    const messageElement = document.getElementById('Message');
        messageElement.innerHTML = '<span class="alert alert-success text-center">Pomyślnie odświeżono!</span>';
        setTimeout(() => {
            messageElement.innerHTML = '';
        }, 500);
}

    function saveperm(id){

        const tacticpermissions = document.getElementById('tactic_perm_'+id).value;
        const email = document.getElementById('emailtactic_'+id).innerText;

        console.log(tacticpermissions + ' ');
        console.log(email + ' ');

        axios.post("{{route('SavePermissions')}}", {
            'permission_id' : tacticpermissions,
            'email' : email,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })
            .then(response => {
                document.getElementById('Message_'+id).innerHTML = '<div class="alert alert-success text-center">' + response.data.success + '</div>';
                setTimeout(() => {
                    document.getElementById('Message_'+id).innerHTML = '';
                }, 1000);
            })
            .catch(error => {
                console.error(error);
                if (error.response && error.response.data) {
                    console.log(error.response.data);
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
            });

    }

    function deltactic(id){
        const email = document.getElementById('emailtactic_'+id).innerText;
        console.log(email + ' ');

        alert('Czy na pewno chcesz usunąć użytkownika : ' + email);

        axios.post("{{route('delTactic')}}", {
            'email' : email,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })
            .then(response => {
                document.getElementById('tactic_'+id).innerHTML = '<div class="alert alert-success">' + response.data.success + '</div>';
            })
            .catch(error => {
                console.error(error);
                if (error.response && error.response.data) {
                    console.log(error.response.data);
                } else if (error.request) {
                    console.log(error.request);
                } else {
                    console.log('Error', error.message);
                }
            });
    }

</script>
