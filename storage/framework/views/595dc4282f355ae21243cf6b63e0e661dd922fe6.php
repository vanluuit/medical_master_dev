
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">Push通知</h4>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">Push通知管理</h5>
                    <a href="<?php echo e(route('push.notification.create')); ?>" class="btn btn-info btn-sm float-right">お知らせPush</a>
                    <a href="<?php echo e(route('push.notification.create.tvpro')); ?>" class="btn btn-info btn-sm float-right mg-r15">学会チャンネルPush</a>
                    <!-- <a href="<?php echo e(route('push.user.list.review')); ?>" class="btn btn-info btn-sm float-right mg-r15">プレビュー送付先</a> -->

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
                          <th style="width:120px">本文</th>
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
                            <td><?php if($message->push==1): ?> 送信済み <?php elseif($message->push==0): ?> 一時保存 <?php else: ?>  reserved <?php endif; ?></td>
                            <td>
                              <?php if($message->push ==0 || $message->push ==2): ?>
                                <a  class="btn btn-cyan btn-sm" href="<?php echo e(route('push.notification.push',$message->id)); ?>">送信
                                  <!-- <br><span style="font-size: 10px; margin-top: -5px;display: contents;">(push)</span> -->
                                </a>
                                <a  class="btn btn-cyan btn-sm" href="<?php echo e(route('push.notification.edit',$message->id)); ?>">編集
                                  <!-- <br><span style=" font-size: 10px; margin-top: -5px;display: contents;">(edit)</span> -->
                                </a>
                              <?php else: ?>
                              <!-- <a class="btn btn-cyan btn-sm" href="<?php echo e(route('push.notification.push',$message->id)); ?>">再利用して送信</a> -->
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
<?php echo $__env->make('layouts.push', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>