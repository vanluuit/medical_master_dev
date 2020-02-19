
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Discussion</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Discussion</h5>
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('category_id', $categories, @request()->category_id, ['placeholder' => '小カテゴリー', 'class'=>'form-control select2 search_change']); ?>

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
                          <th>discription</th>
                          <th style="width: 300px">images</th>
                          <!-- <th>top</th> -->
                          
                          <th style="width:280px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($discussions): ?>
                        <?php $__currentLoopData = $discussions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $discussion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($discussion->id); ?></td>
                            <td><?php echo e($discussion->title); ?></td>
                            <td><?php echo e($discussion->discription); ?></td>
                            <td>
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($discussion->image1); ?>" alt="">
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($discussion->image2); ?>" alt="">
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($discussion->image3); ?>" alt="">
                            </td>
                            <td>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('discussion.comments_list')); ?>?discussion_id=<?php echo e($discussion->id); ?>">Show Comment</a>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('discussion.edit',$discussion->id)); ?>">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('discussion.delete',$discussion->id)); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($discussions->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>