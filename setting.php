<?php
include_once 'service/service.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo SITE?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
    <link rel="stylesheet" href="plugins/alertifyjs/css/themes/bootstrap.min.css">
    <link rel="stylesheet" href="plugins/alertifyjs/css/alertify.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="index.php"><b><img src="images/logo.png?v=2" style="width: 45%;"></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
       

        <form action="" method="post" onsubmit="return false;" id="frmsite">
            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Sitename" name="sitename">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-xs-8">

                </div>

                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                </div>
            </div>
        </form>

    </div>
    <div class="login-footer text-center">
        <p>© 2020 Sun Systems Corporation Limited. All rights reserved.</p>
    </div>

    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/alertifyjs/alertify.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

        $('#frmlogin').submit(function( event ) {
            event.preventDefault();
            var form = $(this);

            $.ajax({
                url:'api.inc.php?mode=F07F1CBA4819C3888747D4F2CB81E38A',
                type:'POST',
                dataType:'json',
                cache:false,
                data:form.serialize(),
                success:function(data){
                    switch(data.success){
                        case 'COMPLETE' :

                            setTimeout(function(){
                                window.location.replace('<?php echo URL;?>/page-'+data.menu);
                            }, 2000);

                            break;
                        default :
                            alertify.alert(data.msg);
                            break;
                    }
                }
            });

        });
    });
</script>
</body>
</html>
