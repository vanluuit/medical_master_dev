
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Member</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php echo Form::open(array('route' => 'members.import', 'enctype'=>'multipart/form-data')); ?>

                      <div class="row">
                        <div class="col-md-12">
                          <input type="file" name="member" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required="">
                          <button type="submit" class="btn btn-primary btn-sm ">Import</button>
                        </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Member</h5>
                    <a href="<?php echo e(route('members.create')); ?>" class="btn btn-info btn-sm float-right">Add member</a>
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('category_id', $categories, @request()->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2 search_change']); ?>

                        </div>
                      </div>
                    </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>学会選択</th>
                          <th>学会会員番号</th>
                          <th style="width:100px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($members): ?>
                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($member->id); ?></td>
                            <td><?php echo e(@$member->association->category); ?></td>
                            <td><?php echo e($member->code); ?></td>
                            <td>
                              
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('members.delete',$member->id)); ?>">Delete</a>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($members->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>