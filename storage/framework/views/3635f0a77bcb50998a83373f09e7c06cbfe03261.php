
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
                    <h5 class="card-title m-b-0 float-left">List category search name</h5>
                    <a href="<?php echo e(route('categoryevents.index')); ?>?seminar_id=<?php echo e(request()->seminar_id); ?>" class="btn btn-info btn-sm float-right">List category</a>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>小カテゴリー</th>
                          <th>Color</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($categories): ?>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <input type="hidden" name="soft[]" class="soft_id" value="<?php echo e($category->id); ?>">
                            <td><?php echo e($category->id); ?></td>
                            <td><?php echo e($category->name); ?></td>
                            <td>
                              <input type="text" name="color" id="color_<?php echo e($category->id); ?>" value="<?php echo e($category->color); ?>">
                              <button class="btn btn-cyan btn-sm setcolor" data_for="#color_<?php echo e($category->id); ?>" data_id="<?php echo e($category->id); ?>">Set</button>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
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

     $(document).on('click','.setcolor', function(){
      var obj = $(this);
      var id = obj.attr('data_id');
      var color = $(obj.attr('data_for')).val();
      color = color.replace("#", "");
      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({url: "<?php echo e(route('categoryevents.setcolor')); ?>?id="+id+'&color='+color, 
        success: function(result){
        if(result == 0){
          alert('The category has been set color');
        }else{
          alert('category has been set color');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>