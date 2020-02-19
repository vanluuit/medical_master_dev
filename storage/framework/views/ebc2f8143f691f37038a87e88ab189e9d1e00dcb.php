
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">NEWS</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
              <div class="border-bottom">
                <div class="card-body">
                  <h5 class="card-title">CRONJOB</h5>
                  
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['user.list.review'], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::select('id', $users, '', ['class'=>'form-control select2']); ?>

                    </div>
                    <div class="col-md-3">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                  <?php echo Form::close(); ?>

                  <table class="table">
                      <thead>
                        <tr>
                          <th>Nickname</th>
                          <th>Email</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($userreviews): ?>
                        <?php $__currentLoopData = $userreviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $userreview): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($userreview->nickname); ?></td>
                            <td><?php echo e($userreview->email); ?></td>
                            <td>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('user.list.review', ['id'=>$userreview->id])); ?>">Delete</a>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
                </div>
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