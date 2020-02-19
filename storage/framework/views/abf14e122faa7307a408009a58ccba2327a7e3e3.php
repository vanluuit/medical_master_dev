
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Category event</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List category</h5>
                    <a href="<?php echo e(route('categoryevents.create')); ?>" class="btn btn-info btn-sm float-right">Add category</a>
                    <a href="<?php echo e(route('categoryevents.namesearch')); ?>?seminar_id=<?php echo e(request()->seminar_id); ?>" class="btn btn-info btn-sm float-right mg-r15">Set Color</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>小カテゴリー</th>
                          <th>Color</th>
                          <th style="width:190px">行動</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($categories): ?>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <input type="hidden" name="soft[]" class="soft_id" value="<?php echo e($category->id); ?>">
                            <td><?php echo e($category->id); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td><?php echo e($category->color); ?></td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('categoryevents.edit',$category->id)); ?>">Edit</a>
                              <?php if($category->id > 0): ?> 
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('categoryevents.delete',$category->id)); ?>">Delete</a>
                              <?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($categories->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php $__env->startSection('script'); ?>
<script>

</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>