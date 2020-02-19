
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Associations</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List associations</h5>
                    <a href="<?php echo e(route('associations.create')); ?>" class="btn btn-info btn-sm float-right">Add associations</a>
                    <a href="<?php echo e(route('user.export.download')); ?>" class="btn btn-info btn-sm float-right mg-r15">Export all user</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>学会選択</th>
                          <th>publish</th>
                          <th style="width: 300px">Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($categories): ?>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($category->id); ?></td>
                            <td><a href="<?php echo e(route('members.index')); ?>?category_id=<?php echo e($category->id); ?>"><?php echo e($category->category); ?></a></td>
                            <td>
                            
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setpublish" id="publish_<?php echo e($category->id); ?>" value="<?php echo e($category->id); ?>" <?php if($category->publish==0): ?> checked="" <?php endif; ?> >
                                <label class="custom-control-label " for="publish_<?php echo e($category->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('notification.get_push_associations',$category->id)); ?>">Push</a>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('associations.edit',$category->id)); ?>">Edit</a>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('associations_video.index')); ?>?category_id=<?php echo e($category->id); ?>">content</a>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('user.export.download')); ?>?category_id=<?php echo e($category->id); ?>">Export User</a>

                              
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
    $(document).on('change','.setpublish', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 0;
      }else{
        var publish = 1;
      }
      $.ajax({url: "<?php echo e(route('associations.ajaxpublish')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The associations has been deleted publish');
        }else{
          alert('associations has been added publish');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>