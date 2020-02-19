@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">Ranking</h4>
            <a href="{{route('push.seminars.index')}}" class="btn btn-info btn-sm float-right">Seminar</a> 
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
                      <input type="hidden" name="seminar_id" value="{{request()->seminar_id}}">
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
                          <th>STT</th>
                          <th>Category</th>
                          <th>Theme</th>
                          <th>View 24h</th>        
                        </tr>
                      </thead>
                      <tbody>
                        @if($rankings)
                        @foreach($rankings as $key => $ranking)
                          <tr>
                            <td>{{$key}}</td>
                            <td>{{@$ranking->category->name}}</td>
                            <td>{{@$ranking->theme->name}}</td>
                            <td>{{$ranking->count_view_count}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <!-- <div class="float-center">
                    {{ $rankings->appends(request()->query())->links() }}
                </div> -->
            </div>
        </div>
        
    </div>
</div>
@stop  