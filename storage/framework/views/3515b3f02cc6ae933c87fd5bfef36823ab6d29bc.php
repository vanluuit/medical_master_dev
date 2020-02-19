
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">banners</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List banners</h5>
                    <a href="<?php echo e(route('banners.create')); ?>" class="btn btn-info btn-sm float-right">Add banners</a>
                    <a href="<?php echo e(route('banners.setNumber')); ?>" class="btn btn-info btn-sm float-right mg-r15">Banner Number</a>
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
                          <th style="width:200px">image</th>
                          <th>Type</th>
                          <th>association</th>
                          <th>video</th>
                          <th>Position</th>
                          <th>start date</th>
                          <th>end date</th>
                          <th>status</th>
                          <th>show</th>
                          <th style="width:160px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($banners): ?>
                        <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($banner->id); ?></td>
                            <td><img style="width: 160px; float: left; margin: 0 5px;" class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($banner->image); ?>" alt=""></td>
                            <td><?php if($banner->type==1): ?> Content <?php else: ?> Url <?php endif; ?></td>
                            <td><?php echo e(@$banner->category->category); ?></td>
                            <td><?php if($banner->type==1): ?> <?php echo e(@$banner->video->title); ?> <?php else: ?> <?php echo e(@$banner->url); ?> <?php endif; ?></td>
                            <td><?php echo e(@$banner->location); ?></td>
                            <?php if($banner->type==1): ?>
                            <td style="width: 120px"><?php echo e(@$banner->video->start_date); ?></td>
                            <td style="width: 120px"><?php echo e(@$banner->video->end_date); ?></td>
                            <?php else: ?>
                            <td style="width: 120px"><?php echo e(@$banner->start_date); ?></td>
                            <td style="width: 120px"><?php echo e(@$banner->end_date); ?></td>
                            <?php endif; ?>
                            <td>
                                <?php
                                  if($banner->type==1) {
                                    $start = strtotime(@$banner->video->start_date);
                                    $end = strtotime(@$banner->video->end_date);
                                  }else{
                                    $start = strtotime(@$banner->start_date);
                                    $end = strtotime(@$banner->end_date);
                                  }
                                  
                                  $now = strtotime(date('Y-m-d H:i:s'));
                                ?>
                                <?php if($start > $now || ($end < $now && $end != 0)): ?>
                                  expire
                                <?php else: ?>
                                  showing
                                <?php endif; ?>

                            </td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="<?php echo e($banner->id); ?>" name="publish" id="showbanners_<?php echo e($banner->id); ?>" <?php if( $banner->publish == 1 ): ?> checked <?php endif; ?> >
                                  <label class="custom-control-label" for="showbanners_<?php echo e($banner->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('banners.edit',$banner->id)); ?>">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="<?php echo e(route('banners.show',$banner->id)); ?>">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('banners.delete',$banner->id)); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($banners->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php $__env->startSection('script'); ?>
  <script>
     $(document).on('change','.setshow', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      console.log(publish);
      $.ajax({url: "<?php echo e(route('banners.ajaxshow')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The banner has been deleted show');
        }else{
          alert('banner has been added show');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>