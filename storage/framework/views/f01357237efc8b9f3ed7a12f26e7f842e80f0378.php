
<?php $__env->startSection('content'); ?>
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
                <a href="<?php echo e(route('users.index')); ?>" class="btn btn-info btn-sm float-right">List user</a>
              </div>
              <div class="border-bottom"></div>
              <div class="card-body">
                <div class="form-group row">
                  <div class="col-md-6">
                    <?php if ($__env->exists('notification', ['errors' => $errors])) echo $__env->make('notification', ['errors' => $errors], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="file" style="display:none;" disabled="" name="avatar" id="avatar" class="form-control" placeholder="avatar" accept="image/*">
                    <label for="avatar"><img id="avatar_show" src="<?php echo e(URL::to('/')); ?>/<?php echo e($user->avatar); ?>" alt="" /></label>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="email" value="<?php echo e($user->email); ?>" value="<?php echo e($user->email); ?>" class="form-control" placeholder="メールアドレス">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" disabled="" name="password" id="password" value="<?php echo e($user->password); ?>" class="form-control" placeholder="パスワード">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="password" disabled="" name="password_conf" value="<?php echo e($user->password); ?>" class="form-control" placeholder="パスワード(確認)">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="nickname" value="<?php echo e($user->nickname); ?>" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" disabled="" name="firstname" value="<?php echo e($user->firstname); ?>" class="form-control" placeholder="姓">
                  </div>
                  <div class="col-md-3">
                    <input type="text" disabled="" name="lastname" value="<?php echo e($user->lastname); ?>" class="form-control" placeholder="名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-3">
                    <input type="text" disabled="" name="firstname_k" value="<?php echo e($user->firstname_k); ?>" class="form-control" placeholder="セイ">
                  </div>
                  <div class="col-md-3">
                    <input type="text" disabled="" name="lastname_k" value="<?php echo e($user->lastname_k); ?>" class="form-control" placeholder="メイ">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <input type="text" disabled="" name="birthday" class="form-control" id="datepicker-autoclose" placeholder="生年月日" value="<?php echo e($user->birthday); ?>">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="sex" value="<?php echo e(@sex()[$user->sex]); ?>" class="form-control" placeholder="性別">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="career" value="<?php echo e(@$careers[$user->career_id]); ?>" class="form-control" placeholder="職業選択">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="zip" value="<?php echo e($user->zip); ?>" class="form-control" placeholder="勤め先の病院・医療機関名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="area_hospital" value="<?php echo e($user->area_hospital); ?>" class="form-control" placeholder="勤め先の病院・医療機関の都道府県">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="city_hospital" value="<?php echo e($user->city_hospital); ?>" class="form-control" placeholder="勤め先の病院・医療機関の市区町村">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" disabled="" name="city_hospital" value="<?php echo e(@$facultys[$user->faculty_id]); ?>" class="form-control" placeholder="主な診療科目">
                  </div>
                </div>
                <div id="association">
                   <?php if($user->association): ?>
                    <?php $__currentLoopData = $user->association; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-group row">
                      <div class="col-md-3">
                        <input type="" value="<?php echo e($item->category->category); ?>" class="form-control" disabled="">
                      </div>
                      <div class="col-md-3">
                        <input type="" value="<?php echo e($item->member->code); ?>" class="form-control" disabled="">
                      </div>
                     <!--  <div class="col-md-1">
                        <label class="btn btn-info add_item">+</label>
                      </div> -->
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php endif; ?>
              </div>
          </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>    
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>