<?php
require_once "../../service/service.php";
require_once "../../service/vendor.php";

function View(Request $request)
{

    global $token;
    $datalist = array();
    $columns = array();
    $column = array();

    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'menu_action' => $data['menu_action'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size']
    );

    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {
        $columnslist = $response['result']['header'];
        $datas = $response['result']['data'];

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;
        foreach ((array)$columnslist as $i => $item) {
            $column[$m]['className'] = 'text-' . $item['column_align'];
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

        foreach ((array)$datas as $i => $item) {
            $btn = '';

            $datalist[$i]['no'] = ($i + 1);

            foreach ((array)$columns as $v => $value) {
                $datalist[$i][$value['data']] = $item[$value['data']];

            }


            $dataattr = array();
            $dataattr[$i] = $item;


            if ($permiss[2]) {
                $btn .= '<button data-code="' . $item['function_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';
            }
            if ($permiss[3]) {
                $btn .= '<button onclick="me.Del(' . $item['function_id'] . ')"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> ' . $permiss[3]['name'] . '</button>';
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


    echo json_encode($result);
}

function Add(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

    $data['role_desc'] = $data['role_description'];
    $data['user_login'] = $user;

    unset($data['role_description']);
    unset($data['code']);
    unset($data['role_id']);


    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    echo json_encode($result);
}

function Edit(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

    $data['role_desc'] = $data['role_description'];
    $data['user_login'] = $user;

    unset($data['role_description']);


    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    echo json_encode($result);
}

function EditSub(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);
    $a = 0;
    foreach ((array)$data['menu'] as $i => $item) {
        if ($item['menu_id'] == 0) continue;
        $data['menus'][$a]['menu_id'] = $item['menu_id'];
        $data['menus'][$a]['sub_menus'] = array();

        $b = 0;
        foreach ((array)$data['menu'][$i]['sub_menus'] as $m => $items) {
            if ($items['submenu_id'] == 0) continue;
            $data['menus'][$a]['sub_menus'][$b]['submenu_id'] = $items['submenu_id'];
            ++$b;
        }
        ++$a;
    }
    $c = 0;
    foreach ((array)$data['function'] as $i => $item) {
        if ($item['function_id'] == 0) continue;
        $data['functions'][$c]['function_id'] = $item['function_id'];
        ++$c;

    }

    $data['user_login'] = $user;

    unset($data['function']);
    unset($data['menu']);
    unset($data['code']);

//    PrintR($data);
//    exit;


    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    echo json_encode($result);
}

function Del(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

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


    echo json_encode($result);
}

function LoadCbo(Request $request)
{

    parse_str($request->getPost()->toString(), $data);

    $params = array(
        'project_id' => 1,
        'menu_action' => $data['menu_action'],
        'page_id' => 1,
        'page_size'=> 100
    );



    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlpostmain($url, $params);

    if ($response['data']) {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $datas = $response['data'];

        $datalist = [];
        foreach ((array)$datas as $i => $item) {
            $datalist[$i]['text'] =$item[$data['name']];
            $datalist[$i]['id'] =$item[$data['code']];
            $datalist[$i]['icon'] ='glyphicon glyphicon-stop';
            $datalist[$i]['selectedIcon'] ='glyphicon glyphicon-stop';
            $datalist[$i]['selectable'] = true;
            $datalist[$i]['state'] = [array('selected'=>true)];
            $datalist[$i]['nodes'] = [];
        }


        /** @noinspection PhpUndefinedVariableInspection */
        $result['item'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }

    $result['msg'] = $response['msg'];
    echo json_encode($result);

}

function LoadCboSub(Request $request)
{

    parse_str($request->getPost()->toString(), $data);

    $params = array(
        'project_id' => 1,
        'menu_action' => $data['menu_action'],
        'category_id' => $data['code'],
        'page_id' => 1,
        'page_size'=> 100
    );



    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlpostmain($url, $params);

    if ($response['data']) {

        /** @noinspection PhpUnusedLocalVariableInspection */
        $datas = $response['data'];

        $datalist = [];
        foreach ((array)$datas as $i => $item) {
            $datalist[$i]['text'] =$item['category_name'];
            $datalist[$i]['id'] =$item['category_id'];

        }


        /** @noinspection PhpUndefinedVariableInspection */
        $result['item'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }

    $result['msg'] = $response['msg'];
    echo json_encode($result);

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


switch ($switchmode) {
    case "View" :
        View($x);
        break;
    case "Add" :
        Add($x);
        break;
    case "Edit" :
        Edit($x);
        break;
    case "EditSub" :
        EditSub($x);
        break;
    case "Del" :
        Del($x);
        break;
    case "LoadCbo" :
        LoadCbo($x);
        break;
    case "LoadCboSub" :
        LoadCboSub($x);
        break;

    default :
        $result['success'] = 'FAIL';
        $result['msg'] = 'ไม่มีข้อมูล';
        echo json_encode($result);
        break;
}