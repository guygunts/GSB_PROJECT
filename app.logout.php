<?php

require_once "service/service.php";

unset($_SESSION[OFFICE]);
$_SESSION[OFFICE]['LOGIN'] = 'OFF';
$_SESSION[OFFICE]['DATA'] = array();
$_SESSION[OFFICE]['ROLE'] = array();

?>
<html>
<head>
  <title>Logout!!</title>
</head>
<body>
  <script language="JavaScript">
    window.location.href= '<?php echo URL?>';
  </script>
</body>
</html>