
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">discussion</h4>
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
                  <h5 class="card-title">discussion edit</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['discussion.update', $discussion->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_id', $categories, $discussion->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_discussion_id', $cate, $discussion->category_discussion_id, ['placeholder' => 'カテゴリー', 'class'=>'form-control association_select']); ?>

                    </div>
                  </div> -->
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('title', $discussion->title, ['placeholder' => 'タイトル', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <textarea class="form-control" name="discription" placeholder="discription" rows="6"><?php echo e($discussion->discription); ?></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="image1" id="image1" data-show="image1_show" data-text="image1_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="image1" class="btn btn-sm btn-primary" id="image1_text">image1</label>
                      <div class="thumbnail">
                        <img id="image1_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($discussion->image1); ?>" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="image2" id="image2" data-show="image2_show" data-text="image2_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="image2" class="btn btn-sm btn-primary" id="image2_text">image2</label>
                      <div class="thumbnail">
                        <img id="image2_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($discussion->image2); ?>" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="image3" id="image3" data-show="image3_show" data-text="image3_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="image3" class="btn btn-sm btn-primary" id="image3_text">image3</label>
                      <div class="thumbnail">
                        <img id="image3_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($discussion->image3); ?>" alt="" />
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
                    'category_discussion_id': {
                        required: true,
                    },
                    'category_id': {
                        required: true,
                    },
                    'title': {
                        required: true,
                    },
                    // 'category_id': {
                    //     required: true,
                    // },
                    // 'content':{
                    //   required: true,
                    // }
                },

                messages: {
                    'category_discussion_id': {
                        required:  "カテゴリーが間違っています。",
                    },
                    'category_id': {
                        required:  "学会選択が間違っています。",
                    },
                    'title': {
                        required:  "カタイトルが間違っています。",
                    },
                    // 'category_id': {
                    //     required:  "学会選択が間違っています。",
                    // },
                    // content:{
                    //     required:"コンテンツが間違っています。",
                    // }
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
        // function readURL(input) {

        //   if (input.files && input.files[0]) {
        //     var reader = new FileReader();

        //     reader.onload = function(e) {
        //       $('#image1_show').attr('src', e.target.result);
        //     }

        //     reader.readAsDataURL(input.files[0]);
        //     $('label[for="image1"]').text(input.files[0].name);
        //     // console.log(input.files[0].name);
        //   }
        // }
        $("#image1").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#image2").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#image3").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        var editor_config = {
            path_absolute : "<?php echo e(URL::to('/')); ?>/",
            selector:'#my-editor',
            plugins: 'preview  fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount  imagetools textpattern help ',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat ',
            height: 400,
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>