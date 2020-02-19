@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">TVProコンテンツ管理</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                {!! Form::open(array('route' => ['push.videos.question.edit', request()->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  <table style="width: 100%">
                    @for($i=0; $i<3; $i++)
                    <tr>
                      <td>Q{{$i+1}}</td>
                      <td colspan="5">
                        <div class="form-group">
                          <input type="hidden" name="question_id[{{$i}}]" value="{{@$questions[$i]->id}}">
                          {!! Form::text('question[]', @$questions[$i]->question, ['placeholder' => 'アンケート設問', 'class'=>'form-control']) !!}
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>
                        <div class="form-group">
                          <input type="hidden" name="answer_id[{{$i}}][]" value="{{@$questions[$i]->answers[0]->id}}">
                          {!! Form::text('answers['.$i.'][]', @$questions[$i]->answers[0]->answer, ['placeholder' => '種類' , 'class'=>'form-control']) !!}
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="hidden" name="answer_id[{{$i}}][]" value="{{@$questions[$i]->answers[1]->id}}">
                          {!! Form::text('answers['.$i.'][]', @$questions[$i]->answers[1]->answer, ['placeholder' => '種類' , 'class'=>'form-control']) !!}
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="hidden" name="answer_id[{{$i}}][]" value="{{@$questions[$i]->answers[2]->id}}">
                          {!! Form::text('answers['.$i.'][]', @$questions[$i]->answers[2]->answer, ['placeholder' => '種類' , 'class'=>'form-control']) !!}
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="hidden" name="answer_id[{{$i}}][]" value="{{@$questions[$i]->answers[3]->id}}">
                          {!! Form::text('answers['.$i.'][]', @$questions[$i]->answers[3]->answer, ['placeholder' => '種類' , 'class'=>'form-control']) !!}
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="hidden" name="answer_id[{{$i}}][]" value="{{@$questions[$i]->answers[4]->id}}">
                          {!! Form::text('answers['.$i.'][]', @$questions[$i]->answers[4]->answer, ['placeholder' => '種類' , 'class'=>'form-control']) !!}
                        </div>
                      </td>
                    </tr>
                    @endfor
                  </table>
                  
                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary float-right">作成</button>
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
                    'url': {
                        required: true,
                    }
                },

                messages: {
                    'name': {
                        required:  "カタイトルが間違っています。",
                    },
                    'url': {
                        required:  "URLが間違っています。",
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