<?php
header("Content-type: application/json; charset=utf-8");
require_once "../../service/service.php";

$json='{"success":"FAIL","msg":"พบข้อผิดพลาดบางประการ"}';
$token = isset($_SESSION[OFFICE]['TOKEN'])?$_SESSION[OFFICE]['TOKEN']:'';

function View()
{
    global $json;
    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $today = Today();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

//    PrintR($data);

    if(!$data['start'] && !$data['end']){
        $datas['start'] = '';
        $datas['end'] = '';
    }else{
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
        'start_date' => ($datas['start']?$datas['start']:''),
        'end_date' => ($datas['end']?$datas['end']:'')
    );

//    PrintR($params);

    $url = URL_API.'/geniespeech/dashbaord';
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

        $result['box2'] = (array)$response['box2'];

        $box3 = array();

        foreach ((array)$response['box3'] as $i => $items) {
            foreach ($response['box3'][$i]['data'] as $z => $item) {
                if(strpos($item['tagname'],'#') === false){
                    $tag = $item['tagname'];
                }else{
                    $tag = strstr($item['tagname'],'#',true);
                }
//                $label[$tag] = "'".$tag."'";
                $label[$tag] = $tag;
                $box3[$tag][$item['datetime']]['x'] = $item['datetime'];
                $box3[$tag][$item['datetime']]['y'] = $item['totalcall'];
                //$box3[$item['datetime']][$item['tagname']] = 0;
            }
        }

//        PrintR($box3);


//        foreach ($box3 as $i => $items) {
//            foreach ($label as $v => $value) {
//                $box3[$i][$value] = 0;
//            }
//
//        }


//        foreach ((array)$response['box3'] as $i => $items) {
//            foreach ($response['box3'][$i]['data'] as $z => $item) {
//                if(strpos($item['tagname'],'#') === false){
//                    $tag = $item['tagname'];
//                }else{
//                    $tag = strstr($item['tagname'],'#',true);
//                }
//
//                $box3[$item['datetime']][$tag] = $item['totalcall'];
//            }
//        }


        foreach ($box3 as $i => $item) {
            foreach($box3[$i] as $m => $items){
                $box3s[$i][] = $items;
            }

        }
        foreach ($label as $i => $item) {
            $labels[] = $item;
        }

        PrintR($box3s);



//        $labels = "'" . implode("', '", $label) . "'";
//        $labels = "'". implode("', '", $label);


        $result['box3']['label'] = $labels;
        $result['box3']['data'] = $box3s;

        $box4 = array();
        foreach ((array)$response['box4'] as $i => $item){
            $box4[$item['servicename']] = $item['totalcall'];
        }

        $box4s = array();
        foreach($box4 as $i => $item){
            $box4s[] = [ $i , $item ];
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
        if($result['box1'][0]['totalcall'] > 0){
            $databox5 = true;
            $box5[0]['name'] = 'Recognize';
            $box5[0]['title'] =  $result['box1'][0]['recog'];

            $box5[1]['name'] = 'Non-Recognize';
            $box5[1]['title'] =  $result['box1'][0]['nonrecog'];

            if($response['box4']){
                $m = 0;
                foreach($response['box4'] as $i => $item){
                    $box5[0]['children'][$i]['name'] = $item['servicename'];
                    $box5[0]['children'][$i]['title'] = $item['totalcall'];
                    ++$m;
                }
            }
        }




        $result['box5s'] = $databox5;
        $result['box5']['name'] = $box5name;
        $result['box5']['title'] = $box5title;
        $result['box5']['children'] = $box5;

        $result['success'] = 'COMPLETE';


    } else {

        $result['success'] = 'FAIL';

    }

    $result['msg'] = $response['result'][0]['msg'];


    $json = json_encode($result);

}


switch($_REQUEST["mode"]){
    case "View" : View(); break;
    case "Add" : Add(); break;
    case "Edit" : Edit(); break;
    case "Del" : Del(); break;

    default :
}

echo $json;
exit;