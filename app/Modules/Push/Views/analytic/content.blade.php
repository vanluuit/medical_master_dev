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
              <form action="" method="GET">
                    <div class="form-group row">
                      <div class="col-md-3">
                        <div class="input-group date">
                          <input type="text" name="start_day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="{{$start_day}}">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-1 text-center">
                        ~
                      </div>
                      <div class="col-md-3">
                        <div class="input-group date">
                          <input type="text" name="end_day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="{{$end_day}}">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <button type="submit" class="btn btn-primary">絞り込み</button>
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
                          <th>種類</th>
                          <th>合計視聴数</th>
                          <th>完全視聴／合計視聴数</th> 
                          <th>期間合計視聴数</th>                       
                          <th>完全視聴／期間合計視聴数</th>
                          <th style="width: 310px"></th>               
                        </tr>
                      </thead>
                      <tbody>
                        @if($contents)
                        @foreach($contents as $key => $content)
                          <tr>
                            <td>{{$content->id}}</td>
                            <td>{{$content->title}}</td>
                            <td>@if($content->type == 1) video @elseif($content->type == 2) PDF @else slide mark @endif</td>
                            <td>{{$content->count_view_count}}</td>
                            <td>
                              @if($content->type == 1) 
                                {{$content->complete_count}} / {{$content->withdrawal_count}}
                              @endif
                            </td>
                            <td>{{$content->count_view_m_count}}</td>
                            <td>
                              @if($content->type == 1) 
                                {{$content->complete_m_count}} / {{$content->withdrawal_m_count}}
                              @endif
                            </td>
                            <td>
                              <form method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$content->id}}">
                                <input type="hidden" name="start_day" value="{{$start_day}}">
                                <input type="hidden" name="end_day" value="{{$end_day}}">
                                <button class="btn btn-cyan btn-sm" type="submit">ダウンロード</button>
                              
                              <a class="btn btn-cyan btn-sm" href="{{route('push.analytic.content.detail',$content->id)}}?start_day={{$start_day}}&end_day={{$end_day}}">詳細</a>
                              <a class="btn btn-cyan btn-sm" href="{{route('push.analytic.content.access.history',$content->id)}}?start_day={{$start_day}}&end_day={{$end_day}}">視聴履歴一覧</a>
                              </form>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $contents->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  