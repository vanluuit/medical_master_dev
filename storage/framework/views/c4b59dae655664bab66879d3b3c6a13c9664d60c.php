
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">new banners</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List new banners</h5>
                    <a href="<?php echo e(route('newbanners.create')); ?>" class="btn btn-info btn-sm float-right">Add new banners</a>
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
                          <!-- <th>Position</th> -->
                          <th>start date</th>
                          <th>end date</th>
                          <th>status</th>
                          <th>show</th>
                          <th style="width:160px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($newbanners): ?>
                        <?php $__currentLoopData = $newbanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $newbanner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($newbanner->id); ?></td>
                            <td><img style="width: 160px; float: left; margin: 0 5px;" class="thumbnail" src="<?php echo e($newbanner->image); ?>" alt=""></td>
                            <td><?php if($newbanner->type==1): ?> Content <?php else: ?> Url <?php endif; ?></td>
                            <td><?php echo e(@$newbanner->category->category); ?></td>
                            <td><?php if($newbanner->type==1): ?> <?php echo e(@$newbanner->video->title); ?> <?php else: ?> <?php echo e(@$newbanner->url); ?> <?php endif; ?></td>
                            <!-- <td><?php echo e(@$newbanner->location); ?></td> -->
                            <?php if($newbanner->type==1): ?>
                            <td style="width: 120px"><?php echo e(@$newbanner->video->start_date); ?></td>
                            <td style="width: 120px"><?php echo e(@$newbanner->video->end_date); ?></td>
                            <?php else: ?>
                            <td style="width: 120px"><?php echo e(@$newbanner->start_date); ?></td>
                            <td style="width: 120px"><?php echo e(@$newbanner->end_date); ?></td>
                            <?php endif; ?>
                            <td>
                                <?php
                                  if($newbanner->type==1) {
                                    $start = strtotime(@$newbanner->video->start_date);
                                    $end = strtotime(@$newbanner->video->end_date);
                                  }else{
                                    $start = strtotime(@$newbanner->start_date);
                                    $end = strtotime(@$newbanner->end_date);
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
                                  <input type="checkbox" class="custom-control-input setshow" value="<?php echo e($newbanner->id); ?>" name="publish" id="shownewbanners_<?php echo e($newbanner->id); ?>" <?php if( $newbanner->publish == 1 ): ?> checked <?php endif; ?> >
                                  <label class="custom-control-label" for="shownewbanners_<?php echo e($newbanner->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('newbanners.edit',$newbanner->id)); ?>">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="<?php echo e(route('newbanners.show',$newbanner->id)); ?>">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('newbanners.delete',$newbanner->id)); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($newbanners->appends(request()->query())->links()); ?>

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
      $.ajax({url: "<?php echo e(route('newbanners.ajaxshow')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The newbanner has been deleted show');
        }else{
          alert('newbanner has been added show');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>