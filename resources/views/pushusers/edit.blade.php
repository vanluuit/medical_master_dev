@extends('layouts.admin')
@section('content')
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
              {!! Form::open(array('route' => ['pushusers.update', $user->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
              @method('PUT')
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6">
                    @includeIf('notification', ['errors' => $errors])
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="username" value="{{$user->username}}" class="form-control" placeholder="ユーザ名" readonly="">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <input type="text" name="password" id="password"  value="{{$user->password}}" class="form-control" placeholder="パスワード">
                  </div>

                  <div class="col-md-2">
                    <button type="button" class="btn btn-success" id="genrate_pass">パスワードを生成</button>
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('category_id', $categories, $user->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
              </div>
              <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              {!! Form::close() !!}
          </div>
        </div>
    </div>
</div>
@stop  

@section('script') 
    <script>
        $(document).ready(function(){
            var validateForm = $('#validate').validate({
                rules: {
                    'password': {
                        minlength: 6,
                        required: true,
                    },
                    'category_id': {
                        required: true,
                    }
                },

                messages: {
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
@stop   