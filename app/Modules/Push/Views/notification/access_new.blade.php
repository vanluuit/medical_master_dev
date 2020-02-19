@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">プッシュ機能</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List notification push</h5>
                    <a href="{{route('push.notification.create')}}" class="btn btn-info btn-sm float-right">notification push</a>
                    <a href="{{route('push.notification.create.news')}}" class="btn btn-info btn-sm float-right mg-r15">Push New</a>
                    <a href="{{route('push.notification.create.tvpro')}}" class="btn btn-info btn-sm float-right mg-r15">Push TVpro</a>
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
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>title</th>
                          <th>message</th>
                          <th>news</th>
                          <th>access</th>
                          <th style="width:120px">sent date</th>
                          <th style="width:190px">Action</th>
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
                            <td>{{@$message->new->title}}</td>
                            <td>{{@$message->access_new_count}}</td>
                            <td>{{$message->push_date}}</td>
                            <td>
                              @if($message->push ==0)
                                <a class="btn btn-cyan btn-sm" href="{{route('push.notification.push',$message->id)}}">Push</a>
                                <a class="btn btn-cyan btn-sm" href="{{route('push.notification.edit',$message->id)}}">Edit</a>
                                
                              {{-- <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('push.notification.delete',$message->id)}}?category_id={{request()->category_id}}">Delete</a> --}}
                              @endif

                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
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