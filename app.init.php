<?php

/* ==================================================
 *  Author : Tirapant Tongpann
 *  Created Date : 11/09/2554 01:30
 *  Module :
 *  Description :
 *  Involve People : -
 *  Last Updated : 11/09/2554 01:30
  ================================================== */
if($_SESSION[OFFICE]["LOGIN"] != "ON"){
    echo PleaseLogin(URL.'/app.logout.php');
    exit;
}

//$_SESSION[OFFICE]['LANG'] = 'th';
if(empty($_SESSION[OFFICE]['LANG'])){
    $lang='th';
    $_SESSION[OFFICE]['LANG']='th';
}else{
    $lang=$_SESSION[OFFICE]['LANG'];
}

$mod= filter_input(INPUT_GET, 'mode');


$member = $_SESSION[OFFICE]['DATA'];

$params = array(
    'user_name' => $member['user_name'],
    'password' => $member['password'],
    'lang' => $lang,
    'authen_type' => 1
);

//PrintR($params);
//exit;

$url = 'https://wso2ei.snapz.mobi/geniespeech/login';
$response = curlpost($url, $params);
//PrintR($response);
//exit;
if ($response['code'] == 200) {

    $_SESSION[OFFICE]['TOKEN'] = $response['token'];
    $_SESSION[OFFICE]['DATA'] = $response['result']['profile'];
    $_SESSION[OFFICE]['ROLE'] = $response['result']['roles'];
    $_SESSION[OFFICE]['DATA']['user_name'] = $member['user_name'];
    $_SESSION[OFFICE]['DATA']['password'] = $member['password'];
    $_SESSION[OFFICE]['LANG'] = $lang;

}



//PrintR($member);

//$url = 'https://wso2ei.snapz.mobi/geniespeech/adminmenu';
//$response = curlposttoken($url, $params, $token);
//
//if ($response['code'] == 200) {
//    $_SESSION[OFFICE]['ROLE'][0]['menus'] = $response['result']['data'];
//}
//PrintR($_SESSION[OFFICE]['ROLE'][0]['menus']);
$menu =  $_SESSION[OFFICE]['ROLE'][0]['menus'];
$permission = $_SESSION[OFFICE]['ROLE'][0]['function'];
foreach((array)$permission as $i => $item){
    $permiss[$item['function_id']]['id'] = $item['function_id'];
    $permiss[$item['function_id']]['name'] = $item['function_name'];
}

if(is_file("module/$mod/$mod.setup.php")){
    include_once "module/$mod/$mod.setup.php";
}