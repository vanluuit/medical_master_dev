@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Content</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">Content list</h5>
                    <a href="{{route('videos.addslider')}}" class="btn btn-info btn-sm float-right">Add slider</a>  
                    <a href="{{route('videos.addpdf')}}" class="btn btn-info btn-sm float-right mg-r15">Add pdf</a>  
                    <a href="{{route('videos.create')}}" class="btn btn-info btn-sm float-right mg-r15">Add Video</a>  
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          {!! Form::select('channel_id', $channels, @request()->channel_id, ['placeholder' => 'チャンネル', 'class'=>'form-control select2 search_change']) !!}
                        </div>
                      </div>
                    </form>
                </div>
                <div class="float-center">
                    {{ $videos->appends(request()->query())->links() }}
                </div>
                {!! Form::open(array('route' => 'videos.deleteall', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                <div class="scroll">
                  <table class="table">
                        <thead>
                          <tr>
                            <th>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input deletenewsall" id="deletenewsall" data-checked="checkdel">
                                  <label class="custom-control-label" for="deletenewsall"></label>
                              </div>
                            </th>
                            <th>id</th>
                            <th>title</th>
                            <th>type</th>
                            <th>thumbnail</th>
                            <th>channel</th>
                            <th>publish</th>
                            <th style="width:300px">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if($videos)
                          @foreach($videos as $key => $video)
                            <tr>
                              <td>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" name="delete[]" class="custom-control-input checkdel" id="delete_{{$video->id}}" value="{{$video->id}}">
                                  <label class="custom-control-label " for="delete_{{$video->id}}"></label>
                                </div>
                              </td>
                              <td>{{$video->id}}</td>
                              <td>{{$video->title}}</td>
                              <td>@if($video->type == 1) video @elseif($video->type == 2) PDF @else slide mark @endif</td>
                              <td><img class="thumbnail" src="{{URL::to('/')}}/{{$video->thumbnail}}" alt=""></td>
                              <td>{{$video->channel->title}}</td>
                              <td>
                                <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setpublish" id="publish_{{$video->id}}" value="{{$video->id}}" @if($video->publish==0) checked="" @endif >
                                  <label class="custom-control-label " for="publish_{{$video->id}}"></label>
                                </div>
                              </td>
                              <td>
                                <a class="btn btn-success btn-sm" href="{{route('questions.index')}}?video_id={{$video->id}}">question</a>
                                <a class="btn btn-success btn-sm" href="{{route('videos.question',$video->id)}}">answer</a>
                                <a class="btn btn-cyan btn-sm" href="{{route('videos.edit',$video->id)}}">Edit</a>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('videos.delete',$video->id)}}?channel_id={{@request()->channel_id}}">Delete</a>

                              </td>
                            </tr>
                          @endforeach
                          @endif
                        </tbody>
                  </table>
                </div>
                <div class="border-top">
                <div class="card-body">
                  <input type="hidden" name="channel_id" value="{{@request()->channel_id}}">
                  <button type="submit" class="btn btn-primary">Delete</button>
                </div>
              </div>
                {!! Form::close() !!}
                <div class="float-center">
                    {{ $videos->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  

@section('script')
  <script>
    $(document).on('change','.setpublish', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 0;
      }else{
        var publish = 1;
      }
      $.ajax({url: "{{route('videos.ajaxpublish')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 1){
          alert('The video has been deleted publish');
        }else{
          alert('video has been added publish');
        }
      }});
    });
  </script>
@stop 