
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
              <div class="border-bottom">
                <div class="card-body">
                  <h5 class="card-title">Member add</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => 'members.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::select('category_id', $categories, '', ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('code', '', ['placeholder' => '学会会員番号', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                </div>
              </div>
              <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
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
        $(document).ready(function(){
            var validateForm = $('#validate').validate({
                rules: {
                    'category_id': {
                        required: true,
                    },
                    'code': {
                        required: true,
                    },
                },

                messages: {
                    'category_id': {
                        required:  "学会選択が間違っています。",
                    },
                    'code': {
                        required:  "学会会員番号が間違っています。",
                    },
                    
                },
                highlight: function (e) {
                    $(e).closest('td').removeClass('has-info').addClass('has-error');
                },

                success: function (e) {
                    $(e).closest('td').removeClass('has-error');//.addClass('has-info');
                    $(e).remove();
                },

                errorPlacement: function (error, element) {
                    if(element.is('input[type=checkbox') || element.is('input[type=radio')) {
                        var controls = element.closest('td');
                        if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    }
                    else error.insertAfter(element);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>