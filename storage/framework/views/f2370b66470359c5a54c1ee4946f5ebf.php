<?php $__env->startSection('styles'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link href="<?php echo e(asset('css/map_creator.css')); ?>" rel="stylesheet">
    <!-- Stylizacja Leaflet -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet/dist/leaflet.css')); ?>"/>
    <!-- Stylizacja Leaflet Control Geocoder -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.css')); ?>"/>
    <!-- Stylizacja Leaflet Routing Machine -->
    <link rel="stylesheet" href="<?php echo e(asset('leaflet-routing-machine/dist/leaflet-routing-machine.css')); ?>"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>

<style>
    .leaflet-routing-alt table {
        display: none; /* lub użyj 'visibility: hidden;' jeśli chcesz zachować miejsce dla tego elementu, ale go ukryć */
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
    <!-- Skrypt Leaflet -->
    <script src="<?php echo e(asset('leaflet/dist/leaflet.js')); ?>"></script>
    
    <script src="<?php echo e(asset('leaflet-control-geocoder/dist/Control.Geocoder.js')); ?>"></script>
    
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <!-- MAPA -->
    <script>
        var map = L.map('map', {
            zoomControl: false, // Wyłącz domyślną kontrolę przybliżania
            attributionControl: false,
            doubleClickZoom: false,
            addWaypointsMode: 'Disabled',
        }).setView([52.237049, 21.017532], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            zoomControl: false,
        }).addTo(map);


        // MARKERY Z BAZY DANYCH
        var markersData = <?php echo json_encode($markerData); ?>; // Konwertuje dane PHP na dane JavaScript
        var waypoints = [];

        markersData.forEach(function (marker) {
            if(marker.queue != null){
                var waypoint = L.latLng(marker.latitude, marker.longitude, marker.queue);
                waypoints.push(waypoint);
            }
        });



        var routingControl = L.Routing.control({
            waypoints: waypoints,
            routeWhileDragging: true,
            draggableWaypoints: false,
            addWaypoints: false,
            showAlternatives: false,
            lineOptions: {
                styles: [{ color: 'blue', opacity: 1, weight: 3 }]
            },
            formatter: new L.Routing.Formatter({ units: 'metric', language: 'pl' }),
        }).addTo(map);





        function addWaypoint(mark) {
            var latLng = L.latLng(mark.latitude, mark.longitude, mark.queue);

            // Sprawdź, czy waypoint już istnieje w tablicy
            var existingWaypoint = waypoints.find(function (wp) {
                return wp.lat === latLng.lat && wp.lng === latLng.lng;
            });

            if (existingWaypoint) {
                // Aktualizuj wartość queue dla istniejącego waypointa
                existingWaypoint.alt = mark.queue;

                // Posortuj tablicę po aktualizacji alt
                waypoints.sort((a, b) => a.alt - b.alt);

            } else {
                // Dodaj nowy waypoint, jeśli nie istnieje
                waypoints.push(latLng);

                // Posortuj tablicę po dodaniu nowego waypointa
                waypoints.sort((a, b) => a.alt - b.alt);
                console.log('Dodano nowy waypoint:', waypoints);
            }

            // Aktualizuj trasę po zmianie waypointów
            routingControl.setWaypoints(waypoints);
        }

        // ... (reszta twojego kodu)


        function delWaypoint(mark) {
            var indexToRemove = -1;
            for (var i = 0; i < waypoints.length; i++) {
                var waypoint = waypoints[i];

                if (waypoints[i]['lat'] == mark.latitude && waypoints[i]['lng'] == mark.longitude) {
                    indexToRemove = i;
                    break;
                }
            }
            if (indexToRemove !== -1) {
                waypoints.splice(indexToRemove, 1); // Usuń waypoint z tablicy
                routingControl.setWaypoints(waypoints); // Zaktualizuj trasę
                console.info('AKTUALIZACJA TRASY <3 ');
                console.log(waypoints);

            }
        }


        routingControl.on('routeselected', function (e) {
            var route = e.route;
            var totalDistance = route.summary.totalDistance / 1000;
            var totalDuration = route.summary.totalTime;

            var totalDurationHours = totalDuration / 3600;

            // Convert totalDuration to hours and minutes
            var hours = Math.floor(totalDurationHours);
            var minutes = Math.floor((totalDurationHours - hours) * 60);
            var formattedTime = hours + ":" + (minutes < 10 ? '0' : '') + minutes;

            // Przykładowe dane o samochodzie
            var fuelConsumption = 5; // spalanie w litrach na 100 km
            var fuelCostPerLiter = 6.70; // koszt paliwa za litr

            // Obliczenia dotyczące paliwa
            var fuelConsumed = (totalDistance / 100) * fuelConsumption; // ilość zużytego paliwa w litrach
            var fuelCost = fuelConsumed * fuelCostPerLiter; // koszt podróży związany z paliwem

            // Obliczenia dodatkowe
            var averageSpeed = totalDistance / totalDurationHours; // średnia prędkość podróży
            var carbonEmission = fuelConsumed * 2.3; // przyjęte przybliżone emitowanie CO2 w kg na litr paliwa

            // Szacowany czas przyjazdu (ETA)
            var now = new Date();
            var eta = new Date(now.getTime() + totalDuration * 1000); // czas przyjazdu w milisekundach od początku epoki

            // Ilość przystanków
            var stopsCount = waypoints.length - 2; // odejmujemy start i cel, ponieważ są one również uwzględnione jako punkty

            // Obliczenia odległości między waypointami
            var distancesBetweenWaypoints = [];
            for (var i = 0; i < waypoints.length - 1; i++) {
                var distanceBetweenPoints = waypoints[i].distanceTo(waypoints[i + 1]) / 1000; // distanceTo zwraca odległość w metrach
                distancesBetweenWaypoints.push(distanceBetweenPoints);
            }


            console.log('Sumaryczny dystans: ' + totalDistance.toFixed(2) + ' km');
            console.log('Sumaryczny czas: ' + hours + ' godzin ' + minutes + ' minut');
            console.log('Średnia prędkość podróży: ' + averageSpeed.toFixed(2) + ' km/h');
            console.log('Średnia prędkość podróży: ' +  Math.ceil(averageSpeed) + ' km/h');
            console.log('Ilość zużytego paliwa: ' + fuelConsumed.toFixed(2) + ' litrów');
            console.log('Koszt podróży (paliwo): ' + fuelCost.toFixed(2) + ' PLN');
            console.log('Ilość emisji CO2: ' + carbonEmission.toFixed(2) + ' kg');
            console.log('Szacowany czas przyjazdu (ETA): ' + eta.toLocaleTimeString());
            console.log('Ilość przystanków: ' + stopsCount);
            console.log('Odległości między waypointami: ' + distancesBetweenWaypoints.join(' km, ') + ' km');



            axios.post("<?php echo e(route('AddRouteData')); ?>", {
                'travel_time' : formattedTime,
                'distance' : totalDistance.toFixed(2),
                'avg_speed' :  Math.ceil(averageSpeed),
                'fuel_consumed' :  Math.ceil(fuelConsumed),
            }, {
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                }
            })
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
                    if (error.response && error.response.data) {
                        console.log(error.response.data);
                    } else if (error.request) {
                        console.log(error.request);
                    } else {
                        console.log('Error', error.message);
                    }
                });

        });

        //dodawnaie markerów do mapy <3
        var markers = markersData.map(function(marker) {
            var newMarker = L.marker([marker.latitude, marker.longitude]).addTo(map);
            newMarker.bindPopup("<b>" + marker.name + "</b><br>" + marker.address);
            return newMarker;
        });


        Echo.private('privateTrip.<?php echo e($trip->trip_id); ?>')
            .listen('TripEvent', (e) => {
                addMarker(e.mark);
            });

        Echo.private('privateTrip.<?php echo e($trip->trip_id); ?>')
            .listen('AddQueueEvent', (e) => {
                const mark = e.mark;
                addWaypoint(mark);
            });
        Echo.private('privateTrip.<?php echo e($trip->trip_id); ?>')
            .listen('DelQueueEvent', (e) => {
                const mark = e.mark;
                delWaypoint(mark)
            });
    </script>
    <script src="<?php echo e(asset('js/map_creator.js')); ?>" ></script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <?php if(session('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>

   <p id="trip_title"><?php echo e($trip->title); ?></p>






    <div id="map"></div>
    

<?php if($trip->owner_id === Auth::user()->user_id): ?>
        <div class="map_bar container">
                        <div id="" >
                            <button class="button-perspective"  onclick="showForm('tactic')">Dodaj Taktyka</button>
                            <button class="button-perspective"  onclick="showForm('tactic_list');reload()">Lista Taktyków</button>
                            <button class="button-perspective"  onclick="showForm('shareTrip')">Udostępnij</button>
                            <button class="button-perspective"  onclick="showForm('setting')">Ustawienia</button>
                        </div>
            <?php echo $__env->make('components.AddUserComponents', ['name' => 'tactic','title' => 'Dodaj Taktyka'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('components.UserListComponents', ['name' => 'tactic_list','title' => 'Lista Taktyków'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('components.AddPublicComponents', ['name' => 'shareTrip','title' => 'Udostępnij Podróż'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('components.TripSettingsComponents', ['name' => 'setting','title' => 'Ustawienia Podróży'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


        </div>
<?php endif; ?>

<?php if($trip->publicTrip != null && $trip->owner_id != Auth::user()->user_id): ?>
    <div class="map_bar container d-flex justify-content-center align-items-center mb-4" >
        <div class="text-center">
            <button class="button-perspective" style="width: 90%" onclick="showForm('copy')">Skopiuj Niezapomniane Chwile</button>
        </div>
    </div>
    <?php echo $__env->make('components.CopyTripComponents', ['name' => 'copy','title' => 'Skopiuj podróż'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>


    <?php echo $__env->make('components.AddPostComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.AddVehicleComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.AddPersonsNumberComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.AddFuelComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.creatorComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="dashboard">
        <div class="dash_menu">
            <div class="outer">
            <button class="dash_bttn tablinks active"   data-tab="markers" onclick="change(event, 'markers')"><i class='fas fa-map-marker-alt' ></i> Lokalizacje</button>
            <button class="dash_bttn tablinks active" data-tab="routes" onclick="change(event, 'routes')"><i class='fas fa-route' ></i> Trasowanie</button>
            <button class="dash_bttn tablinks active"   data-tab="posts" onclick="change(event, 'posts')"><i class='fas fa-clipboard-list' ></i> Wydarzenia</button>
            <button class="dash_bttn tablinks active"   data-tab="posts" onclick="change(event, 'info')"><i class='fas fa-flag-checkered'></i> Podsumowanie</button>

            </div>
        </div>

        <div class="dash_content tabcontent" id="markers" >

                <?php echo $__env->make('components.markerComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="dash_content tabcontent" id="routes" >
                <?php echo $__env->make('components.routeComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="dash_content tabcontent" id="posts"  >
            <?php echo $__env->make('components.postComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="dash_content tabcontent" id="info"  >
            <?php echo $__env->make('components.infoComponents', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

    </div>

<script>
        function RefreshDivs(divIds) {
            divIds.forEach(divId => {
                const divToRefresh = document.getElementById(divId);
                if (divToRefresh) {
                    fetch(location.href)
                        .then(response => response.text())
                        .then(data => {
                            const parser = new DOMParser();
                            const newDocument = parser.parseFromString(data, 'text/html');
                            const newContent = newDocument.getElementById(divId);
                            if (newContent) {
                                divToRefresh.innerHTML = '';
                                divToRefresh.innerHTML = newContent.innerHTML;
                                start();
                                console.info(`udał się zaktualizować zawartość ${divId}`);
                            } else {
                                console.error(`Brak div o id "${divId}" w nowej zawartości.`);
                            }
                        })
                        .catch(error => {
                            console.error('Błąd podczas pobierania zawartości: ', error);
                        });
                } else {
                    console.error(`Brak div o id "${divId}" na obecnej stronie.`);
                }
            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            // Po załadowaniu strony wywołaj funkcję change
            var urlParams = new URLSearchParams(window.location.search);
            var tabType = urlParams.get('tab') || 'markers';
            change(null, tabType);
        });

        function change(evt, tabType) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active"); // Usuń klasę "active" ze wszystkich przycisków
            }
            var activeButton = document.querySelector('button[data-tab="' + tabType + '"]');
            if (activeButton) {
                activeButton.classList.add("active");
            }
            document.getElementById(tabType).style.display = "block";
            if (evt) {
                evt.currentTarget.classList.add("active");
            }

            history.pushState(null, null, '?tab=' + tabType);
        }
</script>



<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/trip_creator.blade.php ENDPATH**/ ?>