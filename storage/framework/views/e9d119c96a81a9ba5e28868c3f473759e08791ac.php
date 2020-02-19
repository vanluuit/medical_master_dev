
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
              <div class="border-bottom">
                <div class="card-body">
                  <h5 class="card-title">new banner edit</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['newbanners.update', $banner->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('type', ['1'=>'Content', '2'=>'Url'], $banner->type, ['placeholder' => 'type', 'class'=>'form-control select2', 'id'=>'change_association_type']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_id', $categories, $banner->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2','id'=>'change_association']); ?>

                    </div>
                  </div>
                  
                  <div class="form-group row" <?php if( $banner->type ==2 ): ?> style="display:none" <?php endif; ?> id="type_1">
                    <div class="col-md-6">
                       <?php echo Form::select('video_id', $videos, $banner->video_id, ['placeholder' => 'video', 'class'=>'form-control select2', 'id'=>'video_id_sl']); ?>

                    </div>
                  </div> 
                  <div class="url-div" <?php if($banner->type ==1): ?> style="display:none" <?php endif; ?> id="type_2">
                    <div class="form-group row">
                      <div class="col-md-6">
                        <?php echo Form::text('url', $banner->url, ['placeholder' => 'Url', 'class'=>'form-control']); ?>

                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-3">
                        <div class="input-group date">
                          <input type="text" name="start_date" class="form-control datetimepicker" id="start_date" placeholder="開始時間" value="<?php echo e($banner->start_date); ?>">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                        <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                      </div>
                      <div class="col-md-3">
                        <div class="input-group date">
                          <input type="text" name="end_date" class="form-control datetimepicker" id="end_date" placeholder="終了時間" value="<?php echo e($banner->end_date); ?>">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                        <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                      </div>
                    </div>
                  </div>
                 <!--  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('location', $location_ar, $banner->location, ['placeholder' => 'location', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>  -->
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="image" class="btn btn-sm btn-primary" id="image_text">image</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="image" id="image" data-show="image_show" data-text="image_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="image_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($banner->image); ?>" alt="" />
                      </div> 
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
                    'type': {
                        required: true,
                    },
                    'location': {
                        required: true,
                    }
                },

                messages: {
                    'category_id': {
                        required:  "カテゴリーが間違っています。",
                    },
                    'type': {
                        required:  "typeが間違っています。",
                    },
                    'location':{
                        required:"locationが間違っています。",
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
        $("#image").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $(document).on('change','#change_association', function(){
          var obj = $(this);
          $.ajax({url: "<?php echo e(route('videos.ajax_association')); ?>?id="+$(this).val(), success: function(result){

            var ar = JSON.parse(result);
            var str ='<option selected=\"selected\" value=\"\">video</option>\"';
            for (var i = 0; i < ar.length; i++) {
              str = str+'<option value="'+ar[i]['id']+'">'+ar[i]['title']+'</option>'
            }
           $('#video_id_sl').html(str);

          }});
        });
        $(document).on('change','#change_association_type', function(){
          var id = '#type_'+ $(this).val();
          $('#type_1').hide();
          $('#type_2').hide();
          $(id).show();
        });
        
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>