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
    
        <!-- Column -->
        
    <div class="card">
    {!! Form::open(array('route' => 'push.notification.store', 'id'=>'form_ajax', 'enctype'=>'multipart/form-data')) !!}
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <div class="col-md-12">
                @includeIf('notification', ['errors' => $errors])
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                {!! Form::text('title', '', ['placeholder' => 'タイトル', 'class'=>'form-control']) !!}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <textarea class="form-control" name="message" cols="30" rows="10" placeholder="本文"></textarea>
              </div>
            </div>  
            <div class="form-group row">
              <div class="col-md-12">
                 <input type="hidden" name="type" value="2">
                 <input type="hidden" name="global" value="1">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                 {!! Form::select('channel_id', $channel, '', ['placeholder' => 'チャンネル選択', 'class'=>'form-control select2', 'id'=>'channel_id']) !!}
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                 {!! Form::select('content_id', $videos, '', ['placeholder' => 'リンク先コンテンツ', 'class'=>'form-control select2', 'id'=>'content_id']) !!}
              </div>
            </div>
            <!-- <div class="form-group row">
                      <div class="col-md-4">
                         <div class="custom-control custom-radio">
                              <input type="radio" class="custom-control-input filter_inc filter_in" id="membercx" name="filter" value="1" data-id="member_show">
                                <label class="custom-control-label" for="membercx">個別配信</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input filter_inc filter_in" id="filter" name="filter" value="2" data-id="filter_show">
                              <label class="custom-control-label" for="filter">配信出し分け</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                            <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input filter_inc filter_in" id="listmember" name="filter" value="3" data-id="listmember_show">
                              <label class="custom-control-label" for="listmember">リストから配信</label>
                          </div>
                      </div>
                      

                    </div> -->
                    <!-- <div class="form-group row swith" id="filter_show">
                      <div class="col-md-12">
                        <div class="cardx">
                          <ul class="nav nav-tabs" role="tablist">
                              <li class="nav-item"> 
                                <div class="nav-link">
                                  <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input filter_in" id="reset_filter" name="reset_filter" value="1">
                                    <label class="custom-control-label" for="reset_filter">全配信</label>
                                  </div>
                                </div> 
                              </li>
                              <li class="nav-item"> <a class="nav-link active"  data-toggle="tab" href="#job" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">就職業別</span></a> </li>
                              <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#age" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">年齢別</span></a> </li>
                              <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#area" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">都道府県別</span></a> </li>
                          </ul>
                          <div class="tab-content tabcontent-border">
                              <div class="tab-pane" id="member" role="tabpanel">
                              </div>
                              <div class="tab-pane  p-20 active" id="job" role="tabpanel">
                                  <div>
                                      <ul class="list_check"  id="career_list_show">
                                        @if($careers)
                                          @foreach($careers as $key => $career)
                                            <li>
                                              <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input filter_in" id="career_{{$key}}" name="career[]" value="{{$key}}">
                                                <label class="custom-control-label" for="career_{{$key}}">{{$career}}</label>
                                              </div>
                                            </li>
                                          @endforeach
                                        @endif
                                      </ul>
                                  </div>
                              </div>
                              <div class="tab-pane p-20" id="age" role="tabpanel">
                                  <div>
                                      <div class="form-group row">
                                          <div class="col-md-4">
                                            <div class="input-group date">
                                              <input type="text" name="olds" class="form-control " >
                                            </div>
                                          </div>
                                          <div class="col-md-2 text-center">
                                            ~
                                          </div>
                                          <div class="col-md-4">
                                            <div class="input-group date">
                                              <input type="text" name="olde" class="form-control " >
                                            </div>
                                          </div>
                                          <div class="col-md-2">
                                    <button type="button" class="btn btn-primary filter-button">filter</button>
                                  </div>
                                        </div>
                                  </div>
                              </div>
                              <div class="tab-pane p-20" id="area" role="tabpanel">
                                  <div>
                                      <ul class="list_check l_province"  id="area_list_check">
                                        @if($provinces)
                                          @foreach($provinces as $key => $province)
                                            <li>
                                              <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input filter_in" id="province_{{$key}}" name="area[]" value="{{$province}}">
                                                <label class="custom-control-label" for="province_{{$key}}">{{$province}}</label>
                                              </div>
                                            </li>
                                          @endforeach
                                        @endif
                                      </ul>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div> -->
                    <!-- <div class="form-group row swith" id="member_show">
                      <div class="col-md-12">
                        <input type='text' class="form-control" id='search' placeholder='Search Member'>
                        <div id="checkbox_member">
                          <ul id="checkbox_member_list">
                            @foreach($members as $key => $member)
                              <li class="content">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input filter_in" id="member_ar_{{$key}}" name="member[]" value="{{$key}}">
                                      <label class="custom-control-label" for="member_ar_{{$key}}">{{$member}}</label>
                                  </div>
                                </li>
                            @endforeach
                          </ul>
                      </div>
                      </div>
                    </div> -->
                    <!-- <div class="form-group row swith" id="listmember_show">
                      <div class="col-md-12">
                         {!! Form::select('listpush', $ListMemberPush, '', ['placeholder' => 'リストから配信', 'class'=>'form-control select2 filter_in']) !!}
                      </div>
                    </div> -->
                    <!-- <div class="form-group row">
                      <div class="col-md-6">
                        <label>配信予約日時</label>
                        </div>
                    </div> -->
                    <!-- <div class="form-group row">
                      <div class="col-md-6">
                        <div class="input-group date">
                          <input type="text" name="day" class="form-control datepicker-autoclose" id="day" placeholder="day push" value="{{dayNow()}}">
                          <div class="input-group-append">
                            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                          </div>
                          
                        </div>
                        <label id="day-error" class="error" for="day"></label>
                      </div>
                      <div class="col-md-3">
                        {!! Form::select('time', timeSel(), NowT('H'), [ 'class'=>'form-control']) !!}
                      </div>
                      <div class="col-md-3">
                        {!! Form::select('minute', minuteSel(), NowT('i'), [ 'class'=>'form-control']) !!}
                      </div>
                    </div> -->
          </div>
          <div class="col-md-6" style="display: none;">
            <p>配信対象計<span>total ( <span id="total_ajax">{{count($users)}}</span> )</span></p>
            <div id="result_user">
              <table id="list_user_filter">
              @foreach($users as $key => $user)
                <tr>
                  <td>
                    {{$user->email}}
                    <input type="hidden" name="users[]" value="{{$user->id}}">
                  </td>
                </tr>
              @endforeach
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="border-top">
              <div class="card-body">
                <button type="button" id="pushbutton" name="push" value="1" class="btn btn-primary">送信</button>
                <button type="submit" name="push" value="0" class="btn btn-primary">保存</button>
               <!--  <button type="button" class="btn btn-primary pushPreview">プレビュー <span id="loading"></span></button>
                <button type="submit" name="push" value="2" class="btn btn-danger">配信予約</button> -->
              </div>
          </div>
      {!! Form::close() !!}
    </div>
        </div>
    </div>
</div>
@stop  

@section('script') 
    <script>

        $(document).ready(function(){
            var validateForm = $('#validate').validate({
                rules: {
                    'title': {
                        required: true,
                    },
                    'message': {
                        required: true,
                    },
                    // 'channel_id': {
                    //     required: true,
                    // },
                    // 'category_id': {
                    //     required: true,
                    // },
                    // 'content_id': {
                    //     required: true,
                    // },
                },

                messages: {
                    'title': {
                        required:  "タイトルが間違っています。",
                    },
                    'message': {
                        required:  "messageが間違っています。",
                    },
                    // 'channel_id': {
                    //     required:  "channelが間違っています。",
                    // },
                    // 'category_id': {
                    //     required:  "学会選択が間違っています。",
                    // },
                    // 'content_id': {
                    //     required:  "contentが間違っています。",
                    // },
                },
                highlight: function (e) {
                    $(e).closest('td').removeClass('has-info').addClass('has-error');
                },

                success: function (e) {
                    $(e).closest('td').removeClass('has-error');//.addClass('has-info');
                    $(e).remove();
                },

                errorPlacement: function (error, element) {
                    if(element.is('input[type=checkbox') || element.is('input[type=radio')) {
                        var controls = element.closest('td');
                        if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    }
                    else error.insertAfter(element);
                }
            });
        });
        $("#media").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        // CKEDITOR.replace( 'content' );
        $(document).on('change','#category_id', function(){
          var category_id = $('#category_id').val();
          // var category_new_id = $('#category_new_id').val();
          $.ajax({url: "{{route('push.channel.by.category')}}?category_id="+category_id, success: function(result){
            var ar = JSON.parse(result);
            // if(ar.length > 0){
              var str ='<option selected=\"selected\" value=\"\">Channel</option>\"';
              for (var i = 0; i < ar.length; i++) {
                str = str+'<option value="'+ar[i].id+'">'+ar[i].title+'</option>'
              }
              $('#channel_id').html(str);
            // }
          }});
        });
        $(document).on('change','#channel_id', function(){
          var channel_id = $('#channel_id').val();
          // var category_new_id = $('#category_new_id').val();
          $.ajax({url: "{{route('push.videos.by.channel')}}?channel_id="+channel_id, success: function(result){
            var ar = JSON.parse(result);
            // if(ar.length > 0){
              var str ='<option selected=\"selected\" value=\"\">リンク先コンテンツ</option>\"';
              for (var i = 0; i < ar.length; i++) {
                str = str+'<option value="'+ar[i].id+'">'+ar[i].title+'</option>'
              }
              $('#content_id').html(str);
            // }
          }});
        });
        $(document).on('change','.filter_in', function(){
          $('#list_user_filter').html('<tr><td><img src="/loading.gif" /></td></tr>');
          $.ajax({url: "{{route('push.user.ajax.get')}}?"+$('#form_ajax').serialize(), 
            success: function(result){
              var str = '';
              var dl = JSON.parse(result);
              for(let i = 0; i < dl.length; i++){
                str = str+'<tr><td>'+dl[i].email+'<input type="hidden" name="users[]" value="'+dl[i].id+'"></td></tr>';
          }
              $('#list_user_filter').html(str);
              $('#total_ajax').html(dl.length);

            }
        });
      });
        $(document).on('click','.filter-button', function(){
          $('#list_user_filter').html('<tr><td><img src="/loading.gif" /></td></tr>');
          $.ajax({url: "{{route('push.user.ajax.get')}}?"+$('#form_ajax').serialize(), 
            success: function(result){
              var str = '';
              var dl = JSON.parse(result);
              for(let i = 0; i < dl.length; i++){
                str = str+'<tr><td>'+dl[i].email+'<input type="hidden" name="users[]" value="'+dl[i].id+'"></td></tr>';
          }
              $('#list_user_filter').html(str);
              $('#total_ajax').html(dl.length);

            }
        });
      });
      $(document).on('change','#category_id', function(){
          $.ajax({url: "{{route('push.member.ajax.getlist')}}?"+$('#form_ajax').serialize(), 
            success: function(result){
              var str = '';
              var dl = JSON.parse(result);
              for(let i = 0; i < dl.length; i++){
                // str = str+'<tr><td>'+dl[i].nickname+'</td><td>'+dl[i].email+'</td></tr>';
                str = str+'<li class="content">\
                      <div class="custom-control custom-checkbox">\
                            <input type="checkbox" class="custom-control-input filter_in" id="member_ar_'+dl[i].id+'" name="member[]" value="'+dl[i].id+'">\
                              <label class="custom-control-label" for="member_ar_'+dl[i].id+'">'+dl[i].code+'</label>\
                          </div>\
                        </li>';
          }
              $('#checkbox_member_list').html(str);

            }
        });
          $.ajax({url: "{{route('push.careers.ajax.getlist')}}?"+$('#form_ajax').serialize(), 
            success: function(result){
              var str = '';
              var dl = JSON.parse(result);
              for(let i = 0; i < dl.length; i++){
                // str = str+'<tr><td>'+dl[i].nickname+'</td><td>'+dl[i].email+'</td></tr>';
                str = str+'<li class="content">\
                      <div class="custom-control custom-checkbox">\
                            <input type="checkbox" class="custom-control-input filter_in" id="career_ar_'+dl[i].id+'" name="career[]" value="'+dl[i].id+'">\
                              <label class="custom-control-label" for="career_ar_'+dl[i].id+'">'+dl[i].name+'</label>\
                          </div>\
                        </li>';
          }
              $('#career_list_show').html(str);
            }
        });
        $.ajax({url: "{{route('push.provinces.ajax.getlist')}}?"+$('#form_ajax').serialize(), 
            success: function(result){
              var str = '';
              var dl = JSON.parse(result);
              for(let i = 0; i < dl.length; i++){
                // str = str+'<tr><td>'+dl[i].nickname+'</td><td>'+dl[i].email+'</td></tr>';
                str = str+'<li class="content">\
                      <div class="custom-control custom-checkbox">\
                            <input type="checkbox" class="custom-control-input filter_in" id="area_ar_'+dl[i].id+'" name="area[]" value="'+dl[i].province_name+'">\
                              <label class="custom-control-label" for="area_ar_'+dl[i].id+'">'+dl[i].province_name+'</label>\
                          </div>\
                        </li>';
          }
              $('#area_list_check').html(str);
            }
        });
      });

      
      $(document).on('click','.filter_inc', function(){
          var id = $(this).attr('data-id');
          $('.swith').hide();
          $('#'+id).show();
      });

    $(document).on('keyup','#search', function(){
      // Search text
      var text = $(this).val();
     
      // Hide all content class element
      $('.content').hide();

      // Search 
      $('.content label:contains("'+text+'")').closest('.content').show();

     
     });
    $(document).on('click','.pushPreview', function(){
      $('#loading').html('<img src="/loading.gif" />');
          $.ajax({url: "{{route('push.notification.push.preview')}}?"+$('#form_ajax').serialize(), 
            success: function(result){
              $('#loading').html('<img src="/tick.png" />');
              // console.log('ok');
            }
        });
      });
    $('#pushbutton').on('click', function(){
            $.confirm({
                title: 'プッシュ機能',
                content: 'Push通知を送信しますか？',
                buttons: {
                    はい: function(){
                      $("<input />").attr("type", "hidden")
                    .attr("name", "push")
                    .attr("value", "1")
                    .appendTo("#pushbutton");
                        $("#form_ajax").submit();
                    },
                    キャンセル: function(){
                        // $.alert('Canceled!');
                    },
                }
            });
        });
    </script>
@stop   