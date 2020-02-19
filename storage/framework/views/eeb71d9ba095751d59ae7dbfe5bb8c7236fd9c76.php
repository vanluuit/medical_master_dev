
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">News</h4>
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
                  <h5 class="card-title">News edit</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['news.update', $News->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_id', $cate, $News->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_new_id', $categories, $News->category_new_id, ['placeholder' => 'カテゴリー', 'class'=>'form-control association_select']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('title', $News->title, ['placeholder' => 'タイトル', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="media" id="media" data-show="media_show" data-text="media_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="media" class="btn btn-sm btn-primary" id="media_text">サムネイル</label>
                      <div class="thumbnail">
                        <img id="media_show" src="<?php echo e($News->media); ?>" alt="" />
                      </div> 
                    </div>
                  </div>
                  <!-- <div class="form-group row">
                    <div class="col-md-12">
                      <div class="custom-control custom-checkbox mr-sm-2">
                          <input type="checkbox" class="custom-control-input" value="1" name="publish" id="shownews" <?php if( $News->publish == 1 ): ?> checked <?php endif; ?> >
                          <label class="custom-control-label" for="shownews">&nbsp;&nbsp;Publish</label>
                      </div>
                    </div>
                  </div> -->
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('url', $News->url, ['placeholder' => 'url', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('copyright', $News->copyright, ['placeholder' => 'copyright', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="input-group date">
                        <input type="text" name="date" class="form-control datetimepicker" id="datetimepicker" placeholder="日付を公開" value="<?php echo e($News->date); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <textarea class="form-control" name="description" placeholder="description"><?php echo e($News->description); ?></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <textarea class="form-control CKEDITOR" id="my-editor" name="content"><?php echo e($News->content); ?></textarea>
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
                    'category_new_id': {
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
                    'category_new_id': {
                        required:  "カテゴリーが間違っています。",
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
        function readURL(input) {

          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
              $('#media_show').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
            $('label[for="media"]').text(input.files[0].name);
            // console.log(input.files[0].name);
          }
        }
        $("#media").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        var editor_config = {
            path_absolute : "<?php echo e(URL::to('/')); ?>/",
            selector:'#my-editor',
            setup : function(ed) {
              ed.on('init', function (ed) {
                  ed.target.editorCommands.execCommand("fontName", false, "Helvetica Neue");
              });
            },
            plugins: 'preview  fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount  imagetools textpattern help ',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | sizeselect | bold italic | fontselect |  fontsizeselect | numlist bullist outdent indent | removeformat',
            fontsize_formats: "8px 10px 12px 13px 14px 15px 16px 17px 18px 24px 36px",
            font_formats: "Andale Mono=andale mono,times;"+
            "Arial=arial,helvetica,sans-serif;"+
            "Arial Black=arial black,avant garde;"+
            "Book Antiqua=book antiqua,palatino;"+
            "Comic Sans MS=comic sans ms,sans-serif;"+
            "Courier New=courier new,courier;"+
            "Georgia=georgia,palatino;"+
            "Helvetica=helvetica;"+
            "Helvetica Neue=Helvetica Neue;"+
            "Impact=impact,chicago;"+
            "Symbol=symbol;"+
            "Tahoma=tahoma,arial,helvetica,sans-serif;"+
            "Terminal=terminal,monaco;"+
            "Times New Roman=times new roman,times;"+
            "Trebuchet MS=trebuchet ms,geneva;"+
            "Verdana=verdana,geneva;"+
            "Webdings=webdings;"+
            "Wingdings=wingdings,zapf dingbats",
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