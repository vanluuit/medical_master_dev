@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title"> Default</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">Default</h5>
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          {{-- <th>id</th> --}}
                          <th>thumbnail</th>
                          <th>copyright</th>
                          <th>param</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($new)
                          <tr>
                            <td>
                              <div class="thumbnail">
                                <img id="thumbnail_show" src="{{URL::to('/')}}/{{$new->thumbnail}}" alt="" />
                              </div> 
                              
                            </td>
                            <td>{{$new->copyright}}</td>
                            <td>{{$new->param}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('newdefault.edit',$new->id)}}">Edit</a>

                            </td>
                          </tr>
                        @endif
                      </tbody>
                </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
