@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Associations</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List associations</h5>
                    <a href="{{route('associations.create')}}" class="btn btn-info btn-sm float-right">Add associations</a>
                    <a href="{{route('user.export.download')}}" class="btn btn-info btn-sm float-right mg-r15">Export all user</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>学会選択</th>
                          <th>publish</th>
                          <th style="width: 300px">Action</th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @if($categories)
                        @foreach($categories as $key => $category)
                          <tr>
                            <td>{{$category->id}}</td>
                            <td><a href="{{route('members.index')}}?category_id={{$category->id}}">{{$category->category}}</a></td>
                            <td>
                            
                              <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input setpublish" id="publish_{{$category->id}}" value="{{$category->id}}" @if($category->publish==0) checked="" @endif >
                                <label class="custom-control-label " for="publish_{{$category->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('notification.get_push_associations',$category->id)}}">Push</a>
                              <a class="btn btn-cyan btn-sm" href="{{route('associations.edit',$category->id)}}">Edit</a>
                              <a class="btn btn-success btn-sm" href="{{route('associations_video.index')}}?category_id={{$category->id}}">content</a>
                              <a class="btn btn-success btn-sm" href="{{route('user.export.download')}}?category_id={{$category->id}}">Export User</a>

                              {{-- <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('category.delete',$user->id)}}">Delete</a> --}}
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
    $(document).on('change','.setpublish', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 0;
      }else{
        var publish = 1;
      }
      $.ajax({url: "{{route('associations.ajaxpublish')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The associations has been deleted publish');
        }else{
          alert('associations has been added publish');
        }
      }});
    });
  </script>
@stop  