<script src="{{ asset('js/modalWindow.js') }}" ></script>
    <div class="row justify-content-center">
        <div id="{{$name}}" style="display: none;" class="form-overlay">
            <div class="form-container text-center">
                    <h4>{{$title}}</h4>
                    @yield('modal.content')

<br />

                    <button type="submit" class="btn btn-primary">zapisz</button>
                    <button type="button" class="btn btn-secondary" onclick="hideForms('{{$name}}')">Anuluj</button>
                </div>

        </div>

    </div>










