@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">News</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List partent 1</h5>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th style="max-width: 300px">title</th>
                      <th>date</th>
                      <th>created</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($new_d)
                    @foreach($new_d as $key => $new)
                      <tr>
                        <td>
                          {{$new->id}}
                        </td>
                        <td>
                          {{$new->title}}
                        </td>
                        <td>
                          {{$new->date}}
                        </td>
                        <td>
                          {{$new->created_at}}
                        </td>
                      </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
            </div>
        </div>
         <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List partent 2</h5>
                </div>
                <table class="table">
                  <thead>
                    <tr>
                      <th>id</th>
                      <th style="max-width: 300px">title</th>
                      <th>date</th>
                      <th>created</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($new2w)
                    @foreach($new2w as $key => $new)
                      <tr>
                        <td>
                          {{$new->id}}
                        </td>
                        <td>
                          {{$new->title}}
                        </td>
                        <td>
                          {{$new->date}}
                        </td>
                        <td>
                          {{$new->created_at}}
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

@section('script')
  <script>
    $(document).on('click','.settop', function(){
      var obj = $(this);
      
      // console.log(1);
      if( obj.is( ":checked" ) == true){
        $('.settop').prop('checked', false);
        obj.prop('checked', true);
        sh = 1;
      }else{
        $('.settop').prop('checked', false);
        sh = 0;
      }
      $.ajax({url: "{{route('news.ajaxtop')}}?id="+$(this).val()+'&sh='+sh, success: function(result){
        if(result == 0){
          alert('The news has been deleted top');
        }else{
          alert('news has been added top');
        }
      }});
    });

     $(document).on('change','.setshow', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      console.log(publish);
      $.ajax({url: "{{route('news.ajaxshow')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The news has been deleted show');
        }else{
          alert('news has been added show');
        }
      }});
    });
  </script>
@stop  