<?php
header("Content-type: application/json; charset=utf-8");
require_once "../../service/service.php";

$json='{"success":"FAIL","msg":"พบข้อผิดพลาดบางประการ"}';
$token = isset($_SESSION[OFFICE]['TOKEN'])?$_SESSION[OFFICE]['TOKEN']:'';

function buildMultiPartRequest($ch, $boundary, $fields, $files, $token) {
    $delimiter = '-------------' . $boundary;
    $data = '';

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . "\r\n"
            . 'Content-Disposition: form-data; name="' . $name . "\"\r\n\r\n"
            . $content . "\r\n";
    }
    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . "\r\n"
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $content['name'] . '"' . "\r\n\r\n"
            . $content . "\r\n";
    }

    $data .= "--" . $delimiter . "--\r\n";

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: multipart/form-data; boundary=' . $delimiter,
            'Content-Length: ' . strlen($data),
            'Authorization:'.$token
        ],
        CURLOPT_POSTFIELDS => $data
    ]);

    return $ch;
}

function curlposttokenfile($url, $params, $token)
{

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_UPLOAD, true);
    $headers = [
        'Content-Type:application/json',
        'Authorization:'.$token
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_VERBOSE,true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = array_filter(json_decode($response, true));
    return $response;
}

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
        'project_id' => $_SESSION[OFFICE]['PROJECT_ID'],
        'menu_action' => $data['menu_action'],
        'start_date' => $data['start_date'],
        'end_date' => $data['end_date'],
        'page_id' => $data['page_id'],
        'page_size' => $data['page_size'],
        'text_search' => $data['text_search']
    );

//    PrintR($params);
    $url = URL_API.'/geniespeech/grammar';
    $response = curlposttoken($url, $params, $token);

    if ($response['code'] == 200) {
        $columnslist = $response['result']['header'];
        $datas = $response['data'];
        $name = 'Upload Grammar';

        $status[0] = 'Upload Success';
        $status[1] = 'Upload Fail';
        $status[2] = 'Process File Success';
        $status[3] = 'Process File Fail';
        $status[4] = 'Build Process';
        $status[5] = 'Build Fail';
        $status[6] = 'Build Success';

        $column[0]['className'] = 'text-center';
        $column[0]['title'] = 'No';
        $column[0]['data'] = 'no';


        $m = 1;
        foreach((array)$columnslist as $i => $item){
            $column[$m]['className'] = 'text-'.$item['column_align'];
            $column[$m]['title'] = $item['column_name'];
            $column[$m]['data'] = $item['column_field'];
//            $column[$m]["DT_RowId"] = $item['column_field'];

            $columns[$m]['data'] = $item['column_field'];
            $columns[$m]['type'] = $item['column_type'];
            ++$m;
        }
        $column[$m]['className'] = 'text-center';
        $column[$m]['title'] = '';
        $column[$m]['data'] = 'btn';
//        $column[$m]["DT_RowId"] = 'row_'.$m;


        $permiss = LoadPermission();

        foreach((array)$datas as $i => $item){
            $btn = '';


            $datalist[$i]['DT_RowId'] = 'row_'.$item['project_id'].'_'.strtotime($item['date_time']);
            $datalist[$i]['no'] = ($i+1);

            foreach((array)$columns as $v => $value){
                if($value['data'] == 'status') {
                    $datalist[$i][$value['data']] = $item['message_error'];
                }else if($value['data'] == 'project_id'){
                    $datalist[$i][$value['data']] = $item['project_name'];
                }else{
                    $datalist[$i][$value['data']] = $item[$value['data']];
                }


            }
            $dataattr = array();
            $dataattr[$i] = $item;


            if($permiss[2]){
                if($item['status'] == 2){
                    $btn .= '<button data-code="'.$item['project_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' type="button" class="btn btn-xs btn-success" disabled><i class="fa fa-save"></i> Process</button>&nbsp;&nbsp;';

                }else if($item['status'] == 0){
                    $btn .= '<button data-code="'.$item['project_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' onclick="me.Process(this)" type="button" class="btn btn-xs btn-success"><i class="fa fa-save"></i> Process</button>&nbsp;&nbsp;';

                }else if($item['status'] == 4){
                    $btn .= '<button data-code="'.$item['project_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' type="button" class="btn btn-xs btn-success" disabled><i class="fa fa-save"></i> Process</button>&nbsp;&nbsp;';

                }else if($item['status'] == 6){
                    $btn .= '<button data-code="'.$item['project_id'].'" data-item='."'".json_encode($dataattr[$i],JSON_HEX_APOS)."'".' type="button" class="btn btn-xs btn-success" disabled><i class="fa fa-save"></i> Process</button>&nbsp;&nbsp;';
                }
            }
            if($permiss[1]){
                if($item['status'] == 2) {
                    $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Build(this)" type="button" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Build</button>&nbsp;&nbsp;';
                }else if($item['status'] == 0){
                    $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs btn-primary" disabled><i class="fa fa-save"></i> Build</button>&nbsp;&nbsp;';
                }else if($item['status'] == 4){
//                    $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Build(this)" type="button" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Build</button>&nbsp;&nbsp;';
                    $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs btn-primary" disabled><i class="fa fa-save"></i> Build</button>&nbsp;&nbsp;';

                }else if($item['status'] == 6){
                    $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs btn-primary" disabled><i class="fa fa-save"></i> Build</button>&nbsp;&nbsp;';

                }
            }

            if($item['status'] == 2) {
                $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs btn-default" disabled><i class="fa fa-save"></i> Download</button>&nbsp;&nbsp;';
            }else if($item['status'] == 0){
//                $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' onclick="me.Download(this)" type="button" class="btn btn-xs btn-default"><i class="fa fa-save"></i> Download</button>&nbsp;&nbsp;';
                $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs btn-default" disabled><i class="fa fa-save"></i> Download</button>&nbsp;&nbsp;';
            }else if($item['status'] == 4){
                $btn .= '<button data-code="' . $item['project_id'] . '" data-item=' . "'" . json_encode($dataattr[$i], JSON_HEX_APOS) . "'" . ' type="button" class="btn btn-xs btn-default" disabled><i class="fa fa-save"></i> Download</button>&nbsp;&nbsp;';
            }else if($item['status'] == 6){
                $filename = str_replace('/app/pyunimrcp/result/','',$item['url_patch']);
                $btn .= '<a href="' . URL_API.'/geniespeech/downloadgrammar/'.$item['result_name'] . '" class="btn btn-xs btn-default" download><i class="fa fa-save"></i> Download</a>&nbsp;&nbsp;';
//                $btn .= '<button data-code="' . $item['result_name'] . '" onclick="me.Download(this)" type="button" class="btn btn-xs btn-default"><i class="fa fa-save"></i> Download</button>&nbsp;&nbsp;';

            }

            if($permiss[3]){
                if($item['status'] == 2) {
                    $btn .= '<button  type="button" class="btn btn-xs btn-danger" disabled><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
                }else if($item['status'] == 0){
//                    $btn .= '<button onclick="me.Del('.$item['project_id'].')"  type="button" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
                    $btn .= '<button  type="button" class="btn btn-xs btn-danger" disabled><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
                }else if($item['status'] == 4){
                    $btn .= '<button  type="button" class="btn btn-xs btn-danger" disabled><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
                }else if($item['status'] == 6){
                    $btn .= '<button  type="button" class="btn btn-xs btn-danger" disabled><i class="fa fa-trash"></i> '.$permiss[3]['name'].'</button>';
                }

            }

            $datalist[$i]['btn'] = $btn;

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

function curl_custom_postfields($ch, array $assoc = array(), array $files = array()) {
    global $token;
    // invalid characters for "name" and "filename"
    static $disallow = array("\0", "\"", "\r", "\n");

    // build normal parameters
    foreach ($assoc as $k => $v) {
        $k = str_replace($disallow, "_", $k);
        $body[] = implode("\r\n", array(
            "Content-Disposition: form-data; name=\"{$k}\"",
            "",
            filter_var($v),
        ));
    }

    // build file parameters
    foreach ($files as $k => $v) {
        switch (true) {
            case false === $v = realpath(filter_var($v)):
            case !is_file($v):
            case !is_readable($v):
                continue; // or return false, throw new InvalidArgumentException
        }
        $data = file_get_contents($v);
        $v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
        $k = str_replace($disallow, "_", $k);
        $v = str_replace($disallow, "_", $v);
        $body[] = implode("\r\n", array(
            "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$k}\"",
            "Content-Type: application/octet-stream",
            "",
            $data,
        ));
    }

    // generate safe boundary
    do {
        $boundary = "---------------------" . md5(mt_rand() . microtime());
    } while (preg_grep("/{$boundary}/", $body));

    // add boundary for each parameters
    array_walk($body, function (&$part) use ($boundary) {
        $part = "--{$boundary}\r\n{$part}";
    });

    // add final boundary
    $body[] = "--{$boundary}--";
    $body[] = "";

    // set options

    curl_setopt_array($ch, array(
        CURLOPT_POST       => true,
        CURLOPT_POSTFIELDS => implode("\r\n", $body),
        CURLOPT_HTTPHEADER => array(
            "Expect: 100-continue",
//            'Content-Type:application/json',
            "Content-Type: multipart/form-data; boundary={$boundary}",
            'Authorization:'.$token
        ),
    ));
    curl_exec($ch);
    curl_close($ch);
   // $response = json_decode($response, true);
   // return $response;
}

function Add(){
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();


    $file = $_FILES['file'];

    $data = $_POST;
    $data['user_login'] = $user;
//    $data = json_encode($data);
//    $data['file_name'] =  new CURLFile(realpath($_FILES['file']['tmp_name']));
//    $data['file_name'] = '@' . realpath($_FILES['file']['tmp_name']) . ';filename='.$_FILES['file']['name']. ';type='.$_FILES['file']['type'];
    $data2[$_FILES['file']['name']] = $_FILES['file']['tmp_name'];

    $url = URL_API.'/geniespeech/grammarupload';
    $ch = curl_init ($url);
    $result = curl_custom_postfields($ch,$data,$data2);

//    if ($result['code'] == 200) {
//        $result['msg'] = 'Upload Success';
//        $result['success'] = 'COMPLETE';
//    } else {
//        $result['success'] = 'FAIL';
//        $result['msg'] = 'Upload Fail';
//    }
//    $result['msg'] = $response['msg'];
    exit;
    $result = array();
    $json = json_encode($result);
}

function build_data_files($boundary, $fields, $files){
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }


    foreach ($files as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
            //. 'Content-Type: image/png'.$eol
            . 'Content-Transfer-Encoding: binary'.$eol
        ;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
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
//    $result['data'] = array();
//    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data[$data['main']] = $data['code'];
    unset($data['code']);
    unset($data['main']);

//    PrintR($data);
//    exit;

    $url = URL_API.'/geniespeech/grammar';
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

function AddGrammar(){
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
//    $result['data'] = array();
//    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data['user_login'] = $user;

    $url = URL_API.'/geniespeech/grammarbuildgrammar';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Process(){
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
//    $result['data'] = array();
//    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data['user_login'] = $user;

    $url = URL_API.'/geniespeech/grammarprocessfile';
    $response = curlposttoken($url, $data, $token);

    if ($response['code'] == 200) {
        $result['success'] = 'COMPLETE';
    } else {
        $result['success'] = 'FAIL';
    }
    $result['msg'] = $response['msg'];


    $json = json_encode($result);
}

function Download(){
    global $json;
    global $token;
    $user = $_SESSION[OFFICE]['DATA']['user_name'];
    $datalist = array();
    $columns = array();
    $column = array();
//    $result['data'] = array();
//    $result['columns'] = array();


    $str = file_get_contents("php://input");
    parse_str($str, $data);

    $data['user_login'] = $user;

    $url = URL_API.'/geniespeech/download';
    $response = curlposttoken($url, $data, $token);




    $json = json_encode($result);
}


switch($_REQUEST["mode"]){
  case "View" : View(); break;
  case "ViewCHNN" : ViewCHNN(); break;
  case "ViewVOICE" : ViewVOICE(); break;
  case "Add" : Add(); break;
  case "AddGrammar" : AddGrammar(); break;
  case "Process" : Process(); break;
  case "Download" : Download(); break;
  case "Edit" : Edit(); break;
  case "Del" : Del(); break;

  default :
}

echo $json;
exit;