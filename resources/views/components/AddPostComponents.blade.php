<div class="row justify-content-center">
    <div id="addpost" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
            <form method="post" id="xxx" action="{{ route('addPost') }}">
                @csrf
                <div class="form-group">
                    <label for="title">Tytuł*:</label>
                    <input type="text" id="tile" name="title" class="form-control" maxlength="50"
                           placeholder="np. Dzień 1 || Lokalizacja" required>
                    <br>
                    <label for="date">Wybierz dzień:</label>
                    <input class="form-control" type="date" name="date" id="date" min="{{$trip->start_date}}"
                           max="{{$trip->end_date}}">
                </div>
            <br />
            <button type="submit" class="btn btn-primary">zapisz</button>
            <button type="button" class="btn btn-secondary" onclick="hideForm('addpost')">Anuluj</button>
            </form>
        </div>
    </div>
</div>
<script>
    $('#xxx').submit(function(event) {
        event.preventDefault();

        var formData = {
            title: document.getElementById('tile').value,
            date: document.getElementById('date').value,
        };

        console.log(formData);
        var form = document.getElementById('xxx');
        axios.post(form.action, formData)
            .then(function(response) {
                console.log(response.data);
                form.reset();

                if (response.data && response.data.success) {
                    alert(response.data.success);
                    hideForm('addpost')
                } else {
                    alert('Post dodany!');
                    hideForm('addpost')
                }
            })
            .catch(function(error) {
                console.error(error);
                if (error.response  && error.response.data && error.response.data.errors) {
                    var errorMessage = error.response.data.message;
                    // alert('Błąd: ' + error.response.data.errors);
                    alert('Błąd: ' + JSON.stringify(error.response.data.errors, null, 2));
                } else {
                    alert('Błąd');
                }
            });

    });






</script>

