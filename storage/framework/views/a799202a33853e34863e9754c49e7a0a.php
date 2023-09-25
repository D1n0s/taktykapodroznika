<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center" style="background-color: white;">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.js" defer></script>

                <style>

                .pagination {
                    text-align: center;
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
            </style>




            <!-- resources/views/mytrips.blade.php -->
            <h1>Moje podróże</h1>


            <!-- Wyświetlanie danych podróży -->
            <table class="table">
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
                    <tr>
                        <td><?php echo e($trip->title); ?></td>
                        <td><?php echo e($trip->desc); ?></td>
                        <td><?php echo e($trip->start_date); ?></td>
                        <td><?php echo e($trip->end_date); ?></td>
                        <td><button class="button-perspective">witom</button></td>

                    </tr>
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







<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/myTrips.blade.php ENDPATH**/ ?>