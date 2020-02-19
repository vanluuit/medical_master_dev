@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Push通知</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">Push通知管理</h5>
                    <a href="{{route('push.notification.create')}}" class="btn btn-info btn-sm float-right">お知らせPush</a>
                    <a href="{{route('push.notification.create.tvpro')}}" class="btn btn-info btn-sm float-right mg-r15">学会チャンネルPush</a>
                    <!-- <a href="{{route('push.user.list.review')}}" class="btn btn-info btn-sm float-right mg-r15">プレビュー送付先</a> -->

                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          {!! Form::select('type', [''=>'All','2'=>'TVpro', '3'=>'News'], @request()->type,['class'=>'form-control select2 search_change']) !!}
                        </div>
                      </div>
                    </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>タイトル</th>
                          <th>本文</th>
                          <th style="width:120px">本文</th>
                          <th>status</th>
                          <th style="width:190px"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($messages)
                        @foreach($messages as $key => $message)
                          <tr >
                            <input type="hidden" name="soft[]" class="soft_id" value="{{$message->id}}">
                            <td>{{$message->id}}</td>
                            <td>{{$message->title}}</td>
                            <td>{{$message->message}}</td>
                            <td> @if($message->push !=0) 
                              {{date('Y-m-d H:i',strtotime($message->push_date))}}
                              @endif
                            </td>
                            <td>@if($message->push==1) 送信済み @elseif($message->push==0) 一時保存 @else  reserved @endif</td>
                            <td>
                              @if($message->push ==0 || $message->push ==2)
                                <a  class="btn btn-cyan btn-sm" href="{{route('push.notification.push',$message->id)}}">送信
                                  <!-- <br><span style="font-size: 10px; margin-top: -5px;display: contents;">(push)</span> -->
                                </a>
                                <a  class="btn btn-cyan btn-sm" href="{{route('push.notification.edit',$message->id)}}">編集
                                  <!-- <br><span style=" font-size: 10px; margin-top: -5px;display: contents;">(edit)</span> -->
                                </a>
                              @else
                              <!-- <a class="btn btn-cyan btn-sm" href="{{route('push.notification.push',$message->id)}}">再利用して送信</a> -->
                              @endif
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
@section('script')
@stop 