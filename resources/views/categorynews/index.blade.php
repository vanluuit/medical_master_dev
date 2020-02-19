@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">小カテゴリー</h4>
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
                    <a href="{{route('category_news.create')}}" class="btn btn-info btn-sm float-right">Add category</a>
                    <a href="{{route('news.index')}}" class="btn btn-info btn-sm float-right mg-r15">List news</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>小カテゴリー</th>
                          <th>Show</th>
                          <th style="width:190px">行動</th>
                        </tr>
                        @if(count($CategoryNews))
                        <tr class="" >
                          <td>{{$Category->id}}</td>
                          <td>{{$Category->category_name}}</td>
                          <td></td>
                          <td>
                            <a class="btn btn-cyan btn-sm" href="{{route('category_news.edit',$Category->id)}}">Edit</a>
                            @if($Category->id > 0) 
                            <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('category_news.delete',$Category->id)}}">Delete</a>
                            @endif
                          </td>
                        </tr>
                        @endif
                      </thead>
                      <tbody>
                        @if($CategoryNews)
                        @foreach($CategoryNews as $key => $category)
                          <tr class="ui-state-default" >
                            <input type="hidden" name="soft[]" class="soft_id" value="{{$category->id}}">
                            <td>{{$category->id}}</td>
                            <td>{{$category->category_name}}</td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="{{$category->id}}" name="publish" id="showcategorys_{{$category->id}}" @if( $category->publish == 1 ) checked @endif >
                                  <label class="custom-control-label" for="showcategorys_{{$category->id}}"></label>
                              </div>
                            </td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('category_news.edit',$category->id)}}">Edit</a>
                              @if($category->id > 0) 
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('category_news.delete',$category->id)}}">Delete</a>
                              @endif
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $CategoryNews->appends(request()->query())->links() }}
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
    // start: function(event, ui) {
    //     var start_pos = ui.item.index()+1;
    //     ui.item.data('start_pos', start_pos);
    // },
    // change: function(event, ui) {
    //     var start_pos = ui.item.data('start_pos');
    //     var index = ui.placeholder.index();
    //     ui.item.data('index', index);
    //     // console.log(start_pos+'_'+index);
    // },
    // update: function(event, ui) {
    //     var start_pos = ui.item.data('start_pos');
    //     var index = ui.item.data('index');
    //     console.log(start_pos+'_'+index);
    //     // $('#sortable li').removeClass('highlights');
    // }
  update: function( event, ui ) {
    let str = "";
    $('tr.ui-state-default').each(function(index, value){
      str += $(this).find('input').val()+'_';
    });
    console.log(str);
    $.ajax({url: "{{route('news.category.ajaxsoft')}}?id="+str, success: function(result){
      console.log(result);
    }});

  }
});
  $(document).on('change','.setshow', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      console.log(publish);
      $.ajax({url: "{{route('categorynews.ajaxshow')}}?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The categorys has been deleted show');
        }else{
          alert('categorys has been added show');
        }
      }});
    });
</script>
@stop 