<script src="<?php echo e(asset('js/modalWindow.js')); ?>" ></script>
    <div class="row justify-content-center">
        <div id="<?php echo $__env->yieldContent('modal.name'); ?>" style="display: none;" class="form-overlay">
            <div class="form-container text-center">
                    <h4><?php echo $__env->yieldContent('modal.title'); ?></h4>
                    <?php echo $__env->yieldContent('modal.content'); ?>

<br />

                    <button type="submit" class="btn btn-primary">zapisz</button>
                    <button type="button" class="btn btn-secondary" onclick="hideForm('<?php echo $__env->yieldContent('modal.name'); ?>')">Anuluj</button>
                </div>

        </div>

    </div>


<!-- usunąć to potem <3 -->
<?php if(session('success')): ?>
    <div class="alert alert-success mt-6">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger mt-6">
        <?php echo e(session('error')); ?>

    </div>
<?php endif; ?>





<?php /**PATH C:\taktykapodroznika\resources\views/components/modalWindow.blade.php ENDPATH**/ ?>