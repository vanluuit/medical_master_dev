
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Version</h4>
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
                  <h5 class="card-title float-left">Version edit <?php if($version->os == 0 ): ?> (IOS) <?php else: ?> (ANDROID) <?php endif; ?></h5>
                  <?php if($version->os == 0 ): ?>
                  <a href="<?php echo e(route('versions.index', ['os'=>1])); ?>" class="btn btn-info btn-sm float-right mg-r15">ANDROID</a>
                  <?php else: ?>
                  <a href="<?php echo e(route('versions.index')); ?>" class="btn btn-info btn-sm float-right">IOS</a>
                  <?php endif; ?>
                </div>

              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['versions.update', $version->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                  <?php echo method_field('PUT'); ?>
                  <input type="hidden" name="os" value="<?php echo e($version->os); ?>">
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('version', $version->version, ['placeholder' => 'version', 'class'=>'form-control']); ?>

                    </div>
                  </div> 
                  <div class="form-group row">
                    <div class="col-md-6">
                    
                    <?php echo Form::select('status', [1=>'not update',2=>'can update',3=>'mandatory update',4=>'mainternance'], $version->status, [ 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <textarea class="form-control" rows="10" name="message" placeholder="message"><?php echo e($version->message); ?></textarea>
                    </div>
                  </div>

                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              </div>
              
              <?php echo Form::close(); ?>

          </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>  


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>