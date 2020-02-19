@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Discussion</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List Discussion</h5>
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
                          <th>title</th>
                          <th>discription</th>
                          <th style="width: 300px">images</th>
                          <!-- <th>top</th> -->
                          
                          <th style="width:280px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($discussions)
                        @foreach($discussions as $key => $discussion)
                          <tr>
                            <td>{{$discussion->id}}</td>
                            <td>{{$discussion->title}}</td>
                            <td>{{$discussion->discription}}</td>
                            <td>
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$discussion->image1}}" alt="">
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$discussion->image2}}" alt="">
                                <img style="width: 80px; float: left; margin: 0 5px;" class="thumbnail" src="{{URL::to('/')}}/{{$discussion->image3}}" alt="">
                            </td>
                            <td>
                              <a class="btn btn-success btn-sm" href="{{route('discussion.comments_list')}}?discussion_id={{$discussion->id}}">Show Comment</a>
                              <a class="btn btn-success btn-sm" href="{{route('discussion.edit',$discussion->id)}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('discussion.delete',$discussion->id)}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $discussions->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  