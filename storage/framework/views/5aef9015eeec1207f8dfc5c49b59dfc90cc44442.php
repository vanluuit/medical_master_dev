
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">RSS URL</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List RSS url</h5>
                    <a href="<?php echo e(route('rssurls.create')); ?>" class="btn btn-info btn-sm float-right">Add rss url</a>
                    <a href="<?php echo e(route('rss.index')); ?>" class="btn btn-info btn-sm float-right mg-r15">list Rss</a>
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>name</th>
                          <th>url</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($rssurls): ?>
                        <?php $__currentLoopData = $rssurls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rssurl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($rssurl->id); ?></td>
                            <td><?php echo e($rssurl->name); ?></td>
                            <td><?php echo e($rssurl->url); ?></td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('rssurls.edit',$rssurl->id)); ?>">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="<?php echo e(route('rssurls.show',$rssurl->id)); ?>">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('rssurls.delete',$rssurl->id)); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($rssurls->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>