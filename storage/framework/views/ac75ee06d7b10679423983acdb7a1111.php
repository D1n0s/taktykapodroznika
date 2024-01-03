<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center" >
                <button class="button-perspective" onclick="showForms('start')">Utwórz podróż</button>
            <a href="/mytrips"><button class="button-perspective">Moje podróże</button></a>
            <a href="/sharedtrips"><button class="button-perspective">Udostępnione podróże</button></a>
            <a href="/publictrips"><button class="button-perspective">Publiczne podróże</button></a>
            </div>


            <?php $__env->startSection('modal.content'); ?>
                <style>
                    [type="date"] {
                        background:#fff url(https://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/calendar_2.png)  97% 50% no-repeat ;
                    }
                    [type="date"]::-webkit-inner-spin-button {
                        display: none;
                    }
                    [type="date"]::-webkit-calendar-picker-indicator {
                        opacity: 0;
                    }
                </style>
                <form method="post" id="tripstartForm" action="<?php echo e(route('init')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="title">Tytuł:</label>
                        <input type="text" id="ttile" name="title" class="form-control"  maxlength="30">
                        <label for="startdate">Data startu</label>
                        <input class="form-control" type="date" name="startdate" id="startdate" min="<?php echo e(date('Y-m-d')); ?>" onchange="updateEndDateMin()">

                        <label for="enddate">Data zakończenia</label>
                        <input class="form-control" type="date" name="enddate" id="enddate" min="<?php echo e(date('Y-m-d')); ?>">
                    </div>



                    <script>
                        function updateEndDateMin() {
                            const startDateInput = document.getElementById('startdate');
                            const endDateInput = document.getElementById('enddate');

                            endDateInput.min = startDateInput.value;

                            // Odśwież pole "Data zakończenia" po zmianie daty rozpoczęcia
                            endDateInput.value = ""; // Wyczyść pole
                        }
                    </script>
            <?php $__env->stopSection(); ?>

<?php if(session('success')): ?>

                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
<?php endif; ?>
<?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
            <?php endif; ?>


</form>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('components/modalWindow', ['name'=>'start' , 'title'=>'Utwórz podróż'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/dashboard.blade.php ENDPATH**/ ?>
