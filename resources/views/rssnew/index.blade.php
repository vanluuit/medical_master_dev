@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">RSS URL</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List RSS url</h5>
                    <a href="{{route('rssnews.create')}}" class="btn btn-info btn-sm float-right">Add rss url</a>
                    <a href="{{route('news.index')}}" class="btn btn-info btn-sm float-right mg-r15">list News</a>
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>name</th>
                          <th>url</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($rssnews)
                        @foreach($rssnews as $key => $rssnew)
                          <tr>
                            <td>{{$rssnew->id}}</td>
                            <td>{{$rssnew->name}}</td>
                            <td>{{$rssnew->url}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('rssnews.edit',$rssnew->id)}}">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="{{route('rssnews.show',$rssnew->id)}}">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('rssnews.delete',$rssnew->id)}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $rssnews->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
