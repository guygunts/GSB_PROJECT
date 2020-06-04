<?php
require_once "../../service/service.php";
require_once "../../service/vendor.php";

function View(Request $request)
{

    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $grammarlist = array();
    $confidenlist = array();
    $intentlist = array();
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['name'] = '';


    parse_str($request->getPost()->toString(), $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'random_num' => ($data['random_num']?$data['random_num']:0),
        'status' => $data['status'],
        'qc' => $data['qc'],
        'grammar' => $data['grammar'],
        'intent' => $data['intent'],
        'confiden' => $data['confiden'],
        'text_search' => $data['text_search'],
        'flag_edit' => ''
    );

//    PrintR($params);

    $url = URL_API . '/geniespeech/voicelog';
    $response = curlposttoken($url, $params, $token);
//PrintR($response);
    if ($response['result'][0]['code'] == 200) {
        $start = $data['start'];
        $recnums['pages'] = $response['result'][0]['pagenum'];
        $recnums['recordsFiltered'] = ($response['result'][0]['pagenum'] * $data['page_size']);
        $recnums['recordsTotal'] = ($response['result'][0]['pagenum'] * $data['page_size']);

        $grammar = $response['recs']['box1'];
        $confiden = $response['recs']['box2'];
        $intent = $response['recs']['box3'];
//        $recnums = $response['recnums'];


        foreach ((array)$grammar as $i => $item) {
            $grammarlist[$i]['code'] = $item['grammar_name'];
            $grammarlist[$i]['name'] = $item['grammar_name'];
        }
        foreach ((array)$confiden as $i => $item) {
            $confidenlist[$i]['code'] = $item['conf_id'];
            $confidenlist[$i]['name'] = $item['conf_name'];
        }
        foreach ((array)$intent as $i => $item) {
            $intentlist[$i]['code'] = $item['intent_tag'];
            $intentlist[$i]['name'] = $item['intent_tag'];
        }


        $columnslist = $response['columns_name'];
        $datas = $response['recs']['box4'];
        $name = 'Voice Log Analytic';
//        PrintR($datas);
//        exit;

        $column[0]['className'] = 'details-control';
        $column[0]['title'] = '';
        $column[0]['data'] = null;
        $column[0]['defaultContent'] = '';

        $column[1]['className'] = 'text-center';
        $column[1]['title'] = 'No';
        $column[1]['data'] = 'no';


        $m = 2;


        foreach ((array)$columnslist as $i => $item) {

            if ($item['column_data'] == 'voice_name' || $item['column_data'] == 'qc_status') {
                $column[$m]['className'] = 'text-center';
            } else {
                $column[$m]['className'] = 'text-center';
            }

            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_data'];

            $columns[$m]['data'] = $item['column_data'];
            $columns[$m]['title'] = $item['column_name'];
            $columns[$m]['type'] = $item['column_type'];

            ++$m;
        }

        $column[$m]['className'] = 'text-center';
        $column[$m]['title'] = '';
        $column[$m]['data'] = 'btn';

        $permiss = LoadPermission();


        foreach ((array)$datas as $i => $item) {
            $btn = '';

            $item['DT_RowId'] = 'row_' . MD5($item[$columns[2]['data']]);
            $datalist[$i]['DT_RowId'] = $item['DT_RowId'];
            $datalist[$i]['no'] = ($i + 1);


            foreach ((array)$columns as $v => $value) {
                if ($value['data'] == 'voice_name') {
//                    $datalist[$i][$value['data']] = '<i class="glyphicon glyphicon-volume-up"></i>';
                    $datalist[$i][$value['data']] = '<audio preload="auto" autobuffer controls><source src="' . $item[$value['data']] . '" type="audio/wav"></audio>';
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('.'"'.$item[$value['data']].'"'.')"><i class="glyphicon glyphicon-volume-up"></i></a>';
                } elseif ($value['data'] == 'intent'){
                    $chkintent = explode('#',$item['intent']);
                    $datalist[$i][$value['data']] = $chkintent[0];

                } elseif ($value['data'] == 'chnn') {
                    $datalist[$i][$value['data']] = '<a href="' . $item['log_file'] . '" target="_blank"><i class="glyphicon glyphicon-new-window"></i></a>';
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN(' . "'" . $item['chnn'] . "'," . $data['page_id'] . ',' . $data['page_size'] . ",'" . $data['start_date'] . "','" . $data['end_date'] . "'" . ')"><i class="glyphicon glyphicon-volume-up"></i></a>';
                } elseif ($value['data'] == 'qc_status') {
                    $datalist[$i][$value['data']] = '<select name="' . $value['data'] . '" class="form-control qc_status' . $item['rec_id'] . ' row' . $item['rec_id'] . ' "><option value="" ' . ($item[$value['data']] == 0 ? 'selected' : '') . '>= Status =</option><option value="P" ' . ($item[$value['data']] == 'P' ? 'selected' : '') . '>Pass</option><option value="F" ' . ($item[$value['data']] == 'F' ? 'selected' : '') . '>Fail</option><option value="G" ' . ($item[$value['data']] == 'G' ? 'selected' : '') . '>Garbage</option><option value="O" ' . ($item[$value['data']] == 'O' ? 'selected' : '') . '>Other</option></select>';
                } elseif ($value['data'] == 'action') {
                    $datalist[$i][$value['data']] = '<select name="' . $value['data'] . '" class="form-control action' . $item['rec_id'] . ' row' . $item['rec_id'] . ' empty' . $item['rec_id'] . ' "><option value="0" ' . ($item[$value['data']] == '0' ? 'selected' : '') . '>None</option><option value="1" ' . ($item[$value['data']] == '1' ? 'selected' : '') . '>Train</option><option value="2" ' . ($item[$value['data']] == '2' ? 'selected' : '') . '>Test</option><option value="3" ' . ($item[$value['data']] == '3' ? 'selected' : '') . '>Test&Train</option></select>';

                } elseif ($value['data'] == 'input_qc' || $value['data'] == 'remark' || $value['data'] == 'Expected') {
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
                    if ($value['data'] == 'Expected') {
                        $option = '<option value="" ' . ($item[$v] == '' ? 'selected' : '') . '>== INTENT ==</option>';
                        foreach ((array)$intent as $m => $item2) {

                            $option .= '<option value="' . $item2['intent_tag'] . '" ' . ($item[$v] == $item2['intent_tag'] ? 'selected' : '') . '>' . $item2['intent_tag'] . '</option>';
                        }
                    }


                    if ($item[$v]) {
                        $datalist[$i][$value['data']] = $item[$v];
                        if ($value['data'] == 'Expected') {
//                            $datalist[$i][$value['data']] = '<input name="' . $value['data'] . '" type="hidden" value="' . $item[$v] . '" class="' . $value['data'] . $item['rec_id'] . ' row' . $item['rec_id'] . ' popupdata"><a href="javascript:void(0)" onclick="me.OpenPopup(' . "'" . $value['data'] . $item['rec_id'] . "'" . ',' . "'" . $value['title'] . "'" . ',' . "'" . $item[$v] . "'" . ')" id="' . $value['data'] . $item['rec_id'] . '">' . $item[$v] . '</a>';
                            $datalist[$i][$value['data']] = '<select name="' . $value['data'] . '" class="select2 expected' . $item['rec_id'] . ' row' . $item['rec_id'] . ' ">' . $option . '</select>';

                        } else {
                            $datalist[$i][$value['data']] = '<input name="' . $value['data'] . '" type="hidden" value="' . $item[$v] . '" class="' . $value['data'] . $item['rec_id'] . ' row' . $item['rec_id'] . ' popupdata"><a href="javascript:void(0)" onclick="me.OpenPopup(' . "'" . $value['data'] . $item['rec_id'] . "'" . ',' . "'" . $value['title'] . "'" . ',' . "'" . $item[$v] . "'" . ')" id="' . $value['data'] . $item['rec_id'] . '">' . $item[$v] . '</a>';

                        }

                    } elseif(!$item[$v]) {
                        if ($value['data'] == 'Expected') {
                            $datalist[$i][$value['data']] = '<select name="' . $value['data'] . '" class="select2 expected' . $item['rec_id'] . ' row' . $item['rec_id'] . ' ">' . $option . '</select>';

                        } else {
                            $datalist[$i][$value['data']] = '<input name="' . $value['data'] . '" type="hidden" class="' . $value['data'] . $item['rec_id'] . ' row' . $item['rec_id'] . ' popupdata"><a href="javascript:void(0)" onclick="me.OpenPopup(' . "'" . $value['data'] . $item['rec_id'] . "'" . ',' . "'" . $value['title'] . "'" . ')" id="' . $value['data'] . $item['rec_id'] . '"><i class="glyphicon glyphicon-edit"></i></a>';

                        }
                    }

                } else {
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }

            }

            $dataattr = array();
            $dataattr[$i] = $item;


            if ($permiss[2]) {
                if ($item[$v]) {
                    $btn .= '<button data-code="' . $item['rec_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.UpdateVoice(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;';

                } else {
                    $btn .= '<button data-code="' . $item['rec_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.UpdateVoice(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;';

                }
            }


            $datalist[$i]['btn'] = $btn;

        }


        $result['name'] = SITE . ' : ' . $name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['grammar'] = $grammarlist;
        $result['confiden'] = $confidenlist;
        $result['intent'] = $intentlist;

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

function ViewQC(Request $request)
{

    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $grammarlist = array();
    $confidenlist = array();
    $intentlist = array();
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['name'] = '';


    parse_str($request->getPost()->toString(), $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'random_num' => $data['random_num'],
        'qc_status' => $data['qc_status'],
        'grammar' => $data['grammar'],
        'intent' => $data['intent'],
        'confiden' => $data['confiden'],
        'text_search' => $data['text_search']

    );


    $url = URL_API . '/geniespeech/voicelog';
    $response = curlposttoken($url, $params, $token);

    if (1) {


        $grammar = $response['result']['box1'];
        $confiden = $response['result']['box2'];
        $intent = $response['result']['box3'];


        foreach ((array)$grammar as $i => $item) {
            $grammarlist[$i]['code'] = $item['grammar_id'];
            $grammarlist[$i]['name'] = $item['grammar_name'];
        }
        foreach ((array)$confiden as $i => $item) {
            $confidenlist[$i]['code'] = $item['conf_id'];
            $confidenlist[$i]['name'] = $item['conf_name'];
        }
        foreach ((array)$intent as $i => $item) {
            $intentlist[$i]['code'] = $item['intent_id'];
            $intentlist[$i]['name'] = $item['intent_tag'];
        }


        $columnslist = $response['result']['header'];
        $datas = $response['result']['box4'];
        $name = 'Voice Log Analytic';
//        PrintR($datas);
//        exit;


        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;


        foreach ((array)$columnslist as $i => $item) {

            if ($item['column_field'] == 'voice_name' || $item['column_field'] == 'qc_status') {
                $column[$m]['className'] = 'text-center';
            } else {
                $column[$m]['className'] = 'text-' . $item['column_align'];
            }

            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_field'];

            $columns[$m]['data'] = $item['column_field'];
            $columns[$m]['title'] = $item['column_name'];
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
                if ($value['data'] == 'voice_name') {
//                    $datalist[$i][$value['data']] = '<i class="glyphicon glyphicon-volume-up"></i>';
                    $datalist[$i][$value['data']] = '<audio preload="auto" autobuffer controls><source src="' . $item[$value['data']] . '" type="audio/wav"></audio>';
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('.'"'.$item[$value['data']].'"'.')"><i class="glyphicon glyphicon-volume-up"></i></a>';
                } elseif ($value['data'] == 'chnn') {
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN(' . "'" . $item['chnn'] . "'," . $data['page_id'] . ',' . $data['page_size'] . ",'" . $data['start_date'] . "','" . $data['end_date'] . "'" . ')"><i class="glyphicon glyphicon-volume-up"></i></a>';
                } elseif ($value['data'] == 'qc_status') {
                    $datalist[$i][$value['data']] = '<select><option value="0" ' . ($item[$value['data']] == 0 ? 'selected' : '') . '>Status</option><option value="P" ' . ($item[$value['data']] == 'P' ? 'selected' : '') . '>Pass</option><option value="F" ' . ($item[$value['data']] == 'F' ? 'selected' : '') . '>Fail</option><option value="G" ' . ($item[$value['data']] == 'G' ? 'selected' : '') . '>Garbage</option><option value="O" ' . ($item[$value['data']] == 'O' ? 'selected' : '') . '>Other</option></select>';
                } elseif ($value['data'] == 'input_qc' || $value['data'] == 'remark') {
                    $chnn = str_replace('CHAN=', '', $item['chnn']);
                    $datalist[$i][$value['data']] = '<input type="hidden" class="' . $value['data'] . $chnn . '"><a href="javascript:void(0)" onclick="me.OpenPopup(' . "'" . $value['data'] . $chnn . "'" . ',' . "'" . $value['title'] . "'" . ')" id="' . $value['data'] . $chnn . '"><i class="glyphicon glyphicon-edit"></i></a>';
                } else {
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }

            }

            $dataattr = array();
            $dataattr[$i] = $item;


            if ($permiss[2]) {
                $btn .= '<button data-code="' . $item['chnn'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.UpdateVoice(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Submit</button>&nbsp;&nbsp;';
            }


            $datalist[$i]['btn'] = $btn;

        }


        $result['name'] = SITE . ' : ' . $name;
        $result['name'] = SITE . ' : ' . $name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['grammar'] = $grammarlist;
        $result['confiden'] = $confidenlist;
        $result['intent'] = $intentlist;

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


    $params = array(
        'rec_id' => $data['rec_id'],
        'qc_status' => $data['qc_status'],
        'expec_intent' => $data['Expected'],
        'new_sentence' => $data['input_qc'],
        'remark' => $data['remark'],
        'action' => $data['action']
    );

//    PrintR($params);
//    exit;

    $url = URL_API . '/geniespeech/uploadvoicelog';
    $response = curlposttoken($url, $params, $token);

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

function LoadCbo(Request $request)
{
    global $token;
    $result['data'] = array();

    parse_str($request->getPost()->toString(), $data);

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
        } elseif ($data['menu_action'] == 'confiden') {
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
    echo json_encode($result);

}

function ViewCHNN(Request $request)
{

    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['name'] = '';


    parse_str($request->getPost()->toString(), $data);

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


    echo json_encode($result);
}

function SaveVoice(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $result['data'] = array();
    $result['columns'] = array();


    parse_str($request->getPost()->toString(), $data);

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


    echo json_encode($result);
}


switch ($switchmode) {
    case "View" :
        View($x);
        break;
    case "ViewCHNN" :
        ViewCHNN($x);
        break;
    case "ViewVOICE" :
        ViewVOICE($x);
        break;
    case "Add" :
        Add($x);
        break;
    case "Edit" :
        Edit($x);
        break;
    case "Del" :
        Del($x);
        break;
    case "LoadCbo" :
        LoadCbo($x);
        break;
    case "ViewQC" :
        ViewQC($x);
        break;
    case "SaveVoice" :
        SaveVoice($x);
        break;

    default :
        $result['success'] = 'FAIL';
        $result['msg'] = 'ไม่มีข้อมูล';
        echo json_encode($result);
        break;
}