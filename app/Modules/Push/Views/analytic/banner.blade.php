@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center" style="display: block!important;">
            <h4 class="page-title" style="float: left;">Associations</h4>
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
                          <th>Associations</th>
                          <th>total</th>
                          <th>Action</th>               
                      	</tr>
                      </thead>
                      <tbody>
                        @if($categories)
                        @foreach($categories as $key => $category)
                          @php 
                            $total = 0; 
                            if($category->banners) {
                              foreach($category->banners as $k => $v) {
                                $total = $total+$v->banner_views_count;
                              }
                            }
                          @endphp
                          <tr>
                            <td>{{$category->id}}</td>
                            <td>{{$category->category}}</td>
                            <td>{{$total}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('push.analytic.banner.view',$category->id)}}">Detail</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop  