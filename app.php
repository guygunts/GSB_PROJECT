<?php
include_once 'service/service.php';
include_once 'app.init.php';
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo SITE?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-3/css/tempusdominus-bootstrap-3.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap-theme/select2-bootstrap.css">

<!--    <link rel="stylesheet" href="bower_components/loading-bar-master/dist/loading-bar.css">-->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/b-1.6.1/b-colvis-1.6.1/r-2.2.3/sc-2.0.1/sl-1.3.1/datatables.min.css">





    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

<!--    <link rel="stylesheet" href="plugins/iCheck/all.css">-->

    <link rel="stylesheet" href="plugins/pace/pace.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="dist/css/skins/skin-purple.css?v=1">
    <link rel="stylesheet" href="plugins/alertifyjs/css/themes/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/alertifyjs/css/alertify.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="app.style.css?v=<?php echo microtime()?>">
    <?php if (is_file("module/$mod/$mod.style.php")) include "module/$mod/$mod.style.php"; ?>
    <link rel="stylesheet" href="<?php echo URL . "/module/$mod/$mod.style.css?t=" . microtime(); ?>">
    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .skin-purple .main-header .navbar {
            background-color: <?php echo COLOR?>;
        }
        .skin-purple .main-header .navbar .sidebar-toggle:hover {
            background-color: <?php echo COLOR?>;
        }
        @media (max-width: 767px) {
            .skin-purple .main-header .navbar .dropdown-menu li a:hover {
                background: <?php echo COLOR?>;
            }
        }
        .skin-purple .main-header .logo {
            background-color: <?php echo COLOR?>;
            color: #ffffff;
            border-bottom: 0 solid transparent;
        }
        .skin-purple .main-header .logo:hover {
            background-color: <?php echo COLOR?>;
        }
        .skin-purple .main-header li.user-header {
            background-color: #ff88f3;
        }
        .skin-purple .sidebar-menu > li.active > a {
            border-left-color: <?php echo COLOR?>;
        }
    </style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-purple sidebar-mini">
<div class="loading-container loading-inactive">
    <div class="loading-progress">
        <img class="img" src="images/loading.svg">
    </div>
</div>

<div class="wrapper">


   <?php
   include_once 'inc/header.php';
   include_once 'inc/menu.php';
   ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header" style="display: none;">
            <h1>
                Page Header
                <small>Optional description</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                <li class="active">Here</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <?php

            if (is_file("module/$mod/$mod.content.php")) {
                include_once "module/$mod/$mod.content.php";
            } else {
                include_once 'app.content.php';
            }
            ?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include_once 'inc/footer.php'?>


</div>

<?php
include_once 'app.modal.php';
if (is_file("module/$mod/$mod.modal.php")) {
    include_once "module/$mod/$mod.modal.php";
}

?>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="dist/js/serialize.js?v=1"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<script src="bower_components/datatables.net/js/jquery.dataTables.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/v/bs/b-1.6.1/b-colvis-1.6.1/r-2.2.3/sc-2.0.1/sl-1.3.1/datatables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!--<script src="bower_components/loading-bar-master/dist/loading-bar.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
<!-- PACE -->
<script src="bower_components/PACE/pace.min.js"></script>

<script src="plugins/select2/js/select2.full.min.js"></script>

<script src="bower_components/moment/min/moment.min.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/alertifyjs/alertify.js?t=<?php echo microtime(); ?>"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script src="main/main.func.js?t=<?php echo microtime(); ?>"></script>
<script src="app.script.js?t=<?php echo microtime(); ?>"></script>
<?php if (is_file("module/$mod/$mod.script.php")) include "module/$mod/$mod.script.php"; ?>

<script>
    $(document).ready(function() {


        me.mod = '<?php echo $mod; ?>';
        me.menu = '<?php echo $mod; ?>';
        me.site = '<?php echo URL; ?>';
        me.api = '<?php echo URL_API; ?>';
        alertify.defaults.glossary.title = '<?php echo SITE; ?>';
        me.Init();
        Pace.restart();


    });
</script>
<script src="<?php echo URL . "/module/$mod/$mod.script.js?t=" . microtime(); ?>" charset="utf-8"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>