@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">Contents</h4>
            <a href="{{route('analytic.channels')}}" class="btn btn-info btn-sm float-right">Channel</a> 
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
                    @if($contents->type==1)
                      <p>{{date('Y年m月d日, F', strtotime($start_day))}} {{$contents->count_view_m_count}} times{{--  {{$time_total->total_time}} 秒 --}}</p>
                    @else
                      <p>{{date('Y年m月d日, F', strtotime($start_day))}} {{$contents->count_view_m_count}} times </p>
                    @endif
                </div>
              </div>
              <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>Time</th>
                          <th>View</th>
                          <th style="width: 80px">action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($data_days)
                        @foreach($data_days as $key => $data_day)
                          <tr class="link_url" data-url="{{route('analytic.content.detail.day.time', [$id,$start_day,$data_day['time']])}}">
                           <td>{{$data_day['time']}}</td>
                           <td>{{$data_day['view']}}</td>
                           <td>
                            @if($data_day['view'])
                            <a class="btn btn-cyan btn-sm" href="{{route('analytic.content.detail.day.time', [$id,$start_day,$data_day['time']])}}">View</a>
                            @endif
                          </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
               </div>
            </div>
        </div>
        
    </div>
</div>
@stop  