<?php $__env->startSection('styles'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<link href="<?php echo e(asset('css/map_creator.css')); ?>" rel="stylesheet">
    <!-- Stylizacja Leaflet -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet/dist/leaflet.css')); ?>"/>
    <!-- Stylizacja Leaflet Control Geocoder -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.css')); ?>"/>
    <!-- Stylizacja Leaflet Routing Machine -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-routing-machine/dist/leaflet-routing-machine.css')); ?>"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="<?php echo e(asset('js/app.js')); ?>"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    main{
        height: 10500px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>






    <?php if(session('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>



    <div id="map"></div>
        <div class="map_bar">
                        <div>
                            <button class="button-perspective" onclick="showForm('button1-form')">DODAJ PUNKT</button>
                        </div>
                        <div id="koniec_" >
                            <button class="button-perspective"  onclick="showForm()">Punkt Końcowy</button>
                        </div>

        </div>

    <?php echo $__env->make('components/creatorComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>





    <div class="dashboard">
        <div class="dash_menu">
            <div class="outer">
            <button class="dash_bttn tablinks" onclick="change(event, 'markers')"><i class="material-icons">location_on</i> Markery</button>
            <button class="dash_bttn tablinks" onclick="change(event, 'routes')"><i class="material-icons">location_on</i> Trasowanie</button>
            <button class="dash_bttn tablinks" onclick="change(event, 'test')"><i class="material-icons">location_on</i> Markery</button>
            <button class="dash_bttn tablinks" onclick="change(event, 'test1')"><i class="material-icons">location_on</i> Markery</button>

            </div>
        </div>
        <div class="dash_content tabcontent" id="markers" >
                <?php echo $__env->make('components.markerComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="dash_content tabcontent" id="routes" >
        TRASOWANIE
        </div>
        <div class="dash_content tabcontent" id="test" >
        cOŚ TAM
        </div>
        <div class="dash_content tabcontent" id="test1" >
         POW POW POW
        </div>
    </div>

    <script>
        function change(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>


    <?php $__env->startSection('scripts'); ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
        <!-- Skrypt Leaflet -->
        <script src="<?php echo e(asset('leaflet/dist/leaflet.js')); ?>"></script>

        <script src="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.js')); ?>"></script>

        <script src="<?php echo e(asset('leaflet-routing-machine/dist/leaflet-routing-machine.js')); ?>"></script>
        <!-- MAPA -->
        <script>
            var map = L.map('map').setView([52.237049, 21.017532], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            }).addTo(map);

                // MARKERY Z BAZY DANYCH
                var markersData = <?php echo json_encode($markerData); ?>; // Konwertuje dane PHP na dane JavaScript
                var markers = markersData.map(function(marker) {
                    var newMarker = L.marker([marker.latitude, marker.longitude]).addTo(map);
                    newMarker.bindPopup("<b>" + marker.name + "</b><br>" + marker.address);
                    return newMarker;
                });


                Echo.private('privateTrip.<?php echo e($trip->trip_id); ?>')
                .listen('TripEvent', (e) => {
                addMarker(e.mark);
            });




        </script>
        <script src="<?php echo e(asset('js/map_creator.js')); ?>" ></script>

    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/trip_creator.blade.php ENDPATH**/ ?>