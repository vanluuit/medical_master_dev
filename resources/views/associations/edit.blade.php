@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Associations</h4>
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
                  <h5 class="card-title">Edit associations</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['associations.update', $category->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  @method('PUT')
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('category', $category->category, ['placeholder' => '学会選択', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  {{-- <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('code', '', ['placeholder' => 'id', 'class'=>'form-control']) !!}
                    </div>
                  </div> --}}
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
                    'category': {
                        required: true,
                    },
                },

                messages: {
                    'category': {
                        required:  "学会選択が間違っています。",
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