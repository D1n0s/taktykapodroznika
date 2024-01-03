<script src="<?php echo e(asset('js/modalWindow.js')); ?>" ></script>
    <div class="row justify-content-center">
        <div id="<?php echo e($name); ?>" style="display: none;" class="form-overlay">
            <div class="form-container text-center">
                    <h4><?php echo e($title); ?></h4>
                    <?php echo $__env->yieldContent('modal.content'); ?>


                </div>

        </div>

    </div>










<?php /**PATH C:\taktykapodroznika\resources\views/components/modalWindow.blade.php ENDPATH**/ ?>