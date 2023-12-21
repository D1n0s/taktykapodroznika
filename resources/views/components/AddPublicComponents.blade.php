<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center">
    <div id="{{$name}}" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4>{{$title}}</h4>
            <div class="form-group">
                @if($publictrip == null)
                <p class="text-center">
                    UWAGA! <br>
                    JEŚLI UDOSTĘPNISZ PODRÓŻ ZOSTANIE ZABLOKOWANA OPCJA EDYCJI DO CZASU ZAKOŃCZENIA PUBLIKACJI. <BR>
                    W TYM CZASIE KAŻDY BĘDZIE MÓGŁ SKOPIOWAĆ PODRÓŻ I KORZYSTAĆ Z NIEJ.
                </p>
                @endif
                    @if($publictrip != null)
                        <p class="text-center">
                            UWAGA! <br>
                            EYCJA JEST ZABLOKOWANA. ABY ODBLOKOWAĆ MOŻLIWOŚĆ ZMIAN ORAZ ZAKOŃCZYĆ PUBLIKACJĘ, KLIKNIJ PRZYCISK PONIŻEJ.
                        </p>
                    @endif
                <br />
            @if($publictrip == null)
                <form method="post" action="{{route('AddPublicTrip')}}">
                    @csrf
                    <button type="submit" class="btn btn-primary" >Udostępnij</button>
                </form><br>
            @endif
            @if($publictrip != null)
                <form method="post" action="{{route('DelPublicTrip')}}">
                    @csrf
                    <button type="submit" class="btn btn-primary" >Przestań udostępniać</button>
                </form><br>
            @endif
                <button type="button" class="btn btn-secondary" onclick="hideForm('{{$name}}')">Anuluj</button>
            </div>

            <div id="infoMessage" style="display: none;"></div>
        </div>
    </div>
</div>

<script>

    function hideForm(formId) {
        var form = document.getElementById(formId);
        form.style.display = "none";
    }

</script>
