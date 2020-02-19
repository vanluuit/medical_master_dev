@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">NEWS</h4>
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
                  <h5 class="card-title">CRONJOB</h5>
                  
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['user.list.review'], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::select('id', $users, '', ['class'=>'form-control select2']) !!}
                    </div>
                    <div class="col-md-3">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                  {!! Form::close() !!}
                  <table class="table">
                      <thead>
                        <tr>
                          <th>Nickname</th>
                          <th>Email</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($userreviews)
                        @foreach($userreviews as $key => $userreview)
                          <tr>
                            <td>{{$userreview->nickname}}</td>
                            <td>{{$userreview->email}}</td>
                            <td>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('user.list.review', ['id'=>$userreview->id])}}">Delete</a>
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
    </div>
</div>
@stop  

@section('script') 
    <script>
    </script>
@stop   