@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">News</h4>
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
                          <th style="width:80px">
                            @php 
                              $view = (request()->view + 1) % 2;
                              $comment = (request()->comment + 1) % 2;
                            @endphp
                            <a href="?start_day={{$start_day}}&end_day={{$end_day}}&view={{$view}}">view<i style="margin-left: 6px" class="fas fa-sort"></i>
                            </a>
                          </th>
                          {{-- <th>like</th>                        --}}
                          <th style="width:120px"><a href="?start_day={{$start_day}}&end_day={{$end_day}}&comment={{$comment}}">comment<i style="margin-left: 6px" class="fas fa-sort"></i></a></th> 
                      	</tr>
                      </thead>
                      <tbody>
                        @if($news)
                        @foreach($news as $key => $new)
                          <tr>
                            <td>{{$new->id}}</td>
                            <td>{{$new->title}}</td>
                            <td><a href="{{route('analytic.news.access', $new->id)}}?start_day={{$start_day}}&end_day={{$end_day}}">{{$new->count_view_count}}</a></td>
                            {{-- <td>{{$new->count_like_count}}</td> --}}
                            <td><a href="{{route('commentnews.index')}}?new_id={{$new->id}}&start_day={{$start_day}}&end_day={{$end_day}}">{{$new->count_comment_count}}</a></td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $news->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  