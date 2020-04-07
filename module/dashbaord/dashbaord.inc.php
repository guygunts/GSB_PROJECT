<?php
header("Content-type: application/json; charset=utf-8");
require_once "../../service/service.php";

$json = '{"success":"FAIL","msg":"พบข้อผิดพลาดบางประการ"}';
$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';

function View()
{
    global $json;
    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $label = array();
    $today = Today();


    $str = file_get_contents("php://input");
    parse_str($str, $data);



    if (!$data['start'] && !$data['end']) {
        $datas['start'] = $today . ' 00:00:00';
        $datas['end'] = $today . ' 23:59:59';
    } else {
        if ($data['start'] == '') {
            $datas['start'] = '';
        } else {
            $datas['start'] = $data['start'] . ' 00:00:00';
        }

        if ($data['end'] == '') {
            $datas['end'] = $today . ' 23:59:59';
        } else {
            $datas['end'] = $data['end'] . ' 23:59:59';
        }
    }


    $params = array(
        'start_date' => ($datas['start'] ? $datas['start'] : ''),
        'end_date' => ($datas['end'] ? $datas['end'] : '')
    );

//    PrintR($params);

    $url = URL_API . '/geniespeech/dashbaord';
    $response = curlpost($url, $params);

//    PrintR($response);

    $result['name'] = array();
    $result['box1'] = array();
    $result['box2'] = array();
    $result['box3'] = array();
    $result['box4'] = array();
    $result['box5'] = array();


    if ($response['result'][0]['code'] == 200) {

        $result['name'] = (array)$response['name'];

        $result['box1'] = (array)$response['box1'];

//        $result['box2'] = (array)$response['box2'];

        $box2 = array();

        $box3 = array();

        foreach ((array)$response['box2'] as $z => $item) {
            $date = explode(' ', $item['datetime']);
            $item['datetime'] = $date[0];
            $result['box2'][$z] = $item;
        }


        foreach ((array)$response['box3'] as $z => $item) {
            if (strpos($item['tag_name'], '#') === false) {
                $tag = $item['tag_name'];
            } else {
                $tag = strstr($item['tag_name'], '#', true);
            }
//                $label[$tag] = "'".$tag."'";
            $label[$tag] = $tag;


//                if($item['datetime']){
            $date = explode(' ', $item['date_time']);
            $date = $date[0];
//                    $box3[$item['datetime']][$item['tagname']] = $date[0];
////                    $box3[$tag][$item['datetime']]['x'] = date("Y-m-d", strtotime($date[0]));
//                }

//                $box3[$tag][$item['datetime']]['y'] = $item['totalcall'];

            $box3[$date][$tag] = 0;
        }


//        PrintR($box3);


        foreach ($box3 as $i => $items) {
            foreach ($label as $v => $value) {
                $box3[$i][$value] = 0;
            }

        }


        foreach ((array)$response['box3'] as $z => $item) {
            if (strpos($item['tag_name'], '#') === false) {
                $tag = $item['tag_name'];
            } else {
                $tag = strstr($item['tag_name'], '#', true);
            }
            $date = explode(' ', $item['date_time']);
            $date = $date[0];
            $box3[$date]['date_time'] = $date;
            $box3[$date][$tag] = $item['total_call'];
        }


//        foreach ($box3 as $i => $item) {
//            foreach($box3[$i] as $m => $items){
//                $box3s[$i]['type'] = 'line';
//                $box3s[$i]['showInLegend'] = true;
//                $box3s[$i]['name'] = $i;
//                $box3s[$i]['xValueFormatString'] = "YYYY-MM-DD";
//                $box3s[$i]['lineDashType'] = "dash";
//                $box3s[$i]['dataPoints'][] = $items;
//
//            }
//
//        }

        foreach ($box3 as $i => $item) {
            foreach ($label as $v => $value) {
                if ($item[$value] == 0) {
                    unset($item[$value]);
                }

            }
            $box3s[] = $item;
        }


        foreach ($box3s as $i => $item) {
            $box = array();
            $box['TOP1'] = 0;
            $box['TOP1NAME'] = 'NODATA';
            $box['TOP2'] = 0;
            $box['TOP2NAME'] = 'NODATA';
            $box['TOP3'] = 0;
            $box['TOP3NAME'] = 'NODATA';
            $box['TOP4'] = 0;
            $box['TOP4NAME'] = 'NODATA';
            $box['TOP5'] = 0;
            $box['TOP5NAME'] = 'NODATA';
            $z = 1;
            foreach ($box3s[$i] as $m => $items) {
                if ($m == 'date_time') {
                    $box[$m] = $items;

                } else {
                    $box['TOP' . $z] = $items;
                    $box['TOP' . $z . 'NAME'] = $m;
                    ++$z;
                }


            }
            $box3ss[$i] = $box;
        }


        $m = 1;
        foreach ($label as $i => $item) {
            if ($m > 5) break;
            $labels[] = 'TOP' . $m;
            ++$m;
        }

//        PrintR($box3ss);


//        $labels = "'" . implode("', '", $label) . "'";
//        $labels = "'". implode("', '", $label);


        $result['box3']['label'] = $labels;
        $result['box3']['data'] = $box3ss;

        $box4 = array();
        foreach ((array)$response['box4'] as $i => $item) {
            $box4[$item['servicename']] = $item['totalcall'];
        }

        $box4s = array();
        foreach ($box4 as $i => $item) {
            $box4s[] = [$i, $item];
        }

        $result['box4'] = $box4s;

        $box5name = '';
        $box5title = '';
        $box5 = array();
        $n = 0;
        $m = 0;
        $databox5 = false;


        $box5name = 'Total Call';
//                $box5title = 'Total Call : '.$item['recog'].'<br> Nonrecog : '.$item['nonrecog'];
        $box5title = $result['box1'][0]['totalcall'];
        if ($result['box1'][0]['totalcall'] > 0) {
            $databox5 = true;
            $box5[0]['name'] = 'Recognize';
            $box5[0]['nodeName'] = 'Recognize';
            $box5[0]['label'] = 'Recognize';
            $box5[0]['code'] = $result['box1'][0]['recog'];
            $box5[0]['link']['name'] = 'Recognize';
            $box5[0]['link']['nodeName'] = 'Recognize';
            $box5[0]['link']['direction'] = 'SYNC';

            $box5[1]['name'] = 'Non-Recognize';
            $box5[1]['nodeName'] = 'Non-Recognize';
            $box5[1]['label'] = 'Non-Recognize';
            $box5[1]['code'] = $result['box1'][0]['nonrecog'];
            $box5[1]['link']['name'] = 'Non-Recognize';
            $box5[1]['link']['nodeName'] = 'Non-Recognize';
            $box5[1]['link']['direction'] = 'SYNC';

            if ($response['box4']) {
                $m = 0;
                foreach ($response['box4'] as $i => $item) {
                    $box5[0]['children'][$i]['nodeName'] = $item['servicename'];
                    $box5[0]['children'][$i]['type'] = 'type1';
                    $box5[0]['children'][$i]['name'] = $item['servicename'];
                    $box5[0]['children'][$i]['label'] = $item['servicename'];
                    $box5[0]['children'][$i]['code'] = $item['totalcall'];
                    $box5[0]['children'][$i]['link']['name'] = 'Link '.$item['servicename'];
                    $box5[0]['children'][$i]['link']['nodeName'] = $item['servicename'];
                    $box5[0]['children'][$i]['link']['direction'] = 'SYNC';
                    ++$m;
                }
            }
        }


        $result['box5s'] = $databox5;
        $result['box5']['tree']['nodeName'] = $box5name;
        $result['box5']['tree']['type'] = 'type2';
        $result['box5']['tree']['name'] = $box5name;
        $result['box5']['tree']['label'] = $box5name;
        $result['box5']['tree']['code'] = $box5title;
        $result['box5']['tree']['link']['name'] = 'Link '.$box5name;
        $result['box5']['tree']['link']['nodeName'] = $box5name;
        $result['box5']['tree']['link']['direction'] = 'ASYN';
        $result['box5']['tree']['children'] = $box5;

        $result['success'] = 'COMPLETE';


    } else {

        $result['success'] = 'FAIL';

    }

    $result['msg'] = $response['result'][0]['msg'];


    $json = json_encode($result);

}


switch ($_REQUEST["mode"]) {
    case "View" :
        View();
        break;
    case "Add" :
        Add();
        break;
    case "Edit" :
        Edit();
        break;
    case "Del" :
        Del();
        break;

    default :
}

echo $json;
exit;