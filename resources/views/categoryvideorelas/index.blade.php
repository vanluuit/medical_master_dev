@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Content related</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List content related</h5>
                    {{-- @if( count($categoryRelas) < 4 ) --}}
                    <a href="{{route('associations_video.create')}}?category_id={{request()->category_id}}" class="btn btn-info btn-sm float-right">Add content related</a>
                    {{-- @endif --}}
                </div>
                <hr>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>association</th>
                          <th>content</th>
                          <th style="width:190px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($categoryRelas)
                        @foreach($categoryRelas as $key => $categoryRela)
                          <tr  class="ui-state-default">
                            <input type="hidden" name="soft[]" class="soft_id" value="{{$categoryRela->id}}">
                            <td>{{$categoryRela->id}}</td>
                            <td>{{$category->category}}</td>
                            <td>{{@$categoryRela->videos->title}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('associations_video.edit',$categoryRela->id)}}?category_id={{request()->category_id}}">Edit</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('associations_video.delete',$categoryRela->id)}}?category_id={{request()->category_id}}">Delete</a>

                            </td>
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
@section('script')
<script>
  // let soft_id = '';
  // $('.soft_id').each(function(index, value){
  //   soft_id += $(this).val()+'_';
  // });
  // console.log(soft_id);

  $( "table tbody" ).sortable( {
  update: function( event, ui ) {
    let str = "";
    $('tr.ui-state-default').each(function(index, value){
      str += $(this).find('input').val()+'_';
    });
    console.log("{{route('associations_video.ajaxsoft')}}?id="+str);
    $.ajax({url: "{{route('associations_video.ajaxsoft')}}?id="+str, success: function(result){
      console.log(result);
    }});

  }
});
</script>
@stop 