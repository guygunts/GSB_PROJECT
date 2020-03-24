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
//        $_SESSION[OFFICE]['ROLE'][0]['menus'] = $datas;
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
        $column[$m]['title'] = 'After';
        $column[$m]['data'] = 'after';

        $column[($m+1)]['className'] = 'text-center';
        $column[($m+1)]['title'] = '';
        $column[($m+1)]['data'] = 'btn';

        $permiss = LoadPermission();
        $newdata = array();
        $v = 0;
        foreach((array)$datas as $i => $item){
            $submenu = $item['sub_menu'];
            unset($item['sub_menu']);
            $newdata[$v] = $item;
            $newdata[$v]['main_menu'] = 1;

            if(count($submenu) > 0){

                foreach((array)$submenu as $m => $value){
                    if(count($value)==0)continue;
                    ++$v;
                    $newdata[$v]['menu_id'] = $value['sub_menu_id'];
                    $newdata[$v]['menu_name'] = $value['sub_menu_name'];
                    $newdata[$v]['menu_path'] = $value['sub_menu_path'];
                    $newdata[$v]['menu_icon'] = $value['sub_menu_icon'];
                    $newdata[$v]['menu_active'] = $value['sub_menu_active'];
                    $newdata[$v]['menu_after'] = $item['menu_name'];
                    $newdata[$v]['main_menu'] = 0;
                    $newdata[$v]['main_id'] = $item['menu_id'];

                }
            }
            ++$v;
        }


        foreach((array)$newdata as $i => $item){
            $btn = '';



            $datalist[$i]['no'] = ($i+1);

            foreach((array)$columns as $v => $value){
                $datalist[$i][$value['data']] = $item[$value['data']];

            }
            $datalist[$i]['menu_name'] = $item['main_menu']==0?'&nbsp;&nbsp;- '.$item['menu_name']:$item['menu_name'];

            $dataafter['main_menu'] = 0;
            $dataafter['menu_active'] = 1;
            $dataafter['menu_after'] = ($item['menu_after']?$item['menu_after']:$item['menu_name']);
            if($item['main_menu'] == 1){
                $btnafter = '<button data-item=' . "'" . json_encode($dataafter, JSON_HEX_APOS) . "'" . '  onclick="me.LoadAfter(this)" type="button" class="btn btn-xs btn-default"><i class="fa fa-plus-circle"></i></button>';

            }else{
                $btnafter = '<button data-item=' . "'" . json_encode($dataafter, JSON_HEX_APOS) . "'" . '  onclick="me.LoadAfterSub(this)" type="button" class="btn btn-xs btn-default"><i class="fa fa-plus-circle"></i></button>';

            }

            if ($permiss[1]) {
                $datalist[$i]['after'] = $btnafter;
            }else{
                $datalist[$i]['after'] = '';
            }


            $dataattr = array();
            $dataattr = array();

            $dataattr[$i] = $item;

            if ($permiss[2]) {
                $btn .= '<button data-code="' . $item['menu_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';

            }
            if($permiss[3]){
//                if($item['main_menu'] == 1){
                    $btn .= '<button data-code="' . $item['menu_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Del(this)"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
//                }else{
//                    $btn .= '<button onclick="me.Del('.$item['menu_id'].','."'".$item['menu_after']."'".')"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
//                }
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


    $data['user_login'] = $user;

    unset($data['code']);
    if(!$data['menu_after']) {
        unset($data['menu_after']);
    }



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

    $data['role_desc'] = $data['role_description'];
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
    $data['user_login'] = $user;
    unset($data['code']);
    unset($data['main']);
//    $data['menu_del']['menu_id'] = $data['menu_id'];
//    unset($data['menu_id']);
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