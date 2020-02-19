@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Default</h4>
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
                  <h5 class="card-title">Default edit</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['newdefault.update', $newdf->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  @method('PUT')
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('copyright', $newdf->copyright, ['placeholder' => 'copyright', 'class'=>'form-control']) !!}
                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('param', $newdf->param, ['placeholder' => 'param', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="thumbnail" id="thumbnail" data-show="thumbnail_show" data-text="thumbnail_text" class="form-control" placeholder="avatar" accept="image/*">
                      <label for="thumbnail" class="btn btn-sm btn-primary" id="thumbnail_text">サムネイル</label>
                      <div class="thumbnail">
                        <img id="thumbnail_show" src="{{URL::to('/')}}/{{$newdf->thumbnail}}" alt="" />
                      </div> 
                      @if($newdf->thumbnail)
                        <div class="custom-control custom-checkbox mr-sm-2" style="margin-top:10px;">
                            <input type="checkbox" class="custom-control-input setshow" value="{{$newdf->id}}" name="delete" id="shownews_{{$newdf->id}}">
                            <label class="custom-control-label" for="shownews_{{$newdf->id}}">&nbsp;&nbsp;&nbsp;delete thumbnail</label>
                        </div>
                      @endif
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
    $("#thumbnail").change(function() {
      readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
    });
  </script>
@stop   