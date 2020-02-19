@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Channels</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Channels</h5>
                    <a href="{{route('channels.create')}}" class="btn btn-info btn-sm float-right">Add Channel</a>  
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          {!! Form::select('category_id', $categories, @request()->category_id,['class'=>'form-control select2 search_change']) !!}
                        </div>
                      </div>
                    </form>
                </div>
                {!! Form::open(array('route' => 'channels.deleteall', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>id</th>
                          <th>title</th>
                          <th>thumbnail</th>
                          <th>discription</th>
                          <th>publish</th>
                          <th>association</th>
                          <th>sponser</th>
                          <th>is_sponser</th>
                          <th>top</th>
                          <th>publish date</th>
                          <th style="width:200px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($channels)
                        @foreach($channels as $key => $channel)
                          <!-- <tr  class="ui-state-default"> -->
                          <tr>
                            <!-- <input type="hidden" name="soft[]" class="soft_id" value="{{$channel->id}}"> -->
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" name="delete[]" class="custom-control-input" id="delete_{{$channel->id}}" value="{{$channel->id}}">
                                <label class="custom-control-label " for="delete_{{$channel->id}}"></label>
                              </div>
                            </td>
                            <td>{{$channel->id}}</td>
                            <td>{{$channel->title}}</td>
                            <td><img class="thumbnail" src="{{URL::to('/')}}/{{$channel->logo}}" alt=""></td>
                            <td style="width:300px">{{$channel->discription}}</td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setpublish" id="publish_{{$channel->id}}" value="{{$channel->id}}" @if($channel->publish==0) checked="" @endif >
                                <label class="custom-control-label " for="publish_{{$channel->id}}"></label>
                              </div>
                            </td>
                            <td>
                              @if($channel->association)
                              @foreach($channel->association as $key => $cates)
                                {{$cates->category->category}} @if($key < count($channel->association)-1) ,  @endif
                              @endforeach
                              @endif
                            </td>
                            <td>{{$channel->sponser}}</td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setis_sponser" id="is_sponser_{{$channel->id}}" value="{{$channel->id}}" @if($channel->is_sponser==1) checked="" @endif >
                                <label class="custom-control-label " for="is_sponser_{{$channel->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <div class="custom-control custom-radio mr-sm-2">
                                <input type="radio" class="custom-control-input settop" id="top_{{$channel->id}}" name="top" value="{{$channel->id}}" @if($channel->nabi==1) checked="" @endif >
                                <label class="custom-control-label " for="top_{{$channel->id}}"></label>
                              </div>
                            </td>
                            <td>{{$channel->publish_date}}</td>
                            <td>
                              <a class="btn btn-success btn-sm" href="{{route('channels.pushNotification',$channel->id)}}?category_id={{@request()->category_id}}">Push</a>
                              <a class="btn btn-cyan btn-sm" href="{{route('channels.edit',$channel->id)}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('channels.delete',$channel->id)}}?category_id={{@request()->category_id}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="border-top">
                <div class="card-body">
                  <input type="hidden" name="category_id" value="{{@request()->category_id}}">
                  <button type="submit" class="btn btn-primary">Delete</button>
                </div>
              </div>
                {!! Form::close() !!}
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
      $.ajax({url: "{{route('channels.ajaxpublish')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 1){
          alert('The channel has been deleted publish');
        }else{
          alert('channel has been added publish');
        }
      }});
    });
    $(document).on('change','.setis_sponser', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var sponser = 1;
      }else{
        var sponser = 0;
      }
      $.ajax({url: "{{route('channels.ajaxis_sponser')}}?id="+$(this).val()+'&sponser='+sponser, success: function(result){
        if(result == 1){
          alert('The channel has been deleted sponser');
        }else{
          alert('channel has been added sponser');
        }
      }});
    });
    $(document).on('change','.settop', function(){
      var obj = $(this);
      console.log($(this).val());
      $.ajax({url: "{{route('channels.ajaxtop')}}?id="+$(this).val(), success: function(result){
        if(result == 1){
          alert('The channel has been set top');
        }else{
          alert('channel has been set top');
        }
      }});
    });

    //  $( "table tbody" ).sortable( {
    //   update: function( event, ui ) {
    //     let str = "";
    //     $('tr.ui-state-default').each(function(index, value){
    //       str += $(this).find('input').val()+'_';
    //     });
    //     console.log(str);
    //     $.ajax({url: "{{route('channel.ajax.ajaxsoft')}}?id="+str, success: function(result){
    //       console.log(result);
    //     }});

    //   }
    // });
  </script>
@stop  