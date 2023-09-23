<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('css/map_creator.css')); ?>" rel="stylesheet">
    <!-- Stylizacja Leaflet -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet/dist/leaflet.css')); ?>"/>
    <!-- Stylizacja Leaflet Control Geocoder -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.css')); ?>"/>
    <!-- Stylizacja Leaflet Routing Machine -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-routing-machine/dist/leaflet-routing-machine.css')); ?>"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

        <p id="title">Tytuł: <?php echo e($trip->title); ?></p>
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
        <!-- Skrypt Leaflet Control Geocoder -->
        <script src="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.js')); ?>"></script>
        <!-- Skrypt Leaflet Routing Machine -->
        <script src="<?php echo e(asset('leaflet-routing-machine/dist/leaflet-routing-machine.js')); ?>"></script>
        <script src="<?php echo e(asset('js/map_creator.js')); ?>" ></script>
        <!-- MAPA -->
        <script>
            var map = L.map('map').setView([52.237049, 21.017532], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            }).addTo(map);


            // MARKERY Z BAZY DANYCH
            <?php $__currentLoopData = $markerData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $marker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            var marker = L.marker([<?php echo e($marker['latitude']); ?>, <?php echo e($marker['longitude']); ?>]).addTo(map);
            marker.bindPopup("<b><?php echo e($marker['name']); ?></b><br><?php echo e($marker['address']); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>




        </script>

    <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/trip_creator.blade.php ENDPATH**/ ?>