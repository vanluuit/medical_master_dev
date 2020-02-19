
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">プッシュ機能</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">プレビュー送付先</h5>
                    <a href="<?php echo e(route('notification.create')); ?>" class="btn btn-info btn-sm float-right">Push作成</a>
                    <a href="<?php echo e(route('notification.create.news')); ?>" class="btn btn-info btn-sm float-right mg-r15">New Push作成</a>
                    <a href="<?php echo e(route('notification.create.tvpro')); ?>" class="btn btn-info btn-sm float-right mg-r15">TVPro Push作成</a>
                    <a href="<?php echo e(route('user.list.review')); ?>" class="btn btn-info btn-sm float-right mg-r15">プレビュー送付先</a>

                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('type', [''=>'All','2'=>'TVpro', '3'=>'News'], @request()->type,['class'=>'form-control select2 search_change']); ?>

                        </div>
                      </div>
                    </form>
                </div>
                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>id</th>
                          <th>タイトル</th>
                          <th>本文</th>
                          <th style="width:120px">送付日</th>
                          <th>status</th>
                          <th style="width:190px"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($messages): ?>
                        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr >
                            <input type="hidden" name="soft[]" class="soft_id" value="<?php echo e($message->id); ?>">
                            <td><?php echo e($message->id); ?></td>
                            <td><?php echo e($message->title); ?></td>
                            <td><?php echo e($message->message); ?></td>
                            <td> <?php if($message->push !=0): ?> 
                              <?php echo e(date('Y-m-d H:i',strtotime($message->push_date))); ?>

                              <?php endif; ?>
                            </td>
                            <td><?php if($message->push==1): ?> success <?php elseif($message->push==0): ?> stored <?php else: ?>  reserved <?php endif; ?></td>
                            <td>
                              <?php if($message->push ==0 || $message->push ==2): ?>
                                <a style="line-height: 1;" class="btn btn-cyan btn-sm" href="<?php echo e(route('notification.push',$message->id)); ?>">送信<br><span style="font-size: 10px; margin-top: -5px;display: contents;line-height: 1;">(push)</span></a>
                                <a style="line-height: 1;" class="btn btn-cyan btn-sm" href="<?php echo e(route('notification.edit',$message->id)); ?>">送信<br><span style=" font-size: 10px; margin-top: -5px;display: contents;line-height: 1;">(edit)</span></a>
                                
                                <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('notification.delete',$message->id)); ?>">削除</a>
                              <?php endif; ?>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="float-center">
                    <?php echo e($messages->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>