<?php
require_once "../../service/service.php";
require_once '../../vendor/autoload.php';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="Voice Log Analytics Report-' . date('mdYHms') . '.xlsx"');
header('Cache-Control: max-age=0');

$datalist = array();
$columns = array();
$column = array();

$start = $_POST['start_date'];
$end = $_POST['end_date'];

$params = array(
    'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
    'start_date' => $start,
    'end_date' => $end,
    'page_id' => 1,
    'page_size' => 25,
    "grammar" => "",
    "qc_status" => "",
    "intent" => "",
    "confiden" => "",
    "text_search" => "",
    "random_num" => 0,
    "export_qc" => 1

);

$status[0] = '';
$status['P'] = 'Pass';
$status['F'] = 'Fail';
$status['G'] = 'Garbage';
$status['O'] = 'Other';

$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
$url = URL_API . '/geniespeech/voicelog';
$response = curlposttoken($url, $params, $token);
if (1) {
    $columnslist = $response['result']['header'];
    $datas = $response['result']['box4'];

    foreach ((array)$columnslist as $i => $item) {
        $columns[$i]['data'] = $item['column_field'];
        $columns[$i]['title'] = $item['column_name'];
        $columns[$i]['type'] = $item['column_type'];

        $column[$i] = $item['column_name'];

    }

    $z = 0;
    foreach ((array)$datas as $i => $item) {
        foreach ((array)$columns as $v => $value) {
            if ($value['data'] == 'voice_name') {
//                    $datalist[$i][$value['data']] = '<i class="glyphicon glyphicon-volume-up"></i>';
                $datalist[$i][$z] = $item[$value['data']];
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('.'"'.$item[$value['data']].'"'.')"><i class="glyphicon glyphicon-volume-up"></i></a>';
            } else if ($value['data'] == 'chnn') {
                $datalist[$i][$z] = $item['log_file'];
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN(' . "'" . $item['chnn'] . "'," . $data['page_id'] . ',' . $data['page_size'] . ",'" . $data['start_date'] . "','" . $data['end_date'] . "'" . ')"><i class="glyphicon glyphicon-volume-up"></i></a>';
            } else if ($value['data'] == 'qc_status') {
                $datalist[$i][$z] = $status[$item[$value['data']]];
            } else if ($value['data'] == 'input_qc' || $value['data'] == 'remark' || $value['data'] == 'Expected') {
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
                    $datalist[$i][$z] = $item[$v];


                } else {
                    $datalist[$i][$z] = '';
                }

            } else {
                $datalist[$i][$z] = $item[$value['data']];
            }
            ++$z;
        }
    }
}


$img = $_POST['img']; //get the image string from ajax post
$img = substr(explode(";", $img)[1], 7); //this extract the exact image
$target = time() . '_img.png';
$image = file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $target, base64_decode($img));
$path = $_SERVER['DOCUMENT_ROOT'] . '/imagefolder/' . $target;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->setTitle("Summary Qc Report");
$drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Paid');
$drawing->setDescription('Paid');
$drawing->setPath($path); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setOffsetX(110);
$drawing->getShadow()->setVisible(true);
$drawing->getShadow()->setDirection(45);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

//$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'My Data');
//$spreadsheet->addSheet($myWorkSheet, 0);
$sheet = $spreadsheet->createSheet();
$sheet->setTitle("Data QC Report");
//$spreadsheet->setTitle("Data QC Report");

$sheet->fromArray($column, NULL, 'A1');
$sheet->fromArray($datalist, NULL, 'A2');


$writer = new Xlsx($spreadsheet);
$writer->setPreCalculateFormulas(false);
$writer->save('php://output');
