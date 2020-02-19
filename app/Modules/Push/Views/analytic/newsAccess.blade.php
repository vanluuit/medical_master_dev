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
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">{{$new->title}}</h4>
                    <p>List access news</p>
                </div>
              </div>
              <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>Nickname</th>  
                          <th>date access</th>  
                      	</tr>
                      </thead>
                      <tbody>
                        @if($viewlists)
                        @foreach($viewlists as $key => $viewlist)
                          <tr>
                            <td>{{@$viewlist->user->nickname}}</td>
                            <td>{{@$viewlist->created_at}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $viewlists->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  