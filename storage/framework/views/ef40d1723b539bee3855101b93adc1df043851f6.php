<?php if(count($errors) > 0): ?>
    <div class="chatter-alert alert alert-danger">
        <div class="container">
            <p><strong><i class="chatter-alert-danger"></i></strong> Please fix the following errors:</p>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
<?php endif; ?>