@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Category event</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List category</h5>
                    <a href="{{route('categoryevents.create')}}" class="btn btn-info btn-sm float-right">Add category</a>
                    <a href="{{route('categoryevents.namesearch')}}?seminar_id={{request()->seminar_id}}" class="btn btn-info btn-sm float-right mg-r15">Set Color</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>小カテゴリー</th>
                          <th>Color</th>
                          <th style="width:190px">行動</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($categories)
                        @foreach($categories as $key => $category)
                          <tr>
                            <input type="hidden" name="soft[]" class="soft_id" value="{{$category->id}}">
                            <td>{{$category->id}}</td>
                            <td>{{$category->name}}</td>
                            <td>{{$category->color}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('categoryevents.edit',$category->id)}}">Edit</a>
                              @if($category->id > 0) 
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('categoryevents.delete',$category->id)}}">Delete</a>
                              @endif
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
@section('script')
<script>

</script>
@stop 