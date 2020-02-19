@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Comment ({{$News->title}})</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Comment</h5>
                    <a href="{{route('news.index')}}" class="btn btn-info btn-sm float-right">List New</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>comment</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($comments)
                        @foreach($comments as $key => $comment)
                          <tr>
                            <td>{{$comment->id}}</td>
                            <td>{{$comment->comment}}</td>
                            <td>
                            	<a class="btn btn-cyan btn-sm" href="{{route('commentnews.edit', $comment->id)}}?new_id={{$id}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('commentnews.delete',$comment->id)}}?new_id={{$id}}">Delete</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $comments->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  