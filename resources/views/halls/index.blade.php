@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Place</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List place</h5>
                    <a href="{{route('seminars.index')}}" class="btn btn-info btn-sm float-right mg-r15">List seminar</a>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>name</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($halls)
                        @foreach($halls as $key => $hall)
                          <tr class="ui-state-default" >
                            <input type="hidden" name="soft[]" class="soft_id" value="{{$hall->id}}">
                            <td>{{$hall->id}}</td>
                            <td>{{$hall->name}}</td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $halls->appends(request()->query())->links() }}
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
    $.ajax({url: "{{route('halls.ajaxsoft')}}?id="+str, success: function(result){
      console.log(result);
    }});

  }
});
</script>
@stop 