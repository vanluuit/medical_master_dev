@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">TVProコンテンツ管理</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card"> 
              <div class="card-body">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading"> @if($contents->type==1) Video @elseif($contents->type==2) Pdf @else Slider @endif {{$contents->title}}</h4>
                    <p>List access content</p>
                </div>
              </div>
              <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>ユーザー名</th>  
                          <th>視聴日時</th>  
                          <th>視聴時間</th> 
                      	</tr>
                      </thead>
                      <tbody>
                        @if($viewlists)
                        @foreach($viewlists as $key => $viewlist)
                          <tr>
                            <td>{{@$viewlist->user->nickname}}</td>
                            <td>{{@$viewlist->created_at}}</td>
                            <td>{{time_convert(@$viewlist->time_view)}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $viewlists->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  