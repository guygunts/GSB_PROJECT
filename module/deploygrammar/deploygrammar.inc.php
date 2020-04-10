<?php
header("Content-type: application/json; charset=utf-8");
require_once "../../service/service.php";

$json = '{"success":"FAIL","msg":"พบข้อผิดพลาดบางประการ"}';
$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';

function View(){
    global $json;
    global $token;
    $datalist = array();
    $columns = array();
    $column = array();

    $result['data'] = array();
    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'menu_action' => $data['menu_action'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'text_search' => $data['text_search']
    );

//    PrintR($params);

    $url = URL_API.'/geniespeech/grammardeploy';
    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {
        $columnslist = $response['result']['header'];
        $datas = $response['data'];
        $name = 'Deploy Grammar';

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;
        foreach((array)$columnslist as $i => $item){
            $column[$m]['className'] = 'text-'.$item['column_align'];
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_field'];

            $columns[$m]['data'] = $item['column_field'];
            $columns[$m]['type'] = $item['column_type'];
            ++$m;
        }
        $column[$m]['className'] = 'text-center';
        $column[$m]['title'] = '';
        $column[$m]['data'] = 'btn';

        $permiss = LoadPermission();

        foreach((array)$datas as $i => $item){
            $btn = '';

            $datalist[$i]['no'] = ($i+1);

            foreach((array)$columns as $v => $value){
                $datalist[$i][$value['data']] = $item[$value['data']];

            }


            $dataattr = array();
            $dataattr[$i] = $item;


//            if($permiss[2]){
                $btn .= '<button data-code="pro" data-val="'.$item['pre_active'].'" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs '.($item['pro_active'] == 1?'btn-success':($item['pro_active'] == 2?'btn-warning':'btn-default')).'" '.($item['pro_active'] == 1?'disabled':'onclick="me.UpdateBtn(this)"').'>Production</button>&nbsp;&nbsp;';
//            }
//            if($permiss[3]){
                $btn .= '<button data-code="pre" data-val="'.$item['pro_active'].'" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs '.($item['pre_active'] == 1?'btn-success':($item['pre_active'] == 2?'btn-warning':'btn-default')).'" '.($item['pre_active'] == 1?'disabled':'onclick="me.UpdateBtn(this)"').'>Pre-Production</button>';
//            }

            $datalist[$i]['btn'] = $btn;
        }



        $result['name'] = SITE . ' : ' . $name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function ViewSub(){
    global $json;
    global $token;
    $datalist = array();
    $columns = array();
    $column = array();

    $result['data'] = array();
    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'menu_action' => $data['menu_action'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size']
    );

    $url = URL_API.'/geniespeech/grammardeploy';
    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {
        $columnslist = $response['result']['header'];
        $datas = $response['result']['data'];
        $name = 'Deploy Grammar';

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = '';
        $column[0]['data'] = 'no';


        $m = 1;
        foreach((array)$columnslist as $i => $item){
            $column[$m]['className'] = 'text-'.$item['column_align'];
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_field'];

            $columns[$m]['data'] = $item['column_field'];
            $columns[$m]['type'] = $item['column_type'];
            ++$m;
        }


        $permiss = LoadPermission();

        foreach((array)$datas as $i => $item){
            $btn = '';

            $datalist[$i]['no'] = '<input type="checkbox" name="build_version" ref="'.$item['build_version'].'">';;

            foreach((array)$columns as $v => $value){
                $datalist[$i][$value['data']] = $item[$value['data']];

            }


            $dataattr = array();
            $dataattr[$i] = $item;


        }



        $result['name'] = SITE . ' : ' . $name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Add()
{
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['user_login'] = $user;

//    PrintR($params);
//    exit;

    $url = URL_API.'/geniespeech/grammardeploy';
    $response = curlposttoken($url, $data, $token);

    if ($response['result'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Edit()
{
    global $json;
    global $token;
    $today = Today();
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();
//    PrintR($today);
//    exit;

    $str = file_get_contents("php://input");
    parse_str($str, $data);
    $data['action_type'] = 2;
    $date1 = explode(' ',$data['startdate']);
    if(strtotime($date1[0]) == strtotime($today)){
        $data['action_type'] = 1;

    }else{
        if($data['type'] == 'pro'){
            $data['pro_active'] = 2;
        }else if($data['type'] == 'pre'){
            $data['pre_active'] = 2;
        }
    }

    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['user_login'] = $user;
    unset($data['type']);
//    PrintR($data);
//    exit;

    $url = URL_API.'/geniespeech/grammardeploy';
    $response = curlposttoken($url, $data, $token);


    if ($response['result'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Del()
{
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data[$data['main']] = $data['code'];
    unset($data['code']);
    unset($data['main']);

    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function LoadPermission()
{
    $permiss = array();
    $permission = $_SESSION[OFFICE]['ROLE'][0]['function'];
    foreach ((array)$permission as $i => $item) {
        $permiss[$item['function_id']]['id'] = $item['function_id'];
        $permiss[$item['function_id']]['name'] = $item['function_name'];
    }
    return $permiss;
}

function LoadCbo()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'random_num' => $data['random_num']
    );

//    PrintR($params);
    $url = URL_API . '/geniespeech/voicelog';
    $response = curlposttoken($url, $params, $token);


    if (1) {

        $columnslist = $response['result']['header'];
        if ($data['menu_action'] == 'grammar') {
            $datas = $response['result']['box1'];
        } else if ($data['menu_action'] == 'confiden') {
            $datas = $response['result']['box2'];
        } else {
            $datas = $response['result']['box3'];
        }


        foreach ((array)$datas as $i => $item) {

            $datalist[$i]['code'] = $item[$data['code']];
            $datalist[$i]['name'] = $item[$data['name']];

        }


        $result['item'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }

    $result['msg'] = $response['msg'];
    $json = json_encode($result);

}

function ViewCHNN()
{
    global $json;
    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['name'] = '';


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $params = array(
        'chnn' => $data['menu_action'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size']
    );

//    PrintR($params);
    $url = URL_API . '/geniespeech/logdetail';
    $response = curlposttoken($url, $params, $token);

    if ($response['result'][0]['code'] == 200) {
        $columnslist = $response['columns_name'];
        $datas = $response['recs'];
        $name = $response['report_name'];

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;


        foreach ((array)$columnslist as $i => $item) {

            $column[$m]['className'] = 'text-center';
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = ($item['column_data']);


            $columns[$i]['data'] = ($item['column_data']);

            ++$m;
        }


        foreach ((array)$datas as $i => $item) {
            $datalist[$i]['no'] = ($i + 1);
            foreach ((array)$columns as $v => $value) {
                if ($value['data'] == 'CHNN') {
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN(' . "'" . str_replace('CHAN=', '', $item[$value['data']]) . "'" . ')">' . $item[$value['data']] . '</a>';
                } elseif ($value['data'] == 'VOICE_NAME') {
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE(' . '"' . $item[$value['data']] . '"' . ')">' . $item[$value['data']] . '</a>';
                } else {
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }

            }
        }


        $result['name'] = SITE . ' : ' . $name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['result'][0]['msg'];


    $json = json_encode($result);
}

function SaveVoice()
{
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data['expr_status'] = $data['expire_date_status'];
    $data['user_status'] = $data['active'];
    $data['expr_date'] = DateTimeFormatNew($data['expire_date']);
    $data['user_login'] = $user;

    unset($data['expire_date_status']);
    unset($data['active']);
    unset($data['code']);


    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}


switch ($_REQUEST["mode"]) {
    case "View" :
        View();
        break;
    case "ViewSub" :
        ViewSub();
        break;
    case "ViewVOICE" :
        ViewVOICE();
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
    case "LoadCbo" :
        LoadCbo();
        break;
    case "ViewQC" :
        ViewQC();
        break;
    case "SaveVoice" :
        SaveVoice();
        break;


    default :
}

echo $json;
exit;