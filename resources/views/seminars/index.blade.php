@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">seminar</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List seminar</h5>
                    <a href="{{route('seminars.create')}}" class="btn btn-info btn-sm float-right">Add seminars</a>
                   {{--  <a href="{{route('events.index')}}" class="btn btn-info btn-sm float-right mg-r15">seminars event list</a> --}}
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>title</th>
                          <th>banner</th>
                          <th>image </th>
                          <th>map image</th>
                          <th>map pdf</th>
                          <th style="width: 150px">start date</th>
                          <th style="width: 150px">end date</th>
                          <th>link</th>
                          <th>show</th>
                          <th style="width:210px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($seminars)
                        @foreach($seminars as $key => $seminar)
                          <tr>
                            <td>{{$seminar->id}}</td>
                            <td>{{$seminar->title}}</td>
                            <td><img class="thumbnail" src="{{URL::to('/')}}/{{$seminar->banner}}" alt=""></td>
                            <td><img class="thumbnail" src="{{URL::to('/')}}/{{$seminar->image}}" alt=""></td>
                            <td><img class="thumbnail" src="{{URL::to('/')}}/{{$seminar->map_image}}" alt=""></td>
                            <td><a target="_blade" href="{{URL::to('/')}}/{{$seminar->map}}">pdf</a></td>
                            <td>{{$seminar->start_date}}</td>
                            <td>{{$seminar->end_date}}</td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setlink" value="{{$seminar->id}}" name="link" id="linkseminars_{{$seminar->id}}" @if( $seminar->link == 1 ) checked @endif >
                                  <label class="custom-control-label" for="linkseminars_{{$seminar->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="{{$seminar->id}}" name="publish_{{$seminar->category_id}}" id="showseminars_{{$seminar->id}}" @if( $seminar->publish == 1 ) checked @endif >
                                  <label class="custom-control-label" for="showseminars_{{$seminar->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('seminars.edit',$seminar->id)}}">Edit</a>
                              <a class="btn btn-success btn-sm" href="{{route('events.index')}}?seminar_id={{$seminar->id}}">Event</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('seminars.delete',$seminar->id)}}">Delete</a>
                              <a style="margin-top: 6px;" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete All Event?');" href="{{route('events.deleteall',$seminar->id)}}">Empty Event</a>
                              <a style="margin-top: 6px;" class="btn btn-cyan btn-sm" href="{{route('analytic.rankink')}}?seminar_id={{$seminar->id}}">Ranking</a>
                              <a style="margin-top: 6px;" class="btn btn-cyan btn-sm" href="{{route('place.index', $seminar->id)}}">Place</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{-- {{ $seminars->appends(request()->query())->links() }} --}}
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
      var name = obj.attr('name');
      console.log(name);
      if(obj.prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      $('input[name="'+name+'"]').prop('checked', false);
      if(publish==1)  {
        obj.prop('checked', true);
      }else{  
        obj.prop('checked', false);
      }
      

      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({url: "{{route('seminars.ajaxshow')}}?id="+$(this).val()+'&publish='+publish, 
        success: function(result){
        if(result == 0){
          alert('The seminar has been deleted show');
        }else{
          alert('seminar has been added show');
        }
      }});
    });
    $(document).on('change','.setlink', function(){
      var obj = $(this);
      

      if($(this).prop("checked") == true) {
        var link = 1;
      }else{
        var link = 0;
      }

     

      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({url: "{{route('seminars.ajaxlink')}}?id="+$(this).val()+'&link='+link, 
        success: function(result){
        if(result == 0){
          alert('The seminar has been deleted link');
        }else{
          alert('seminar has been added link');
        }
      }});
    });
  </script>
@stop  
