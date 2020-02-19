@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Users</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title m-b-0 float-left">User edit</h5>
                <!-- <a href="{{route('users.index')}}" class="btn btn-info btn-sm float-right">List user</a> -->
              </div>
              <div class="border-bottom"></div>
              <div class="card-body">
                {!! Form::open(['route' => ['push.users.update', $user->id], 'id'=>'validate','enctype'=>'multipart/form-data']) !!}
                @method('PUT')
                <div class="form-group row">
                  <div class="col-md-6">
                    @includeIf('notification', ['errors' => $errors])
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="file" style="display:none;" name="avatar" id="avatar" class="form-control" placeholder="avatar" accept="image/*">
                    <label for="avatar"><img id="avatar_show" src="{{URL::to('/')}}/{{$user->avatar}}" alt="" /></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="email" value="{{$user->email}}" value="{{$user->email}}" class="form-control" placeholder="メールアドレス" disabled="">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" name="password" id="password" value="{{$user->password}}" class="form-control" placeholder="パスワード">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" name="password_conf" value="{{$user->password}}" class="form-control" placeholder="パスワード(確認)">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="nickname" value="{{$user->nickname}}" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" name="lastname" value="{{$user->lastname}}" class="form-control" placeholder="姓">
                  </div>
                  <div class="col-md-3">
                    <input type="text" name="firstname" value="{{$user->firstname}}" class="form-control" placeholder="名">
                  </div>
                  
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" name="firstname_k" value="{{$user->firstname_k}}" class="form-control" placeholder="セイ">
                  </div>
                  <div class="col-md-3">
                    <input type="text" name="lastname_k" value="{{$user->lastname_k}}" class="form-control" placeholder="メイ">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" name="birthday" class="form-control" id="datepicker-autoclose" placeholder="生年月日" value="{{$user->birthday}}">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('sex', sex(), $user->sex, ['placeholder' => '性別', 'class'=>'form-control']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('career_id', $careers, $user->career_id, ['placeholder' => '職業選択', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="hospital_name" value="{{$user->hospital_name}}" class="form-control" placeholder="勤め先の病院・医療機関名">
                  </div>
                </div>
                {{-- <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="area_hospital" value="{{$user->area_hospital}}" class="form-control" placeholder="勤め先の病院・医療機関の都道府県">
                  </div>
                </div> --}}
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('area_hospital', $area_hospital, $user->area_hospital, ['placeholder' => '勤め先の病院・医療機関の都道府県', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="city_hospital" value="{{$user->city_hospital}}" class="form-control" placeholder="勤め先の病院・医療機関の市区町村">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    {!! Form::select('faculty_id', $facultys, $user->faculty_id, ['placeholder' => '主な診療科目', 'class'=>'form-control select2']) !!}
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
                    'password': {
                        minlength: 6,
                        required: true,
                    },
                    'password_conf': {
                        equalTo: "#password",
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

        $(document).ready(function(){
          $('.association_select').each(function(){
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
        });


        var val  = "{{ $associ }}";
        var ar = JSON.parse(val) ;
        $(".select2-member-mutil-edit").select2({
            placeholder: "学会選択",
            // val : ['3','4','5'],
        });
        $(".select2-member-mutil-edit").val(ar).trigger("change")

    </script>
@stop   