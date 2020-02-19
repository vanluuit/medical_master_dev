@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">prs</h4>
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
                  <h5 class="card-title">pr add</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => 'prs.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  <div class="form-group row">
                    <div class="col-md-6">
                      @includeIf('notification', ['errors' => $errors])
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('category_id', $categories, '', ['placeholder' => '学会選択', 'class'=>'form-control select2', 'id'=>'change_association']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::select('video_id', [], '', ['placeholder' => 'video', 'class'=>'form-control select2', 'id'=>'video_id_sl']) !!}
                    </div>
                  </div> 
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('title', '', ['placeholder' => 'title', 'class'=>'form-control']) !!}
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
                    'category_id': {
                        required: true,
                    },
                    'video_id': {
                        required: true,
                    },
                    'image': {
                        required: true,
                    }
                },

                messages: {
                    'category_id': {
                        required:  "カテゴリーが間違っています。",
                    },
                    'video_id': {
                        required:  "videoが間違っています。",
                    },
                    'image':{
                        required:"imageが間違っています。",
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
        $("#image").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });

        $(document).on('change','#change_association', function(){
          var obj = $(this);
          $.ajax({url: "{{route('videos.ajax_association')}}?id="+$(this).val(), success: function(result){

            var ar = JSON.parse(result);
            var str ='<option selected=\"selected\" value=\"\">video</option>\"';
            for (var i = 0; i < ar.length; i++) {
              str = str+'<option value="'+ar[i]['id']+'">'+ar[i]['title']+'</option>'
            }
           $('#video_id_sl').html(str);

          }});
        });
    </script>
@stop   