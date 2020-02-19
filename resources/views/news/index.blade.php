@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">News</h4>
            @if(count($dtcs))
              @foreach($dtcs as $k => $v)
                {{$v['id']}} = {{$v['id1']}} & 
              @endforeach
            @endif
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List news</h5>
                    <a href="{{route('news.create')}}" class="btn btn-info btn-sm float-right">Add New</a>
                    <a href="{{route('news.getrss')}}" class="btn btn-info btn-sm float-right mg-r15">Get RSS</a>
                    <a href="{{route('category_news.create')}}" class="btn btn-info btn-sm float-right mg-r15">Add Category</a>
                    <a href="{{route('rssnews.index')}}" class="btn btn-info btn-sm float-right mg-r15">List url rss</a>
                    <a href="{{route('new.crontime')}}" class="btn btn-info btn-sm float-right mg-r15">CRONJOB</a>
                    <a href="{{route('new.list_cron_delete')}}" class="btn btn-info btn-sm float-right mg-r15">Delete</a>

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
                <div class="float-center">
                    {{ $News->appends(request()->query())->links() }}
                </div>
                {!! Form::open(array('route' => 'news.deleteall', 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
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
                          <th style="max-width: 300px">title</th>
                          <th>association</th>
                          <th>type</th>
                          <th style="width:240px">url</th>
                          <th>thumbnail</th>
                          <th>category</th>
                          <th>publish date</th>
                          <th>comment</th>
                          <th>show</th>
                          <th>top</th>
                          <!-- <th>top</th> -->
                          <th style="width:240px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($News)
                        @foreach($News as $key => $new)
                          <tr>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input checkdel" value="{{$new->id}}" name="delete[]" id="deletenews_{{$new->id}}">
                                  <label class="custom-control-label" for="deletenews_{{$new->id}}"></label>
                              </div>
                            </td>
                            <td>{{$new->id}}</td>
                            <td>@if($new->url !="") <a target="_blade" href="{{$new->url}}">{{$new->title}}</a> @else {{$new->title}} @endif</td>
                            <td>
                              @if($new->category_id==0)
                                全ての学会
                              @else
                                {{@$new->association->category}}
                              @endif
                              
                            </td>
                            <td>@if($new->type==0) original @else rss @endif</td>
                            <td style="word-break: break-all;"><p style="min-width: 240px;">{{$new->url}}</p></td>
                            @if(!strpos('ccc'.$new->media, 'http'))
                            <td><img class="thumbnail" src="{{$new->media}}" alt=""></td>
                            @else
                            <td><img class="thumbnail" src="{{$new->media}}" alt=""></td>
                            @endif
                            <td>{{@$new->category->category_name}}</td>
                            <td>{{$new->date}}</td>
                            <td><a href="{{route('commentnews.index')}}?new_id={{$new->id}}">{{@$new->comments_count}}</a></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="{{$new->id}}" name="publish" id="shownews_{{$new->id}}" @if( $new->publish == 1 ) checked @endif >
                                  <label class="custom-control-label" for="shownews_{{$new->id}}"></label>
                              </div>
                            </td>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input settop" id="top_{{$new->id}}" name="top" value="{{$new->id}}" @if( $new->top == 1 ) checked @endif >
                                    <label class="custom-control-label" for="top_{{$new->id}}"></label>
                                </div>
                            </td>

                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('news.edit',$new->id)}}">Edit</a>
                              <a class="btn btn-success btn-sm" href="{{route('news.show',$new->id)}}">Show</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('news.delete',$new->id)}}">Delete</a>

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
                <div class="float-center">
                    {{ $News->appends(request()->query())->links() }}
                </div>
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