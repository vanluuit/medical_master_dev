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
                  <h5 class="card-title">Event edit</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['events.update', $event->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                @method('PUT')
                  <div class="form-group row">
                    <div class="col-md-6">
                      @includeIf('notification', ['errors' => $errors])
                    </div>
                  </div>
                  <input type="hidden" name="seminar_id" value="{{$event->seminar_id}}">
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('category_event_id', $categories, $event->category_event_id, ['placeholder' => 'カテゴリー', 'class'=>'form-control select2']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('theme_event_id', $theme, $event->theme_event_id, ['placeholder' => 'テーマ', 'class'=>'form-control select2']) !!}
                    </div>
                  </div>
                   
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('name_basis', $event->name_basis, ['placeholder' => '施設名', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('hall', $event->hall, ['placeholder' => '会場', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('floor', $event->floor, ['placeholder' => 'フロア', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('room', $event->room, ['placeholder' => '部屋', 'class'=>'form-control']) !!}
                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_time" class="form-control datetimepicker" id="" placeholder="start time" value="{{$event->start_time}}">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="start_time-error" class="error" for="start_time"></label>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_time" class="form-control datetimepicker" id="" placeholder="end time"  value="{{$event->end_time}}">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="end_time-error" class="error" for="end_time"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea name="preside" id="" cols="30" rows="10" placeholder="preside" class="form-control">{{$event->preside}}</textarea>
                    </div>
                  </div>
                  
                  @if($event->event_detail)
                    @foreach($event->event_detail as $k => $item)
                    <div class="cthl">
                      <div class="form-group row">
                        <div class="col-md-6">
                          {!! Form::text('topic_number['.$item->id.']', $item->topic_number, ['placeholder' => '演題番号', 'class'=>'form-control']) !!}
                        </div>
                      </div> 

                      <div class="form-group row">
                        <div class="col-md-12">
                          {!! Form::text('name['.$item->id.']', $item->name, ['placeholder' => '部屋', 'class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-6">
                          <textarea name="member[{{$item->id}}]" id="" cols="30" rows="10" placeholder="member" class="form-control">{{$item->member}}</textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-12">
                          <textarea name="content[{{$item->id}}]" id="" cols="30" rows="10" placeholder="抄録原稿A" class="form-control">{{$item->content}}</textarea>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  @endif
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