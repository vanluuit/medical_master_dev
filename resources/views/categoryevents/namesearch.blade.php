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
                    <h5 class="card-title m-b-0 float-left">List category search name</h5>
                    <a href="{{route('categoryevents.index')}}?seminar_id={{request()->seminar_id}}" class="btn btn-info btn-sm float-right">List category</a>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>小カテゴリー</th>
                          <th>Color</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($categories)
                        @foreach($categories as $key => $category)
                          <tr>
                            <input type="hidden" name="soft[]" class="soft_id" value="{{$category->id}}">
                            <td>{{$category->id}}</td>
                            <td>{{$category->name}}</td>
                            <td>
                              <input type="text" name="color" id="color_{{$category->id}}" value="{{$category->color}}">
                              <button class="btn btn-cyan btn-sm setcolor" data_for="#color_{{$category->id}}" data_id="{{$category->id}}">Set</button>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
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

     $(document).on('click','.setcolor', function(){
      var obj = $(this);
      var id = obj.attr('data_id');
      var color = $(obj.attr('data_for')).val();
      color = color.replace("#", "");
      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({url: "{{route('categoryevents.setcolor')}}?id="+id+'&color='+color, 
        success: function(result){
        if(result == 0){
          alert('The category has been set color');
        }else{
          alert('category has been set color');
        }
      }});
    });
  </script>
@stop 