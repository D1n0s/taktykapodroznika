<style>


:where(.ra-link) {
display: var(--ra-link-display, inline-flex);
}

:where(.ra-link[href]) {
color: var(--ra-link-color, inherit);
text-decoration: var(--ra-link-text-decoration, none);
}

:where(.ra-heading) {
margin-block-start: var(--ra-heading-margin-block-start, 0);
margin-block-end: var(--ra-heading-margin-block-end, 0);
}


/*
=====
UIA-TIMELINE
=====
*/

.uia-timeline__container {
display: var(--uia-timeline-display, grid);
}

.uia-timeline__groups {
display: var(--uia-timeline-groups-display, grid);;
gap: var(--uia-timeline-groups-gap, 1rem);
}

/*
SKIN 1
*/

[data-uia-timeline-skin="skin-1"] .uia-timeline__step {
line-height: 1;
font-size: var(--uia-timeline-step-font-size, 2rem);
font-weight: var(--uia-timeline-step-font-weight, 700);
color: var(--uia-timeline-step-color);
}

[data-uia-timeline-skin="skin-1"] .uia-timeline__point-intro {
display: grid;
grid-template-columns: min-content 1fr;
align-items: center;
gap: var(--uia-timeline-point-intro-gap, .5rem);
}

[data-uia-timeline-skin="skin-1"] .uia-timeline__point-date {
grid-row: 1;
grid-column: 2;
}

[data-uia-timeline-skin="skin-1"] .uia-timeline__point-heading {
grid-column: span 2;
}

[data-uia-timeline-skin="skin-1"] .uia-timeline__point-description {
margin-block-start: var(--uia-timeline-group-gap, 1rem);
width: min(100%, var(--uia-timeline-content-max-width));
}

/*
SKIN 2
*/

[data-uia-timeline-skin="2"] {
--_uia-timeline-line-color_default: #222;
--_uia-timeline-minimal-gap: var(--uia-timeline-minimal-gap, .5rem);
--_uia-timeline-space: calc(var(--_uia-timeline-arrow-size) + var(--_uia-timeline-dot-size) + var(--_uia-timeline-dot-size) / 2 + var(--_uia-timeline-minimal-gap));
--_uia-timeline-dot-size: var(--uia-timeline-dot-size, 1rem);
--_uia-timeline-arrow-size: var(--uia-timeline-arrow-size, .25rem);
--_uia-timeline-arrow-position: var(--uia-timeline-arrow-position, 1rem);
}

[data-uia-timeline-skin="2"] .uia-timeline__container {
position: relative;
padding-inline-start: calc(var(--_uia-timeline-space));
}

[data-uia-timeline-skin="2"] .uia-timeline__line {
width: var(--uia-timeline-line-width, 3px);
height: 100%;
background-color: var(--uia-timeline-line-color, var(--_uia-timeline-line-color_default));

position: absolute;
inset-block-start: 0;
inset-inline-start: calc(var(--_uia-timeline-dot-size) / 2);
transform: translateX(-50%);
}

[data-uia-timeline-skin="2"] .uia-timeline__group {
position: relative;
}

[data-uia-timeline-skin="2"] .uia-timeline__dot {
box-sizing: border-box;
width: var(--_uia-timeline-dot-size);
height: var(--_uia-timeline-dot-size);

border-radius: 50%;
border: var(--uia-timeline-dot-border-width, 1px) solid var(--uia-timeline-dot-border-color, var(--_uia-timeline-line-color_default));
background-color: var(--uia-timeline-dot-color, var(--_uia-timeline-line-color_default));

position: absolute;
/* - 4px is used for set the default gap from the top border */
inset-block-start: calc(var(--uia-timeline-dot-position, var(--_uia-timeline-arrow-position)) + 4px);
inset-inline-start: calc(-1 * var(--_uia-timeline-space));
}

[data-uia-timeline-skin="2"] .uia-timeline__point {
position: relative;
background-color: var(--uia-timeline-point-background-color, #fff);
}

[data-uia-timeline-skin="2"] .uia-timeline__point::before {
content: "";
width: 0;
height: 0;

border: var(--_uia-timeline-arrow-size) solid var(--uia-timeline-arrow-color, var(--_uia-timeline-line-color_default));
border-block-start-color: transparent;
border-inline-end-color: transparent;

position: absolute;
/* - 6px is used for set the default gap from the top border */
inset-block-start: calc(var(--_uia-timeline-arrow-position) + 6px);
inset-inline-start: calc(-1 * var(--_uia-timeline-arrow-size) + 1px);
transform: rotate(45deg);
}

[data-uia-timeline-adapter-skin-2="ui-card-skin-#1"] {
--uia-card-padding: var(--uia-timeline-point-padding, 1.5rem 1.5rem 1.25rem);
--uia-card-border-left: var(--uia-timeline-point-border-width, 3px) var(--uia-timeline-point-border-style, solid) var(--uia-timeline-point-border-color, var(--_uia-timeline-line-color_default));
--uia-card-background-color: var(--uia-timeline-point-background-color);
}

/*
SKIN 3
*/

[data-uia-timeline-skin="3"] {
--_uia-timeline-line-color_default: #222;
--_uia-timeline-space: var(--uia-timeline-space, 2rem);
--_uia-timeline-point-line-position: var(--uia-timeline-point-line-position, 50%);
--_uia-timeline-point-line-thickness: var(--uia-timeline-point-line-thickness, 2px);
}

[data-uia-timeline-skin="3"] .uia-timeline__container {
position: relative;
gap: var(--uia-timeline-annual-sections-gap, 2.5rem);
}

[data-uia-timeline-skin="3"] .uia-timeline__line {
width: var(--uia-timeline-line-width, 3px);
height: 100%;
background-color: var(--uia-timeline-line-color, var(--_uia-timeline-line-color_default));

position: absolute;
inset-block-start: 0;
inset-inline-start: 0;
}

[data-uia-timeline-skin="3"] .uia-timeline__annual-sections {
display: grid;
gap: 2rem;
}

[data-uia-timeline-skin="3"] .uia-timeline__groups {
display: grid;
gap: var(--uia-timeline-groups-gap, 2rem);
padding-inline-start: calc(var(--_uia-timeline-space));
}

[data-uia-timeline-skin="3"] .uia-timeline__group {
position: relative;
isolation: isolate;
}

[data-uia-timeline-skin="3"] .uia-timeline__point {
position: relative;
background-color: var(--uia-timeline-point-background-color, #fff);
}

[data-uia-timeline-skin="3"] .uia-timeline__point::before {
content: "";
inline-size: 100%;
block-size: var(--_uia-timeline-point-line-thickness);
background-color: var(--uia-timeline-line-color, var(--_uia-timeline-line-color_default));

position: absolute;
inset-block-start: var(--_uia-timeline-point-line-position);
inset-inline-start: calc(-1 * var(--_uia-timeline-space));
z-index: -1;
}

[data-uia-timeline-skin="3"] .uia-timeline__year {
width: fit-content;
font-size: larger;
padding: var(--uia-timeline-year-padding, .35rem .95rem);
background-color: var(--uia-timeline-year-background-color, var(--_uia-timeline-line-color_default));
color: var(--uia-timeline-year-color, #f0f0f0);
}

[data-uia-timeline-adapter-skin-3="ui-card-skin-#1"] {
--uia-card-padding: var(--uia-timeline-point-padding, 1.5rem 1.5rem 1.25rem);
--uia-card-border-left: var(--uia-timeline-point-border-width, 3px) var(--uia-timeline-point-border-style, solid) var(--uia-timeline-point-border-color, var(--_uia-timeline-line-color_default));
--uia-card-background-color: var(--uia-timeline-point-background-color);
}

/*
=====
UIA-CARD
=====
*/

.uia-card__time-divider::before {
content: "—";
margin-inline: var(--uia-card-time-divider-margin-inline, .15rem);
}

[data-uia-card-skin="1"] .uia-card__container {
display: grid;
gap: .5rem;
padding: var(--uia-card-padding);

background-color: var(--uia-card-background-color);
border-radius: var(--uia-card-border-radius, 2px);
box-shadow: var(--uia-card-box-shadow, 0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24));

border-block-start: var(--uia-card-border-top, var(--uia-card-border-top-width, 0) var(--uia-card-border-top-style, solid) var(--uia-card-border-top-color));
border-inline-end: var(--uia-card-border-right, var(--uia-card-border-right-width, 0) var(--uia-card-border-right-style, solid) var(--uia-card-border-right-color));
border-block-end: var(--uia-card-border-bottom, var(--uia-card-border-bottom-width, 0) var(--uia-card-border-bottom-style, solid) var(--uia-card-border-bottom-color));
border-inline-start: var(--uia-card-border-left, var(--uia-card-border-left-width, 0) var(--uia-card-border-left-style, solid) var(--uia-card-border-left-color));
}

[data-uia-card-skin="1"] .uia-card__intro {
display: grid;
gap: var(--uia-card-intro-gap, 1rem);
}

[data-uia-card-skin="1"] .uia-card__time {
grid-row: 1 / 1;
width: fit-content;
padding: var(--uia-card-time-padding, .25rem 1.25rem .25rem);
background-color: var(--uia-card-time-background-color, #f0f0f0);

font-weight: var(--uia-card-time-font-weight, 700);
font-size: var(--uia-card-time-font-size, 1.0rem);
text-transform: var(--uia-card-time-text-transform, uppercase);
color: var(--uia-card-time-color, currentColor);
}


:root {
--uia-timeline-line-color: #4557bb;
--uia-timeline-dot-color: #4557bb;
--uia-timeline-arrow-color: #4557bb;
--uia-timeline-point-line-thickness: 5px;
--uia-timeline-point-border-color: #4557bb;
--uia-timeline-group-padding: 1.5rem 1.5rem 1.25rem;
--uia-timeline-year-background-color: #4557bb;
}


.page {
    width: 95%;
    max-width: 95%;
}

</style>
<div class="box">
    @if($permission == 1)

<div class="first">
    <button type="button" class="button-perspective" onclick="showForm('addpost')">Dodaj Post</button>
</div>
    @endif

<br><br>


<div class="page " id="postlist" data-uia-timeline-skin="3" data-uia-timeline-adapter-skin-3="ui-card-skin-#1">
    @foreach( $posts as $post)
<div class="uia-timeline ">
<div class="uia-timeline__container">
<div class="uia-timeline__line"></div>
<div class="uia-timeline__annual-sections">
<span class="uia-timeline__year " aria-hidden="true">
@if($post->date != null)
Dzień {{$post->day}}  ( {{date('d-m-Y', strtotime($post->date))}} ) || <span id="post_title_{{$post->post_id}}">{{$post->title}}</span>
@endif
@if($post->date == null && $post->day == null)
        <span id="post_title_{{$post->post_id}}">{{$post->title}}</span>
@endif
    @if($permission == 1)

    <button style="font-size: 2.5vh;" class="btn btn-close-white" type="button" onclick="delPost({{$post->post_id}})" >
        <i class="far fa-trash-alt "></i>
    </button>
    @endif
</span>


<div class="uia-timeline__groups">

@foreach($attractions as $att)
@if($att->post_id == $post->post_id)

    <!--         POST TU SIE ZACZYNA -->
    <section class="uia-timeline__group" id="attraction_{{$att->attraction_id}}">
        <div class="uia-timeline__point uia-card" data-uia-card-skin="1">
            <div class="uia-card__container">
                <div class="uia-card__intro">
<span class="uia-card__time ">
@if($att->time_end != null && $att->time_start != null && $att->duration != null)
<span class="uia-card__day">{{$att->getTime('time_start')}} - {{$att->getTime('time_end')}} (czas trwania: {{$att->getTime('duration')}})</span>
@endif
@if($att->time_start != null &&  $att->time_end == null)
<span class="uia-card__day">{{$att->getTime('time_start')}}</span>
@endif
@if($att->time_end == null && $att->time_start == null && $att->duration == null)
<span class="uia-card__day">Uzupełnij dane o czasie</span>
@endif
</span>
                </div>


<div class="row " style="overflow: hidden;margin-left: 0px;margin-right:0px ">
    <div class="col-4  " >
    <!-- TYTUŁ  -->
        <h3 class="ra-heading  " style="word-wrap: break-word;">{{ ucfirst($att->title) }}</h3>
    <!-- opis   -->
    <div class="mt-4" style=" box-sizing: border-box; word-wrap: break-word; overflow: hidden;">
        <p style="max-width: 100%; margin: 0;word-wrap: break-word;">{{$att->desc}}</p>
    </div>
    <!-- KONIEC -->
    </div>
    <div class="col-4 " style="border-left: 3px solid darkgrey" >
        <!-- TYTUŁ  -->
        <h3 class="ra-heading ">Szczegóły</h3>
        <!-- opis   -->
        <div class="mt-4" style=" box-sizing: border-box;  word-wrap: break-word; ">
           <h5> <p style="float: left;">Cena atrakcji:</p> <p style="float: right;margin-right: 10%;">{{$att->cost}}zł</p> </h5>
        </div>
        <!-- KONIEC -->
    </div>
    @if($att->mark != null)
    <div class="col-4 " style="border-left: 3px solid darkgrey" >
    <!-- TYTUŁ  -->
        <h3 class="ra-heading ">Lokalizacja</h3>
    <!-- opis   -->
        <div class="mt-4 " style=" box-sizing: border-box;word-wrap: break-word; overflow: hidden;">
            <h5> <p style="clear:both;float: left;">Nazwa lokalizacji:</p><p style="float: right;margin-right: 10%;">{{$att->mark->name}}</p> </h5>
@if($att->mark->queue != null)
            <h5> <p style="clear:both;float: left;">przystanek nr:</p> <p style="float: right;margin-right: 10%;">{{$att->mark->queue}}</p> </h5>
@endif
            <h5> <p style="clear:both;float: left;">opis:</p> <p style="float: right;margin-right: 10%;">{{$att->mark->desc}}</p> </h5>
            <h5> <p style="clear:both;float: left;">adres:</p> <p style="float: right;margin-right: 10%;">{{$att->mark->address}}</p> </h5>
        </div>
    <!-- KONIEC -->
    </div>
    @endif
</div>
                @if($permission == 1)
                <!-- BUTTONY <3 -->
                <div class="text-center">
                    <form class="d-inline" action="{{ route('editAttraction') }}" method="post">
                        @csrf
                        <input type="hidden" name="attractionId" value="{{ $att->attraction_id }}">
                        <button style="font-size: 3vh;" class="btn btn-info " type="submit">
                            <i class="far fa-edit mx-3"></i>
                        </button>
                    </form>
                    <button style="font-size: 3vh;color:black;" class="btn btn-danger  " type="button" onclick="delAttraction({{ $att->attraction_id }})">
                        <i class="far fa-trash-alt mx-3"></i>
                    </button>
                    <button style="font-size: 3vh;color:black;" class="btn btn-warning  " type="button" onclick="moveAttractionForm({{ $att->attraction_id }})">
                        <i class="fas fa-list mx-3"></i>
                    </button>
                        @include('components.moveAttractionComponents', ['name' => $att->attraction_id,'post_id'=> $post->post_id,'attraction_id'=>$att->attraction_id])
                </div>
                @endif

</div>
        </div>
    </section>
@endif
@endforeach
    @if($permission == 0)
<br>
    @endif

    @if($permission == 1)

<!--         POST TU SIE ZACZYNA -->
<section class="uia-timeline__group">
<div class="text-center  uia-card" style="background-color: inherit;">
    <form action="{{route('Attraction')}}" method="post">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->post_id }}">
        <button class="dash_bttn tablinks" type="submit">Dodaj wpis</button>
    </form>
</div>
</section>
    @endif
<!--         POST TU SIE KOŃCZY  -->


</div>
</div>

</div>
</div>
    @endforeach

</div>

    <script>

        function moveAttractionForm(name){
            showForm('attraction_move_'+name);
        }

        function delAttraction(attractionId) {
            var potwierdzenie = confirm('Czy na pewno chcesz usunąć te atrakcję ?');
            if(potwierdzenie){
                axios.post('{{ route("delAttraction") }}', {
                    attractionId: attractionId,
                })
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

        }

        function delPost(postId) {
            var potwierdzenie = confirm('Czy na pewno chcesz usunać post wraz z jego atrakcjami ? ');
            if(potwierdzenie){
                axios.post('{{ route("delPost") }}', {
                    postid : postId,
                })
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

        }

        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('AttractionEvent', (e) => {
                $("#postlist").load(location.href + " #postlist");
            });

        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('DelPostEvent', (e) => {
                console.log('Usunięto Post ');
                $("#postlist").load(location.href + " #postlist");
            });
        Echo.private('privateTrip.{{$trip->trip_id}}')
            .listen('AddPostEvent', (e) => {
              console.log('DODANOW NOWY POST ! ');
                $("#postlist").load(location.href + " #postlist");
            });
    </script>
</div>
