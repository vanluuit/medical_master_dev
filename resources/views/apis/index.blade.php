@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Api</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Api</h5>
                    <a href="{{route('apis.create')}}" class="btn btn-info btn-sm float-right">Add key</a>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>Key</th>
                          <th>Created</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($apis)
                          @foreach($apis as $key=>$api)
                          <tr>
                            <td>{{$api->id}}</td>
                            <td>{{$api->api_key}}</td>
                            <td>{{$api->created_at}}</td>
                            <td>
                              <a class="btn btn-danger btn-sm" href="{{route('apis.destroy',$api->id)}}">Delete</a>
                            </td>
                          </tr>
                          @endforeach
                        @endif
                      </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>
@stop  