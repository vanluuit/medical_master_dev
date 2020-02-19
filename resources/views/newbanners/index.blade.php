@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">new banners</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List new banners</h5>
                    <a href="{{route('newbanners.create')}}" class="btn btn-info btn-sm float-right">Add new banners</a>
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          {!! Form::select('category_id', $categories, @request()->category_id, ['placeholder' => '小カテゴリー', 'class'=>'form-control select2 search_change']) !!}
                        </div>
                      </div>
                    </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th style="width:200px">image</th>
                          <th>Type</th>
                          <th>association</th>
                          <th>video</th>
                          <!-- <th>Position</th> -->
                          <th>start date</th>
                          <th>end date</th>
                          <th>status</th>
                          <th>show</th>
                          <th style="width:160px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($newbanners)
                        @foreach($newbanners as $key => $newbanner)
                          <tr>
                            <td>{{$newbanner->id}}</td>
                            <td><img style="width: 160px; float: left; margin: 0 5px;" class="thumbnail" src="{{$newbanner->image}}" alt=""></td>
                            <td>@if($newbanner->type==1) Content @else Url @endif</td>
                            <td>{{@$newbanner->category->category}}</td>
                            <td>@if($newbanner->type==1) {{@$newbanner->video->title}} @else {{@$newbanner->url}} @endif</td>
                            <!-- <td>{{@$newbanner->location}}</td> -->
                            @if($newbanner->type==1)
                            <td style="width: 120px">{{@$newbanner->video->start_date}}</td>
                            <td style="width: 120px">{{@$newbanner->video->end_date}}</td>
                            @else
                            <td style="width: 120px">{{@$newbanner->start_date}}</td>
                            <td style="width: 120px">{{@$newbanner->end_date}}</td>
                            @endif
                            <td>
                                @php
                                  if($newbanner->type==1) {
                                    $start = strtotime(@$newbanner->video->start_date);
                                    $end = strtotime(@$newbanner->video->end_date);
                                  }else{
                                    $start = strtotime(@$newbanner->start_date);
                                    $end = strtotime(@$newbanner->end_date);
                                  }
                                  
                                  $now = strtotime(date('Y-m-d H:i:s'));
                                @endphp
                                @if($start > $now || ($end < $now && $end != 0))
                                  expire
                                @else
                                  showing
                                @endif

                            </td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="{{$newbanner->id}}" name="publish" id="shownewbanners_{{$newbanner->id}}" @if( $newbanner->publish == 1 ) checked @endif >
                                  <label class="custom-control-label" for="shownewbanners_{{$newbanner->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('newbanners.edit',$newbanner->id)}}">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="{{route('newbanners.show',$newbanner->id)}}">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('newbanners.delete',$newbanner->id)}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $newbanners->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
@section('script')
  <script>
     $(document).on('change','.setshow', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      console.log(publish);
      $.ajax({url: "{{route('newbanners.ajaxshow')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The newbanner has been deleted show');
        }else{
          alert('newbanner has been added show');
        }
      }});
    });
  </script>
@stop  