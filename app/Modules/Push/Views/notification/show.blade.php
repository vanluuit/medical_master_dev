@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">News</h4>
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
                  <h5 class="card-title">{{$News->title}}</h5>
                </div>
              </div>
              <div class="card-body">
                  <div class="form-group row">
                    <div class="col-md-6">
                       <input type="text" class="form-control" name="" disabled="" value="{{$cate[$News->category_id]}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <input type="text" class="form-control" name="" disabled="" value="{{$categories[$News->category_new_id]}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="" disabled="" value="{{$News->title}}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <img id="media_show" src="{{URL::to('/')}}/{{$News->media}}" alt="" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-12">
                      {!! $News->content !!}
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
@stop 