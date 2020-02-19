
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">News</h4> 
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card"> 
              <div class="card-body">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading"><?php echo e($new->title); ?></h4>
                    <p>List access news</p>
                </div>
              </div>
              <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>Nickname</th>  
                          <th>date access</th>  
                      	</tr>
                      </thead>
                      <tbody>
                        <?php if($viewlists): ?>
                        <?php $__currentLoopData = $viewlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $viewlist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e(@$viewlist->user->nickname); ?></td>
                            <td><?php echo e(@$viewlist->created_at); ?></td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($viewlists->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>