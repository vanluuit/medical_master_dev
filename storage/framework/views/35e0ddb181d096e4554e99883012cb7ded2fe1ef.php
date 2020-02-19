
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">seminar</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List seminar</h5>
                    <a href="<?php echo e(route('seminars.create')); ?>" class="btn btn-info btn-sm float-right">Add seminars</a>
                   
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>title</th>
                          <th>banner</th>
                          <th>image </th>
                          <th>map image</th>
                          <th>map pdf</th>
                          <th style="width: 150px">start date</th>
                          <th style="width: 150px">end date</th>
                          <th>link</th>
                          <th>show</th>
                          <th style="width:210px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($seminars): ?>
                        <?php $__currentLoopData = $seminars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seminar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td><?php echo e($seminar->id); ?></td>
                            <td><?php echo e($seminar->title); ?></td>
                            <td><img class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($seminar->banner); ?>" alt=""></td>
                            <td><img class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($seminar->image); ?>" alt=""></td>
                            <td><img class="thumbnail" src="<?php echo e(URL::to('/')); ?>/<?php echo e($seminar->map_image); ?>" alt=""></td>
                            <td><a target="_blade" href="<?php echo e(URL::to('/')); ?>/<?php echo e($seminar->map); ?>">pdf</a></td>
                            <td><?php echo e($seminar->start_date); ?></td>
                            <td><?php echo e($seminar->end_date); ?></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setlink" value="<?php echo e($seminar->id); ?>" name="link" id="linkseminars_<?php echo e($seminar->id); ?>" <?php if( $seminar->link == 1 ): ?> checked <?php endif; ?> >
                                  <label class="custom-control-label" for="linkseminars_<?php echo e($seminar->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="<?php echo e($seminar->id); ?>" name="publish_<?php echo e($seminar->category_id); ?>" id="showseminars_<?php echo e($seminar->id); ?>" <?php if( $seminar->publish == 1 ): ?> checked <?php endif; ?> >
                                  <label class="custom-control-label" for="showseminars_<?php echo e($seminar->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('seminars.edit',$seminar->id)); ?>">Edit</a>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('events.index')); ?>?seminar_id=<?php echo e($seminar->id); ?>">Event</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('seminars.delete',$seminar->id)); ?>">Delete</a>
                              <a style="margin-top: 6px;" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete All Event?');" href="<?php echo e(route('events.deleteall',$seminar->id)); ?>">Empty Event</a>
                              <a style="margin-top: 6px;" class="btn btn-cyan btn-sm" href="<?php echo e(route('analytic.rankink')); ?>?seminar_id=<?php echo e($seminar->id); ?>">Ranking</a>
                              <a style="margin-top: 6px;" class="btn btn-cyan btn-sm" href="<?php echo e(route('place.index', $seminar->id)); ?>">Place</a>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    
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
      var name = obj.attr('name');
      console.log(name);
      if(obj.prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      $('input[name="'+name+'"]').prop('checked', false);
      if(publish==1)  {
        obj.prop('checked', true);
      }else{  
        obj.prop('checked', false);
      }
      

      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({url: "<?php echo e(route('seminars.ajaxshow')); ?>?id="+$(this).val()+'&publish='+publish, 
        success: function(result){
        if(result == 0){
          alert('The seminar has been deleted show');
        }else{
          alert('seminar has been added show');
        }
      }});
    });
    $(document).on('change','.setlink', function(){
      var obj = $(this);
      

      if($(this).prop("checked") == true) {
        var link = 1;
      }else{
        var link = 0;
      }

     

      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({url: "<?php echo e(route('seminars.ajaxlink')); ?>?id="+$(this).val()+'&link='+link, 
        success: function(result){
        if(result == 0){
          alert('The seminar has been deleted link');
        }else{
          alert('seminar has been added link');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>