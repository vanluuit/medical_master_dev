@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Push Users</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Push User</h5>
                    <a href="{{route('pushusers.create')}}" class="btn btn-info btn-sm float-right">Add user</a>
                </div>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <input type="text" name="s" value="{{request()->s}}" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <button type="submit" class="btn btn-primary"> Search </button>
                        </div>
                      </div>
                    </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>ユーザ名</th>
                          <th>Association</th>
                          <th style="width:210px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($pushusers)
                        @foreach($pushusers as $key => $user)
                          <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->category->category}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('pushusers.edit',$user->id)}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('pushusers.delete',$user->id)}}">Delete</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $pushusers->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  