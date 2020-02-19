@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Notification</h4>
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
                  <h5 class="card-title">Notification add and push</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['notification.update', $message->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                @method('PUT')
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('title', $message->title, ['placeholder' => 'タイトル', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea class="form-control" name="message" cols="30" rows="10" placeholder="message">{{$message->message}}</textarea>
                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('type', [1=>'Quick',2=>'TVpro',3=>'News',4=>'Mypage'], $message->type, ['placeholder' => 'select tab', 'class'=>'form-control select2']) !!}
                    </div>
                  </div>
                  
                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" name="push" value="1" class="btn btn-primary">push</button>
                  <button type="submit" name="push" value="0" class="btn btn-primary">Submit</button>
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
                    'message': {
                        required: true,
                    },
                },

                messages: {
                    'message': {
                        required:  "messageが間違っています。",
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
        $("#media").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        CKEDITOR.replace( 'content' );
    </script>
@stop   