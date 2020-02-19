@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Member</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {!! Form::open(array('route' => 'push.notification.importMember.post', 'enctype'=>'multipart/form-data')) !!}
                    
                      <div class="row">
                        <div class="col-md-12">
                          <input type="file" name="member" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required="">
                          <button type="submit" class="btn btn-primary btn-sm ">Import</button>
                        </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Member</h5>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>学会選択</th>
                          <th>学会会員番号</th>
                          <th style="width:100px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($members)
                        @foreach($members as $key => $member)
                          <tr>
                            <td>{{$member->id}}</td>
                            <td>{{$member->association->category}}</td>
                            <td>{{$member->code}}</td>
                            <td>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('members.delete',$member->id)}}">Delete</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $members->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop  