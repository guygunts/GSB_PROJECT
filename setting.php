<?php
include_once 'service/service.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo SITE ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
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
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page container-fluid">
<div class="col-md-6">


    <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">


            <form action="" method="post" onsubmit="return false;" id="frmsite">
                <div class="form-group has-feedback">
                    <label>SATE NAME</label>
                    <input type="text" class="form-control" placeholder="Sitename" name="name"
                           value="<?php echo SITE ?>">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">

            <form action="" method="post" onsubmit="return false;" id="frmurl">
                <div class="form-group has-feedback">
                    <label>BASE URL</label>
                    <input type="text" class="form-control" placeholder="Site url" name="name"
                           value="<?php echo URL ?>">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">

            <form action="" method="post" onsubmit="return false;" id="frmapi">
                <div class="form-group has-feedback">
                    <label>URL API</label>
                    <input type="text" class="form-control" placeholder="Site Api" name="name"
                           value="<?php echo URL_API ?>">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
</div>
<div class="col-md-6">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">

            <form action="" method="post" onsubmit="return false;" id="frmcolor">
                <div class="form-group has-feedback">
                    <label>THEME COLOR</label>
                    <input type="THEME COLOR" class="form-control" placeholder="THEME COLOR" name="name" id="colorname"
                           value="<?php echo COLOR ?>">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>

    <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">


            <form action="" enctype="multipart/form-data" method="post" onsubmit="return false;" id="frmlogo"
                  name="frmlogo">
                <div class="form-group has-feedback">
                    <label>Logo (Support .PNG Only)</label>
                    <input type="file" class="form-control" placeholder="logo.png" name="name">

                </div>
                <div class="row">
                    <div class="col-xs-8">
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- iCheck -->
<script src="plugins/alertifyjs/alertify.min.js"></script>
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        alertify.defaults.glossary.title = '<?php echo SITE; ?>';
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

        $('#colorname').colorpicker();

        $('#frmsite').submit(function (event) {
            event.preventDefault();
            var form = $(this);

            $.ajax({
                url: 'api.inc.php?mode=EEEA15DD1B17E8A3487CB8CAAD3EA9BB',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: form.serialize(),
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE' :

                            alertify.alert(data.msg);

                            break;
                        default :
                            alertify.alert(data.msg);
                            break;
                    }
                }
            });

        });

        $('#frmurl').submit(function (event) {
            event.preventDefault();
            var form = $(this);

            $.ajax({
                url: 'api.inc.php?mode=CE5CA4A7D2A1A0A68250B4A6364EA43A',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: form.serialize(),
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE' :

                            alertify.alert(data.msg);

                            break;
                        default :
                            alertify.alert(data.msg);
                            break;
                    }
                }
            });

        });

        $('#frmurlapi').submit(function (event) {
            event.preventDefault();
            var form = $(this);

            $.ajax({
                url: 'api.inc.php?mode=EA8122F50A48F89DB8A4BD0C4127E17E',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: form.serialize(),
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE' :

                            alertify.alert(data.msg);

                            break;
                        default :
                            alertify.alert(data.msg);
                            break;
                    }
                }
            });

        });

        $('#frmcolor').submit(function (event) {
            event.preventDefault();
            var form = $(this);

            $.ajax({
                url: 'api.inc.php?mode=5C82FE0690591DA4A0CE9659FE91FAAD',
                type: 'POST',
                dataType: 'json',
                cache: false,
                data: form.serialize(),
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE' :

                            alertify.alert(data.msg);

                            break;
                        default :
                            alertify.alert(data.msg);
                            break;
                    }
                }
            });

        });

        $('#frmlogo').submit(function (event) {
            event.preventDefault();
            var form = $(this);
            var formData = new FormData(document.forms.namedItem("frmlogo"));
            $.ajax({
                url: 'api.inc.php?mode=D9F5719F10F152E8378FE8A3D2505194',
                type: 'POST',
                dataType: 'json',
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (data) {
                    switch (data.success) {
                        case 'COMPLETE' :

                            alertify.alert(data.msg);

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
