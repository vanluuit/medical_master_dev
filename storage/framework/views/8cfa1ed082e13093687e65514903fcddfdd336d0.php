
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">RSS</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List RSS</h5>
                    <a href="<?php echo e(route('rss.create')); ?>" class="btn btn-info btn-sm float-right">add Rss</a>
                    <a href="<?php echo e(route('rss.getrss')); ?>" class="btn btn-info btn-sm float-right mg-r15">getRss</a>
                    <a href="<?php echo e(route('rssurls.index')); ?>" class="btn btn-info btn-sm float-right mg-r15">List url rss</a>
                    <a href="<?php echo e(route('rss.crontime')); ?>" class="btn btn-info btn-sm float-right mg-r15">CRONJOB</a>
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
                          <th>url</th>
                          <th>show</th>
                          <th style="width: 180px">date</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($rss): ?>
                        <?php $__currentLoopData = $rss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $rs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($rs->id); ?></td>
                            <td><?php echo e($rs->title); ?></td>
                            <td><div style="max-width: 500px"><?php echo e($rs->url); ?></div></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="<?php echo e($rs->id); ?>" name="publish" id="showrss_<?php echo e($rs->id); ?>" <?php if( $rs->publish == 1 ): ?> checked <?php endif; ?> >
                                  <label class="custom-control-label" for="showrss_<?php echo e($rs->id); ?>"></label>
                              </div>
                            </td>
                            <td><?php echo e($rs->date); ?></td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('rss.edit',$rs->id)); ?>">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="<?php echo e(route('rss.show',$rs->id)); ?>">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('rss.delete',$rs->id)); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($rss->appends(request()->query())->links()); ?>

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
      $.ajax({url: "<?php echo e(route('rss.ajaxshow')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The rss has been deleted show');
        }else{
          alert('rss has been added show');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>