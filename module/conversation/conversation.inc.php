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
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['name'] = '';


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $params = array(
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'text_search' => $data['text_search']
    );

//    PrintR($params);
    $url = URL_API.'/geniespeech/conver';
    $response = curlposttoken($url, $params, $token);

    if ($response['result'][0]['code'] == 200) {
        $columnslist = $response['columns_name'];
        $datas = $response['recs'];
        $name = $response['report_name'];

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;


        foreach((array)$columnslist as $i => $item){
            if($item['column_data'] == 'SPOK'){
                $column[$m]['className'] = 'text-left';
            }else{
                $column[$m]['className'] = 'text-center';
            }

            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] =  ($item['column_data']);


            $columns[$i]['data'] = ($item['column_data']);

            ++$m;
        }



        foreach((array)$datas as $i => $item){
            if($item['DATE_TIME'] == 0)break;
            $datalist[$i]['no'] = ($i+1);
            foreach((array)$columns as $v => $value){
                if($value['data'] == 'LOG_FILE'){
                    $datalist[$i][$value['data']] = '<a href="'.$item[$value['data']].'" target="_blank"><i class="glyphicon glyphicon-new-window"></i></a>';
                }elseif($value['data'] == 'CHNN'){
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('."'".$item['CHNN']."',".$data['page_id'].','.$data['page_size'].",'".$data['start_date']."','".$data['end_date']."'".')"><i class="glyphicon glyphicon-volume-up"></i></a>';
//                    $datalist[$i][$value['data']] = '<audio controls><source src="'.$item[$value['data']].'" type="audio/wav"></audio>';
                }else{
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }

            }
        }




        $result['name'] = SITE.' : '.$name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['result'][0]['msg'];


    $json = json_encode($result);
}

function ViewCHNN(){
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
    $url = URL_API.'/geniespeech/logdetail';
    $response = curlposttoken($url, $params, $token);

    if ($response['result'][0]['code'] == 200) {
        $columnslist = $response['columns_name'];
        $datas = $response['recs'];
        $name = $response['report_name'];

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;


        foreach((array)$columnslist as $i => $item){

            $column[$m]['className'] = 'text-center';
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] =  ($item['column_data']);


            $columns[$i]['data'] = ($item['column_data']);

            ++$m;
        }



        foreach((array)$datas as $i => $item){
            $datalist[$i]['no'] = ($i+1);
            foreach((array)$columns as $v => $value){
                if($value['data'] == 'CHNN'){
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN('."'".str_replace('CHAN=','',$item[$value['data']])."'".')">'.$item[$value['data']].'</a>';
                }elseif($value['data'] == 'VOICE_NAME'){
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('.'"'.$item[$value['data']].'"'.')">'.$item[$value['data']].'</a>';
                }else{
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }

            }
        }




        $result['name'] = SITE.' : '.$name;
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['result'][0]['msg'];


    $json = json_encode($result);
}

function ViewVOICE(){
    global $json;
    global $token;

    $datalist = array();
    $columns = array();
    $column = array();
    $name = '';
    $result['data'] = array();
    $result['columns'] = array();
    $result['name'] = '';
    $result['chnn'] = '';


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
    $url = URL_API.'/geniespeech/logvoice';
    $response = curlposttoken($url, $params, $token);

    if ($response['result'][0]['code'] == 200) {
        $columnslist = $response['columns_name'];
        $datas = $response['recs'];
        $name = $response['report_name'];

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';

//        $column[1]['className'] = 'text-center';
//        $column[1]['title'] = '';
//        $column[1]['data'] = 'pass';


        $m = 1;


        foreach((array)$columnslist as $i => $item){
            if($item['column_data'] == 'spok'){
                $column[$m]['className'] = 'text-left';
            }else{
                $column[$m]['className'] = 'text-center';
            }

            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] =  ($item['column_data']);


            $columns[$i]['data'] = ($item['column_data']);

            ++$m;
        }



        foreach((array)$datas as $i => $item){

            $datalist[$i]['pass'] = '<input type="checkbox" name="pass" ref="'.$item['vname'].'">';
            $datalist[$i]['no'] = ($i+1);
            foreach((array)$columns as $v => $value){
                if($value['data'] == 'CHNN'){
                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenCHNN('."'".str_replace('CHAN=','',$item[$value['data']])."'".')">'.$item[$value['data']].'</a>';
                }elseif($value['data'] == 'voice_name'){
                    $datalist[$i][$value['data']] = '<audio controls><source src="'.$item[$value['data']].'" type="audio/wav"></audio>';
//                    $datalist[$i][$value['data']] = '<a href="javascript:void(0)" onclick="me.OpenVOICE('.'"'.$item[$value['data']].'"'.')">'.$item[$value['data']].'</a>';
                }else{
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }

            }
        }




        $result['name'] = SITE.' : '.$name;
        $result['chnn'] = $data['menu_action'];
        $result['columns'] = $column;
        $result['data'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['result'][0]['msg'];


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



    $url = URL_API.'/geniespeech/updatevoice';
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

    $data['expr_status'] = $data['expire_date_status'];
    $data['user_status'] = $data['active'];
    $data['expr_date'] = DateTimeFormatNew($data['expire_date']);
    $data['user_login'] = $user;

    unset($data['expire_date_status']);
    unset($data['active']);
    unset($data['code']);


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
  case "ViewCHNN" : ViewCHNN(); break;
  case "ViewVOICE" : ViewVOICE(); break;
  case "Add" : Add(); break;
  case "Edit" : Edit(); break;
  case "Del" : Del(); break;

  default :
}

echo $json;
exit;