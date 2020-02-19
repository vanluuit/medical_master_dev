@extends('layouts.admin')
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">seminar</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
              <div class="border-bottom">
                <div class="card-body">
                  <h5 class="card-title">seminar edit</h5>
                </div>
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => ['seminars.update', $seminar->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')) !!}
                @method('PUT')
                  <div class="form-group row">
                    <div class="col-md-6">
                      @includeIf('notification', ['errors' => $errors])
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       {!! Form::select('category_id', $categories, $seminar->category_id, ['placeholder' => '学会選択', 'class'=>'form-control select2']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('title', $seminar->title, ['placeholder' => 'タイトル', 'class'=>'form-control']) !!}
                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      {!! Form::text('website', $seminar->website, ['placeholder' => 'website', 'class'=>'form-control']) !!}
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_date" class="form-control datepicker-autoclose" id="start_date" placeholder="start date" value="{{$seminar->start_date}}">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="start_date-error" class="error" for="start_date"></label>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_date" class="form-control datepicker-autoclose" id="end_date" placeholder="end date"  value="{{$seminar->end_date}}"> 
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        
                      </div>
                      <label id="end_date-error" class="error" for="end_date"></label>
                    </div>
                  </div>
                  @php
                    $time = json_decode($seminar->time, true);
                  @endphp
                  <div class="form-group row">
                    <div class="col-md-6">
                      <table class="table table-bordered table-inverse">
                        <tbody id="set_time">
                          @if( @count($time))
                            @foreach(@$time as $k => $v)
                               
                              <tr>
                                <td>{{$k}}</td>
                                <td>
                                  {!! Form::select('time['.$k.'][start_time]', timeSelect(), @$v["start_time"], [ 'class'=>'form-control']) !!}
                                </td>
                                <td>
                                  {!! Form::select('time['.$k.'][end_time]', timeSelect(), @$v["end_time"], [ 'class'=>'form-control']) !!}
                                </td>
                              </tr>
                            @endforeach
                          @endif
                        </tbody>
                      </table>
                  </div>
                </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="banner" class="btn btn-sm btn-primary" id="banner_text">Banner</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="banner" id="banner" data-show="banner_show" data-text="banner_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="banner_show" src="{{URL::to('/')}}/{{$seminar->banner}}" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="image" class="btn btn-sm btn-primary" id="image_text">Image</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="image" id="image" data-show="image_show" data-text="image_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="image_show" src="{{URL::to('/')}}/{{$seminar->image}}" alt="" />
                      </div> 
                      <div class="custom-control custom-checkbox mr-sm-2" style="margin-top:10px;">
                            <input type="checkbox" class="custom-control-input setshow" value="8" name="delete_image" id="shownews_8">
                            <label class="custom-control-label" for="shownews_8">&nbsp;&nbsp;&nbsp;delete thumbnail</label>
                        </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="map_image" class="btn btn-sm btn-primary" id="map_image_text">Map image</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="map_image" id="map_image" data-show="map_image_show" data-text="map_image_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="map_image_show" src="{{URL::to('/')}}/{{$seminar->map_image}}" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="map" class="btn btn-sm btn-primary" id="map_text">Map pdf</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="map" id="map" data-show="map_show" data-text="map_text" class="form-control" placeholder="avatar" accept="application/pdf">
                      
                    </div>
                  </div>
                  {{-- <div class="form-group row">
                    <div class="col-md-6">
                      <label for="event_excel" class="btn btn-sm btn-primary" id="event_excel_text">select file event excel</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="event_excel" id="event_excel" data-show="event_excel_show" data-text="event_excel_text" class="form-control" placeholder="avatar" accept=".xls,.xlsx">
                    </div>
                  </div> --}}
                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              </div>
              
              {!! Form::close() !!}
              <table  style="display: none">
                <tbody id="base_tr">
                <tr>
                  <td>__time__</td>
                  <td>
                      {!! Form::select('time[__time__][time_start]', timeSelect(), '', [ 'class'=>'form-control']) !!}
                  </td>
                  <td>
                    {!! Form::select('time[__time__][end_start]', timeSelect(), '23:00', [ 'class'=>'form-control']) !!}
                  </td>
                </tr>
                </tbody>
              </table>
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
                    'category_id': {
                        required: true,
                    },
                    'title': {
                        required: true,
                    },
                    'url': {
                        required: true,
                    },
                    'start_date':{
                      required: true,
                    },
                    'end_date':{
                      required: true,
                    }
                },

                messages: {
                    'category_id': {
                        required:  "学会選択が間違っています。",
                    },
                    'title': {
                        required:  "カタイトルが間違っています。",
                    },
                    'url': {
                        required:  "URLが間違っています。",
                    },
                    'start_date':{
                        required:"start dateが間違っています。",
                    },
                    'end_date':{
                        required:"end dateが間違っています。",
                    }
                    
                    
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
        $("#banner").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#image").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#map_image").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        $("#map").change(function() {
          readURLlb(this, $(this).attr('data-text'));
        });
        $("#event_excel").change(function() {
          readURLlb(this, $(this).attr('data-text'));
        });
         $(document).on('change', '#start_date', function(){
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          if(start_date !='' && end_date !='') {
            buildTimeSelect(start_date, end_date);
          }
        });
        $(document).on('change', '#end_date', function(){
          var start_date = $('#start_date').val();
          var end_date = $('#end_date').val();
          if(start_date !='' && end_date !='') {
            buildTimeSelect(start_date, end_date);
          }
        });
        function buildTimeSelect(start_date, end_date){
            date1 = new Date(start_date);
            date2 = new Date(end_date);
            var d1 = Date.parse(date1);
            var d2 = Date.parse(date2);
            var day = Math.ceil((d2 - d1) / (24*60*60*1000));
            var str = '';
            var str_base = $('#base_tr').html();
            // alert("Ngày mai: " + ); 
          for (var i = 0; i <= day; i++) {
            var df = new Date(date1.getFullYear(), date1.getMonth(), date1.getDate() + i);
            var mm = df.getMonth() + 1;
            var dd = df.getDate();
            var yyyy = df.getFullYear();
            if(mm < 10) mm = '0'+mm;
            if(dd < 10) dd = '0'+dd;
            var show = yyyy+'-'+mm+'-'+dd;
            str += str_base.replace(/__time__/g, show);
          }
          $('#set_time').html(str);
        }
    </script>
@stop   