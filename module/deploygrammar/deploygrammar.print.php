<?php
require_once "../../service/service.php";
header('Content-Disposition: attachment; filename="' . $_GET['mod'] . '-' . time() . '.xls"');
header("Content-Type: application/vnd.ms-excel");


$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
$str = file_get_contents("php://input");
parse_str($str, $data);

PrintR($data);
exit;

$params = array(
    'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
    'start_date' => $data['start_date'].' 00:00:00',
    'end_date' => $data['end_date'].' 23:59:59'
);



$url = URL_API . '/geniespeech/summaryqc';
$response = curlposttoken($url, $params, $token);

?>

<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=utf-8"/>
</head>
<body>
<table width="100%" cellpadding="5" cellspacing="0" border="1">
    <thead>
    <tr>
        <th>intent</th>
        <th>count</th>
        <th>pass</th>
        <th>fail</th>
        <th>garbage</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ((array)$response as $i => $item) {
        ?>
    <tr>
        <td><?php echo $item['intent']?></td>
        <td><?php echo $item['count']?></td>
        <td><?php echo $item['pass']?></td>
        <td><?php echo $item['fail']?></td>
        <td><?php echo $item['garbage']?></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>
</body>
</html>
