@extends('layouts.push')
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
                <h5 class="card-title m-b-0 float-left">User show</h5>
                <a href="{{route('users.edit', $user->id)}}" class="btn btn-info btn-sm float-right">Edit user</a>
              </div>
              <div class="border-bottom"></div>
              <div class="card-body">
                <table class="show-tb">
                  <tbody>
                    <tr>
                      <th>メールアドレス</th>
                      <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                      <th>パスワード</th>
                      <td>**********</td>
                    </tr>
                    <tr>
                      <th>ニックネーム</th>
                      <td>{{$user->nickname}}<td>
                    </tr>
                    <tr>
                      <th>姓名</th>
                      <td>{{$user->firstname}}{{$user->lastname}}<td>
                    </tr>
                    <tr>
                      <th>姓-名</th>
                      <td>{{$user->firstname}}-{{$user->lastname}}<td>
                    </tr>
                    <tr>
                      <th>セイ-メイ</th>
                      <td>{{$user->firstname_k}}-{{$user->lastname_k}}<td>
                    </tr>
                    <tr>
                      <th>生年月日</th>
                      <td>{{$user->birthday}}<td>
                    </tr>
                    <tr>
                      <th>性別</th>
                      <td>{{sex()[$user->sex]}}<td>
                    </tr>
                    <tr>
                      <th>職業選択</th>
                      <td>{{$user->career}}<td>
                    </tr>
                    <tr>
                      <th>勤め先の病院・医療機関名</th>
                      <td>{{$user->zip}}<td>
                    </tr>
                    <tr>
                      <th>勤め先の病院・医療機関の都道府県</th>
                      <td>{{$user->area_hospital}}<td>
                    </tr>
                    <tr>
                      <th>勤め先の病院・医療機関の市区町村</th>
                      <td>{{$user->city_hospital}}<td>
                    </tr>
                    <tr>
                      <th>主な診療科目</th>
                      <td>{{$user->hospital_name}}<td>
                    </tr>
                  </tbody>
                </table>
              </div>
          </div>
        </div>
        
    </div>
</div>
@stop  
  