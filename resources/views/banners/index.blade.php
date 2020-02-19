@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">banners</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List banners</h5>
                    <a href="{{route('banners.create')}}" class="btn btn-info btn-sm float-right">Add banners</a>
                    <a href="{{route('banners.setNumber')}}" class="btn btn-info btn-sm float-right mg-r15">Banner Number</a>
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
                          <th>Position</th>
                          <th>start date</th>
                          <th>end date</th>
                          <th>status</th>
                          <th>show</th>
                          <th style="width:160px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($banners)
                        @foreach($banners as $key => $banner)
                          <tr>
                            <td>{{$banner->id}}</td>
                            <td><img style="width: 160px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$banner->image}}" alt=""></td>
                            <td>@if($banner->type==1) Content @else Url @endif</td>
                            <td>{{@$banner->category->category}}</td>
                            <td>@if($banner->type==1) {{@$banner->video->title}} @else {{@$banner->url}} @endif</td>
                            <td>{{@$banner->location}}</td>
                            @if($banner->type==1)
                            <td style="width: 120px">{{@$banner->video->start_date}}</td>
                            <td style="width: 120px">{{@$banner->video->end_date}}</td>
                            @else
                            <td style="width: 120px">{{@$banner->start_date}}</td>
                            <td style="width: 120px">{{@$banner->end_date}}</td>
                            @endif
                            <td>
                                @php
                                  if($banner->type==1) {
                                    $start = strtotime(@$banner->video->start_date);
                                    $end = strtotime(@$banner->video->end_date);
                                  }else{
                                    $start = strtotime(@$banner->start_date);
                                    $end = strtotime(@$banner->end_date);
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
                                  <input type="checkbox" class="custom-control-input setshow" value="{{$banner->id}}" name="publish" id="showbanners_{{$banner->id}}" @if( $banner->publish == 1 ) checked @endif >
                                  <label class="custom-control-label" for="showbanners_{{$banner->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('banners.edit',$banner->id)}}">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="{{route('banners.show',$banner->id)}}">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('banners.delete',$banner->id)}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $banners->appends(request()->query())->links() }}
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
      $.ajax({url: "{{route('banners.ajaxshow')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The banner has been deleted show');
        }else{
          alert('banner has been added show');
        }
      }});
    });
  </script>
@stop  