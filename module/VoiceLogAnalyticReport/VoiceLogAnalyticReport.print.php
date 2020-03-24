<?php
require '../../vendor/autoload.php';

$img = $_GET['img'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


//$sheeti = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
//$sheeti->setName('name');
//$sheeti->setDescription('description');
//$sheeti->setPath($img);
//$sheeti->setHeight(90);
//$sheeti->setCoordinates("G14");
//$sheeti->setOffsetX(20);
//$sheeti->setOffsetY(5);
//$sheeti->setWorksheet($sheet);

$writer = new Xlsx($spreadsheet);
$writer->save('VoiceLogAnalyticReport.xlsx');
?>