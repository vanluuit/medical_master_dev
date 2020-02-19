@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Comments</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Comments</h5>
                    <a href="{{route('discussion.index')}}" class="btn btn-info btn-sm float-right">List Discussion</a>
                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          {!! Form::select('discussion_id', $discussions, @request()->discussion_id, ['placeholder' => '小カテゴリー', 'class'=>'form-control select2 search_change']) !!}
                        </div>
                      </div>
                    </form>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>comment</th>
                          <th style="width: 300px">images</th>
                          <th>Repost</th>
                          <th style="width:220px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($comments)
                        @foreach($comments as $key => $comment)
                          <tr>
                            <td>{{$comment->id}}</td>
                            <td>{{$comment->comment}}</td>
                            <td>
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$comment->image1}}" alt="">
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$comment->image2}}" alt="">
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$comment->image3}}" alt="">
                            </td>
                            <td>{{$comment->count_report_count}}</td>
                            
                            <td>
                              <a class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('discusion.comment.empty',$comment->id)}}?discussion_id={{request()->discussion_id}}">Empty report</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('discusion.comment.delete',$comment->id)}}?discussion_id={{request()->discussion_id}}">Delete</a>
                              

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $comments->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  