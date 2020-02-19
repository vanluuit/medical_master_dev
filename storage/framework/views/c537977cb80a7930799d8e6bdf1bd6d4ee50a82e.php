
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">PDF</h4>
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
                  <h5 class="card-title">PDF edit</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['videos.update', $video->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                  <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('channel_id', $channels, $video->channel_id, ['placeholder' => 'チャンネル', 'class'=>'form-control select2', 'id'=>'change_channel']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('title', $video->title, ['placeholder' => 'タイトル', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="thumbnail" class="btn btn-sm btn-primary" id="thumbnail_text">サムネイル</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="thumbnail" id="thumbnail" data-show="thumbnail_show" data-text="thumbnail_text" class="form-control" placeholder="avatar" accept="image/*">
                      <div class="thumbnail">
                        <img id="thumbnail_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($video->thumbnail); ?>" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="thumbnail_detail" class="btn btn-sm btn-primary" id="thumbnail_detail_text">サムネイルの詳細</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="thumbnail_detail" id="thumbnail_detail" data-show="thumbnail_detail_show" data-text="thumbnail_detail_text" class="form-control" placeholder="avatar" accept="image/*">
                      <div class="thumbnail_detail">
                        <img id="thumbnail_detail_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($video->thumbnail_detail); ?>" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="pdf" class="btn btn-sm btn-primary" id="pdf_text">PDFを選択</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0"  name="pdf_edit" id="pdf" data-text="pdf_text" class="form-control" placeholder="pdf" accept="application/pdf">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea class="form-control CKEDITOR" rows="10" id="discription" name="discription" placeholder="説明"><?php echo e($video->discription); ?></textarea>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <?php echo Form::text('ads_text', $video->ads_text, ['placeholder' => 'ads text', 'class'=>'form-control']); ?>

                    </div>
                  <!-- </div>
                  <div class="form-group row"> -->
                    <div class="col-md-3">
                      <?php echo Form::text('ads_url', $video->ads_url, ['placeholder' => 'ads url', 'class'=>'form-control']); ?>

                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <?php echo Form::text('ads_text_2', $video->ads_text_2, ['placeholder' => 'ads text 2', 'class'=>'form-control']); ?>

                    </div>
                  <!-- </div>
                  <div class="form-group row"> -->
                    <div class="col-md-3">
                      <?php echo Form::text('ads_url_2', $video->ads_url_2, ['placeholder' => 'ads url 2', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  

                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('sponser', $video->sponser, ['placeholder' => 'スポンサー', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_date" class="form-control datetimepicker" id="start_date" placeholder="開始時間" value="<?php echo e($video->start_date); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_date" class="form-control datetimepicker" id="end_date" placeholder="終了時間"  value="<?php echo e($video->end_date); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('related[]', $videos, '', ['class'=>'form-control  select2-video-mutil', 'multiple'=>'multiple', 'id'=>'select2-video-mutil']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="banner" class="btn btn-sm btn-primary" id="banner_text">Banner</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="banner" id="banner" data-show="banner_show" data-text="banner_text" class="form-control" placeholder="avatar" accept="image/*">
                      <div class="banner">
                        <img id="banner_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($video->banner); ?>" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('url', $video->url, ['placeholder' => 'Url', 'class'=>'form-control']); ?>

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
                    'channel_id': {
                        required: true,
                    },
                    'title': {
                        required: true,
                    },
                    
                    'discription':{
                      required: true,
                    },
                    'sponser':{
                      required: true,
                    },
                    
                },

                messages: {
                    'channel_id': {
                        required:  "チャンネルが間違っています。",
                    },
                    'title': {
                        required:  "カタイトルが間違っています。",
                    },
                    
                    'discription':{
                        required:"説明が間違っています。",
                    },
                    'sponser': {
                        required:  "スポンサーが間違っています。",
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
        
        $("#thumbnail").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#thumbnail_detail").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#banner").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#pdf").change(function() {
          readURLlb(this, $(this).attr('data-text'));
        });

        var val = "<?php echo e($valj); ?>"; 
        var ar = JSON.parse(val) ;


        var select = $("#select2-video-mutil").select2({
            placeholder: "関連した",
            maximumSelectionLength : 3,
        });
         $("#select2-video-mutil").val(ar).trigger("change")

        $(document).on('change','#change_channel', function(){
          var obj = $(this);
          $.ajax({url: "<?php echo e(route('videos.ajaxrelated')); ?>?id="+$(this).val(), success: function(result){
            var obj = JSON.parse(result);
            console.log(obj);
            $("#select2-video-mutil").select2({
                placeholder: "関連した",
                data: obj,
                maximumSelectionLength : 3,
            }).trigger('change');
          }});
        });
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>