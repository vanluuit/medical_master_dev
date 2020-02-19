@extends('layouts.push')
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
                </div>
                <div class="card-body">
                    <form action="" method="GET">
                      
                      <div class="row">
                        <div class="col-md-6">
                          <input type="text" name="s" value="{{@request()->s}}" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <button type="submit" class="btn btn-primary"> Search </button>
                        </div>
                      </div>
                    </form>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>ニックネーム</th>
                          <th>性別</th>
                          <th>職業選択</th>
                          <th>メールアドレス</th>
                          <th>Status</th>
                          <th>request date</th>
                          <th>approve date</th>
                          <th>refuse date</th>
                          <!-- <th style="width:210px">Action</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        @if($users)
                        @foreach($users as $key => $user)
                          <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->nickname}}</td>
                            <td>{{sex()[$user->sex]}}</td>
                            <td>{{@$user->career->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                              @if(@$user->association[0]->status == 0)
                                request
                              @elseif(@$user->association[0]->status == 1)
                                approve
                              @else refuse
                              @endif

                            </td>
                            <!-- <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('users.edit',$user->id)}}">Edit</a>
                              <a class="btn btn-success btn-sm" href="{{route('users.show',$user->id)}}">Show</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('users.delete',$user->id)}}">Delete</a>
                            </td> -->
                            <td>{{@$user->association[0]->request_date}}</td>
                            <td>{{@$user->association[0]->approve_date}}</td>
                            <td>{{@$user->association[0]->refuse_date}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    
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