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
                          <th>duration</th> 
                      	</tr>
                      </thead>
                      <tbody>
                        @if($viewlists)
                        @foreach($viewlists as $key => $user)
                          <tr>
                            <td>{{@$user->user->nickname}}</td>
                            <td>{{time_convert(@$user->total_time)}}</td>
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