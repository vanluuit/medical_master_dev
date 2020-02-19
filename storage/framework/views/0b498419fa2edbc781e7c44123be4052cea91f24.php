
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Push Users</h4>
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
                  <h5 class="card-title">User Push add</h5>
                </div>
              </div>
              <?php echo Form::open(array('route' => 'pushusers.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6">
                    <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="username" class="form-control" placeholder="ユーザ名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <input type="text" name="password" id="password" class="form-control" placeholder="パスワード">
                  </div>

                  <div class="col-md-2">
                    <button type="button" class="btn btn-success" id="genrate_pass">パスワードを生成</button>
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-6">
                    <?php echo Form::select('category_id', $categories, '', ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

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
                    'username': {
                        required: true,
                    },
                    'password': {
                        minlength: 6,
                        required: true,
                    },
                    'category_id': {
                        required: true,
                    }
                },

                messages: {
                    'username': {
                        required:  "ニックネームが間違っています。",
                    },
                    'password': {
                        minlength: "パスワードは6文字以上で入力してください。",
                        required:  "パスワードは6文字以上で入力してください。",
                    },
                    'category_id': {
                        required:  "学会選択が間違っています。",
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
            // select();
        });
        $(document).on('click','#genrate_pass', function(){
          $('#password').val(makeid(15));
        });
        function makeid(length) {
         var result           = '';
         var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789?}]{[)(;/';
         var charactersLength = characters.length;
         for ( var i = 0; i < length; i++ ) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
         }
         return result;
        }
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>