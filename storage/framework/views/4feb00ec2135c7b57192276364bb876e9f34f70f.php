
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">topics</h4>
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
                  <h5 class="card-title">RSS add</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['topics.update', $topic->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_id', $categories, $topic->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('name', @$topic->name, ['placeholder' => 'タイトル', 'class'=>'form-control']); ?>

                    </div>
                  </div>  
                  
                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="input-group date">
                        <input type="text" name="start_date" class="form-control datepicker-autoclose" id="" placeholder="start date" value="<?php echo e($topic->start_date); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="input-group date">
                        <input type="text" name="end_date" class="form-control datepicker-autoclose" id="" placeholder="end date" value="<?php echo e($topic->end_date); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('building', $topic->building, ['placeholder' => 'building', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('address', $topic->address, ['placeholder' => 'address', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('map', $topic->map, ['placeholder' => 'map', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('url', $topic->url, ['placeholder' => 'URL', 'class'=>'form-control']); ?>

                    </div>
                  </div><div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('url_text', $topic->url_text, ['placeholder' => 'URL text', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::textarea('unit', $topic->unit, ['placeholder' => 'unit', 'class'=>'form-control']); ?>

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

<?php $__env->startSection('script'); ?> 
    <script>

        $(document).ready(function(){
            var validateForm = $('#validate').validate({
                rules: {
                    'category_id': {
                        required: true,
                    },
                    'name': {
                        required: true,
                    },
                    // 'url': {
                    //     required: true,
                    // },
                    'date':{
                      required: true,
                    }
                },

                messages: {
                    'category_id': {
                        required:  "カテゴリーが間違っています。",
                    },
                    'name': {
                        required:  "カタイトルが間違っています。",
                    },
                    // 'url': {
                    //     required:  "URLが間違っています。",
                    // },
                    'date':{
                        required:"dateが間違っています。",
                    }
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
        $("#media").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        CKEDITOR.replace( 'content' );
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>