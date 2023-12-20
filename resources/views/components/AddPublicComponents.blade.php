<script src="{{ asset('js/modalWindow.js') }}" ></script>
<div class="row justify-content-center">
    <div id="{{$name}}" style="display: none;" class="form-overlay">
        <div class="form-container text-center">
            <h4></h4>
            <div class="form-group">
                <label for="title">Podaj email Taktyka:</label>
                <input type="text" id="email" class="form-control" maxlength="30" placeholder="Taktyk@tactics.tcs" required value="{{$totalFuelCost}}">

                <br />
            @if($publictrip == null)
                <form method="post" action="{{route('AddPublicTrip')}}">
                    @csrf
                    <button type="submit" class="btn btn-primary" >Udostępnij</button>
                </form>
            @endif
            @if($publictrip != null)
                <form method="post" action="{{route('DelPublicTrip')}}">
                    @csrf
                    <button type="submit" class="btn btn-primary" >Przestań udostępniać</button>
                </form>
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
