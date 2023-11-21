<div class="row justify-content-center">
    <div id="addpost" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
            <form method="post" id="xxx" action="{{ route('addPost') }}">
                @csrf
                <div class="form-group">
                    <label for="title">Tytuł*:</label>
                    <input type="text" id="tile" name="title" class="form-control" maxlength="30"
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
                form.reset(); // reset() jest metodą Vanilla JavaScript do resetowania formularza
                hideForm('addpost');
                alert('Post dodany!');
            })
            .catch(function(error) {
                console.error(error);
            });

    });






</script>

