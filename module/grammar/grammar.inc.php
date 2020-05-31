<?php
require_once "../../service/service.php";
require_once "../../service/vendor.php";

function ShowActive($code, $status){
    if($status == 0){
        return '<button type="button" class="btn btn-default btn-xs" data-type="1" data-code="'.$code.'" onclick="me.Enable(this)">Inactive</button>';
    }elseif($status == 1){
        return '<button type="button" class="btn btn-warning btn-xs" data-type="0" data-code="'.$code.'" onclick="me.Enable(this)">Active</button>';
    }
}

function ShowActiveSub($code, $status){
    if($status == 0){
        return '<button type="button" class="btn btn-default btn-xs" data-type="1" data-code="'.$code.'" onclick="me.EnableSub(this)">Inactive</button>';
    }elseif($status == 1){
        return '<button type="button" class="btn btn-warning btn-xs" data-type="0" data-code="'.$code.'" onclick="me.EnableSub(this)">Active</button>';
    }
}

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
        'category_id' => $data['category_id'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size']
    );

    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $params, $token);
//    PrintR($response);

    if ($response['code'] == 200) {
        $start = $data['start'];
        $recnums['pages'] = $response['page_num'];
        $recnums['recordsFiltered'] = $response['rec_num'];
        $recnums['recordsTotal'] = $response['rec_num'];

        $columnslist = $response['result'];
        $datas = $response['data'];

        $column[0]['className'] = 'details-control';
        $column[0]['title'] = '';
        $column[0]['data'] = null;
        $column[0]['defaultContent'] = '';

        $column[1]['className'] = 'text-center';
        $column[1]['title'] = 'No';
        $column[1]['data'] = 'no';

        $columnslist[0]['column_field'] = 'user_question';
        $columnslist[1]['column_field'] = 'intent_tag';
        $columnslist[2]['column_field'] = 'active';

        $m = 2;
        foreach ((array)$columnslist as $i => $item) {
            $column[$m]['className'] = 'text-' . ($item['column_align']?$item['column_align']:'center');
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_data'];

            $columns[$m]['data'] = $item['column_data'];
            $columns[$m]['type'] = $item['column_type'];
            ++$m;
        }
        $column[$m]['className'] = 'text-center';
        $column[$m]['title'] = '';
        $column[$m]['data'] = 'btn';

        $column[($m+1)]['className'] = 'text-center';
        $column[($m+1)]['title'] = 'Sentence';
        $column[($m+1)]['data'] = 'sentence';

        $permiss = LoadPermission();

        foreach ((array)$datas as $i => $item) {
            $btn = '';
            $btnsuntence = '';

            $item['DT_RowId'] = 'row_' . MD5($item[$columns[2]['data']]);
            $datalist[$i]['DT_RowId'] = $item['DT_RowId'];
            ++$start;
            $datalist[$i]['no'] = $start;

            foreach ((array)$columns as $v => $value) {
                if($value['data'] == 'active'){
                    $datalist[$i][$value['data']] = ShowActive($item['intent_id'],$item[$value['data']]);
                }else{
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }


            }

            $paramssub = array(
                'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
                'menu_action' => 'getsubintentbyintent',
                'intent_id' => $item['intent_id'],
                'page_id' => $data['page_id'],
                'page_size' => $data['page_size']
            );

            $responsesub = curlposttoken($url, $paramssub, $token);
            if ($responsesub['code'] == 200) {
                $columnsublist = $responsesub['result'];
                $datasub = $responsesub['data'];
                $t = 0;
                foreach ((array)$columnsublist as $m => $items) {
                    $columnssub[$t]['data'] = $items['column_data'];
                    $columnssub[$t]['type'] = $items['column_type'];
                    ++$t;
                }

                foreach ((array)$datasub as $z => $itemsub) {
                    $btnsub = '';
                    $btnsubsentence = '';

                    foreach ((array)$columnssub as $n => $valuesub) {
                        if($valuesub['data'] == 'active'){
                            $datalistsub[$z][$valuesub['data']] = ShowActiveSub($itemsub['sub_intent_id'],$itemsub[$valuesub['data']]);
                        }else{
                            $datalistsub[$z][$valuesub['data']] = $itemsub[$valuesub['data']];
                        }

                        $datalistsub[$z]['name'] = 'subrow_' . $z . '_' . $i;

                    }

                    $datasubattr = array();
                    $datasubattr[$z] = $itemsub;

                    if ($permiss[2]) {
                        $btnsub .= '<button data-code="' . $item['sub_intent_id'] . '" data-item=' . "'" . json_encode($datasubattr[$z], JSON_HEX_APOS) . "'" . ' onclick="me.LoadSub(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';
                        $btnsubsentence .= '<button data-intent_id="' . $item['intent_id'] . '" data-subintent_id="' . $item['sub_intent_id'] . '" data-item=' . "'" . json_encode($datasubattr[$z], JSON_HEX_APOS) . "'" . ' onclick="me.LoadSub(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';

                    }
                    if ($permiss[3]) {
                        $btnsub .= '<button data-code="' . $item['sub_intent_id'] . '" onclick="me.DelSub(this)"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> ' . $permiss[3]['name'] . '</button>';
                    }


                    $datalistsub[$z]['btn'] = $btnsub;
                    $datalistsub[$z]['sentence'] = $btnsubsentence;
                }
                $item['variation'] = $datasub;
            }



            $datalist[$i]['variation'] = json_encode($datalistsub, JSON_HEX_APOS);


            $dataattr = array();
            $dataattr[$i] = $item;
//            $dataattr[$i]['variation'] = json_encode($datasubattr,JSON_FORCE_OBJECT);

            if ($permiss[2]) {
                $btn .= '<button data-code="' . $item['intent_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';
                $btnsuntence .= '<button data-intent_id="' . $item['intent_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.LoadSentence(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';

            }
            if ($permiss[3]) {
                $btn .= '<button data-code="' . $item['intent_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Del(this)"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> ' . $permiss[3]['name'] . '</button>';
            }

            $datalist[$i]['btn'] = $btn;
            $datalist[$i]['sentence'] = $btnsuntence;

        }


        $result['columns'] = $column;
        $result['data'] = $datalist;

        $result['draw'] = ($data['draw']*1);
        $result['recordsTotal'] = $recnums['recordsTotal'];
        $result['recordsFiltered'] = $recnums['recordsTotal'];

        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    echo json_encode($result);
}

function ViewSub(Request $request)
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
        'category_id' => $data['category_id'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'intent_id' => $data['intent_id'],
        'subintent_id' => $data['subintent_id'],
        'text_search' => $data['text_search']
    );


    $url = URL_API . '/geniespeech/adminmenu';
    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {
        $columnslist = $response['result'];
        $datas = $response['data'];

        $column[0]['className'] = 'details-control';
        $column[0]['title'] = '';
        $column[0]['data'] = null;
        $column[0]['defaultContent'] = '';

        $column[1]['className'] = 'text-center';
        $column[1]['title'] = 'No';
        $column[1]['data'] = 'no';

//        $columnslist[0]['column_field'] = 'user_question';
//        $columnslist[1]['column_field'] = 'intent_tag';
//        $columnslist[2]['column_field'] = 'active';

        $m = 2;
        foreach ((array)$columnslist as $i => $item) {
            $column[$m]['className'] = 'text-' . ($item['column_align']?$item['column_align']:'center');
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_data'];

            $columns[$m]['data'] = $item['column_data'];
            $columns[$m]['type'] = $item['column_type'];
            ++$m;
        }
        $column[$m]['className'] = 'text-center';
        $column[$m]['title'] = '';
        $column[$m]['data'] = 'btn';


        $permiss = LoadPermission();

        foreach ((array)$datas as $i => $item) {
            $btn = '';
            $btnsuntence = '';

            $item['DT_RowId'] = 'row_' . MD5($item[$columns[2]['data']]);
            $datalist[$i]['DT_RowId'] = $item['DT_RowId'];
            $datalist[$i]['no'] = ($i + 1);

            foreach ((array)$columns as $v => $value) {
                if($value['data'] == 'active'){
                    $datalist[$i][$value['data']] = ShowActive($item['intent_id'],$item[$value['data']]);
                }else{
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }


            }



            $dataattr = array();
            $dataattr[$i] = $item;
//            $dataattr[$i]['variation'] = json_encode($datasubattr,JSON_FORCE_OBJECT);

            if ($permiss[2]) {
                $btn .= '<button data-code="' . $item['intent_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';
                $btnsuntence .= '<button data-code="' . $item['intent_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.LoadSentence(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';

            }
            if ($permiss[3]) {
                $btn .= '<button data-code="' . $item['intent_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Del(this)"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> ' . $permiss[3]['name'] . '</button>';
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

    $a = 0;
    $ch = array();
    foreach ((array)$data['subintent'] as $i => $item) {

        $data['subintent'][$a] = $item;
        ++$a;

    }

//    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['project_id'] = 1;
    $data['user_login'] = $user;


    unset($data['code']);
//    unset($data['concept_id']);
    unset($data['sub']);

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

function AddSub(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

//    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['project_id'] = 1;
    $data['user_login'] = $user;

//PrintR($data);
//exit;

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

//    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['project_id'] = 1;
    $data['user_login'] = $user;

//PrintR($data);
//exit;

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

//    $data['role_desc'] = $data['role_description'];
    $data['user_login'] = $user;

//   PrintR($data);
//   exit;


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

function EditSub_(Request $request)
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

    //    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['project_id'] = 1;
    $data['user_login'] = $user;

    $data['intent_del'][0][$data['main']] = $data['code'];
    unset($data['code']);
    unset($data['main']);

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

function DelSub(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

    //    $data['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
    $data['project_id'] = 1;
    $data['user_login'] = $user;

    $data[$data['main']] = $data['code'];
    unset($data['code']);
    unset($data['main']);

    PrintR($data);
    exit;

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
            $datalist[$i]['active'] =$item['active'];
            $datalist[$i]['checkable'] = false;
            $datalist[$i]['lazyLoad'] = true;
//            $datalist[$i]['tags'] = ['test'];
//            $datalist[$i]['tags'] = ['<button class="btn main" type="button" id="main'.$item['category_id'].'" onclick="event.preventDefault();"><i class="glyphicon glyphicon-plus"></i></button>'];
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
            $datalist[$i]['main'] =$data['code'];
            $datalist[$i]['tags'] = ['<button class="btn sub" type="button" id="sub'.$item['category_id'].'"></button>'];

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

function Enable(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

    $data['user_login'] = $user;



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
    case "ViewSub" :
        ViewSub($x);
        break;
    case "Add" :
        Add($x);
        break;
    case "AddSub" :
        AddSub($x);
        break;
    case "Edit" :
        Edit($x);
        break;
    case "EditSub" :
        EditSub($x);
        break;
    case "DelSub" :
        DelSub($x);
        break;
    case "Del" :
        Del($x);
        break;
    case "Enable" :
        Enable($x);
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