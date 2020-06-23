<?php
require_once "../../service/service.php";
require_once '../../vendor/autoload.php';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="Voice Log Analytics Report-' . date('mdYHms') . '.xlsx"');
header('Cache-Control: max-age=0');

$datalist = array();
$datalistnew = array();
$columns = array();
$column = array();
$columnnew = array();

$start = $_POST['start_date'];
$end = $_POST['end_date'];
$user = $_SESSION[OFFICE]['DATA']['user_name'];

$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
$params = array(
    'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
    'report_name' => 'QC',
    'start_date' => $start,
    'end_date' => $end,
    'user_login' => $user
);

//    PrintR($params);
$url = URL_API . '/geniespeech/report';
$response = curlposttoken($url, $params, $token);

if ($response['result'][0]['code'] == 200) {
    $responsenew['column_name'][0]['column_data'] = 'Intent';
    $responsenew['column_name'][1]['column_data'] = 'Pass%';
    $responsenew['column_name'][2]['column_data'] = 'Pass';
    $responsenew['column_name'][3]['column_data'] = 'Fail';
    $responsenew['column_name'][4]['column_data'] = 'Garbage';
    $responsenew['column_name'][5]['column_data'] = 'Other';
    $responsenew['column_name'][6]['column_data'] = 'Valid';
    $responsenew['column_name'][7]['column_data'] = 'Totalcall';

    $columnslist = $responsenew['column_name'];
    $datas = $response['recs'];
    $data_footer = $response['grand_total'];
    $name = $response['report_name:'];


    $m = 0;
    $z = 0;
    $newfooter = array();
    foreach ((array)$data_footer as $i => $item) {
        foreach ((array)$item as $v => $item2) {
            $newfooter[$z] = $item2;
            ++$z;
        }

    }

    foreach ((array)$columnslist as $i => $item) {
        $column[$i] = $item['column_data'];

        $columns[$m]['data'] = $item['column_data'];
        $columns[$m]['type'] = '';


        ++$m;
    }

    $z = 0;
    foreach ((array)$datas as $i => $item) {
        $z = 0;
        foreach ((array)$columns as $v => $value) {
            $datalist[$i][$z][$value['data']] = $item[$value['data']];
            ++$z;
        }

    }

}

$columnslist = array();
$datas = array();


$params = array(
    'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
    'start_date' => $start,
    'end_date' => $end,
    'page_id' => 1,
    'page_size' => 10000,
    "grammar" => "",
    "qc_status" => "",
    "intent" => "",
    "confiden" => "",
    "text_search" => "",
    "random_num" => 0,
    "export_qc" => 1,
    "flag_edit" => ""

);

$status[0] = '';
$status['P'] = 'Pass';
$status['F'] = 'Fail';
$status['G'] = 'Garbage';
$status['O'] = 'Other';

//PrintR($params);

$url = URL_API . '/geniespeech/voicelog';
$response = curlposttoken($url, $params, $token);
if ($response['result'][0]['code'] == 200) {
    $columnslist = $response['columnsname'];
    $datas = $response['recs']['box4'];

    foreach ((array)$columnslist as $i => $item) {
        $columns[$i]['data'] = $item['column_data'];
        $columns[$i]['title'] = $item['column_name'];
        $columns[$i]['type'] = $item['column_type'];

        $columnnew[$i] = $item['column_name'];

    }

    $z = 0;
    foreach ((array)$datas as $i => $item) {
        $z = 0;
        foreach ((array)$columns as $v => $value) {
            if ($value['data'] == 'voice_name') {
//                    $datalist[$i][$value['data']] = '<i class="glyphicon glyphicon-volume-up"></i>';
                $datalistnew[$i][$z] = $item[$value['data']];
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('.'"'.$item[$value['data']].'"'.')"><i class="glyphicon glyphicon-volume-up"></i></a>';
            } elseif ($value['data'] == 'chnn') {
                $datalistnew[$i][$z] = $item['log_file'];
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN(' . "'" . $item['chnn'] . "'," . $data['page_id'] . ',' . $data['page_size'] . ",'" . $data['start_date'] . "','" . $data['end_date'] . "'" . ')"><i class="glyphicon glyphicon-volume-up"></i></a>';
            } elseif ($value['data'] == 'qc_status') {
                $datalistnew[$i][$z] = $status[$item[$value['data']]];
            } elseif ($value['data'] == 'input_qc' || $value['data'] == 'remark' || $value['data'] == 'Expected' || $value['data'] == 'new_sentence' || $value['data'] == 'expec_intent') {
                switch ($value['data']) {
                    case 'input_qc':
                        $v = 'new_sentence';
                        break;
                    case 'Expected':
                        $v = 'expec_intent';
                        break;
                    default:
                        $v = $value['data'];
                        break;
                }


                if ($item[$v]) {
                    $datalistnew[$i][$z] = $item[$v];


                } else {
                    $datalistnew[$i][$z] = '';
                }

            } else {
                $datalistnew[$i][$z] = $item[$value['data']];
            }
            ++$z;
        }
    }
}


$pieimg = $_POST['pie']; //get the image string from ajax post
$pieimg = substr(explode(";", $pieimg)[1], 7); //this extract the exact image
$targetpie = time() . '_pie.png';
$image = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $targetpie, base64_decode($pieimg));
$pathpie = $_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $targetpie;

$barimg = $_POST['bar']; //get the image string from ajax post
$barimg = substr(explode(";", $barimg)[1], 7); //this extract the exact image
$targetbar = time() . '_bar.png';
$image = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $targetbar, base64_decode($barimg));
$pathbar = $_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $targetbar;

$img = $_POST['img']; //get the image string from ajax post
$img = substr(explode(";", $img)[1], 7); //this extract the exact image
$target = time() . '_img.png';
$image = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $target, base64_decode($img));
$path = $_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $target;


$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('template.xlsx');
//$spreadsheet = new Spreadsheet();
$main = $spreadsheet->setActiveSheetIndex(0);
//$main->getColumnDimension('A')->setWidth(12);
$main->fromArray($datalist, NULL, 'A17');
//$main->setTitle("Summary Qc Report");


$drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath($pathpie); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(0);
//$drawing->getShadow()->setVisible(true);
//$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($main);

$drawing1 = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing1->setName('Paid');
$drawing1->setDescription('Paid');
$drawing1->setPath($pathbar); // put your path and image here
$drawing1->setCoordinates('D1');
$drawing1->setOffsetX(110);
$drawing1->getShadow()->setVisible(true);
$drawing1->getShadow()->setDirection(45);
$drawing1->setWorksheet($main);

$drawing2 = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing2->setName('Paid');
$drawing2->setDescription('Paid');
$drawing2->setPath($path); // put your path and image here
$drawing2->setCoordinates('G1');
$drawing2->setOffsetX(110);
$drawing2->getShadow()->setVisible(true);
$drawing2->getShadow()->setDirection(45);
$drawing2->setWorksheet($main);

//$main->fromArray($column, NULL, 'A23');



//$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'My Data');
//$spreadsheet->addSheet($myWorkSheet, 0);
//$sheet = $spreadsheet->createSheet();
$sheet = $spreadsheet->setActiveSheetIndex(1);
//$sheet->setTitle("Data QC Report");
//$spreadsheet->setTitle("Data QC Report");

//$sheet->fromArray($columnnew, NULL, 'A1');
$sheet->fromArray($datalistnew, NULL, 'A2');
//$sheet->getCell('A2')->setValue($datalistnew[0][0]);
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
//$writer->save('write.xls');
//$writer = new Xlsx($spreadsheet);
//$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
//$writer->setPreCalculateFormulas(false);
$writer->save('php://output');
