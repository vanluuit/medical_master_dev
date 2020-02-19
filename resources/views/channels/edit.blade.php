@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Channels</h4>
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
                  <h5 class="card-title">channels Edit</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['channels.update', $channel->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                @method('PUT')
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('category_id[]', $categories, '', ['class'=>'form-control select2-cate-mutil-edit', 'multiple'=>'multiple']) !!}
                    </div>
                  </div>
                  {{-- <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('category_channel_id', $cates, $channel->category_channel_id, ['placeholder' => 'カテゴリー', 'class'=>'form-control select2']) !!}
                    </div>
                  </div> --}}
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('title', $channel->title, ['placeholder' => 'タイトル', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="logo" id="logo" data-show="logo_show" data-text="logo_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="logo" class="btn btn-sm btn-primary" id="logo_text">サムネイル</label>
                      <div class="thumbnail">
                        <img id="logo_show" src="{{URL::to('/')}}/{{$channel->logo}}" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea class="form-control CKEDITOR" rows="10" id="discription" name="discription" placeholder="説明">{{$channel->discription}}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('sponser', $channel->sponser, ['placeholder' => 'スポンサー', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <div class="input-group date">
                        <input type="text" name="publish_date" class="form-control datetimepicker" id="datetimepicker" placeholder="日付を公開" value="{{$channel->publish_date}}">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="datetimepicker-error" class="error" for="datetimepicker"></label>
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
                    'category_id[]': {
                        required: true,
                    },
                    'title': {
                        required: true,
                    },
                    'discription':{
                      required: true,
                    },
                    'sponser':{
                      required: true,
                    },
                    'publish_date':{
                      required: true,
                    }
                },

                messages: {
                    'category_id[]': {
                        required:  "アソシエーションが間違っています。",
                    },
                    'title': {
                        required:  "カタイトルが間違っています。",
                    },
                    'discription':{
                        required:"説明が間違っています。",
                    },
                    'sponser': {
                        required:  "スポンサーが間違っています。",
                    },
                    'publish_date':{
                        required:"日付を公開が間違っています。",
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
        
        $("#logo").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        var val  = "{{ $channel->association }}";
        var ar = JSON.parse(val) ;
        console.log(val);
        $(".select2-cate-mutil-edit").select2({
            placeholder: "アソシエーション",
            // val : ['3','4','5'],
        });
        $(".select2-cate-mutil-edit").val(ar).trigger("change")
    </script>
@stop   