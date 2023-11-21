<div class="text-center mt-4" id="attraction_move_{{$name}}" style="display: none;">
    <h4>Gdzie chcesz przenieść atrakcję?</h4>
    <div class="form-group mx-auto" style="width: 50vh; max-width: none;">
        <ul class="list-group">
            @foreach($posts as $post)
                @if($post->post_id != $post_id)
            <li class="list-group-item list-group-item-action" onclick="moveAttraction({{$post->post_id}},{{$attraction_id}})">
                @if($post->date != null) Dzień {{$post->day}}  ( {{date('d-m-Y', strtotime($post->date))}} ) ||@endif {{$post->title}}
            </li>
                @endif
            @endforeach
        </ul>
        <br>
        <button type="button" class="btn btn-secondary mt-6" onclick="hideForms('attraction_move_{{$name}}')">Anuluj</button>

    </div>
</div>

<script>


    function moveAttraction(post_id,attraction_id){
         alert('czy na pewno chcesz przeniść atrakcję ? ');

        axios.post('{{ route("moveAttraction") }}', {
            post_id : post_id,
            attraction_id : attraction_id,
        })
            .then(response => {
                console.log(response.data);
            })
            .catch(error => {
                console.error(error);
            });



    }


function reload(){
    $("#listoftactics").load(location.href + " #listoftactics");
    const messageElement = document.getElementById('Message');
    if(messageElement) {
        messageElement.innerHTML = '<div class="alert alert-success">Pomyślnie odświeżono!</div>';
        setTimeout(() => {
            messageElement.innerHTML = '';
        }, 3000);
    }
}

</script>
