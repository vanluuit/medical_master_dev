@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">RSS</h4>
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
                  <h5 class="card-title">RSS edit</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => 'topics.posttopics', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('category_id', $categories, '', ['placeholder' => '学会選択', 'class'=>'form-control select2']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="topic_excel" class="btn btn-sm btn-primary" id="topic_excel_text">select file excel</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="topic_excel" id="topic_excel" data-show="topic_excel_show" data-text="topic_excel_text" class="form-control" placeholder="avatar" accept=".xls,.xlsx">
                    </div>
                  </div>
                  <!-- http://www.jrs.or.jp/modules/whatsnew/topics.php -->
                  
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
                    'topic_excel': {
                        required: true,
                    },
                },

                messages: {
                    'category_id': {
                        required:  "カテゴリーが間違っています。",
                    },
                    'topic_excel': {
                        required:  "Topic Excel が間違っています。",
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
        $("#topic_excel").change(function() {
          readURLlb(this, $(this).attr('data-text'));
        });
        CKEDITOR.replace( 'content' );
    </script>
@stop   