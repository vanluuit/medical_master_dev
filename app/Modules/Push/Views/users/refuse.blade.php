@extends('layouts.push')
@section('content')
<style type="text/css">
  form.action {
    float: left;
    margin-left: 3px;
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">会員管理</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">却下一覧</h5>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>登録日</th>
                          <th>氏名</th>
                          <th>生年月日</th>
                          <th>会員番号</th>
                          <th>メールアドレス</th>
                          <!-- <th style="width: 280px"></th> -->
                        </tr>
                      </thead>
                      <tbody>
                        @if($usercates)
                        @foreach($usercates as $key => $usercate)
                          <tr>
                            <td>{{date('Y-m-d',strtotime($usercate->user->created_at))}}</td>
                            <td>{{$usercate->user->lastname}} {{$usercate->user->firstname}}</td>
                            <td>{{$usercate->user->birthday}}</td>
                            <td>{{@$usercate->member->code}}</td>
                            <td><a href="mailto:{{@$usercate->user->email}}">{{@$usercate->user->email}}</a></td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $usercates->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
      </div>
</div>

@stop  
@section('script')
  {{-- <script>
    $(document).on('change','.setpublish', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 0;
      }else{
        var publish = 1;
      }
      $.ajax({url: "{{route('associations.ajaxpublish')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The associations has been deleted publish');
        }else{
          alert('associations has been added publish');
        }
      }});
    });
  </script> --}}
@stop  