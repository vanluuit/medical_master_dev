@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">会員登録</h4>
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
                  <h5 class="card-title">新規アカウント作成</h5>
                </div>
              </div>
              {!! Form::open(array('route' => 'push.users.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6">
                    @includeIf('notification', ['errors' => $errors])
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <!-- <input type="file" style="display:none;" name="avatar" id="avatar" class="form-control" placeholder="avatar" accept="image/*"> -->
                    <label ><img id="avatar_show" src="/avatar.png" alt="" /></label>
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
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="nickname" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" name="lastname" class="form-control" placeholder="姓">
                  </div>
                  <div class="col-md-3">
                    <input type="text" name="firstname" class="form-control" placeholder="名">
                  </div>
                  
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" name="firstname_k" class="form-control" placeholder="セイ">
                  </div>
                  <div class="col-md-3">
                    <input type="text" name="lastname_k" class="form-control" placeholder="メイ">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" name="birthday" class="form-control" id="datepicker-autoclose" placeholder="生年月日">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('sex', sex(), '', ['placeholder' => '性別', 'class'=>'form-control']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('career_id', $careers, '', ['placeholder' => '職業選択', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="hospital_name" class="form-control" placeholder="勤め先の病院・医療機関名">
                  </div>
                </div>
                {{-- <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="area_hospital" class="form-control" placeholder="勤め先の病院・医療機関の都道府県">
                  </div>
                </div> --}}
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('area_hospital', $area_hospital, '', ['placeholder' => '勤め先の病院・医療機関の都道府県', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="city_hospital" class="form-control" placeholder="勤め先の病院・医療機関の市区町村">

                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('faculty_id', $facultys, '', ['placeholder' => '主な診療科目', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('member_id', $members, '', ['placeholder' => '学会会員番号', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
              </div>
              <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">登録</button>
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
                    'member_id': {
                      required: true,
                    },
                    'firstname': {
                      required: true,
                    },
                    'lastname': {
                      required: true,
                    },
                    'firstname_k': {
                      required: true,
                    },
                    'lastname_k': {
                      required: true,
                    },
                    'birthday': {
                      required: true,
                    },
                    'sex': {
                      required: true,
                    },
                    'career_id': {
                      required: true,
                    },
                    'area_hospital': {
                      required: true,
                    },
                    'city_hospital': {
                      required: true,
                    },
                    'faculty_id': {
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
                    
                    'member_id': {
                      required: "学会会員番号が間違っています。",
                    },
                    'firstname': {
                      required: "姓が間違っています。",
                    },
                    'lastname': {
                      required: "名が間違っています。",
                    },
                    'firstname_k': {
                      required: "セイが間違っています。",
                    },
                    'lastname_k': {
                      required: "メイが間違っています。",
                    },
                    'birthday': {
                      required: "生年月日が間違っています。",
                    },
                    'sex': {
                      required: "性別が間違っています。",
                    },
                    'career_id': {
                      required: "職業選択が間違っています。",
                    },
                    'area_hospital': {
                      required: "勤め先の病院・医療機関の都道府県が間違っています。",
                    },
                    'city_hospital': {
                      required: "勤め先の病院・医療機関の市区町村が間違っています。",
                    },
                    'faculty_id': {
                      required: "学会会員番号が間違っています。",
                    },
                    'password': {
                        minlength: "パスワードは6文字以上で入力してください。",
                        required:  "パスワードは6文字以上で入力してください。",
                    },
                    'password_conf': {
                        required:  "確認用パスワードが一致していません。",
                        equalTo: "確認用パスワードが一致していません。",
                    },
                    'hospital_name': {
                        required: "勤め先の病院・医療機関名が間違っています。",
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