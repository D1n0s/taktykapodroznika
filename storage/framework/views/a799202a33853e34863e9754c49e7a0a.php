<?php $__env->startSection('styles'); ?>
    <link href="<?php echo e(asset('css/buttons.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&display=swap");

        .pagination {
            padding: 2vh;
            border-radius: 8px;
            background-color: rgba(21, 33, 72, 0.85);
            justify-content: center; /* Wyśrodkowanie w poziomie */
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
            width: 30%;
            position: absolute;
            bottom: 1vh;
        }


        .pagination a,
        .pagination .current {
            font-family: "Helvetica Neue", Arial, Helvetica, sans-serif;
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #000000;
            color: #fff;
            text-decoration: #0cde17;
            border-radius: 5px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
        }

        .pagination a:hover {
            background-color: #0056b3;
        }
        .center{
            display: flex;
            justify-content: center; /* Wyśrodkowanie w poziomie */
            align-items: center; /* Wyśrodkowanie w pionie */
        }
        /*MOTION FOR ROW*/
        .pow {
            background-image: linear-gradient(135deg, rgba(74, 205, 222, 0.25), rgba(29, 123, 219, 0.2) 20%, #152148 40%, rgba(21, 33, 72, 0.8) 100%);
            padding-top:5vh;
            padding-bottom: 5vh;
            min-height: 80vh;
            max-height: 120vh;
            color:white;
            padding: 1vh;


        }

        .framed {
        
/*border-image-source: url("https://raw.githubusercontent.com/robole/artifice/main/framed-content/img/frame.png");*/
            border-image:url(<?php echo e(asset('storage/path31.png')); ?>) 1;
            border-image-slice: 85 140 140 310;
            border-image-repeat: stretch;
            border-style: inset;
            border-width: 8vh;
            border-left-width: 20vh;
            border-right-width: 8vh;
            border-top-width: 5vh;
            display: grid;
            min-height: 80vh;
            max-height: 120vh;

            margin: 0 auto;
            margin-left: 27vh;



            overflow: auto;

        }

    </style>
    <?php if(!empty($trips)): ?>
        <div class=" framed container_content " style="padding: 0;">
            <div class="text-center pow"  >
                <!-- resources/views/mytrips.blade.php -->
                <h1>Moje podróże</h1>
                <br> <br><BR>
                <table class="table  text-center text-white" style="vertical-align: middle;">
                    <thead>
                    <tr>
                        <th>Tytuł</th>
                        <th>Data wyjazdu</th>
                        <th>Data przyjazdu</th>
                        <th>AKCJE</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr >
                                <td><?php echo e($trip->title); ?></td>
                                <td><?php echo e($trip->start_date); ?></td>
                                <td><?php echo e($trip->end_date); ?></td>
                                <td class="panel green">
                                    <form action="map/<?php echo e($trip->trip_id); ?>?" method="get">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"  >Wejdź</button>
                                    </form>
                                </td >
                                <td class="panel pink">
                                    <form action="<?php echo e(route('delTrip', ['trip_id' => $trip->trip_id])); ?>" method="post" onsubmit="return confirm('Czy na pewno chcesz usunąć tego tripa?');">
                                        <?php echo csrf_field(); ?>
                                        <span class="panel pink">
                                        <button type="submit" class="panel pink">Usuń</button>
                                        </span>
                                    </form>
                                </td>



                            </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <!-- Paginacja -->
                <div class="center ">
                    <div class="pagination ">
                        <?php if($trips->currentPage() > 1): ?>
                            <a href="<?php echo e($trips->previousPageUrl()); ?>">POPRZEDNIE</a>
                        <?php endif; ?>

                        <?php if($trips->currentPage() > 3): ?>
                            <a href="<?php echo e($trips->url(1)); ?>">1</a>
                            <?php if($trips->currentPage() > 4): ?>
                                <span>...</span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for($i = max(1, $trips->currentPage() - 2); $i <= min($trips->currentPage() + 2, $trips->lastPage()); $i++): ?>
                            <?php if($i == $trips->currentPage()): ?>
                                <span class="current"><?php echo e($i); ?></span>
                            <?php else: ?>
                                <a href="<?php echo e($trips->url($i)); ?>"><?php echo e($i); ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if($trips->currentPage() < $trips->lastPage() - 2): ?>
                            <?php if($trips->currentPage() < $trips->lastPage() - 3): ?>
                                <span>...</span>
                            <?php endif; ?>
                            <a href="<?php echo e($trips->url($trips->lastPage())); ?>"><?php echo e($trips->lastPage()); ?></a>
                        <?php endif; ?>

                        <?php if($trips->currentPage() < $trips->lastPage()): ?>
                            <a href="<?php echo e($trips->nextPageUrl()); ?>">DALEJ</a>
                        <?php endif; ?>
                    </div></div>

            </div>


        </div>

    <?php else: ?>
    <?php endif; ?>







<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/myTrips.blade.php ENDPATH**/ ?>