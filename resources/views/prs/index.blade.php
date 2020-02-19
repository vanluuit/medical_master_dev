@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">prs</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List prs</h5>
                    <a href="{{route('prs.create')}}" class="btn btn-info btn-sm float-right">Add prs</a>
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
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>title</th>
                          <th>association</th>
                          <th>video</th>
                          <th>start date</th>
                          <th>end date</th>
                          <th>status</th>
                          <th>show</th>
                          <th style="width:160px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($prs)
                        @foreach($prs as $key => $pr)
                          <tr>
                            <td>{{$pr->id}}</td>
                            <td>{{$pr->title}}</td>
                            <td>{{@$pr->category->category}}</td>
                            <td>{{@$pr->video->title}}</td>
                            <td style="width: 120px">{{@$pr->video->start_date}}</td>
                            <td style="width: 120px">{{@$pr->video->end_date}}</td>

                            <td>
                                @php
                                  $start = strtotime(@$pr->video->start_date);
                                  $end = strtotime(@$pr->video->end_date);
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
                                  <input type="checkbox" class="custom-control-input setshow" value="{{$pr->id}}" name="publish" id="showprs_{{$pr->id}}" @if( $pr->publish == 1 ) checked @endif >
                                  <label class="custom-control-label" for="showprs_{{$pr->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('prs.edit',$pr->id)}}">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="{{route('prs.show',$pr->id)}}">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('prs.delete',$pr->id)}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $prs->appends(request()->query())->links() }}
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
      $.ajax({url: "{{route('prs.ajaxshow')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The pr has been deleted show');
        }else{
          alert('pr has been added show');
        }
      }});
    });
  </script>
@stop  