@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Version</h4>
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
                  <h5 class="card-title float-left">Version edit @if($version->os == 0 ) (IOS) @else() (ANDROID) @endif</h5>
                  @if($version->os == 0 )
                  <a href="{{route('versions.index', ['os'=>1])}}" class="btn btn-info btn-sm float-right mg-r15">ANDROID</a>
                  @else()
                  <a href="{{route('versions.index')}}" class="btn btn-info btn-sm float-right">IOS</a>
                  @endif
                </div>

              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['versions.update', $version->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                  @method('PUT')
                  <input type="hidden" name="os" value="{{$version->os}}">
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('version', $version->version, ['placeholder' => 'version', 'class'=>'form-control']) !!}
                    </div>
                  </div> 
                  <div class="form-group row">
                    <div class="col-md-6">
                    
                    {!! Form::select('status', [1=>'not update',2=>'can update',3=>'mandatory update',4=>'mainternance'], $version->status, [ 'class'=>'form-control select2']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      <textarea class="form-control" rows="10" name="message" placeholder="message">{{$version->message}}</textarea>
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

