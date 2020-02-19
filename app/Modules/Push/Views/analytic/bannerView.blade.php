@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">Banner</h4>
            <a href="{{route('push.analytic.banner')}}" class="btn btn-info btn-sm float-right">Associations</a> 
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
              <form action="" method="GET">
                    <div class="form-group row">
                      <div class="col-md-3">
                        <div class="input-group date">
                          <input type="text" name="start_day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="{{$start_day}}">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-1 text-center">
                        ~
                      </div>
                      <div class="col-md-3">
                        <div class="input-group date">
                          <input type="text" name="end_day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="{{$end_day}}">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <button type="submit" class="btn btn-primary">GET</button>
                      </div>
                    </div>
                  </form>
            </div>
            <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th style="width:200px">image</th>
                          <th>Type</th>
                          <th>video</th>
                          <th>Position</th>
                          <th>total</th>
                        </tr>
                      </thead>
                      <tbody>
                       @if($banners)
                        @foreach($banners as $key => $banner)
                          <tr>
                            <td>{{$banner->id}}</td>
                            <td><img style="width: 160px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$banner->image}}" alt=""></td>
                            <td>@if($banner->type==1) Content @else Url @endif</td>
                            <td>@if($banner->type==1) {{@$banner->video->title}} @else {{@$banner->url}} @endif</td>
                            <td>{{@$banner->location}}</td>
                            <td>{{@$banner->banner_views_count}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $banners->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  