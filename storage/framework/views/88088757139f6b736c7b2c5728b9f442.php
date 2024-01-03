<?php $__env->startSection('content'); ?>
<div class="container py-5">
<div class="row">

      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body">


            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Imię</p>
              </div>
              <div class="col-2 text-truncat">
                  <?php echo e(Auth::user()->name); ?>


              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nazwisko</p>
              </div>
                <div class="col-2 text-truncat">
                    <?php echo e(Auth::user()->surname); ?>


                </div>
            </div>
            <hr>




            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
                <div class="col-2 text-truncat row">
                    <?php echo e(Auth::user()->email); ?>


                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Numer telefonu</p>
              </div>
                <div class="col-2 text-truncat">
                    <?php echo e(Auth::user()->phone); ?>


                </div>




            </div>


              <hr>
              <a href="/profile_edit" class="btn btn-primary">Edytuj</a>



            </div>

          </div>
          <?php if(session('success')): ?>
              <div class="alert alert-success">
                  <?php echo e(session('success')); ?>

              </div>
          <?php endif; ?>
          <?php if(session('info')): ?>
              <div class="alert alert-info" style="background-color: lightyellow;">
                  <?php echo e(session('info')); ?>

              </div>
          <?php endif; ?>
        </div>

</div>


</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/profile.blade.php ENDPATH**/ ?>