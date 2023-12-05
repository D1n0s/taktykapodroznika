<link href="{{ asset('css/components/markerComponents.css')}}" rel="stylesheet">


<div class="box" style="">
    <div class="first">

        <div>
            @if($permission == 1)
            <button class="button-perspective" onclick="showForm('button1-form')">Dodaj Punkt</button>
            @endif
        </div>

    </div>

    <div class="secound" id="RefreshDivMarkers">
    @forelse($markerData as $mark)
@if($mark['queue'] != null && $mark['queue'] <= count($markerData))
                <div class="card box Queue" id="box_{{$mark['id']}}" >
                    <div class="circle" id="circle_{{$mark['id']}}">
                        <span class="number">{{$mark['queue']}}</span>
                    </div>

@else
                <div class="card box" id="box_{{$mark['id']}}">

@endif

                <h2>LOKALIZACJA </h2>
                <br><br>
                <div class="txt" style="height:80%;width: 100%;">
                    <form  id="form_{{$mark['id']}}" method="POST" action="{{route('editMarker')}}" onsubmit="saveChanges(event, '{{$mark['id']}}')" >
                        @csrf
                        <div class="group">
                            <input class="input_card " type="text" value="{{$mark['name']}}" required id="name_{{$mark['id']}}" readonly data-original-value="{{$mark['name']}}">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label class="label_read_only label_card">Tytuł</label>
                        </div>
                        <div class="group">
                            <input class="input_card "  type="text" value="{{$mark['desc']}}" required id="desc_{{$mark['id']}}" readonly data-original-value="{{$mark['desc']}}">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label class="label_read_only label_card">Opis</label>
                        </div>
                        <div class="group">
                            <input class="input_card "  type="text" value="{{$mark['address']}}" required id="address_{{$mark['id']}}" readonly data-original-value="{{$mark['address']}}">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label class="label_read_only label_card">Adres</label>
                        </div>
@if($permission == 1)
                        <div class="group editControls">
                            <button class="btn " type="button" id="del-button_{{$mark['id']}}" onclick="delMarker('{{$mark['id']}}')"><i class='fas fa-trash-alt' style='font-size:30px;'></i></button>
                            <button class="btn " type="button" id="edit-button_{{$mark['id']}}" onclick="toggleEditMode('{{$mark['id']}}')"><i class='fas fa-edit' style='font-size:30px;'></i></button>
                            <button type="submit" id="save-button_{{$mark['id']}}" style="display: none;" onclick="saveChanges('{{$mark['id']}}')">Zapisz</button>
                            <button type="button" id="cancel-button_{{$mark['id']}}" style="display: none;" onclick="cancelEdit('{{$mark['id']}}')">Anuluj</button>
                        </div>
@endif
                    </form>

                </div>
            </div>

    @empty


            <div class="card box" id="RefreshDivMarkers">
                <h2>LOKALIZACJA NR :</h2>
                <br><br>
                <div class="txt" style="height:80%;width: 100%;">
                    <h2>NIC TU NIE MA</h2>
                </div>
            </div>

    @endforelse
</div>
</div>
    <script>
        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('AddQueueEvent', (e) => {
                const markId = e.mark.mark_id;
                const markElement = document.getElementById(`box_${markId}`);
                const circle = document.getElementById(`circle_${markId}`);
                if(circle != null){
                    circle.remove();
                }
                markElement.classList.add('Queue');

                const circleElement = document.createElement("div");
                circleElement.className = "circle";
                circleElement.id = "circle_"+markId;
                const numberElement = document.createElement("span");
                numberElement.className = "number";
                numberElement.textContent = e.mark.queue;
                circleElement.appendChild(numberElement);
                markElement.appendChild(circleElement);
            });
        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('DelQueueEvent', (e) => {
                const markId = e.mark.mark_id;
                const markElement = document.getElementById(`box_${markId}`);
                const circle = document.getElementById(`circle_${markId}`);
                if(circle != null){
                    circle.remove();
                }
                markElement.classList.remove('Queue');
            });
        function RefreshDivMarkers() {
            $("#RefreshDivMarkers").load(location.href + " #RefreshDivMarkers");
        }
        function delMarker(Mark_id){
            var potwierdzenie = confirm('czy na pewno chcesz usunąc marker ? ');
            if(potwierdzenie){
                axios.post('{{ route("delMarker") }}', {
                    mark_id : Mark_id,
                })
            }
        }


        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('MarkEvent', (e) => {
                //console.log('usunięto marker');
                $("#RefreshDivMarkers").load(location.href + " #RefreshDivMarkers");
                RefreshDivs(['routes']);
                removeMarker(e.mark);
            });


        function toggleEditMode(id) {
            const inputFields = document.querySelectorAll(`#box_${id} .group input[type="text"]`);
            const saveButton = document.getElementById(`save-button_${id}`);
            const editButton = document.getElementById(`edit-button_${id}`);
            const delButton = document.getElementById(`del-button_${id}`);
            const cancelButton = document.getElementById(`cancel-button_${id}`);

            inputFields.forEach(input => {
                input.readOnly = false; // Ustawiamy na false, aby umożliwić edycję
            });

            // Toggle button visibility
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
            editButton.style.display = 'none';
            delButton.style.display = 'none';
        }

        function saveChanges(id) {
            event.preventDefault(); // Zatrzymaj domyślną akcję formularza (przeładowanie strony)
            const form = document.getElementById(`form_${id}`);

            const name = document.getElementById(`name_${id}`);
            const desc = document.getElementById(`desc_${id}`);
            const address = document.getElementById(`address_${id}`);

            const saveButton = document.getElementById(`save-button_${id}`);
            const editButton = document.getElementById(`edit-button_${id}`);
            const delButton = document.getElementById(`del-button_${id}`);
            const cancelButton = document.getElementById(`cancel-button_${id}`);

            const data = {
                id: id,
                name: name.value,
                desc: desc.value,
                address: address.value,
            };

            axios.post(form.getAttribute('action'), data)
                .then(response => {
                    // Tutaj możesz obsłużyć odpowiedź od serwera (np. wyświetlić komunikat o sukcesie)
                    console.log(response.data);

                   // Zaktualizuj pola wejściowe z ich oryginalnymi wartościami
                    name.setAttribute('data-original-value', name.value);
                    desc.setAttribute('data-original-value', desc.value);
                    address.setAttribute('data-original-value', address.value);

                    // Ustaw pola wejściowe na tylko do odczytu
                    name.readOnly = true;
                    desc.readOnly = true;
                    address.readOnly = true;

                    // Przełącz widoczność przycisków
                    saveButton.style.display = 'none';
                    cancelButton.style.display = 'none';
                    editButton.style.display = 'inline-block';
                    delButton.style.display = 'inline-block';
                })
                .catch(error => {
                    // Ustaw pola wejściowe na tylko do odczytu
                    name.readOnly = true;
                    desc.readOnly = true;
                    address.readOnly = true;

                    // Przełącz widoczność przycisków
                    saveButton.style.display = 'none';
                    cancelButton.style.display = 'none';
                    editButton.style.display = 'inline-block';
                    delButton.style.display = 'inline-block';
                    // Tutaj możesz obsłużyć błędy (np. wyświetlić komunikat o błędzie)
                    console.error(error);
                });

        }

        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('EditMarkEvent', (e) => {
                const mark = e.mark;
                const markId = e.mark.mark_id;
                console.log("Dane z e.mark:", e.mark.name);

                const form = document.getElementById(`form_${markId}`);
                form.querySelector(`#name_${markId}`).value = mark.name;
                form.querySelector(`#desc_${markId}`).value = mark.desc;
                form.querySelector(`#address_${markId}`).value = mark.address;
                editMarker(mark);
                RefreshDivs(['routes']);

            });




        function cancelEdit(id) {
            const inputFields = document.querySelectorAll(`#box_${id} .group input[type="text"]`);
            const saveButton = document.getElementById(`save-button_${id}`);
            const editButton = document.getElementById(`edit-button_${id}`);
            const delButton = document.getElementById(`del-button_${id}`);
            const cancelButton = document.getElementById(`cancel-button_${id}`);

            inputFields.forEach(input => {
                input.readOnly = true; // Ustawiamy na true, aby wyłączyć edycję
            });

            // Przywracamy oryginalne wartości pól tekstowych
            inputFields.forEach(input => {
                const originalValue = input.getAttribute('data-original-value');
                if (originalValue !== null) {
                    input.value = originalValue;
                }
            });

            // Toggle button visibility
            saveButton.style.display = 'none';
            cancelButton.style.display = 'none';
            editButton.style.display = 'inline-block';
            delButton.style.display = 'inline-block';
        }


    </script>



