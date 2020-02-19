
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Seminar</h4>
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
                  <h5 class="card-title">Seminar add</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => 'seminars.store', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_id', $categories, '', ['placeholder' => '学会選択', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('title', '', ['placeholder' => 'タイトル', 'class'=>'form-control']); ?>

                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('website', '', ['placeholder' => 'website', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_date" class="form-control datepicker-autoclose" id="start_date" placeholder="start date">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="start_date-error" class="error" for="start_date"></label>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_date" class="form-control datepicker-autoclose" id="end_date" placeholder="end date">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                        
                      </div>
                      <label id="end_date-error" class="error" for="end_date"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <table class="table table-bordered table-inverse">
                        <tbody id="set_time">
                          
                        </tbody>
                      </table>
                  </div>
                </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="banner" class="btn btn-sm btn-primary" id="banner_text">Banner</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="banner" id="banner" data-show="banner_show" data-text="banner_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="banner_show" src="#" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="image" class="btn btn-sm btn-primary" id="image_text">Image</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="image" id="image" data-show="image_show" data-text="image_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="image_show" src="#" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="map_image" class="btn btn-sm btn-primary" id="map_image_text">Map image</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="map_image" id="map_image" data-show="map_image_show" data-text="map_image_text" class="form-control" placeholder="avatar" accept="image/*">
                      
                      <div class="thumbnail">
                        <img id="map_image_show" src="#" alt="" />
                      </div> 
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="map" class="btn btn-sm btn-primary" id="map_text">Map pdf</label>
                      <input type="file" style="width: 0; height: 0; line-height: 0; margin: 0; padding: 0" name="map" id="map" data-show="map_show" data-text="map_text" class="form-control" placeholder="avatar" accept="application/pdf">
                      
                    </div>
                  </div>
                  
                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              </div>
              
              <?php echo Form::close(); ?>

              <table  style="display: none">
                <tbody id="base_tr">
                <tr>
                  <td>__time__</td>
                  <td>
                      <?php echo Form::select('time[__time__][start_time]', timeSelect(), '', [ 'class'=>'form-control']); ?>

                  </td>
                  <td>
                    <?php echo Form::select('time[__time__][end_time]', timeSelect(), '23:00', [ 'class'=>'form-control']); ?>

                  </td>
                </tr>
                </tbody>
              </table>
          </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>  

<?php $__env->startSection('script'); ?> 
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
                    },
                    'banner':{
                      required: true,
                    },
                    'image':{
                      required: true,
                    },
                    'map_image':{
                      required: true,
                    },
                    'map':{
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
                    },
                    'banner':{
                       required:"bannerが間違っています。",
                    },
                    'image':{
                       required:"imageが間違っています。",
                    },
                    'map_image':{
                       required:"map_imageが間違っています。",
                    },
                    'map':{
                       required:"map pdfが間違っています。",
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
        // CKEDITOR.replace( 'content' );
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
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>