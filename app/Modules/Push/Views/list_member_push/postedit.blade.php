@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">プッシュ機能</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                {!! Form::open(array('route' => ['push.listmemberpushs.update', $list->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                    @method('PUT')
                    @if(count($data))
                        @foreach($data as $mem)
                            <input type="hidden" name="member_id[]" value="{{$mem}}">
                        @endforeach
                    @endif

                  <div class="form-group row">
                    <div class="col-md-12">
                      {{$name}}
                      <input type="hidden" name="name" value="{{$name}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6">
                      アップロード件数
                    </div>
                    <div class="col-sm-6">
                      {{$total}}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-6">
                      会員登録済み件数
                    </div>
                    <div class="col-sm-6">
                      {{$user_total}}
                    </div>
                  </div>
                </div>
                <div class="border-top">
                    <div class="card-body" style="min-height: 60px">
                        <button type="button" onclick="window.history.back();" class="btn btn-primary float-right">編集</button>
                        <button type="submit" class="btn btn-primary float-right mg-r15">作成</button>
                    </div>
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
                    'name': {
                        required: true,
                    },
                    'member': {
                        required: true,
                    },
                },

                messages: {
                    'name': {
                        required:  "新規リストタイトルが間違っています。",
                    },
                    'member': {
                        required:  "Fileが間違っています。",
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
              $('#media_show').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
            $('label[for="media"]').text(input.files[0].name);
            // console.log(input.files[0].name);
          }
        }
        $("#media").change(function() {
          readURL(this);
          // var filename = $(this).val().replace(/C:\\fakepath\\/i, '');

        });
        CKEDITOR.replace( 'content' );
    </script>
@stop   