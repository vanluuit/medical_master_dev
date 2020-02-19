
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Place</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List place</h5>
                    <a href="<?php echo e(route('seminars.index')); ?>" class="btn btn-info btn-sm float-right mg-r15">List seminar</a>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>name</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($halls): ?>
                        <?php $__currentLoopData = $halls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $hall): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr class="ui-state-default" >
                            <input type="hidden" name="soft[]" class="soft_id" value="<?php echo e($hall->id); ?>">
                            <td><?php echo e($hall->id); ?></td>
                            <td><?php echo e($hall->name); ?></td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
                <div class="float-center">
                    <?php echo e($halls->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php $__env->startSection('script'); ?>
<script>
  // let soft_id = '';
  // $('.soft_id').each(function(index, value){
  //   soft_id += $(this).val()+'_';
  // });
  // console.log(soft_id);

  $( "table tbody" ).sortable( {
    // start: function(event, ui) {
    //     var start_pos = ui.item.index()+1;
    //     ui.item.data('start_pos', start_pos);
    // },
    // change: function(event, ui) {
    //     var start_pos = ui.item.data('start_pos');
    //     var index = ui.placeholder.index();
    //     ui.item.data('index', index);
    //     // console.log(start_pos+'_'+index);
    // },
    // update: function(event, ui) {
    //     var start_pos = ui.item.data('start_pos');
    //     var index = ui.item.data('index');
    //     console.log(start_pos+'_'+index);
    //     // $('#sortable li').removeClass('highlights');
    // }
  update: function( event, ui ) {
    let str = "";
    $('tr.ui-state-default').each(function(index, value){
      str += $(this).find('input').val()+'_';
    });
    console.log(str);
    $.ajax({url: "<?php echo e(route('halls.ajaxsoft')); ?>?id="+str, success: function(result){
      console.log(result);
    }});

  }
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>