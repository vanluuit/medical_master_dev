
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Users</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List User</h5>
                    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-info btn-sm float-right">Add user</a>
                    <a href="<?php echo e(route('analytic.user')); ?>" class="btn btn-info btn-sm float-right mg-r15">Analytic</a>
                </div>
                <div class="card-body">
                    <form action="" method="GET">
                      <!-- <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('category_id', $categories, @request()->category_id, ['placeholder' => '小カテゴリー', 'class'=>'form-control select2 search_change']); ?>

                        </div>
                      </div> -->
                      <div class="row">
                        <div class="col-md-6">
                          <input type="text" name="s" value="<?php echo e(request()->s); ?>" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <button type="submit" class="btn btn-primary"> Search </button>
                        </div>
                      </div>
                    </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>氏名</th>
                          <th>学会会員番号</th>
                          <th>職業</th>
                          <th>勤務地の名前</th>
                          <th>住所</th>
                          <th>生年月日</th>
                          <th>registered</th>
                          <th>PRO</th>
                          <th style="width:210px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($users): ?>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($user->nickname); ?></td>
                            <td>
                            	<?php if(count(@$user->association)): ?>
                            		<?php $__currentLoopData = $user->association; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keya => $association): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($association->member->code); ?> 
                                <?php if($keya < count(@$user->association) -1 ): ?>,<?php endif; ?> 
                            		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            	<?php endif; ?>
                            </td>
                            <td><?php echo e(@$user->career->name); ?></td>
                            <td><?php echo e($user->hospital_name); ?></td>
                            <td><?php echo e(@$user->area_hospital); ?></td>
                            <td><?php echo e($user->birthday); ?></td>
                            <td><?php echo e($user->created_at); ?></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setpro" id="userpro_<?php echo e($user->id); ?>" value="<?php echo e($user->id); ?>" <?php if($user->pro==1): ?> checked="" <?php endif; ?> >
                                <label class="custom-control-label " for="userpro_<?php echo e($user->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('users.edit',$user->id)); ?>">Edit</a>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('users.show',$user->id)); ?>">Show</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('users.delete',$user->id)); ?>">Delete</a>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
                </div>
                <div class="float-center">
                    <?php echo e($users->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php $__env->startSection('script'); ?>
  <script>
    $(document).on('change','.setpro', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var pro = 1;
      }else{
        var pro = 0;
      }
      $.ajax({url: "<?php echo e(route('users.ajaxpro')); ?>?id="+$(this).val()+'&pro='+pro, success: function(result){
        if(result == 0){
          alert('The user has been deleted pro');
        }else{
          alert('user has been added pro');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>