@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Users</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List User</h5>
                    <a href="{{route('admin.create')}}" class="btn btn-info btn-sm float-right">Add admin</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>nickname</th>
                          <th>email</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($users)
                        @foreach($users as $key => $user)
                          <tr>
                            <td>{{$user->nickname}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('admin.edit',$user->id)}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('admin.delete',$user->id)}}">Delete</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                </div>
                <div class="float-center">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
@section('script')
  <script>
    $(document).on('change','.setpro', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var pro = 1;
      }else{
        var pro = 0;
      }
      $.ajax({url: "{{route('users.ajaxpro')}}?id="+$(this).val()+'&pro='+pro, success: function(result){
        if(result == 0){
          alert('The user has been deleted pro');
        }else{
          alert('user has been added pro');
        }
      }});
    });
  </script>
@stop  