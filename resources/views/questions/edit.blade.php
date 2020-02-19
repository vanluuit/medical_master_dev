@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Question</h4>
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
                  <h5 class="card-title">Question add</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['questions.update', $question->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                @method('PUT')
                  
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('question', $question->question, ['placeholder' => 'question', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <p>answer</p>
                  @if($answer)  
                  @foreach($answer as $key => $ans)
                    @php $s = $key+1 @endphp
                    <div class="form-group row">
                      <div class="col-md-6">
                        <input type="hidden" name="answer_id[]" value="{{$ans->id}}">
                        {!! Form::text('answers[]', $ans->answer, ['placeholder' => 'answer '.$s , 'class'=>'form-control']) !!}
                      </div>
                    </div>
                  @endforeach
                  @endif
<!--                   @if(count($answer)<4)
                    @for($i=1;$i <= 5 - count($answer); $i++)
                      @php
                        $s = count($answer) + $i;
                        $sh = 'answer '.$s
                      @endphp
                      <div class="form-group row">
                      <div class="col-md-6">
                        <input type="hidden" name="answers_id[]" value="">
                        {!! Form::text('answers[]', '', ['placeholder' => $sh, 'class'=>'form-control']) !!}
                      </div>
                    </div>
                    @endfor
                  @endif -->
                  
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