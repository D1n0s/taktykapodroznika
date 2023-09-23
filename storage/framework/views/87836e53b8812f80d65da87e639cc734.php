<?php $__env->startSection('content'); ?>
<div class="container py-5">
<div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
              class="rounded-circle img-fluid" style="width: 150px;">
            <h5 class="my-3"><?php echo e(Auth::user()->login); ?></h5>
            <p class="text-muted mb-1"><?php echo e(Auth::user()->desc); ?></p>
            <div class="d-flex justify-content-center mb-2">
              <button type="button" class="btn btn-outline-primary ms-1">Zmień zdjęcie</button>
            </div>
          </div>
        </div>

      </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
              <form method="get"  action="<?php echo e(route('profile.update')); ?>">
                  <input type="hidden" name="user_id" value="<?php echo e(Auth::user()->user_id); ?>">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Imię</p>
              </div>
                <div class="col-sm-9 ">
                    <input type="text" class="form-control" id="phone" name="name" value="<?php echo e(Auth::user()->name); ?>" />
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nazwisko</p>
              </div>
                <div class="col-sm-9 ">
                    <input type="text" class="form-control" id="phone" name="surname" value="<?php echo e(Auth::user()->surname); ?>" data-inputmask-mask="[9]" />
                </div>
            </div>
            <hr>




            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
                <div class="col-sm-9 ">
                    <input type="text" class="form-control" id="phone" name="email" value="<?php echo e(Auth::user()->email); ?>" data-inputmask-mask="[9]" />
                </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Numer telefonu</p>
              </div>
              <div class="col-sm-9 ">
                  <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(Auth::user()->phone); ?>" data-inputmask-mask="[9]" />
              </div>
            </div>


              <hr>

                  <button type="submit"  class="btn btn-primary border-dark" style="background-color: green;">Zapisz</button>
                  <a type="submit" href="<?php echo e(route('profile.cancel')); ?>"  class="btn btn-primary border-dark" style="background-color: red;margin-left: 1em;">Anuluj</a>

              </form>



            </div>
          </div>
          <?php if($errors->any()): ?>
              <div class="alert alert-danger">
                  <ul>
                      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php echo e($error); ?>

                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
              </div>
          <?php endif; ?>
      </div>
        </div>

</div>


</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\taktykapodroznika\resources\views/profile_edit.blade.php ENDPATH**/ ?>