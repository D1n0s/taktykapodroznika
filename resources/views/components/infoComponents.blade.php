<div class="box">
<div class="first">

</div>

<br><br>
<div class="secound text-center container" style="background-color: white;" >
    WITOM
</div>
</div>
<script>
    routingControl.on('routeselected', function(e) {
        var route = e.route;
        var totalDistance = route.summary.totalDistance;
        var totalDuration = route.summary.totalTime;

        // Konwertuj czas z sekund na godziny
        var totalDurationHours = totalDuration / 3600;

        // Wyświetl informacje w konsoli
        console.log('Sumaryczny dystans: ' + (totalDistance / 1000) + ' km');
        console.log('Sumaryczny czas: ' + totalDurationHours + ' godzin');
    });
</script>
