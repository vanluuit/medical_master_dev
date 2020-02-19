@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Events</h4>
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
                  <h5 class="card-title">Event add</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => 'events.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  <div class="form-group row">
                    <div class="col-md-6">
                      @includeIf('notification', ['errors' => $errors])
                    </div>
                  </div>
                  <input type="hidden" name="seminar_id" value="{{request()->seminar_id}}">
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('name', '', ['placeholder' => 'タイトル', 'class'=>'form-control']) !!}
                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('location', '', ['placeholder' => 'location', 'class'=>'form-control']) !!}
                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="custom-control custom-checkbox mr-sm-2">
                        <input type="checkbox" class="custom-control-input setpro" id="lunch" value="1">
                        <label class="custom-control-label " for="lunch"> &nbsp;&nbsp;lunch</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_time" class="form-control datetimepicker" id="" placeholder="start time">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="start_time-error" class="error" for="start_time"></label>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_time" class="form-control datetimepicker" id="" placeholder="end time">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="end_time-error" class="error" for="end_time"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea name="excerpt" id="" cols="30" rows="10" placeholder="excerpt" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea name="content" id="" cols="30" rows="10" placeholder="content" class="form-control"></textarea>
                    </div>
                  </div>
                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
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
                    'location': {
                        required: true,
                    },
                    'name': {
                        required: true,
                    },
                    'start_time': {
                        required: true,
                    },
                    'end_time':{
                      required: true,
                    },
                    'excerpt':{
                      required: true,
                    },
                    'content':{
                      required: true,
                    }
                },

                messages: {
                    'location': {
                      required:  "locationが間違っています。",
                    },
                    'name': {
                      required:  "カタイトルが間違っています。",
                    },
                    'start_time': {
                      required:  "start timeが間違っています。",
                    },
                    'end_time':{
                      required:"end timeが間違っています。",
                    },
                    'excerpt': {
                      required:  "概要が空欄です。",
                    },
                    'content':{
                      required:"contentが間違っています。",
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
        });
        $("#media").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        // CKEDITOR.replace( 'content' );
    </script>
@stop   