<link href="{{ asset('css/components/markerComponents.css')}}" rel="stylesheet">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


<div class="box" style="">
    <div class="first">
        <div x-data="{ message: 'Witaj, Alpine.js!' }">
            <p x-text="message"></p>
        </div>

    </div>
    @forelse($markerData as $mark)
        <div class="secound">
            <div class="card box" id="box_{{$mark['id']}}">
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
                            <label class="label_read_only label_card">Tytuł</label>
                        </div>
                        <div class="group">
                            <input class="input_card "  type="text" value="{{$mark['address']}}" required id="address_{{$mark['id']}}" readonly data-original-value="{{$mark['address']}}">
                            <span class="highlight"></span>
                            <span class="bar"></span>
                            <label class="label_read_only label_card">Tytuł</label>
                        </div>

                        <div class="group editControls">
                            <button type="button" id="edit-button_{{$mark['id']}}" onclick="toggleEditMode('{{$mark['id']}}')">Przełącz Tryb Edycji</button>
                            <button type="submit" id="save-button_{{$mark['id']}}" style="display: none;" onclick="saveChanges('{{$mark['id']}}')">Zapisz</button>
                            <button type="button" id="cancel-button_{{$mark['id']}}" style="display: none;" onclick="cancelEdit('{{$mark['id']}}')">Anuluj</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @empty


    <div class="secound">
            <div class="card box" id="">
                <h2>LOKALIZACJA NR :</h2>
                <br><br>
                <div class="txt" style="height:80%;width: 100%;">
                    <h2>NIC TU NIE MA</h2>
                </div>
            </div>
        </div>
    @endforelse
</div>

    <script>
        function addMarkerDiv(mark) {
            var newDiv = `
                    <div class="card box" id="box_${mark.id}">
                        <h2>LOKALIZACJA</h2>
                        <br><br>
                        <div class="txt" style="height:80%;width: 100%;">
                            <form id="form_${mark.id}" method="POST" action="{{route('editMarker')}}" onsubmit="saveChanges(event, '${mark.id}')">
                                @csrf
            <div class="group">
                <input class="input_card " type="text" value="${mark.name}" required id="name_${mark.id}" readonly data-original-value="${mark.name}">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label class="label_read_only label_card">Tytuł</label>
                                </div>
                                <div class="group">
                                    <input class="input_card " type="text" value="${mark.desc}" required id="desc_${mark.id}" readonly data-original-value="${mark.desc}">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label class="label_read_only label_card">Tytuł</label>
                                </div>
                                <div class="group">
                                    <input class="input_card " type="text" value="${mark.address}" required id="address_${mark.id}" readonly data-original-value="${mark.address}">
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label class="label_read_only label_card">Tytuł</label>
                                </div>
                                <div class="group editControls">
                                    <button type="button" id="edit-button_${mark.id}" onclick="toggleEditMode('${mark.id}')">Przełącz Tryb Edycji</button>
                                    <button type="submit" id="save-button_${mark.id}" style="display: none;" onclick="saveChanges('${mark.id}')">Zapisz</button>
                                    <button type="button" id="cancel-button_${mark.id}" style="display: none;" onclick="cancelEdit('${mark.id}')">Anuluj</button>
                                </div>
                            </form>
                        </div>
                    </div>

            `;

            // Dodaj nowy div do kontenera (np. divContainer)
            $('.secound').append(newDiv);
        }


        function toggleEditMode(id) {
            const inputFields = document.querySelectorAll(`#box_${id} .group input[type="text"]`);
            const saveButton = document.getElementById(`save-button_${id}`);
            const editButton = document.getElementById(`edit-button_${id}`);
            const cancelButton = document.getElementById(`cancel-button_${id}`);

            inputFields.forEach(input => {
                input.readOnly = false; // Ustawiamy na false, aby umożliwić edycję
            });

            // Toggle button visibility
            saveButton.style.display = 'inline-block';
            cancelButton.style.display = 'inline-block';
            editButton.style.display = 'none';
        }

        function saveChanges(id) {
            event.preventDefault(); // Zatrzymaj domyślną akcję formularza (przeładowanie strony)
            const form = document.getElementById(`form_${id}`);

            const name = document.getElementById(`name_${id}`);
            const desc = document.getElementById(`desc_${id}`);
            const address = document.getElementById(`address_${id}`);

            const saveButton = document.getElementById(`save-button_${id}`);
            const editButton = document.getElementById(`edit-button_${id}`);
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
                    // Tutaj możesz obsłużyć błędy (np. wyświetlić komunikat o błędzie)
                    console.error(error);
                });

        }
        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('EditMarkEvent', (e) => {
                const mark = e.mark;
                const markId = e.mark.mark_id;
                // Wyświetl dane z e.mark w konsoli
                console.log("Dane z e.mark:", e.mark.name);

                // Pobierz formularz (zmień to na odpowiedni selektor, jeśli jest inny)
                const form = document.getElementById(`form_${markId}`);
                // Zaktualizuj pola formularza nowymi danymi
                form.querySelector(`#name_${markId}`).value = mark.name;
                form.querySelector(`#desc_${markId}`).value = mark.desc;
                form.querySelector(`#address_${markId}`).value = mark.address;


                editMarker(mark);
            });




        function cancelEdit(id) {
            const inputFields = document.querySelectorAll(`#box_${id} .group input[type="text"]`);
            const saveButton = document.getElementById(`save-button_${id}`);
            const editButton = document.getElementById(`edit-button_${id}`);
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
        }


    </script>



