
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Content</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">Content list</h5>
                    <a href="<?php echo e(route('videos.addslider')); ?>" class="btn btn-info btn-sm float-right">Add slider</a>  
                    <a href="<?php echo e(route('videos.addpdf')); ?>" class="btn btn-info btn-sm float-right mg-r15">Add pdf</a>  
                    <a href="<?php echo e(route('videos.create')); ?>" class="btn btn-info btn-sm float-right mg-r15">Add Video</a>  
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('channel_id', $channels, @request()->channel_id, ['placeholder' => 'チャンネル', 'class'=>'form-control select2 search_change']); ?>

                        </div>
                      </div>
                    </form>
                </div>
                <div class="float-center">
                    <?php echo e($videos->appends(request()->query())->links()); ?>

                </div>
                <?php echo Form::open(array('route' => 'videos.deleteall', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <div class="scroll">
                  <table class="table">
                        <thead>
                          <tr>
                            <th>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input deletenewsall" id="deletenewsall" data-checked="checkdel">
                                  <label class="custom-control-label" for="deletenewsall"></label>
                              </div>
                            </th>
                            <th>id</th>
                            <th>title</th>
                            <th>type</th>
                            <th>thumbnail</th>
                            <th>channel</th>
                            <th>publish</th>
                            <th style="width:300px">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if($videos): ?>
                          <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                              <td>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" name="delete[]" class="custom-control-input checkdel" id="delete_<?php echo e($video->id); ?>" value="<?php echo e($video->id); ?>">
                                  <label class="custom-control-label " for="delete_<?php echo e($video->id); ?>"></label>
                                </div>
                              </td>
                              <td><?php echo e($video->id); ?></td>
                              <td><?php echo e($video->title); ?></td>
                              <td><?php if($video->type == 1): ?> video <?php elseif($video->type == 2): ?> PDF <?php else: ?> slide mark <?php endif; ?></td>
                              <td><img class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($video->thumbnail); ?>" alt=""></td>
                              <td><?php echo e($video->channel->title); ?></td>
                              <td>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setpublish" id="publish_<?php echo e($video->id); ?>" value="<?php echo e($video->id); ?>" <?php if($video->publish==0): ?> checked="" <?php endif; ?> >
                                  <label class="custom-control-label " for="publish_<?php echo e($video->id); ?>"></label>
                                </div>
                              </td>
                              <td>
                                <a class="btn btn-success btn-sm" href="<?php echo e(route('questions.index')); ?>?video_id=<?php echo e($video->id); ?>">question</a>
                                <a class="btn btn-success btn-sm" href="<?php echo e(route('videos.question',$video->id)); ?>">answer</a>
                                <a class="btn btn-cyan btn-sm" href="<?php echo e(route('videos.edit',$video->id)); ?>">Edit</a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('videos.delete',$video->id)); ?>?channel_id=<?php echo e(@request()->channel_id); ?>">Delete</a>

                              </td>
                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                        </tbody>
                  </table>
                </div>
                <div class="border-top">
                <div class="card-body">
                  <input type="hidden" name="channel_id" value="<?php echo e(@request()->channel_id); ?>">
                  <button type="submit" class="btn btn-primary">Delete</button>
                </div>
              </div>
                <?php echo Form::close(); ?>

                <div class="float-center">
                    <?php echo e($videos->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  

<?php $__env->startSection('script'); ?>
  <script>
    $(document).on('change','.setpublish', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 0;
      }else{
        var publish = 1;
      }
      $.ajax({url: "<?php echo e(route('videos.ajaxpublish')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 1){
          alert('The video has been deleted publish');
        }else{
          alert('video has been added publish');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>