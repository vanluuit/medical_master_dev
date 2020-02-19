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
                {{-- <h5 class="card-title">Additional Content</h5> --}}
                <form action="" method="GET">
                  <div class="form-group row">
                    @if(isset(request()->day))
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="day" class="form-control datepicker-autoclose" id="" placeholder="start date" value="{{$start_day}}">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </div>
                    @else
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
                    @endif
                    <div class="col-md-5">
                      <button type="submit" class="btn btn-primary">GET</button>
                    </div>
                  </div>
                </form>
                <hr>
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading"> @if($contents->type==1) Video @elseif($contents->type==2) Pdf @else Slider @endif {{$contents->title}}</h4>
                    <p>List user view content</p>
                </div>
              </div>
              <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>Nickname</th>  
                          <th>Age</th>  
                          <th>area</th>  
                          <th>target</th>  
                          {{-- <th>action</th> --}}
                      	</tr>
                      </thead>
                      <tbody>
                        @if($user_vs)
                        @foreach($user_vs as $key => $user)
                          <tr>
                            <td>{{@$user->user->nickname}}</td>
                            <td>
                              @php
                                $date1 = new DateTime(date('Y-m-d H:i:s'));
                                $date2 = new DateTime(@$user->user->birthday);
                                $interval = $date1->diff($date2);
                                if(@$user->user->birthday == '0000-00-00') $interval->y = 0;
                              @endphp
                              @if($interval->y != 0) {{$interval->y}} @else --- @endif
                            </td>
                            <td>{{@$user->user->city_hospital}}</td>
                            <td>{{@$user->user->area_hospital}}</td>
                            {{-- <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('push.analytic.content.user.history',[@$user->user->id])}}">History</a>
                            </td> --}}
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