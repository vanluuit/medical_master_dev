
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">topics</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List topics</h5>
                    <a href="<?php echo e(route('topics.create')); ?>" class="btn btn-info btn-sm float-right">Add topics</a>
                    <a href="<?php echo e(route('topics.gettopics')); ?>" class="btn btn-info btn-sm float-right mg-r15">import topics</a>
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
                            <th style="width: 150px">start date</th>
                            <th style="width: 150px">end date</th>
                            <!-- <th>builing</th>
                            <th>address</th>
                            <th>map</th> -->
                            
                            <th style="width:190px">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if($topics): ?>
                          <?php $__currentLoopData = $topics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $topic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr <?php if(strtotime($topic->start_date) > strtotime($topic->end_date)): ?> class="tr_error" <?php endif; ?>>
                              <td><?php echo e($topic->id); ?></td>
                              <td><?php echo e($topic->name); ?></td>
                              <td><?php echo e($topic->start_date); ?>(<?php echo e($topic->start_sunday); ?>)</td>
                              <td><?php echo e($topic->end_date); ?>(<?php echo e($topic->end_sunday); ?>)</td>
                             <!--  <td><?php echo e($topic->builing); ?></td>
                              <td><?php echo e($topic->address); ?></td>
                              <td><?php echo e($topic->map); ?></td> -->
                              <td>
                                <a class="btn btn-cyan btn-sm" href="<?php echo e(route('topics.edit',$topic->id)); ?>">Edit</a>
                                <!-- <a class="btn btn-success btn-sm" href="<?php echo e(route('topics.show',$topic->id)); ?>">Show</a> -->
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('topics.delete',$topic->id)); ?>">Delete</a>

                              </td>
                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                        </tbody>
                  </table>
                </div>
                </div>
                
              </div>
                <div class="float-center">
                    <?php echo e($topics->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>