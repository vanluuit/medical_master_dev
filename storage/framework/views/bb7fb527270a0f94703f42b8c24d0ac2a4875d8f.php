
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">News</h4>
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
                  <h5 class="card-title"><?php echo e(@$News->title); ?></h5>
                </div>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                    <div class="col-md-6">
                       <input type="text" class="form-control" name="" disabled="" value="<?php echo e(@$cate[$News->category_id]); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <input type="text" class="form-control" name="" disabled="" value="<?php echo e(@$categories[$News->category_new_id]); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="" disabled="" value="<?php echo e(@$News->title); ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <img id="media_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e(@$News->media); ?>" alt="" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <?php echo @$News->desctiption; ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <?php echo @$News->content; ?>

                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>