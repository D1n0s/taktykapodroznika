<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('css/map_creator.css')); ?>" rel="stylesheet">
    <!-- Stylizacja Leaflet -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet/dist/leaflet.css')); ?>"/>
    <!-- Stylizacja Leaflet Control Geocoder -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.css')); ?>"/>
    <!-- Stylizacja Leaflet Routing Machine -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-routing-machine/dist/leaflet-routing-machine.css')); ?>"/>
<script src="<?php echo e(asset('js/app.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>





<div style="background-color: white">
    <h3> TO JEST WIADOMOŚĆ PRYWATNA ! </h3>
    <input id="txt_priv" type="text" value="nothing">
    <button class="button-perspective" onclick="sendpriv()">WYŚLIJ PRYWATNIE</button>
    <div id="response_priv" class="p-6 bg-white border-b border-gray-200">
        ----------NOTHING----------
    </div>
</div>
<script>


    window.onload = sendpriv;

        function sendpriv() {
        let messagepriv = document.getElementById('txt_priv')

        axios.post("<?php echo e(route('trip.event')); ?>", {
                trip_id: <?php echo e($trip->trip_id); ?>,
                message: messagepriv.value
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            }
        })

    }

    Echo.private('privateTrip.<?php echo e($trip->trip_id); ?>')
        .listen('TripEvent', (e) => {
            document.getElementById('message').innerText = e.message+ " " +  e.mark.name + " " + e.mark.desc;
            document.getElementById('response_priv').innerText = e.message + "NOWA WESJA";
            addMarker(e.mark);
        });


</script>

<br><br><br>



<div style="background-color: white">
    <h3> TO JEST WIADOMOŚĆ PUBLICZNA ! </h3>
    <input id="txt_pub" type="text">
    <button class="button-perspective" onclick="sendpub()">WYŚLIJ PRYWATNIE</button>
    <div id="response_pub" class="p-6 bg-white border-b border-gray-200">
        ----------NOTHING----------
    </div>
</div>
<script>
    function sendpub() {
        let messagepub = document.getElementById('txt_pub');

        axios.post("<?php echo e(route('fire.public.event')); ?>", {
            color: messagepub.value
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8',
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            }
        })



    }

    Echo.channel('public')
        .listen('PublicEvent', (e) => {
            document.getElementById('response_pub').innerText = e.color;

        });
</script>

















<br><br><br><br>
<div style="background-color: white;">
    <p id="message">NIC TU NIE MA</p>
    <p><?php echo e(auth()->user()->user_id); ?></p>
</div>

<script>
    Echo.private('private.')
        .listen('PrivateEvent', (e) => document.getElementById('message').innerText = e.message);
</script>








    <?php if(session('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

        <p id="title">Tytuł: <?php echo e($trip->title); ?> <?php echo e($trip->trip_id); ?></p>
        <p  id="title">Opis: <?php echo e($trip->desc); ?></p>

    <div id="map"></div>
        <div class="map_bar">
                        <div>
                            <button class="button-perspective" onclick="showForm('button1-form')">Punkt Startowy</button>
                        </div>
                        <div id="koniec_" >
                            <button class="button-perspective"  onclick="showForm()">Punkt Końcowy</button>
                        </div>

        </div>

    <?php echo $__env->make('components/creatorComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



    <div id="button1-form" style="display: none;" class="form-overlay">
        <div class="form-container">
            <h4>Formularz</h4>
            <form>
                <div class="mb-3">
                    <label for="start">Miejsce Startowe:</label>
                    <input type="text" class="form-control" id="start" name="start">
                </div>
                <button type="button" class="btn btn-primary" onclick="saveForm()">Zapisz</button>
                <button type="button" class="btn btn-secondary" onclick="hideForm('button1-form')">Anuluj</button>
            </form>
        </div>
    </div>

    <?php if(isset($data)): ?>
        <h1>Dane:</h1>
        <p>Name: <?php echo e($data['name']); ?></p>
        <p>Description: <?php echo e($data['desc']); ?></p>
        <p>Address: <?php echo e($data['address']); ?></p>
        <p>Latitude: <?php echo e($data['latitude']); ?></p>
        <p>Longitude: <?php echo e($data['longitude']); ?></p>
    <?php endif; ?>



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




        </script>
        <script src="<?php echo e(asset('js/map_creator.js')); ?>" ></script>

    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/trip_creator.blade.php ENDPATH**/ ?>