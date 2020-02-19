@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Question</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List question</h5>
                    @if(count($questions) < 5)
                    <a href="{{route('questions.create')}}?video_id={{request()->video_id}}" class="btn btn-info btn-sm float-right">Add question</a>
                    @endif
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>question</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($questions)
                        @foreach($questions as $key => $rssurl)
                          <tr>
                            <td>{{$rssurl->id}}</td>
                            <td>{{$rssurl->question}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('questions.edit',$rssurl->id)}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('questions.delete',$rssurl->id)}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $questions->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
