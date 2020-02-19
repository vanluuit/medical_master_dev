
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">events</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List events</h5>
                    <!-- <a href="<?php echo e(route('events.create')); ?>?seminar_id=<?php echo e(request()->seminar_id); ?>" class="btn btn-info btn-sm float-right">Add events</a> -->
                    <a href="<?php echo e(route('events.getevents')); ?>?seminar_id=<?php echo e(request()->seminar_id); ?>" class="btn btn-info btn-sm float-right mg-r15">import events</a>
                    <a href="<?php echo e(route('seminars.index')); ?>" class="btn btn-info btn-sm float-right mg-r15">list seminar</a>
                    <a href="<?php echo e(route('categoryevents.index')); ?>?seminar_id=<?php echo e(request()->seminar_id); ?>" class="btn btn-info btn-sm float-right mg-r15">Category Event</a>
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>Number</th>
                          <th>start time</th>
                          <th>end time</th>
                          <th>category</th>
                          <th>Theme</th>
                          <th style="width:140px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($events): ?>
                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($event->id); ?></td>
                            <td><?php echo e($event->topic_number); ?></td>
                            <td><?php echo e(date('H:i', strtotime($event->start_time))); ?></td>
                            <td><?php echo e(date('H:i', strtotime($event->end_time))); ?></td>
                            <td><?php echo e(@$event->category->name); ?></td>
                            <td><?php echo e(@$event->theme->name); ?></td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('events.edit',$event->id)); ?>">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="<?php echo e(route('events.show',$event->id)); ?>">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('events.delete',$event->id)); ?>?seminar_id=<?php echo e(request()->seminar_id); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($events->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>