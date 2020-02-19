@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">アンケート回答を見る</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left"></h5>
                    <a href="{{route('push.videos.index')}}" class="btn btn-info btn-sm float-right">List videos</a>   
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>user</th>
                          <th>question</th>
                          <th>answer</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($questions)
                        @foreach($questions as $key => $question)
                          <tr>
                            <td>{{$question->user->nickname}}</td>
                            <td>{{$question->question->question}}</td>
                            <td>{{$question->answer->answer}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $questions->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  