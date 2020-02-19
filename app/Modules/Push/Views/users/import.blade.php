@extends('layouts.push')
@section('content')
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
                    {!! Form::open(array('route' => 'push.user.import.post', 'enctype'=>'multipart/form-data')) !!}
                      <div class="row">
                        <div class="col-md-12">
                          <input type="file" name="member" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required="">
                          <button type="submit" class="btn btn-primary btn-sm ">アップロード</button>
                          <a href="{{route('push.user.export')}}" class="btn btn-primary btn-sm">ダウンロード</a>
                          <a href="{{route('push.members.create')}}" class="btn btn-info btn-sm float-right">新規追加</a>
                        </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">会員番号リスト  会員番号数（{{$members->total()}}）登録会員数（{{$user_total}}）</h5>
                </div>
                <table class="table">
                      <thead>
                        <tr>
                          <th>学会会員番号</th>
                          <th>アカウント名</th>
                          <th>氏名</th>
                          <th>職業</th>
                          <th>勤め先の病院</th>
                          <th>都道府県</th>
                          <th>生年月日</th>
                          <th>登録日</th>
                          <th style="width:100px"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @if($members)
                        @foreach($members as $key => $member)
                          @if(count(@$member->associationu))
                            @foreach($member->associationu as $key1 => $association)
                            <tr>
                              <td>{{$member->code}}</td>
                              <td>
                                
                                <a href="#" class="" data-toggle="modal" data-target="#Modal_{{@$association->user->id}}">{{@$association->user->nickname}}</a>
                                <div class="modal fade" id="Modal_{{@$association->user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                                  <div class="modal-dialog modal-lg" role="document">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">{{@$association->user->nickname}}</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">×</span>
                                              </button>
                                          </div>
                                          <div class="modal-body">
                                          @if(@$association->user->avatar)
                                           <div class="form-group row">
                                              <div class="col-md-7">
                                                <img id="avatar_show" src="{{URL::to('/')}}/{{@$association->user->avatar}}" alt="" />
                                              </div>
                                            </div>
                                        @endif
                                            <div class="form-group row">
                                              <div class="col-md-5">メールアドレス</div>
                                              <div class="col-md-7">
                                                {{@$association->user->email}}
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">パスワード</div>
                                              <div class="col-md-7">
                                                @if(@$association->user->password)
                                                <!-- {{@Crypt::decryptString(@$association->user->password)}} -->
                                                ........
                                                @endif
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">ニックネーム</div>
                                              <div class="col-md-7">
                                                {{@$association->user->nickname}} 
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">姓名</div>
                                              <div class="col-md-7">
                                                {{@$association->user->lastname}} {{@$association->user->firstname}} 
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">セイ メイ</div>
                                              <div class="col-md-7">
                                                {{@$association->user->lastname_k}}
                                                {{@$association->user->firstname_k}} 
                                              </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                              <div class="col-md-5">生年月日</div>
                                              <div class="col-md-7">
                                                {{@$association->user->birthday}}
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">性別</div>
                                              <div class="col-md-7">
                                                {{@sex()[@$association->user->sex]}}
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">職業選択</div>
                                              <div class="col-md-7">
                                                {{@$careers[@$association->user->career_id]}}
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">勤め先の病院・医療機関名</div>
                                              <div class="col-md-7">
                                                {{@$association->user->hospital_name}}
                                              </div>
                                            </div>
                                            {{-- <div class="form-group row">
                                              <div class="col-md-5">勤め先の病院・医療機関の都道府県</div>
                                              <div class="col-md-7">
                                                {{@$association->user->area_hospital}}
                                              </div>
                                            </div> --}}
                                            <div class="form-group row">
                                              <div class="col-md-5">勤め先の病院・医療機関の都道府県</div>
                                              <div class="col-md-7">
                                                {{@$area_hospital[@$association->user->area_hospital]}}
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">勤め先の病院・医療機関の市区町村</div>
                                              <div class="col-md-7">
                                                {{@$association->user->city_hospital}}
                                              </div>
                                            </div>
                                            <div class="form-group row">
                                              <div class="col-md-5">主な診療科目</div>
                                              <div class="col-md-7">
                                                {{@$facultys[@$association->user->faculty_id]}}
                                              </div>
                                            </div>
                                          </div>
                                         <!--  <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                              <button type="submit" class="btn btn-primary">編集して承認</button>
                                          </div> -->
                                      </div>
                                  </div>
                                  
                                </div> 
                              </td>
                              <td>{{@$association->user->lastname}} {{@$association->user->firstname}}</td>
                              <td>{{@$association->user->career->name}}</td>
                              <td>{{@$association->user->hospital_name}}</td>
                              <td>{{@$association->user->area_hospital}}</td>
                              <td>{{@$association->user->birthday}}</td>
                              <td>{{date('Y-m-d', strtotime(@$association->user->created_at))}}</td>
                              <td>
                                <a class="btn btn-danger btn-sm" onclick="return confirm('本当に削除を行いますか？');" href="{{route('push.members.delete',$member->id)}}">削除</a>
                              </td>
                            </tr>
                            @endforeach
                          @else
                          <tr>
                              <td>{{$member->code}}</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('本当に削除を行いますか？');" href="{{route('push.members.delete',$member->id)}}">削除</a>
                            </td>
                            </tr>
                          @endif
                        @endforeach
                        @endif
                      </tbody>
                </table>
                <div class="float-center">
                    {{ $members->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop  