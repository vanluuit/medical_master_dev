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
                    <h5 class="card-title m-b-0 float-left">Analytic User</h5>
                    <a href="{{route('users.index')}}" class="btn btn-info btn-sm float-right">List user</a>
                </div>
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
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>ニックネーム</th>
                          <th>total user</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($categories)
                        @foreach($categories as $key => $category)
                          <tr>
                            <td>{{$category->id}}</td>
                            <td>{{$category->category}}</td>

                            <td>{{@count(@$category->user_category)}}</td>
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