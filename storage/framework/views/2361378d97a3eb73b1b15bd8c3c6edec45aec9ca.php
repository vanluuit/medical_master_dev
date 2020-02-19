
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Channels</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Channels</h5>
                    <a href="<?php echo e(route('channels.create')); ?>" class="btn btn-info btn-sm float-right">Add Channel</a>  
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('category_id', $categories, @request()->category_id,['class'=>'form-control select2 search_change']); ?>

                        </div>
                      </div>
                    </form>
                </div>
                <?php echo Form::open(array('route' => 'channels.deleteall', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>id</th>
                          <th>title</th>
                          <th>thumbnail</th>
                          <th>discription</th>
                          <th>publish</th>
                          <th>association</th>
                          <th>sponser</th>
                          <th>is_sponser</th>
                          <th>top</th>
                          <th>publish date</th>
                          <th style="width:200px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($channels): ?>
                        <?php $__currentLoopData = $channels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $channel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <!-- <tr  class="ui-state-default"> -->
                          <tr>
                            <!-- <input type="hidden" name="soft[]" class="soft_id" value="<?php echo e($channel->id); ?>"> -->
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" name="delete[]" class="custom-control-input" id="delete_<?php echo e($channel->id); ?>" value="<?php echo e($channel->id); ?>">
                                <label class="custom-control-label " for="delete_<?php echo e($channel->id); ?>"></label>
                              </div>
                            </td>
                            <td><?php echo e($channel->id); ?></td>
                            <td><?php echo e($channel->title); ?></td>
                            <td><img class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($channel->logo); ?>" alt=""></td>
                            <td style="width:300px"><?php echo e($channel->discription); ?></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setpublish" id="publish_<?php echo e($channel->id); ?>" value="<?php echo e($channel->id); ?>" <?php if($channel->publish==0): ?> checked="" <?php endif; ?> >
                                <label class="custom-control-label " for="publish_<?php echo e($channel->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <?php if($channel->association): ?>
                              <?php $__currentLoopData = $channel->association; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cates): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($cates->category->category); ?> <?php if($key < count($channel->association)-1): ?> ,  <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php endif; ?>
                            </td>
                            <td><?php echo e($channel->sponser); ?></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setis_sponser" id="is_sponser_<?php echo e($channel->id); ?>" value="<?php echo e($channel->id); ?>" <?php if($channel->is_sponser==1): ?> checked="" <?php endif; ?> >
                                <label class="custom-control-label " for="is_sponser_<?php echo e($channel->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <div class="custom-control custom-radio mr-sm-2">
                                <input type="radio" class="custom-control-input settop" id="top_<?php echo e($channel->id); ?>" name="top" value="<?php echo e($channel->id); ?>" <?php if($channel->nabi==1): ?> checked="" <?php endif; ?> >
                                <label class="custom-control-label " for="top_<?php echo e($channel->id); ?>"></label>
                              </div>
                            </td>
                            <td><?php echo e($channel->publish_date); ?></td>
                            <td>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('channels.pushNotification',$channel->id)); ?>?category_id=<?php echo e(@request()->category_id); ?>">Push</a>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('channels.edit',$channel->id)); ?>">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('channels.delete',$channel->id)); ?>?category_id=<?php echo e(@request()->category_id); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="border-top">
                <div class="card-body">
                  <input type="hidden" name="category_id" value="<?php echo e(@request()->category_id); ?>">
                  <button type="submit" class="btn btn-primary">Delete</button>
                </div>
              </div>
                <?php echo Form::close(); ?>

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
      $.ajax({url: "<?php echo e(route('channels.ajaxpublish')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 1){
          alert('The channel has been deleted publish');
        }else{
          alert('channel has been added publish');
        }
      }});
    });
    $(document).on('change','.setis_sponser', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var sponser = 1;
      }else{
        var sponser = 0;
      }
      $.ajax({url: "<?php echo e(route('channels.ajaxis_sponser')); ?>?id="+$(this).val()+'&sponser='+sponser, success: function(result){
        if(result == 1){
          alert('The channel has been deleted sponser');
        }else{
          alert('channel has been added sponser');
        }
      }});
    });
    $(document).on('change','.settop', function(){
      var obj = $(this);
      console.log($(this).val());
      $.ajax({url: "<?php echo e(route('channels.ajaxtop')); ?>?id="+$(this).val(), success: function(result){
        if(result == 1){
          alert('The channel has been set top');
        }else{
          alert('channel has been set top');
        }
      }});
    });

    //  $( "table tbody" ).sortable( {
    //   update: function( event, ui ) {
    //     let str = "";
    //     $('tr.ui-state-default').each(function(index, value){
    //       str += $(this).find('input').val()+'_';
    //     });
    //     console.log(str);
    //     $.ajax({url: "<?php echo e(route('channel.ajax.ajaxsoft')); ?>?id="+str, success: function(result){
    //       console.log(result);
    //     }});

    //   }
    // });
  </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>