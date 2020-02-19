@extends('layouts.push')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">プッシュ機能</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left"></h5>
                    <a href="{{route('push.listmemberpushs.create')}}" class="btn btn-info btn-sm float-left">新規リスト作成</a>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>日付</th>
                          <th>リスト名</th>
                          <th>人数</th>
                          <th style="width: 300px"></th>
                          
                        </tr>
                      </thead>
                      <tbody>
                        @if($lists)
                        @foreach($lists as $key => $item)
                          <tr>
                            <td>{{date('Y/m/d', strtotime($item->created_at))}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{count($item->memberList)}}</td>
                            <td>
                              <a class="btn btn-cyan btn-sm" href="{{route('push.listmemberpushs.edit',$item->id)}}">編集</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');"  href="{{route('push.listmemberpushs.delete',$item->id)}}">削除</a>
                              
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    {{ $lists->appends(request()->query())->links() }}
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