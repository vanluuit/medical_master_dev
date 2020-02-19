
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
              <div class="border-bottom">
                <div class="card-body">
                  <h5 class="card-title">RSS edit</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['rssnews.update', $rssnew->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                  <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::select('category_id', $cates, $rssnew->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('name', $rssnew->name, ['placeholder' => 'タイトル', 'class'=>'form-control']); ?>

                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('url', $rssnew->url, ['placeholder' => 'URL', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::select('category_new_id', $categories, $rssnew->category_new_id, ['placeholder' => 'カテゴリー', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::select('destroy', [0=>'after 3 days', 1=>'after 2 weeks'], $rssnew->destroy, ['placeholder' => 'Delete articles', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('copyright', $rssnew->copyright, ['placeholder' => 'コピーライト', 'class'=>'form-control']); ?>

                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('param', $rssnew->param, ['placeholder' => 'パラメーター', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="thumbnail" id="thumbnail" data-show="thumbnail_show" data-text="thumbnail_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="thumbnail" class="btn btn-sm btn-primary" id="thumbnail_text">サムネイル</label>
                      <div class="thumbnail">
                        <img id="thumbnail_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($rssnew->thumbnail); ?>" alt="" />
                      </div> 
                      <?php if($rssnew->thumbnail): ?>
                        <div class="custom-control custom-checkbox mr-sm-2" style="margin-top:10px;">
                            <input type="checkbox" class="custom-control-input setshow" value="<?php echo e($rssnew->id); ?>" name="delete" id="shownews_<?php echo e($rssnew->id); ?>">
                            <label class="custom-control-label" for="shownews_<?php echo e($rssnew->id); ?>">&nbsp;&nbsp;&nbsp;delete thumbnail</label>
                        </div>
                      <?php endif; ?>
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
                    'name': {
                        required: true,
                    },
                    'url': {
                        required: true,
                    }
                },

                messages: {
                    'name': {
                        required:  "カタイトルが間違っています。",
                    },
                    'url': {
                        required:  "URLが間違っています。",
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
        $("#thumbnail").change(function() {
      readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
    });
        CKEDITOR.replace( 'content' );
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>