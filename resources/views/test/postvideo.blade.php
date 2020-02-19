@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Videos</h4>
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
                  <h5 class="card-title">Videos add</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => 'test.postvideo1', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  <div class="form-group row">
                    <div class="col-md-6">
                      @includeIf('notification', ['errors' => $errors])
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="video" class="btn btn-sm btn-primary" id="video_text">ビデオ</label>
                      <input type="file" name="video_create" id="video" data-text="video_text" class="form-control" placeholder="video" accept="video/*">
                      
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
