
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
                  <h5 class="card-title">News get by rss</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => 'news.postrss', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_id', $cate, '', ['placeholder' => '学会選択', 'class'=>'form-control select2','disabled'=>'disabled', 'id'=>'category_select_hh']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_new_id1', $categories, '', ['placeholder' => 'カテゴリー', 'class'=>'form-control select2','disabled'=>'disabled', 'id'=>'category_select']); ?>

                       <input type="hidden" name="category_new_id" id="category_new_id">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      
                      <?php echo Form::select('rss', $rssurls, '', ['placeholder' => 'Rss Url', 'class'=>'form-control select2', 'id'=>'select_url']); ?>

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
      $(document).on('change','#select_url', function(){
        var id = $(this).val();
        $.ajax({url: "<?php echo e(route('news.ajax_cate_url')); ?>?id="+$(this).val(), success: function(result){
          $('#category_select').val(result).trigger('change.select2');
        }});
        $.ajax({url: "<?php echo e(route('rssnews.ajax_cate_url')); ?>?id="+$(this).val(), success: function(result){
          console.log(result);
            $('#category_select_hh').val(result).trigger('change.select2');
          }});
      });
      $(document).ready(function(){
            var validateForm = $('#validate').validate({
                rules: {
                    'rss': {
                        required: true,
                    },
                    'category_id': {
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
                    'rss': {
                        required:  "rssが間違っています。",
                    },
                    'category_id': {
                        required:  "全ての学会が間違っています。",
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
    </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>