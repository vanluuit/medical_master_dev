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
                {{-- <h5 class="card-title">Additional Content</h5> --}}
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
                      <button type="submit" class="btn btn-primary">GET</button>
                    </div>
                  </div>
                </form>
                <hr>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading"> @if($contents->type==1) Video @elseif($contents->type==2) Pdf @else Slider @endif {{$contents->title}}</h4>
                    @if($contents->type==1)
                      <p>{{date('Y年m月d日', strtotime($start_day))}} ~ {{date('Y年m月d日', strtotime($end_day))}} {{@$contents->count_view_m_count}} times {{@$time_total->total_time}} 秒</p>
                    @else
                      <p>{{date('Y年m月d日', strtotime($start_day))}} ~ {{date('Y年m月d日', strtotime($end_day))}} {{$contents->count_view_m_count}} times </p>
                    @endif
                </div>
              </div>
              <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          @if($contents->type==1)
                            <th>Completed</th>
                            <th>Midway</th>
                            <th>Views</th>
                          @else
                            <th>People</th>
                          @endif
                          <th>Date</th>  
                          <th>Action</th>  
                      	</tr>
                      </thead>
                      <tbody>
                        @if($viewlists)
                        @foreach($viewlists as $key => $viewlists)
                          <tr>
                            @if($contents->type==1)
                            <td><a href="{{route('analytic.content.detail.user',[$id, 1])}}?day={{$viewlists->day_list}}">{{$viewlists->comp_u}} people</a></td>
                            <td><a href="{{route('analytic.content.detail.user',[$id, 0])}}?day={{$viewlists->day_list}}">{{$viewlists->withx_u}} people</a></td>
                            <td>{{$viewlists->total}} times</td>
                            @else
                            <td>{{$viewlists->withx_u}}</td>
                            @endif
                            <td>{{date('F, M d', strtotime($viewlists->day_list))}}</td>
                            <td><a class="btn btn-cyan btn-sm" href="{{route('analytic.content.detail.day',[$id, $viewlists->day_list])}}">View day</a></td>

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