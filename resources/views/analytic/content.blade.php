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
            </div>
            <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>title</th>
                          <th>type</th>
                          <th>total</th>
                          <th>Comp/with total</th> 
                          <th>total filter</th>                       
                          <th>Comp/with filter</th>
                          <th style="width: 220px">Action</th>               
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
                                <button class="btn btn-cyan btn-sm" type="submit">export</button>
                              
                              <a class="btn btn-cyan btn-sm" href="{{route('analytic.content.detail',$content->id)}}?start_day={{$start_day}}&end_day={{$end_day}}">Detail</a>
                              <a class="btn btn-cyan btn-sm" href="{{route('analytic.content.access.history',$content->id)}}?start_day={{$start_day}}&end_day={{$end_day}}">History</a>
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