
<?php $__env->startSection('content'); ?>
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
                        <?php if($usercates): ?>
                        <?php $__currentLoopData = $usercates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $usercate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td>
                              <?php echo e(date('Y-m-d',strtotime($usercate->user->created_at))); ?><br>
                              <!-- <a href="#" data-toggle="modal" data-target="#Modal_<?php echo e($usercate->user->id); ?>">編集</a> -->
                              <?php echo Form::open(['route' => ['push.users.update', $usercate->user->id], 'id'=>'validate','enctype'=>'multipart/form-data']); ?>

                <?php echo method_field('PUT'); ?>
                              <div class="modal fade" id="Modal_<?php echo e($usercate->user->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                                <input type="hidden" name="id" value="<?php echo e($usercate->user->id); ?>">
                                
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"><?php echo e($usercate->user->lastname); ?> <?php echo e($usercate->user->firstname); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                          
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="text" name="email" value="<?php echo e($usercate->user->email); ?>" value="<?php echo e($usercate->user->email); ?>" class="form-control" placeholder="メールアドレス" >
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="password" name="password" id="password" value="<?php echo e(@Crypt::decryptString($usercate->user->password)); ?>" class="form-control" placeholder="パスワード">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="password" name="password_conf" value="<?php echo e(@Crypt::decryptString($usercate->user->password)); ?>" class="form-control" placeholder="パスワード(確認)">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="text" name="nickname" value="<?php echo e($usercate->user->nickname); ?>" class="form-control" placeholder="ニックネーム">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="firstname" value="<?php echo e($usercate->user->firstname); ?>" class="form-control" placeholder="姓">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="lastname" value="<?php echo e($usercate->user->lastname); ?>" class="form-control" placeholder="名">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-6">
                    <input type="text" name="firstname_k" value="<?php echo e($usercate->user->firstname_k); ?>" class="form-control" placeholder="セイ">
                  </div>
                  <div class="col-md-6">
                    <input type="text" name="lastname_k" value="<?php echo e($usercate->user->lastname_k); ?>" class="form-control" placeholder="メイ">
                  </div>
                </div>
                
                <div class="form-group row">
                  <div class="col-md-12">
                    <div class="input-group">
                      <input type="text" name="birthday" class="form-control" id="datepicker-autoclose" placeholder="生年月日" value="<?php echo e($usercate->user->birthday); ?>">
                      <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <?php echo Form::select('career_id', $careers, $usercate->user->career_id, ['placeholder' => '職業選択', 'class'=>'form-control select2']); ?>

                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-12">
                    <input type="text" name="member" class="form-control suggets" value="<?php echo e($usercate->member->code); ?>" placeholder="会員番号">
                                       
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
                              <?php echo Form::close(); ?>

                            </td>
                            <td><?php echo e($usercate->user->lastname); ?> <?php echo e($usercate->user->firstname); ?> </td>
                            <td><?php echo e($usercate->user->birthday); ?></td>
                            <td><?php echo e(@$usercate->member->code); ?></td>
                            <td><a href="mailto:<?php echo e(@$usercate->user->email); ?>"><?php echo e(@$usercate->user->email); ?></a></td>
                            <td>
                              <?php if($usercate->status == 0): ?>
                              <?php echo Form::open(['route' => 'push.user.editapprove', 'class'=>'action']); ?>

                              <!-- <button class="btn btn-cyan btn-sm" data-toggle="modal" data-target="#Modal_<?php echo e($usercate->user->id); ?>_<?php echo e(@$association->member->id); ?>" type="button">編集して承認</button>
                              <div class="modal fade" id="Modal_<?php echo e($usercate->user->id); ?>_<?php echo e(@$association->member->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
                                <input type="hidden" name="id" value="<?php echo e($usercate->user->id); ?>">
                                
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
                              <?php echo Form::close(); ?>

                              <?php echo Form::open(['route' => 'push.user.approve', 'class'=>'action']); ?>

                              <input type="hidden" name="id" value="<?php echo e($usercate->user->id); ?>">
                              <input type="hidden" name="member_id" value="<?php echo e(@$association->member->id); ?>">
                              <button class="btn btn-cyan btn-sm" type="submit">承認</button>
                              <?php echo Form::close(); ?>

                              <?php echo Form::open(['route' => 'push.user.refuse', 'class'=>'action']); ?>

                              <input type="hidden" name="id" value="<?php echo e($usercate->user->id); ?>">
                              <input type="hidden" name="member_id" value="<?php echo e(@$association->member->id); ?>">
                              <button class="btn btn-danger btn-sm" type="submit">却下</button>
                              <?php echo Form::close(); ?>

                              <?php else: ?>
                              <?php echo Form::open(['route' => 'push.user.resendAuth', 'class'=>'action']); ?>

                              <input type="hidden" name="id" value="<?php echo e($usercate->user->id); ?>">
                              <input type="hidden" name="member_id" value="<?php echo e(@$association->member->id); ?>">
                              <button class="btn btn-cyan btn-sm" type="submit">認証コード再送</button>
                              <?php echo Form::close(); ?>


                              <?php endif; ?>
                              <!-- <input type="hidden" name="id" value="<?php echo e($usercate->user->id); ?>">
                              <input type="hidden" name="member_id" value="<?php echo e(@$association->member->id); ?>">
                              <button class="btn btn-danger btn-sm" type="submit">削除</button> -->
                              <a style="line-height: 39px; margin-left: 10px; display: inline-block;" href="<?php echo e(route('push.user.deleterequest', $usercate->user->id)); ?>">削除</a>
                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
                <div class="float-center">
                    <?php echo e($usercates->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
      </div>
</div>

<?php $__env->stopSection(); ?>  
<?php $__env->startSection('script'); ?>
  <script>
    $(document).on('keyup','.suggets', function(){
        var obj = $(this);
        var res = obj.closest('form').find('.suggets');
        if(obj.val() == ''){
        	res.html('');
        }else{
        	$.ajax({url: "<?php echo e(route('push.member.ajax_search')); ?>?member="+obj.val(), 
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
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.push', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>