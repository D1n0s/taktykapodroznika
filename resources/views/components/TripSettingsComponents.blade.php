<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center">
    <div id="{{$name}}" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4>{{$title}}</h4>
            <div class="form-group" id="settingsrefresh">


                <label for="title">Nazwa podróży</label>
                <input type="text" id="title_form" class="form-control" maxlength="30" value="{{$trip->title}}" data-old-value="{{$trip->title}}" placeholder="Taktyk@tactics.tcs" required>

                <label for="startdate">Data startu</label>
                <input class="form-control" type="date" name="startdate" id="startdate" value="{{$trip->start_date}}" min="{{ ($trip->start_date < date('Y-m-d')) ? $trip->start_date : date('Y-m-d') }}" data-old-value="{{$trip->start_date}}" onchange="updateEndDateMin()">

                <label for="enddate">Data zakończenia</label>
                <input class="form-control" type="date" name="enddate" id="enddate" value="{{$trip->end_date}}" min="{{$trip->start_date}}" data-old-value="{{$trip->end_date}}">
                <br />


                <button type="button" class="btn btn-primary" onclick="changeSetting()">Zmień</button>
                <button type="button" class="btn btn-secondary" onclick="cancel();hideForm('{{$name}}');">Anuluj</button>


            </div>
            <br>
            <div class="infoMessage"></div>

        </div>
    </div>
</div>

<script>

    function updateEndDateMin() {
        const startDateInput = document.getElementById('startdate');
        const endDateInput = document.getElementById('enddate');
if(startDateInput.value > endDateInput.value){
    endDateInput.min = startDateInput.value;
    endDateInput.value = "";
}else{
    endDateInput.min = startDateInput.value;
}

    }


    function cancel(){
        const title_form = document.getElementById('title_form');
        const startdate = document.getElementById('startdate');
        const enddate = document.getElementById('enddate');
        var infoMessages = document.getElementsByClassName('infoMessage');


        for (var i = 0; i < infoMessages.length; i++) {
            infoMessages[i].innerHTML = '';
        }

        title_form.value = title_form.getAttribute('data-old-value');
        startdate.value = startdate.getAttribute('data-old-value');
        enddate.value = enddate.getAttribute('data-old-value');
    }

    Echo.private('privateTrip.{{$trip->trip_id}}')
        .listen('settingsUpdateEvent', (e) => {
            $("#settingsrefresh").load(location.href + " #settingsrefresh");
            $("#trip_title").load(location.href + " #trip_title");

        });

    function changeSetting() {
        const title_form = document.getElementById('title_form');
        const startdate = document.getElementById('startdate');
        const enddate = document.getElementById('enddate');
        var infoMessages = document.getElementsByClassName('infoMessage');

        for (var i = 0; i < infoMessages.length; i++) {
            infoMessages[i].innerHTML = '';
        }

        axios.post("{{ route('ChangeTripSetting') }}", {
            title: title_form.value,
            startdate: startdate.value,
            enddate: enddate.value,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            }
        })
            .then(response => {
                for (var i = 0; i < infoMessages.length; i++) {
                    infoMessages[i].innerHTML = '<br/><div class="alert alert-success">' + response.data.success + '</div>';
                }
            })
            .catch(error => {
                for (var i = 0; i < infoMessages.length; i++) {
                    var errorMessage = error.response.data.errors;
                    var alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger';
                    for (var key in errorMessage) {
                        if (errorMessage.hasOwnProperty(key)) {
                            alertDiv.innerHTML += errorMessage[key] + '<br/>';
                        }
                    }
                    infoMessages[i].appendChild(alertDiv);

                }
            });
    }
</script>
