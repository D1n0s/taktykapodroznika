<style>


    :where(.ra-link){
        display: var(--ra-link-display, inline-flex);
    }

    :where(.ra-link[href]){
        color: var(--ra-link-color, inherit);
        text-decoration: var(--ra-link-text-decoration, none);
    }

    :where(.ra-heading){
        margin-block-start: var(--ra-heading-margin-block-start, 0);
        margin-block-end: var(--ra-heading-margin-block-end, 0);
    }



    /*
    =====
    UIA-TIMELINE
    =====
    */

    .uia-timeline__container{
        display: var(--uia-timeline-display, grid);
    }

    .uia-timeline__groups{
        display: var(--uia-timeline-groups-display, grid);;
        gap: var(--uia-timeline-groups-gap, 1rem);
    }

    /*
    SKIN 1
    */

    [data-uia-timeline-skin="skin-1"] .uia-timeline__step{
        line-height: 1;
        font-size: var(--uia-timeline-step-font-size, 2rem);
        font-weight: var(--uia-timeline-step-font-weight, 700);
        color: var(--uia-timeline-step-color);
    }

    [data-uia-timeline-skin="skin-1"] .uia-timeline__point-intro{
        display: grid;
        grid-template-columns: min-content 1fr;
        align-items: center;
        gap: var(--uia-timeline-point-intro-gap, .5rem);
    }

    [data-uia-timeline-skin="skin-1"] .uia-timeline__point-date{
        grid-row: 1;
        grid-column: 2;
    }

    [data-uia-timeline-skin="skin-1"] .uia-timeline__point-heading{
        grid-column: span 2;
    }

    [data-uia-timeline-skin="skin-1"] .uia-timeline__point-description{
        margin-block-start: var(--uia-timeline-group-gap, 1rem);
        width: min(100%, var(--uia-timeline-content-max-width));
    }

    /*
    SKIN 2
    */

    [data-uia-timeline-skin="2"]{
        --_uia-timeline-line-color_default: #222;
        --_uia-timeline-minimal-gap: var(--uia-timeline-minimal-gap, .5rem);
        --_uia-timeline-space: calc(var(--_uia-timeline-arrow-size) + var(--_uia-timeline-dot-size) + var(--_uia-timeline-dot-size) / 2 + var(--_uia-timeline-minimal-gap));
        --_uia-timeline-dot-size: var(--uia-timeline-dot-size, 1rem);
        --_uia-timeline-arrow-size: var(--uia-timeline-arrow-size, .25rem);
        --_uia-timeline-arrow-position: var(--uia-timeline-arrow-position, 1rem);
    }

    [data-uia-timeline-skin="2"] .uia-timeline__container{
        position: relative;
        padding-inline-start: calc(var(--_uia-timeline-space));
    }

    [data-uia-timeline-skin="2"] .uia-timeline__line{
        width: var(--uia-timeline-line-width, 3px);
        height: 100%;
        background-color: var(--uia-timeline-line-color, var(--_uia-timeline-line-color_default));

        position: absolute;
        inset-block-start: 0;
        inset-inline-start: calc(var(--_uia-timeline-dot-size) / 2);
        transform: translateX(-50%);
    }

    [data-uia-timeline-skin="2"] .uia-timeline__group{
        position: relative;
    }

    [data-uia-timeline-skin="2"] .uia-timeline__dot{
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

    [data-uia-timeline-skin="2"] .uia-timeline__point{
        position: relative;
        background-color: var(--uia-timeline-point-background-color, #fff);
    }

    [data-uia-timeline-skin="2"] .uia-timeline__point::before{
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

    [data-uia-timeline-adapter-skin-2="ui-card-skin-#1"]{
        --uia-card-padding: var(--uia-timeline-point-padding, 1.5rem 1.5rem 1.25rem);
        --uia-card-border-left:  var(--uia-timeline-point-border-width, 3px) var(--uia-timeline-point-border-style, solid) var(--uia-timeline-point-border-color, var(--_uia-timeline-line-color_default));
        --uia-card-background-color: var(--uia-timeline-point-background-color);
    }

    /*
    SKIN 3
    */

    [data-uia-timeline-skin="3"]{
        --_uia-timeline-line-color_default: #222;
        --_uia-timeline-space: var(--uia-timeline-space, 2rem);
        --_uia-timeline-point-line-position: var(--uia-timeline-point-line-position, 50%);
        --_uia-timeline-point-line-thickness: var(--uia-timeline-point-line-thickness, 2px);
    }

    [data-uia-timeline-skin="3"] .uia-timeline__container{
        position: relative;
        gap: var(--uia-timeline-annual-sections-gap, 2.5rem);
    }

    [data-uia-timeline-skin="3"] .uia-timeline__line{
        width: var(--uia-timeline-line-width, 3px);
        height: 100%;
        background-color: var(--uia-timeline-line-color, var(--_uia-timeline-line-color_default));

        position: absolute;
        inset-block-start: 0;
        inset-inline-start: 0;
    }

    [data-uia-timeline-skin="3"] .uia-timeline__annual-sections{
        display: grid;
        gap: 2rem;
    }

    [data-uia-timeline-skin="3"] .uia-timeline__groups{
        display: grid;
        gap: var(--uia-timeline-groups-gap, 2rem);
        padding-inline-start: calc(var(--_uia-timeline-space));
    }

    [data-uia-timeline-skin="3"] .uia-timeline__group{
        position: relative;
        isolation: isolate;
    }

    [data-uia-timeline-skin="3"] .uia-timeline__point{
        position: relative;
        background-color: var(--uia-timeline-point-background-color, #fff);
    }

    [data-uia-timeline-skin="3"] .uia-timeline__point::before{
        content: "";
        inline-size: 100%;
        block-size: var(--_uia-timeline-point-line-thickness);
        background-color: var(--uia-timeline-line-color, var(--_uia-timeline-line-color_default));

        position: absolute;
        inset-block-start: var(--_uia-timeline-point-line-position);
        inset-inline-start: calc(-1 * var(--_uia-timeline-space));
        z-index: -1;
    }

    [data-uia-timeline-skin="3"] .uia-timeline__year{
        width: fit-content;
        font-size: larger;
        padding: var(--uia-timeline-year-padding, .35rem .95rem);
        background-color: var(--uia-timeline-year-background-color, var(--_uia-timeline-line-color_default));
        color: var(--uia-timeline-year-color, #f0f0f0);
    }

    [data-uia-timeline-adapter-skin-3="ui-card-skin-#1"]{
        --uia-card-padding: var(--uia-timeline-point-padding, 1.5rem 1.5rem 1.25rem);
        --uia-card-border-left:  var(--uia-timeline-point-border-width, 3px) var(--uia-timeline-point-border-style, solid) var(--uia-timeline-point-border-color, var(--_uia-timeline-line-color_default));
        --uia-card-background-color: var(--uia-timeline-point-background-color);
    }

    /*
    =====
    UIA-CARD
    =====
    */

    .uia-card__time-divider::before{
        content: "—";
        margin-inline: var(--uia-card-time-divider-margin-inline, .15rem);
    }

    [data-uia-card-skin="1"] .uia-card__container{
        display: grid;
        gap: .5rem;
        padding: var(--uia-card-padding);

        background-color: var(--uia-card-background-color);
        border-radius: var(--uia-card-border-radius, 2px);
        box-shadow: var(--uia-card-box-shadow, 0 1px 3px 0 rgba(0, 0, 0, .12), 0 1px 2px 0 rgba(0, 0, 0, .24));

        border-block-start: var(--uia-card-border-top, var(--uia-card-border-top-width, 0) var(--uia-card-border-top-style, solid) var(--uia-card-border-top-color));
        border-inline-end: var(--uia-card-border-right, var(--uia-card-border-right-width, 0) var(--uia-card-border-right-style, solid) var(--uia-card-border-right-color));
        border-block-end:	var(--uia-card-border-bottom, var(--uia-card-border-bottom-width, 0) var(--uia-card-border-bottom-style, solid) var(--uia-card-border-bottom-color));
        border-inline-start:	var(--uia-card-border-left, var(--uia-card-border-left-width, 0) var(--uia-card-border-left-style, solid) var(--uia-card-border-left-color));
    }

    [data-uia-card-skin="1"] .uia-card__intro{
        display: grid;
        gap: var(--uia-card-intro-gap, 1rem);
    }

    [data-uia-card-skin="1"] .uia-card__time{
        grid-row: 1 / 1;
        width: fit-content;
        padding: var(--uia-card-time-padding, .25rem 1.25rem .25rem);
        background-color: var(--uia-card-time-background-color, #f0f0f0);

        font-weight: var(--uia-card-time-font-weight, 700);
        font-size: var(--uia-card-time-font-size, 1.0rem);
        text-transform: var(--uia-card-time-text-transform, uppercase);
        color: var(--uia-card-time-color, currentColor);
    }


    :root{
        --uia-timeline-line-color: #4557bb;
        --uia-timeline-dot-color: #4557bb;
        --uia-timeline-arrow-color: #4557bb;
        --uia-timeline-point-line-thickness: 5px;
        --uia-timeline-point-border-color: #4557bb;
        --uia-timeline-group-padding: 1.5rem 1.5rem 1.25rem;
        --uia-timeline-year-background-color: #4557bb;
    }


    .page{
        max-width: 95%;
    }

</style>
<div class="box">
    <div class="first">
        <button type="button" onclick="showForm('addPost')"  >ADD POST</button>
        @include('components/modalWindow')
        @section('modal.name', 'addPost')
        @section('modal.title') UTWÓRZ NOWY POST @endsection
        @section('modal.content')
            <form method="post" id="tripstartForm" action="{{ route('addPost') }}">
                @csrf
                <div class="form-group">
                    <label for="title">Tytuł*:</label>
                    <input type="text" id="tile" name="title" class="form-control"  maxlength="30"  placeholder="np. Dzień 1 || Lokalizacja" required>
                    <br>
                    <label for="date">Wybierz dzień:</label>
                    <input class="form-control" type="date" name="date" id="date" min="{{$trip->start_date}}" max="{{$trip->end_date}}" >
                    <button type="submit">WYŚLIJ TO GÓWNO</button>

                </div>
        @endsection
    </div>

        <br><br>

    @foreach( $posts as $post)
    <div class="page secound" data-uia-timeline-skin="3" data-uia-timeline-adapter-skin-3="ui-card-skin-#1">
        <div class="uia-timeline">
            <div class="uia-timeline__container">
                <div class="uia-timeline__line"></div>
                <div class="uia-timeline__annual-sections">
                    <span class="uia-timeline__year" aria-hidden="true">
                        @if($post->date != null) Dzień {{$post->day}}  ( {{date('d-m-Y', strtotime($post->date))}} ) || {{$post->title}}@endif
                            @if($post->date == null && $post->day == null){{$post->title}} @endif
                     </span>


                     <div class="uia-timeline__groups">
                         <!--         POST TU SIE ZACZYNA -->
                         <section class="uia-timeline__group" >
                             <div class="uia-timeline__point uia-card" data-uia-card-skin="1">
                                 <div class="uia-card__container">
                                     <div class="uia-card__intro">
                                         <span class="uia-card__time">
                                       <span class="uia-card__day">2 February 2008</span>
                                         </span>
<!-- TYTUŁ  -->
                        <h3  class="ra-heading">The part of my life in University of Pennsylvania</h3>
                                     </div>
<!-- opis   -->
             <div class="uia-card__body">
                 <div class="uia-card__description">
                     <p>Attends the Philadelphia Museum School of Industrial Art. Studies design with Alexey Brodovitch, art director at Harper's Bazaar, and works as his assistant.</p>
                 </div>
             </div>
                                 </div>
                             </div>
                         </section>
<!--         POST TU SIE KOŃCZY  -->



                         <!--         POST TU SIE ZACZYNA -->
                         <section class="uia-timeline__group" >
                             <div class="uia-timeline__point uia-card" style="background-color: inherit;">
                                 <button type="button" onclick="showForm('add')"  >ADD POST</button>

                             </div>
                         </section>
                         <!--         POST TU SIE KOŃCZY  -->

                     </div>
                 </div>

             </div>
         </div>
     </div>

     @endforeach



 </div>
