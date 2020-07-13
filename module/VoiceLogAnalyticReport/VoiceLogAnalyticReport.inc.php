<?php
require_once "../../service/service.php";
require_once "../../service/vendor.php";

function View(Request $request)
{

    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['datafooter'] = array();
    $result['name'] = '';


    parse_str($request->getPost()->toString(), $data);

    $params = array(
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'report_name' => $data['menu_action'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'user_login' => $user
    );

//    PrintR($params);
    $url = URL_API . '/geniespeech/report';
    $response = curlposttoken($url, $params, $token);

    if ($response['result'][0]['code'] == 200) {
        $response['column_name'][0]['column_data'] = 'Intent';
        $response['column_name'][1]['column_data'] = 'Pass%';
        $response['column_name'][2]['column_data'] = 'Pass';
        $response['column_name'][3]['column_data'] = 'Fail';
        $response['column_name'][4]['column_data'] = 'Garbage';
        $response['column_name'][5]['column_data'] = 'Other';
        $response['column_name'][6]['column_data'] = 'Valid';
        $response['column_name'][7]['column_data'] = 'Totalcall';

        $columnslist = $response['column_name'];
        $datas = $response['recs'];
        $data_footer = $response['grand_total'];
        $name = $response['report_name:'];

        $pipechart = $response['pipechart'];
        $barchart = $response['barchart'];

        $mydata = '<table class="table">';
        $mydata .= '<tbody>';
        $mydata .= '<tr>';
        $mydata .= '<td width="50%" align="right"><b>Customer</b> :</td><td align="left">' . $response['Customer'] . '</td>';
        $mydata .= '</tr>';
        $mydata .= '<tr>';
        $mydata .= '<td align="right"><b>Project</b> :</td><td align="left">' . $response['Project'] . '</td>';
        $mydata .= '</tr>';
        $mydata .= '<tr>';
        $mydata .= '<td align="right"><b>Test Start Date</b> :</td><td align="left">' . $response['Test_Start_Date'] . '</td>';
        $mydata .= '</tr>';
        $mydata .= '<tr>';
        $mydata .= '<td align="right"><b>Report Date</b> :</td><td align="left">' . $response['Report_Date'] . '</td>';
        $mydata .= '</tr>';
        $mydata .= '<tr>';
        $mydata .= '<td align="right"><b>Summary</b> :</td><td align="left">' . $response['Summary'][0]['Total_calls'] . ' Total Calls</td>';
        $mydata .= '</tr>';
        $mydata .= '<tr>';
        $mydata .= '<td></td><td align="left">' . $response['Summary'][1]['Valid_calls'] . ' Valid calls</td>';
        $mydata .= '</tr>';
        $mydata .= '<tr>';
        $mydata .= '<td></td><td align="left">' . $response['Summary'][2]['Passed_calls'] . ' Passed calls</td>';
        $mydata .= '</tr>';

        $mydata .= '</tbody>';
        $mydata .= '</table>';
        $mydata .= '<div class="alert alert-success text-center"><p style="font-size: 30px;">' . $response['Summary'][3]['Accuary'] . ' Accuary</p></div>';


        $barcolor[0] = 'window.chartColors.red';
        $barcolor[1] = 'window.chartColors.orange';
        $barcolor[2] = 'window.chartColors.yellow';
        $barcolor[3] = 'window.chartColors.green';
        $barcolor[4] = 'window.chartColors.blue';
//        $barcolor[5] = '#d2d6de';
        $n = 0;
        foreach ((array)$pipechart as $i => $item) {
            if ($i == 0) {
                $pipecharts['capture'] = $item['caption'];
            } else {
                foreach ((array)$item as $m => $items) {
                    $pipecharts['label'][$n] = $m;

//                    $pipecharts['data'][$n]['x'] = $m;
                    $pipecharts['data'][$n]['label'] = $m;
//                    $pipecharts['data'][$n]['indexLabel'] = $m;
                    $pipecharts['data'][$n]['name'] = $items[0]['call'];
                    $pipecharts['data'][$n]['y'] = $items[1]['%'];
                    $pipecharts['color'][$n] = $barcolor[$n];

                    ++$n;
                }
            }
        }

        $n = 0;
        foreach ((array)$barchart as $i => $item) {
            if ($i == 0) {
                $barcharts['capture'] = $item['caption'];
            } else {
                foreach ((array)$item as $m => $items) {
                    $barcharts['label'][$n] = $m;

                    $barcharts['data'][$n]['label'] = $m;
                    $barcharts['data'][$n]['y'] = $items;
                    ++$n;
                }
            }
        }

//        PrintR($barcharts);
//        exit;


        $m = 0;
        $z = 0;
        $newfooter = array();
        foreach ((array)$data_footer as $i => $item) {
            foreach ((array)$item as $v => $item2) {
                $newfooter[$z] = $item2;
                ++$z;
            }

        }

//        array_push($datas,$newfooter);

//        foreach((array)$data_footer as $i => $item){
//            foreach((array)$columns as $v => $value){
//                $datalist_footer[$i][$value['data']] = $item[$value['data']];
//
//            }
//        }

        foreach ((array)$columnslist as $i => $item) {
            $column[$m]['className'] = 'text-center';
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_data'];

            $columns[$m]['data'] = $item['column_data'];
            $columns[$m]['type'] = '';
            ++$m;
        }

        $count = 1;
        foreach ((array)$datas as $i => $item) {
            $count += $i;
            $z = 0;
            foreach ((array)$columns as $v => $value) {
                $datalist[$i][$value['data']] = $item[$value['data']];
                $datalists[$i][$z] = $item[$value['data']];
            ++$z;
            }

        }

        $datalists[($count+1)] = $newfooter;

        $result['name'] = SITE . ' : ' . $name;
        $result['columns'] = $column;
        $result['datafooter'] = $newfooter;
        $result['pipechart'] = $pipecharts;
        $result['barchart'] = $barcharts;
        $result['text'] = $mydata;
        $result['data'] = $datalist;
        $result['datatest'] = $datalists;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['result'][0]['msg'];


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

    $data['expr_status'] = $data['expire_date_status'];
    $data['user_status'] = $data['active'];
    $data['expr_date'] = DateTimeFormatNew($data['expire_date']);
    $data['user_login'] = $user;

    unset($data['expire_date_status']);
    unset($data['active']);
    unset($data['code']);
    unset($data['user_id']);


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
    case "Del" :
        Del($x);
        break;

    default :
        $result['success'] = 'FAIL';
        $result['msg'] = 'ไม่มีข้อมูล';
        echo json_encode($result);
        break;
}