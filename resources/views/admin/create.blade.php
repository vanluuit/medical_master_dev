@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Admin</h4>
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
                  <h5 class="card-title">Admin add</h5>
                </div>
              </div>
              {!! Form::open(array('route' => 'admin.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6">
                    @includeIf('notification', ['errors' => $errors])
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="nickname" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="email" class="form-control" placeholder="メールアドレス">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" name="password" id="password" class="form-control" placeholder="パスワード">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" name="password_conf" class="form-control" placeholder="パスワード(確認)">
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
                    'email': {
                        required: true,
                        email:true,
                    },
                    'nickname': {
                        required: true,
                    },
                    'association_id[]': {
                      required: true,
                    },
                    'member_code[]': {
                      required: true,
                    },
                    'password': {
                        minlength: 6,
                        required: true,
                    },
                    'password_conf': {
                        equalTo: "#password"
                    },
                    'hospital_name': {
                        required: true,
                    },
                },

                messages: {
                    'email': {
                        required:  "メールアドレスが間違っています。",
                        email: "メールアドレスが間違っています。",
                    },
                    'nickname': {
                        required:  "ニックネームが間違っています。",
                    },
                    'password': {
                        minlength: "パスワードは6文字以上で入力してください。",
                        required:  "パスワードは6文字以上で入力してください。",
                    },
                    'password_conf': {
                        required:  "確認用パスワードが一致していません。",
                        equalTo: "確認用パスワードが一致していません。",
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
        function readURL(input) {

          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
              $('#avatar_show').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
          }
        }

        $("#avatar").change(function() {
          readURL(this);
        });
        $(document).on('click','.add_item', function(){
          // var d = new Date();
          // var n = d.getTime();
          // var obj = $(this);
          var str = $('#mau').html();
          str = str.replace('xxxxx', 'select');
          $('#association').append(str);
          $('.select').select2();
        });
        $(document).on('click','.remove_item', function(){
          var obj = $(this);
          var str = obj.closest('.row').remove();
          // $(".select2").select2();
        });

        $(document).on('change','.association_select', function(){
          var obj = $(this);
          $.ajax({url: "{{route('members.ajaxcode')}}?category_id="+$(this).val(), success: function(result){
            var ar = JSON.parse(result);
            var str ='<option selected=\"selected\" value=\"\">学会会員番号</option>\"';
            for (var i = 0; i < ar.length; i++) {
              str = str+'<option value="'+ar[i]+'">'+ar[i]+'</option>'
            }
            obj.closest('.row').find('.member_code').html(str);
          }});
        });

        $(".select2-member-mutil").select2({
            placeholder: "学会選択",
        });
    </script>
@stop   