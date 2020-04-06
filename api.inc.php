<?php
session_start();
header("Content-type: application/json; charset=utf-8");
require_once "service/service.php";


$json = '{"success":"FAIL","msg":"Something Fail"}';
$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';


function Login()
{

    global $json;

    $str = file_get_contents("php://input");
    parse_str($str, $data);
    $data['lang'] = 'th';
    unset($_SESSION[OFFICE]);

    $_SESSION[OFFICE]['LOGIN'] = 'OFF';
    $_SESSION[OFFICE]['TOKEN'] = '';
    $_SESSION[OFFICE]['DATA'] = array();
    $_SESSION[OFFICE]['ROLE'] = array();
    $_SESSION[OFFICE]['LANG'] = 'th';

    $params = array(

        'user_name' => $data['username'],
        'password' => $data['password'],
        'lang' => $data['lang'],
        'authen_type' => 1

    );

    $url = URL_API . '/geniespeech/login';
    $response = curlpost($url, $params);


    if ($response['code'] == 200) {

        $_SESSION[OFFICE]['LOGIN'] = 'ON';
        $_SESSION[OFFICE]['TOKEN'] = $response['token'];
        $_SESSION[OFFICE]['DATA'] = $response['result']['profile'];
        $_SESSION[OFFICE]['DATA']['user_name'] = $data['username'];
        $_SESSION[OFFICE]['DATA']['password'] = $data['password'];
        $_SESSION[OFFICE]['ROLE'] = $response['result']['roles'];
        $_SESSION[OFFICE]['LANG'] = $data['lang'];

        if ($response['result']['roles'][0]['menus'][0]['sub_menu'][0]['sub_menu_path']) {
            $result['menu'] = $response['result']['roles'][0]['menus'][0]['sub_menu'][0]['sub_menu_path'];
        } else {
            $result['menu'] = $response['result']['roles'][0]['menus'][0]['menu_path'];
        }

        $result['success'] = 'COMPLETE';


    } else {

        $result['success'] = 'FAIL';

    }

//    PrintR($_SESSION);
//    exit;

    $result['msg'] = $response['msg'];
    $json = json_encode($result);

}

function LoadData()

{

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

    $url = URL_API . '/geniespeech/adminmenu';


    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {

        $columnslist = $response['result']['header'];

        $datas = $response['result']['data'];

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = '#';
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


            $item['user_role'] = $item['role']['role_name'];


            $datalist[$i]['no'] = ($i + 1);
            $dataattr = array();

            foreach ((array)$columns as $v => $value) {

                $datalist[$i][$value['data']] = $item[$value['data']];
                $dataattr[$i][$value['data']] = $item[$value['data']];

            }


            if ($permiss[2]) {
                $btn .= '<button data-item="' . json_encode($dataattr[$i]) . '" onclick="me.Load(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> ' . $permiss[2]['name'] . '</button>&nbsp;&nbsp;';
            }

            if ($permiss[3]) {
                $btn .= '<button type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> ' . $permiss[3]['name'] . '</button>';
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


function LoadCbo()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);


    $params = array(

        'menu_action' => $data['menu_action'],
        'page_id' => 1,
        'page_size' => 100

    );

    $url = URL_API . '/geniespeech/adminmenu';

    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {

        $columnslist = $response['result']['header'];
        $datas = $response['result']['data'];


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

function LoadCboMain()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);


    $params = array(

        'menu_action' => $data['menu_action'],
        'page_id' => 1,
        'page_size' => 100

    );

    $url = URL_API . '/geniespeech/adminmenu';

    $response = curlposttoken($url, $params, $token);


    if ($response['code'] == 200) {

        $columnslist = $response['result']['header'];
        $datas = $response['result']['data'];


        foreach ((array)$datas as $i => $item) {
            if ($i == 0) {
                if(empty($_SESSION[OFFICE]['PROJECT_ID'])){
                    $_SESSION[OFFICE]['PROJECT_ID'] = $item[$data['code']];
                }

            }
            $datalist[$i]['code'] = $item[$data['code']];
            $datalist[$i]['name'] = $item[$data['name']];

        }


        $result['project_id'] = $_SESSION[OFFICE]['PROJECT_ID'];
        $result['item'] = $datalist;
        $result['success'] = 'COMPLETE';

    } else {
        $result['success'] = 'FAIL';
    }

    $result['msg'] = $response['msg'];
    $json = json_encode($result);

}

function ChangeTop()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);


    $_SESSION[OFFICE]['PROJECT_ID'] = $data['code'];
    $result['code'] = $data['code'];
    $json = json_encode($result);

}

function SaveSite()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $strFileName = "service/SITE.txt";
    $objFopen = fopen($strFileName, 'w');

    fwrite($objFopen, $data['name']);
    if($objFopen)
    {
        $result['success'] = 'COMPLETE';
        $result['msg'] = 'COMPLETE';
    }

    fclose($objFopen);

    $json = json_encode($result);

}

function SaveUrl()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $strFileName = "service/URL.txt";
    $objFopen = fopen($strFileName, 'w');

    fwrite($objFopen, $data['name']);
    if($objFopen)
    {
        $result['success'] = 'COMPLETE';
        $result['msg'] = 'COMPLETE';
    }

    fclose($objFopen);

    $json = json_encode($result);

}

function SaveUrlApi()

{

    global $json;
    global $token;

    $result['data'] = array();

    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $strFileName = "service/URL_API.txt";
    $objFopen = fopen($strFileName, 'w');

    fwrite($objFopen, $data['name']);
    if($objFopen)
    {
        $result['success'] = 'COMPLETE';
        $result['msg'] = 'COMPLETE';
    }

    fclose($objFopen);

    $json = json_encode($result);

}

function SaveLogo()

{

    global $json;
    global $token;

    $result['data'] = array();


    $target = 'logo.png';
    $file_tmp = $_FILES['name']['tmp_name'];
    if(move_uploaded_file($file_tmp, "images/" . $target)){
        $result['success'] = 'COMPLETE';
        $result['msg'] = 'COMPLETE';
    }

    $json = json_encode($result);

}

switch ($_REQUEST["mode"]) {

    case strtoupper(md5('api_login')) :
        Login();
        break;

    case strtoupper(md5('api_loaddata')) :
        LoadData();
        break;

    case strtoupper(md5('api_loadcbo')) :
        LoadCbo();
        break;

    case strtoupper(md5('api_loadcbomain')) :
        LoadCboMain();
        break;

    case strtoupper(md5('api_changetop')) :
        ChangeTop();
        break;

    case strtoupper(md5('api_savesite')) :
        SaveSite();
        break;

    case strtoupper(md5('api_saveurl')) :
        SaveUrl();
        break;

    case strtoupper(md5('api_saveurlapi')) :
        SaveUrlApi();
        break;

    case strtoupper(md5('api_savelogo')) :
        SaveLogo();
        break;

    default :
        $json = '{"success":"FAIL","msg":"พบข้อผิดพลาดบางประการ"}';

}

echo $json;
exit;

