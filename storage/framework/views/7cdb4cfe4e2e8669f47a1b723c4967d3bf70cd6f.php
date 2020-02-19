<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="robots" content="noindex,follow">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(URL::asset('/assets/images/favicon.png')); ?>">
    <title>Medical</title>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="<?php echo e(URL::asset('/assets/libs/flot/css/float-chart.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/extra-libs/multicheck/multicheck.css">
    <link href="<?php echo e(URL::asset('/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')); ?>" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/libs/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/libs/jquery-minicolors/jquery.minicolors.css">
    <link rel="stylesheet" type="text/css" href="/assets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/libs/quill/dist/quill.snow.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
    <link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('/css/bootstrap-datetimepicker.min.css')); ?>">
    <link href="<?php echo e(URL::asset('/css/jquery-confirm.min.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(URL::asset('/dist/css/style.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('/css/styles.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')); ?>"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?php echo e(URL::asset('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')); ?>"></script>
<![endif]-->
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="/">
                        <!-- Logo icon -->
                        <b class="logo-icon p-l-10">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo e(URL::asset('/assets/images/logo-icon.png')); ?>" alt="homepage" class="light-logo" />
                           
                        </b>
                        <!--End Logo icon -->
                         <!-- Logo text -->
                        <span class="logo-text">
                             <!-- dark Logo text -->
                             <h2 style="padding-top:10px;">Medical Dev</h2>
                            
                        </span>
                        <!-- Logo icon -->
                        <!-- <b class="logo-icon"> -->
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <!-- <img src="<?php echo e(URL::asset('/assets/images/logo-text.png')); ?>" alt="homepage" class="light-logo" /> -->
                            
                        <!-- </b> -->
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>

                </div>

                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->

                    <ul class="navbar-nav float-left mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link waves-effect waves-light" style="font-size: 14px;"><?php echo e(session('user')['category']); ?></a>
                        </li> 
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->
                        
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?php echo e(URL::asset('/assets/images/users/1.jpg')); ?>" alt="user" class="rounded-circle" width="31"></a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated">
                                <a class="dropdown-item" href="<?php echo e(route('push.logout')); ?>"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="p-t-30">
                        <li class="sidebar-item"> <a class="sidebar-link-push waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><span class="hide-menu">会員管理 </span></a>
                            <ul aria-expanded="false" class="">
                                <li class="sidebar-item"><a href="<?php echo e(route('push.user.list_confirm')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> 登録希望者の承認 </span></a></li>
                                <li class="sidebar-item"><a href="<?php echo e(route('push.user.list_refuse')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> 却下一覧 </span></a></li>
                                <li class="sidebar-item"><a href="<?php echo e(route('push.user.import')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> 会員番号リスト </span></a></li>
                                <li class="sidebar-item"><a href="<?php echo e(route('push.users.create')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> 新規アカウント作成 </span></a></li>
                            </ul>
                        </li>
                        <li class="sidebar-item"> <a class="sidebar-link-push waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><span class="hide-menu">Push通知</span></a>
                            <ul aria-expanded="false" class="">
                                <li class="sidebar-item"><a href="<?php echo e(route('push.notification.index')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> Push通知管理 </span></a></li>
                                <!-- <li class="sidebar-item"><a href="<?php echo e(route('push.listmemberpushs.index')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> 配信リスト管理 </span></a></li> -->

                            </ul>
                        </li>

                        <!-- <li class="sidebar-item"> <a class="sidebar-link-push waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><span class="hide-menu">TVPro</span></a>
                            <ul aria-expanded="false" class="">
                                <li class="sidebar-item"><a href="<?php echo e(route('push.videos.index')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> コンテンツ管理 </span></a></li>
                                <li class="sidebar-item"><a href="<?php echo e(route('push.analytic.content')); ?>" class="sidebar-link-push">&nbsp;&nbsp;&nbsp;&nbsp;<span class="hide-menu"> 視聴状況 </span></a></li>

                            </ul>
                        </li> -->
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            
            <?php echo $__env->yieldContent('content'); ?>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center">
                Medical</a>.
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo e(URL::asset('/assets/libs/jquery/dist/jquery.min.js')); ?>"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js'></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo e(URL::asset('/assets/libs/popper.js/dist/umd/popper.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/extra-libs/sparkline/sparkline.js')); ?>"></script>
    <!--Wave Effects -->
    <script src="<?php echo e(URL::asset('/dist/js/waves.js')); ?>"></script>
    <!--Menu sidebar -->
    <script src="<?php echo e(URL::asset('/dist/js/sidebarmenu.js')); ?>"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo e(URL::asset('/dist/js/custom.min.js')); ?>"></script>
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

    <!--This page JavaScript -->
    <script src="<?php echo e(URL::asset('/assets/extra-libs/multicheck/datatable-checkbox-init.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('/assets/extra-libs/multicheck/datatable-checkbox-init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/extra-libs/multicheck/jquery.multicheck.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/tinymce/tinymce.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('/assets/libs/inputmask/dist/min/jquery.inputmask.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/dist/js/pages/mask/mask.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/select2/dist/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/select2/dist/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/jquery-asColor/dist/jquery-asColor.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/jquery-asGradient/dist/jquery-asGradient.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/jquery-asColorPicker/dist/jquery-asColorPicker.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/jquery-minicolors/jquery.minicolors.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/jquery-validation/dist/jquery.validate.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/assets/libs/quill/dist/quill.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    <script src="<?php echo e(URL::asset('/js/bootstrap-datetimepicker.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/js/jquery-confirm.min.js')); ?>"></script>
    
    <script>
        //***********************************//
        // For select 2
        //***********************************//
        $(".select2").select2();
        $(".select2-cate-mutil").select2({
            placeholder: "アソシエーション",
        });


        /*colorpicker*/
        $('.demo').each(function() {
        //
        // Dear reader, it's actually very easy to initialize MiniColors. For example:
        //
        //  $(selector).minicolors();
        //
        // The way I've done it below is just for the demo, so don't get confused
        // by it. Also, data- attributes aren't supported at this time...they're
        // only used for this demo.
        //
        $(this).minicolors({
                control: $(this).attr('data-control') || 'hue',
                position: $(this).attr('data-position') || 'bottom left',

                change: function(value, opacity) {
                    if (!value) return;
                    if (opacity) value += ', ' + opacity;
                    if (typeof console === 'object') {
                        console.log(value);
                    }
                },
                theme: 'bootstrap'
            });

        });
        /*datwpicker*/
        jQuery('.mydatepicker').datepicker();
        // $('.datetimepicker').datetimepicker();
        $('.datetimepicker').datetimepicker({
           // dateFormat: 'dd-mm-yy',
           format:'YYYY-MM-DD HH:mm:ss',
            // minDate: getFormattedDate(new Date())
        });

        function getFormattedDate(date) {
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear().toString().slice(2);
            return day + '-' + month + '-' + year;
        }
        
        jQuery('#datepicker-autoclose').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });
        jQuery('.datepicker-autoclose').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        var quill = new Quill('#editor', {
            theme: 'snow'
        });
        $(document).on('change','.search_change', function(){
            $(this).closest('form').submit();
        });
        function readURL(input, show, text) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
              $('#'+show).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            $('#'+text).text(input.files[0].name);
          }
        }
        function readURLlb(input, text) {
          if (input.files && input.files[0]) {
            $('#'+text).text(input.files[0].name);
          }
        }

    </script>
    <script>
        // $(document).ready(function(){
        //     var t = $('#report_total').text();
        //     if(t=="") t = 0; 
        //     var total = parseInt(t);
        //     var socket = io.connect('http://visoftech.com:9001');
        //     socket.on('ReportComment', function(data){
        //         total++;
        //         $('#report_total').text(total);
        //         console.log(data);
        //     });
        //     socket.on('notify', function(data){
        //         console.log(data);
        //     });
        // });
    </script>
    <?php echo $__env->yieldContent('script'); ?>

</body>

</html>