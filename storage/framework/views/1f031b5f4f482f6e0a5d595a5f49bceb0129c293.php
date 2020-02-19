
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
                    <form action="" method="GET">
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="<?php echo e($start_day); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-1 text-center">
                      ~
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="<?php echo e($end_day); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <button type="submit" class="btn btn-primary">GET</button>
                    </div>
                  </div>
                </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>title</th>
                          <th style="width:80px">
                            <?php 
                              $view = (request()->view + 1) % 2;
                              $comment = (request()->comment + 1) % 2;
                            ?>
                            <a href="?start_day=<?php echo e($start_day); ?>&end_day=<?php echo e($end_day); ?>&view=<?php echo e($view); ?>">view<i style="margin-left: 6px" class="fas fa-sort"></i>
                            </a>
                          </th>
                          
                          <th style="width:120px"><a href="?start_day=<?php echo e($start_day); ?>&end_day=<?php echo e($end_day); ?>&comment=<?php echo e($comment); ?>">comment<i style="margin-left: 6px" class="fas fa-sort"></i></a></th> 
                      	</tr>
                      </thead>
                      <tbody>
                        <?php if($news): ?>
                        <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $new): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($new->id); ?></td>
                            <td><?php echo e($new->title); ?></td>
                            <td><a href="<?php echo e(route('analytic.news.access', $new->id)); ?>?start_day=<?php echo e($start_day); ?>&end_day=<?php echo e($end_day); ?>"><?php echo e($new->count_view_count); ?></a></td>
                            
                            <td><a href="<?php echo e(route('commentnews.index')); ?>?new_id=<?php echo e($new->id); ?>&start_day=<?php echo e($start_day); ?>&end_day=<?php echo e($end_day); ?>"><?php echo e($new->count_comment_count); ?></a></td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($news->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>