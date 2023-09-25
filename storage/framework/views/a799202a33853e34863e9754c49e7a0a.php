    <?php $__env->startSection('styles'); ?>
        <link href="<?php echo e(asset('css/buttons.css')); ?>" rel="stylesheet">
    <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <style>             .pagination {
            display: flex;
            justify-content: center; /* Wyśrodkowanie w poziomie */
            align-items: center; /* Wyśrodkowanie w pionie */
        }


        .pagination a,
        .pagination .current {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        /*MOTION FOR ROW*/
        .pow {
    background-color: white;
            padding-top:5vh;
            padding-bottom: 5vh;
            min-height: 80vh;
            max-height: 120vh;
        }

        .framed {
           
            /*border-image-source: url("https://raw.githubusercontent.com/robole/artifice/main/framed-content/img/frame.png");*/
            border-image:url(<?php echo e(asset('storage/path31.png')); ?>) 1;
            border-image-slice: 85 140 140 310;
            border-image-repeat: stretch;
            border-style: inset;
            border-width: 8vh;
            border-left-width: 20vh;
            border-top-width: 5vh;
            display: grid;
            row-gap: 2rem;
            min-height: 80vh;
            max-height: 120vh;

            margin: 0 auto;



            overflow: auto;

        }

        #frame{

        }
    </style>

    <div class=" framed container_content" style="padding: 0;">
        <div class="text-center pow"  >

            <!-- resources/views/mytrips.blade.php -->
            <h1>Moje podróże</h1>
<br> <br>
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Opis</th>
                    <th>Data wyjazdu</th>
                    <th>Data przyjazdu</th>
                    <th>AKCJE</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $trips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<form action="map/<?php echo e($trip->trip_id); ?>?" method="get">
                    <tr >
                        <td><?php echo e($trip->title); ?></td>
                        <td><?php echo e($trip->desc); ?></td>
                        <td><?php echo e($trip->start_date); ?></td>
                        <td><?php echo e($trip->end_date); ?></td>
                        <td class="panel green"><button type="submit" >Wejdź</button></td>
                        <td class="panel pink"><button>Usuń</button></td>

                    </tr>
</form>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <!-- Paginacja -->
            <div class="pagination">
                <?php if($trips->currentPage() > 1): ?>
                    <a href="<?php echo e($trips->previousPageUrl()); ?>">Previous</a>
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
                    <a href="<?php echo e($trips->nextPageUrl()); ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>

    </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/myTrips.blade.php ENDPATH**/ ?>