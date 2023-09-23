<script src="{{ asset('js/modalWindow.js') }}" ></script>
    <div class="row justify-content-center">
        <div id="@yield('modal.name')" style="display: none;" class="form-overlay">
            <div class="form-container text-center">
                    <h4>@yield('modal.title')</h4>
                    @yield('modal.content')

<br />

                    <button type="submit" class="btn btn-primary">zapisz</button>
                    <button type="button" class="btn btn-secondary" onclick="hideForm('@yield('modal.name')')">Anuluj</button>
                </div>

        </div>

    </div>


<!-- usunąć to potem <3 -->
@if(session('success'))
    <div class="alert alert-success mt-6">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-6">
        {{ session('error') }}
    </div>
@endif





