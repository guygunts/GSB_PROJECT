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

            $datalist[$i]['no'] = ($i+1);

            foreach((array)$columns as $v => $value){
                $datalist[$i][$value['data']] = $item[$value['data']];

            }
            $dataattr_menu = array();
            $dataattr_per = array();
            $dataattr = array();

            foreach((array)$item['menus'] as $v => $value){
                if($value['menu_id']){
                    $dataattr_per[$i]['menu_'.$value['menu_id']] = 1;
                }
                if(count($value['sub_menu']) > 0){
                    foreach((array)$value['sub_menu'] as $m => $values){
                        $dataattr_per[$i]['menu_'.$value['menu_id'].'_'.$values['sub_menu_id']] = 1;
                    }
                }
            }

            foreach((array)$item['function'] as $v => $value){
//                if($value['menu_id']){
//                    $dataattr_per[$i]['func_'.$value['menu_id']] = 1;
//                }
                $dataattr_per[$i]['func_'.$value['function_id']] = 1;
            }

            unset($item['menus']);
            unset($item['functions']);
            $dataattr[$i] = $item;


            if($permiss[1]) {
                $btn .= '<button data-code="' . $item['role_id'] . '" data-item=' . "'" . json_encode($dataattr_per[$i], JSON_HEX_APOS) . "'" . ' onclick="me.LoadPermission(this)" type="button" class="btn btn-xs btn-warning"><i class="fa fa-check"></i> Permission</button>&nbsp;&nbsp;';
            }
            if($permiss[2]){
                $btn .= '<button data-code="'.$item['role_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> '.$permiss[2]['name'].'</button>&nbsp;&nbsp;';
            }
            if($permiss[3]){
                $btn .= '<button onclick="me.Del('.$item['role_id'].')"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
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

//    $data['role_desc'] = $data['role_description'];
    $data['user_login'] = $user;

    unset($data['role_description']);
    unset($data['code']);
    unset($data['role_id']);



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

//    $data['role_desc'] = $data['role_desc'];
    $data['user_login'] = $user;

    unset($data['role_description']);


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

function EditSub(){
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
    $a = 0;
    foreach((array)$data['menu'] as $i => $item){
        if($item['menu_id'] == "0")continue;
        $data['menus'][$a]['menu_id'] = $item['menu_id']*1;
        $data['menus'][$a]['sub_menus'] = array();

        $b = 0;
        foreach((array)$data['menu'][$i]['sub_menus'] as $m => $items){
            if($items['submenu_id'] == "0")continue;
            $data['menus'][$a]['sub_menus'][$b]['sub_menu_id'] = $items['submenu_id']*1;
            ++$b;
        }
        ++$a;
    }
    $c = 0;
    foreach((array)$data['function'] as $i => $item){
        if($item['function_id'] == "0")continue;
        $data['functions'][$c]['function_id'] = $item['function_id']*1;
        ++$c;

    }

    $data['role_id'] =  $data['role_id'] * 1;
    $data['user_login'] = $user;

    unset($data['function']);
    unset($data['menu']);
    unset($data['code']);

//    PrintR ($data);
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
  case "EditSub" : EditSub(); break;
  case "Del" : Del(); break;

  default :
}

echo $json;
exit;