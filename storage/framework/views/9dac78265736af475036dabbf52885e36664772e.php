  
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Events</h4>
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
                  <h5 class="card-title">Event edit</h5>
                </div>
              </div>
              <div class="card-body">
                <?php echo Form::open(array('route' => ['events.update', $event->id], 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <?php echo method_field('PUT'); ?>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                  </div>
                  <input type="hidden" name="seminar_id" value="<?php echo e($event->seminar_id); ?>">
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('category_event_id', $categories, $event->category_event_id, ['placeholder' => 'カテゴリー', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                       <?php echo Form::select('theme_event_id', $theme, $event->theme_event_id, ['placeholder' => 'テーマ', 'class'=>'form-control select2']); ?>

                    </div>
                  </div>
                   
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('name_basis', $event->name_basis, ['placeholder' => '施設名', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('hall', $event->hall, ['placeholder' => '会場', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('floor', $event->floor, ['placeholder' => 'フロア', 'class'=>'form-control']); ?>

                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <?php echo Form::text('room', $event->room, ['placeholder' => '部屋', 'class'=>'form-control']); ?>

                    </div>
                  </div>  
                  <div class="form-group row">
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="start_time" class="form-control datetimepicker" id="" placeholder="start time" value="<?php echo e($event->start_time); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="start_time-error" class="error" for="start_time"></label>
                    </div>
                    <div class="col-md-3">
                      <div class="input-group date">
                        <input type="text" name="end_time" class="form-control datetimepicker" id="" placeholder="end time"  value="<?php echo e($event->end_time); ?>">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                      <label id="end_time-error" class="error" for="end_time"></label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-md-6">
                      <textarea name="preside" id="" cols="30" rows="10" placeholder="preside" class="form-control"><?php echo e($event->preside); ?></textarea>
                    </div>
                  </div>
                  
                  <?php if($event->event_detail): ?>
                    <?php $__currentLoopData = $event->event_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="cthl">
                      <div class="form-group row">
                        <div class="col-md-6">
                          <?php echo Form::text('topic_number['.$item->id.']', $item->topic_number, ['placeholder' => '演題番号', 'class'=>'form-control']); ?>

                        </div>
                      </div> 

                      <div class="form-group row">
                        <div class="col-md-12">
                          <?php echo Form::text('name['.$item->id.']', $item->name, ['placeholder' => '部屋', 'class'=>'form-control']); ?>

                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-6">
                          <textarea name="member[<?php echo e($item->id); ?>]" id="" cols="30" rows="10" placeholder="member" class="form-control"><?php echo e($item->member); ?></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-md-12">
                          <textarea name="content[<?php echo e($item->id); ?>]" id="" cols="30" rows="10" placeholder="抄録原稿A" class="form-control"><?php echo e($item->content); ?></textarea>
                        </div>
                      </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
                </div>
                <div class="border-top">
                <div class="card-body">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              </div>
              <?php echo Form::close(); ?>

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
                    'location': {
                        required: true,
                    },
                    'name': {
                        required: true,
                    },
                    'start_time': {
                        required: true,
                    },
                    'end_time':{
                      required: true,
                    },
                    'excerpt':{
                      required: true,
                    },
                    'content':{
                      required: true,
                    }
                },

                messages: {
                    'location': {
                      required:  "locationが間違っています。",
                    },
                    'name': {
                      required:  "カタイトルが間違っています。",
                    },
                    'start_time': {
                      required:  "start timeが間違っています。",
                    },
                    'end_time':{
                      required:"end timeが間違っています。",
                    },
                    'excerpt': {
                      required:  "概要が空欄です。",
                    },
                    'content':{
                      required:"contentが間違っています。",
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
        $("#media").change(function() {
          readURL(this, $(this).attr('data-show'), $(this).attr('data-text'));
        });
        // CKEDITOR.replace( 'content' );
    </script>
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>