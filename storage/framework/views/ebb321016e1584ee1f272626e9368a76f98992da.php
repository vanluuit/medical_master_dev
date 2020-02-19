
<?php $__env->startSection('content'); ?>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
            <h4 class="page-title">News</h4>
            <?php if(count($dtcs)): ?>
              <?php $__currentLoopData = $dtcs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($v['id']); ?> = <?php echo e($v['id1']); ?> & 
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <!-- Column -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title m-b-0 float-left">List news</h5>
                    <a href="<?php echo e(route('news.create')); ?>" class="btn btn-info btn-sm float-right">Add New</a>
                    <a href="<?php echo e(route('news.getrss')); ?>" class="btn btn-info btn-sm float-right mg-r15">Get RSS</a>
                    <a href="<?php echo e(route('category_news.create')); ?>" class="btn btn-info btn-sm float-right mg-r15">Add Category</a>
                    <a href="<?php echo e(route('rssnews.index')); ?>" class="btn btn-info btn-sm float-right mg-r15">List url rss</a>
                    <a href="<?php echo e(route('new.crontime')); ?>" class="btn btn-info btn-sm float-right mg-r15">CRONJOB</a>
                    <a href="<?php echo e(route('new.list_cron_delete')); ?>" class="btn btn-info btn-sm float-right mg-r15">Delete</a>

                </div>
                <hr>
                <div class="card-body">
                    <form action="" method="GET">
                      <div class="row">
                        <div class="col-md-6">
                          <?php echo Form::select('category_id', $categories, @request()->category_id, ['placeholder' => '小カテゴリー', 'class'=>'form-control select2 search_change']); ?>

                        </div>
                      </div>
                    </form>
                </div>
                <div class="float-center">
                    <?php echo e($News->appends(request()->query())->links()); ?>

                </div>
                <?php echo Form::open(array('route' => 'news.deleteall', 'id'=>'validate', 'enctype'=>'multipart/form-data')); ?>

                <div class="scroll">
                <table class="table">
                      <thead>
                        <tr>
                          <th>
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" class="custom-control-input deletenewsall" id="deletenewsall" data-checked="checkdel">
                                <label class="custom-control-label" for="deletenewsall"></label>
                            </div>
                          </th>
                          <th>id</th>
                          <th style="max-width: 300px">title</th>
                          <th>association</th>
                          <th>type</th>
                          <th style="width:240px">url</th>
                          <th>thumbnail</th>
                          <th>category</th>
                          <th>publish date</th>
                          <th>comment</th>
                          <th>show</th>
                          <th>top</th>
                          <!-- <th>top</th> -->
                          <th style="width:240px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if($News): ?>
                        <?php $__currentLoopData = $News; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $new): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <tr>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input checkdel" value="<?php echo e($new->id); ?>" name="delete[]" id="deletenews_<?php echo e($new->id); ?>">
                                  <label class="custom-control-label" for="deletenews_<?php echo e($new->id); ?>"></label>
                              </div>
                            </td>
                            <td><?php echo e($new->id); ?></td>
                            <td><?php if($new->url !=""): ?> <a target="_blade" href="<?php echo e($new->url); ?>"><?php echo e($new->title); ?></a> <?php else: ?> <?php echo e($new->title); ?> <?php endif; ?></td>
                            <td>
                              <?php if($new->category_id==0): ?>
                                全ての学会
                              <?php else: ?>
                                <?php echo e(@$new->association->category); ?>

                              <?php endif; ?>
                              
                            </td>
                            <td><?php if($new->type==0): ?> original <?php else: ?> rss <?php endif; ?></td>
                            <td style="word-break: break-all;"><p style="min-width: 240px;"><?php echo e($new->url); ?></p></td>
                            <?php if(!strpos('ccc'.$new->media, 'http')): ?>
                            <td><img class="thumbnail" src="<?php echo e($new->media); ?>" alt=""></td>
                            <?php else: ?>
                            <td><img class="thumbnail" src="<?php echo e($new->media); ?>" alt=""></td>
                            <?php endif; ?>
                            <td><?php echo e(@$new->category->category_name); ?></td>
                            <td><?php echo e($new->date); ?></td>
                            <td><a href="<?php echo e(route('commentnews.index')); ?>?new_id=<?php echo e($new->id); ?>"><?php echo e(@$new->comments_count); ?></a></td>
                            <td>
                              <div class="custom-control custom-checkbox mr-sm-2">
                                  <input type="checkbox" class="custom-control-input setshow" value="<?php echo e($new->id); ?>" name="publish" id="shownews_<?php echo e($new->id); ?>" <?php if( $new->publish == 1 ): ?> checked <?php endif; ?> >
                                  <label class="custom-control-label" for="shownews_<?php echo e($new->id); ?>"></label>
                              </div>
                            </td>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input settop" id="top_<?php echo e($new->id); ?>" name="top" value="<?php echo e($new->id); ?>" <?php if( $new->top == 1 ): ?> checked <?php endif; ?> >
                                    <label class="custom-control-label" for="top_<?php echo e($new->id); ?>"></label>
                                </div>
                            </td>

                            <td>
                              <a class="btn btn-cyan btn-sm" href="<?php echo e(route('news.edit',$new->id)); ?>">Edit</a>
                              <a class="btn btn-success btn-sm" href="<?php echo e(route('news.show',$new->id)); ?>">Show</a>
                              <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');" href="<?php echo e(route('news.delete',$new->id)); ?>">Delete</a>

                            </td>
                          </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                      </tbody>
                </table>
              </div>
                <div class="border-top">
                <div class="card-body">
                  <input type="hidden" name="category_id" value="<?php echo e(@request()->category_id); ?>">
                  <button type="submit" class="btn btn-primary">Delete</button>
                </div>
              </div>
                <?php echo Form::close(); ?>

                <div class="float-center">
                    <?php echo e($News->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        
    </div>
</div>
<?php $__env->stopSection(); ?>  

<?php $__env->startSection('script'); ?>
  <script>
    $(document).on('click','.settop', function(){
      var obj = $(this);
      
      // console.log(1);
      if( obj.is( ":checked" ) == true){
        $('.settop').prop('checked', false);
        obj.prop('checked', true);
        sh = 1;
      }else{
        $('.settop').prop('checked', false);
        sh = 0;
      }
      $.ajax({url: "<?php echo e(route('news.ajaxtop')); ?>?id="+$(this).val()+'&sh='+sh, success: function(result){
        if(result == 0){
          alert('The news has been deleted top');
        }else{
          alert('news has been added top');
        }
      }});
    });

     $(document).on('change','.setshow', function(){
      var obj = $(this);
      if($(this).prop("checked") == true) {
        var publish = 1;
      }else{
        var publish = 0;
      }
      console.log(publish);
      $.ajax({url: "<?php echo e(route('news.ajaxshow')); ?>?id="+$(this).val()+'&publish='+publish, success: function(result){
        if(result == 0){
          alert('The news has been deleted show');
        }else{
          alert('news has been added show');
        }
      }});
    });
  </script>
<?php $__env->stopSection(); ?>  
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>