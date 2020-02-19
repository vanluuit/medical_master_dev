@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">Channels</h4>
            <a href="{{route('analytic.content')}}" class="btn btn-info btn-sm float-right">Content</a> 
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
                      <div class="row">
                        <div class="col-md-6">

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
                          <th>total</th>
                          <th>last month</th>                       
                          <th>this month</th> 
                          <!-- <th>Action</th>                -->
                      	</tr>
                      </thead>
                      <tbody>
                        @if($channels)
                        @foreach($channels as $key => $channel)
                          <tr>
                            <td>{{$channel->id}}</td>
                            <td>{{$channel->title}}</td>
                            <td>{{$channel->count_view_count}}</td>
                            <td>{{$channel->count_view_l_count}}</td>
                            <td>{{$channel->count_view_m_count}}</td>
<!--                             <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('channels.edit',$channel->id)}}">Detail</a>
                            </td> -->
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $channels->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  