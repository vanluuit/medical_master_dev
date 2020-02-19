@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Users</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title m-b-0 float-left">User edit</h5>
                <a href="{{route('users.index')}}" class="btn btn-info btn-sm float-right">List user</a>
              </div>
              <div class="border-bottom"></div>
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6">
                    @includeIf('notification', ['errors' => $errors])
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="file" style="display:none;" disabled="" name="avatar" id="avatar" class="form-control" placeholder="avatar" accept="image/*">
                    <label for="avatar"><img id="avatar_show" src="{{URL::to('/')}}/{{$user->avatar}}" alt="" /></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="email" value="{{$user->email}}" value="{{$user->email}}" class="form-control" placeholder="メールアドレス">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" disabled="" name="password" id="password" value="{{$user->password}}" class="form-control" placeholder="パスワード">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" disabled="" name="password_conf" value="{{$user->password}}" class="form-control" placeholder="パスワード(確認)">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="nickname" value="{{$user->nickname}}" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" disabled="" name="firstname" value="{{$user->firstname}}" class="form-control" placeholder="姓">
                  </div>
                  <div class="col-md-3">
                    <input type="text" disabled="" name="lastname" value="{{$user->lastname}}" class="form-control" placeholder="名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" disabled="" name="firstname_k" value="{{$user->firstname_k}}" class="form-control" placeholder="セイ">
                  </div>
                  <div class="col-md-3">
                    <input type="text" disabled="" name="lastname_k" value="{{$user->lastname_k}}" class="form-control" placeholder="メイ">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" disabled="" name="birthday" class="form-control" id="datepicker-autoclose" placeholder="生年月日" value="{{$user->birthday}}">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="sex" value="{{@sex()[$user->sex]}}" class="form-control" placeholder="性別">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="career" value="{{@$careers[$user->career_id]}}" class="form-control" placeholder="職業選択">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="zip" value="{{$user->zip}}" class="form-control" placeholder="勤め先の病院・医療機関名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="area_hospital" value="{{$user->area_hospital}}" class="form-control" placeholder="勤め先の病院・医療機関の都道府県">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="city_hospital" value="{{$user->city_hospital}}" class="form-control" placeholder="勤め先の病院・医療機関の市区町村">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="city_hospital" value="{{@$facultys[$user->faculty_id]}}" class="form-control" placeholder="主な診療科目">
                  </div>
                </div>
                <div id="association">
                   @if($user->association)
                    @foreach($user->association as $key => $item)
                    <div class="form-group row">
                      <div class="col-md-3">
                        <input type="" value="{{$item->category->category}}" class="form-control" disabled="">
                      </div>
                      <div class="col-md-3">
                        <input type="" value="{{$item->member->code}}" class="form-control" disabled="">
                      </div>
                     <!--  <div class="col-md-1">
                        <label class="btn btn-info add_item">+</label>
                      </div> -->
                    </div>
                    @endforeach
                  @endif
              </div>
          </div>
        </div>
        
    </div>
</div>
@stop    