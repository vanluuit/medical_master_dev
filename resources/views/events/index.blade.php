@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">events</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List events</h5>
                    <!-- <a href="{{route('events.create')}}?seminar_id={{request()->seminar_id}}" class="btn btn-info btn-sm float-right">Add events</a> -->
                    <a href="{{route('events.getevents')}}?seminar_id={{request()->seminar_id}}" class="btn btn-info btn-sm float-right mg-r15">import events</a>
                    <a href="{{route('seminars.index')}}" class="btn btn-info btn-sm float-right mg-r15">list seminar</a>
                    <a href="{{route('categoryevents.index')}}?seminar_id={{request()->seminar_id}}" class="btn btn-info btn-sm float-right mg-r15">Category Event</a>
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>Number</th>
                          <th>start time</th>
                          <th>end time</th>
                          <th>category</th>
                          <th>Theme</th>
                          <th style="width:140px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($events)
                        @foreach($events as $key => $event)
                          <tr>
                            <td>{{$event->id}}</td>
                            <td>{{$event->topic_number}}</td>
                            <td>{{date('H:i', strtotime($event->start_time))}}</td>
                            <td>{{date('H:i', strtotime($event->end_time))}}</td>
                            <td>{{@$event->category->name}}</td>
                            <td>{{@$event->theme->name}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('events.edit',$event->id)}}">Edit</a>
                              <!-- <a class="btn btn-success btn-sm" href="{{route('events.show',$event->id)}}">Show</a> -->
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('events.delete',$event->id)}}?seminar_id={{request()->seminar_id}}">Delete</a>

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  
