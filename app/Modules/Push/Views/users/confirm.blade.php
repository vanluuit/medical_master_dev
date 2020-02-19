@extends('layouts.push')
@section('content')
<style type="text/css">
  form.action {
    float: left;
    margin-left: 3px;
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">会員管理</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">登録希望者一覧</h5>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>登録日</th>
                          <th>氏名</th>
                          <th>生年月日</th>
                          <th>会員番号</th>
                          <th>メールアドレス</th>
                          <th style="width: 280px"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($usercates)
                        @foreach($usercates as $key => $usercate)
                          <tr>
                            <td>
                              {{date('Y-m-d',strtotime($usercate->user->created_at))}}<br>
                              <!-- <a href="#" data-toggle="modal" data-target="#Modal_{{$usercate->user->id}}">編集</a> -->
                              {!! Form::open(['route' => ['push.users.update', $usercate->user->id], 'id'=>'validate','enctype'=>'multipart/form-data']) !!}
                @method('PUT')
                              <div class="modal fade" id="Modal_{{$usercate->user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                                <input type="hidden" name="id" value="{{$usercate->user->id}}">
                                
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">{{$usercate->user->lastname}} {{$usercate->user->firstname}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="text" name="email" value="{{$usercate->user->email}}" value="{{$usercate->user->email}}" class="form-control" placeholder="メールアドレス" >
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="password" name="password" id="password" value="{{@Crypt::decryptString($usercate->user->password)}}" class="form-control" placeholder="パスワード">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="password" name="password_conf" value="{{@Crypt::decryptString($usercate->user->password)}}" class="form-control" placeholder="パスワード(確認)">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="text" name="nickname" value="{{$usercate->user->nickname}}" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="firstname" value="{{$usercate->user->firstname}}" class="form-control" placeholder="姓">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="lastname" value="{{$usercate->user->lastname}}" class="form-control" placeholder="名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="firstname_k" value="{{$usercate->user->firstname_k}}" class="form-control" placeholder="セイ">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="lastname_k" value="{{$usercate->user->lastname_k}}" class="form-control" placeholder="メイ">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-12">
                    <div class="input-group">
                      <input type="text" name="birthday" class="form-control" id="datepicker-autoclose" placeholder="生年月日" value="{{$usercate->user->birthday}}">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    {!! Form::select('career_id', $careers, $usercate->user->career_id, ['placeholder' => '職業選択', 'class'=>'form-control select2']) !!}
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="text" name="member" class="form-control suggets" value="{{$usercate->member->code}}" placeholder="会員番号">
                                       
                                          <ul class="suggets">
                                          </ul>
                  </div>
                </div>
                
              
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                
                              </div>
                              {!! Form::close() !!}
                            </td>
                            <td>{{$usercate->user->lastname}} {{$usercate->user->firstname}} </td>
                            <td>{{$usercate->user->birthday}}</td>
                            <td>{{@$usercate->member->code}}</td>
                            <td><a href="mailto:{{@$usercate->user->email}}">{{@$usercate->user->email}}</a></td>
                            <td>
                              @if($usercate->status == 0)
                              {!! Form::open(['route' => 'push.user.editapprove', 'class'=>'action']) !!}
                              <!-- <button class="btn btn-cyan btn-sm" data-toggle="modal" data-target="#Modal_{{$usercate->user->id}}_{{@$association->member->id}}" type="button">編集して承認</button>
                              <div class="modal fade" id="Modal_{{$usercate->user->id}}_{{@$association->member->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                                <input type="hidden" name="id" value="{{$usercate->user->id}}">
                                
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">編集して承認</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          <input type="text" name="member" class="form-control suggets" placeholder="会員番号">
                                       
                                          <ul class="suggets">
                                          </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                            <button type="submit" class="btn btn-primary">編集して承認</button>
                                        </div>
                                    </div>
                                </div>
                                
                              </div> -->
                              {!! Form::close() !!}
                              {!! Form::open(['route' => 'push.user.approve', 'class'=>'action']) !!}
                              <input type="hidden" name="id" value="{{$usercate->user->id}}">
                              <input type="hidden" name="member_id" value="{{@$association->member->id}}">
                              <button class="btn btn-cyan btn-sm" type="submit">承認</button>
                              {!! Form::close() !!}
                              {!! Form::open(['route' => 'push.user.refuse', 'class'=>'action']) !!}
                              <input type="hidden" name="id" value="{{$usercate->user->id}}">
                              <input type="hidden" name="member_id" value="{{@$association->member->id}}">
                              <button class="btn btn-danger btn-sm" type="submit">却下</button>
                              {!! Form::close() !!}
                              @else
                              {!! Form::open(['route' => 'push.user.resendAuth', 'class'=>'action']) !!}
                              <input type="hidden" name="id" value="{{$usercate->user->id}}">
                              <input type="hidden" name="member_id" value="{{@$association->member->id}}">
                              <button class="btn btn-cyan btn-sm" type="submit">認証コード再送</button>
                              {!! Form::close() !!}

                              @endif
                              <!-- <input type="hidden" name="id" value="{{$usercate->user->id}}">
                              <input type="hidden" name="member_id" value="{{@$association->member->id}}">
                              <button class="btn btn-danger btn-sm" type="submit">削除</button> -->
                              <a style="line-height: 39px; margin-left: 10px; display: inline-block;" href="{{route('push.user.deleterequest', $usercate->user->id)}}">削除</a>
                            </td>
                          </tr>
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $usercates->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
      </div>
</div>

@stop  
@section('script')
  <script>
    $(document).on('keyup','.suggets', function(){
        var obj = $(this);
        var res = obj.closest('form').find('.suggets');
        if(obj.val() == ''){
        	res.html('');
        }else{
        	$.ajax({url: "{{route('push.member.ajax_search')}}?member="+obj.val(), 
	            success: function(result){
	              var str = '';
	              var dl = JSON.parse(result);
	              for(let i = 0; i < dl.length; i++){
	                str = str+'<li>'+dl[i].code+'</li>';
	                }
	              res.html(str);
	            }
	        });
        }
        
      });
  </script> 
@stop  