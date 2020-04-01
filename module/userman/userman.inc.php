<?php
header("Content-type: application/json; charset=utf-8");
require_once "../../service/service.php";

$json='{"success":"FAIL","msg":"พบข้อผิดพลาดบางประการ"}';
$token = isset($_SESSION[OFFICE]['TOKEN'])?$_SESSION[OFFICE]['TOKEN']:'';

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
        'menu_action' => $data['menu_action'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
    );
    $url = URL_API.'/geniespeech/adminmenu';
    $response = curlposttoken($url, $params, $token);

    if ($response['code'] == 200) {
        $columnslist = $response['result']['header'];
        $datas = $response['result']['data'];

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


            $item['user_role'] = $item['role']['role_name'];
            $item['role_id'] = $item['role']['role_id'];

            $item['DT_RowId'] = 'row_'.MD5($item[$columns[1]['data']]);
            $datalist[$i]['DT_RowId'] = $item['DT_RowId'];
            $datalist[$i]['no'] = ($i+1);

            foreach((array)$columns as $v => $value){
                $datalist[$i][$value['data']] = $item[$value['data']];

            }
            $dataattr = array();
            $dataattr[$i] = $item;


            if($permiss[2]){
                $btn .= '<button data-code="'.$item['user_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> '.$permiss[2]['name'].'</button>&nbsp;&nbsp;';
            }
            if($permiss[3]){
                $btn .= '<button data-code="'.$item['user_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' onclick="me.Del(this)"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
            }

            $datalist[$i]['btn'] = $btn;

        }




        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Add(){
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

//    $data['expr_status'] = $data['expire_date_status'];
//    $data['user_status'] = $data['active'];
    $data['expire_date'] = ($data['expire_date']?DateTimeFormatNew($data['expire_date']):'');
    $data['user_login'] = $user;

//    unset($data['expire_date']);
//    unset($data['expire_date_status']);
//    unset($data['active']);
    unset($data['code']);
    unset($data['user_id']);

//    PrintR($data);
//    exit;

    $url = URL_API.'/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Edit(){
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

//    $data['expr_status'] = $data['expire_date_status'];
//    $data['user_status'] = $data['active'];
    $data['expire_date'] = ($data['expire_date']?DateTimeFormatNew($data['expire_date']):'');
    $data['user_login'] = $user;

//    unset($data['expire_date']);
//    unset($data['expire_date_status']);
//    unset($data['active']);
    unset($data['code']);

//    PrintR($data);
//    exit;


    $url = URL_API.'/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Del(){
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

    $url = URL_API.'/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function LoadPermission(){
    $permiss = array();
    $permission = $_SESSION[OFFICE]['ROLE'][0]['function'];
    foreach((array)$permission as $i => $item){
        $permiss[$item['function_id']]['id'] = $item['function_id'];
        $permiss[$item['function_id']]['name'] = $item['function_name'];
    }
    return $permiss;
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