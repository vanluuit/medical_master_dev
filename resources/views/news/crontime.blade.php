@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">NEWS</h4>
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
                  <h5 class="card-title">CRONJOB</h5>
                  
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['new.postcrontime'], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                <div class="form-group row">
                    <div class="col-md-3">
                      {!! Form::select('time', $time, '', ['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                      {!! Form::select('minute', $minute, '', ['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-3">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                  {!! Form::close() !!}
                  <table class="table">
                      <thead>
                        <tr>
                          <th>Time</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($crontime)
                        @foreach($crontime as $key => $cron)
                          <tr>
                            <td>{{($cron->time < 10) ? 0 : ''}}{{$cron->time}}:{{($cron->minute < 10) ? 0 : ''}}{{$cron->minute}}</td>
                            <td>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('new.crontime.delete', $cron->id)}}">Delete</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                </div>
              </div>
              
              
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
                    'category_id': {
                        required: true,
                    },
                    'title': {
                        required: true,
                    },
                    'url': {
                        required: true,
                    },
                    'date':{
                      required: true,
                    }
                },

                messages: {
                    'category_id': {
                        required:  "カテゴリーが間違っています。",
                    },
                    'title': {
                        required:  "カタイトルが間違っています。",
                    },
                    'url': {
                        required:  "URLが間違っています。",
                    },
                    'date':{
                        required:"dateが間違っています。",
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
        CKEDITOR.replace( 'content' );
    </script>
@stop   