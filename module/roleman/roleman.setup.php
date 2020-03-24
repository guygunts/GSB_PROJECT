<?php
$token = isset($_SESSION[OFFICE]['TOKEN'])?$_SESSION[OFFICE]['TOKEN']:'';
$data = array();
$params = array(
    'menu_action' => 'getmenus',
    'page_id' => 1,
    'page_size' => 1000
);
$url = URL_API.'/geniespeech/adminmenu';
$response = curlposttoken($url, $params, $token);
//PrintR($response);
if ($response['code'] == 200) {
    $data = $response['result']['data'];

//    $_SESSION[OFFICE]['ROLE'][0]['menus'] = $data;
    $menu =  $_SESSION[OFFICE]['ROLE'][0]['menus'];
}



$data = array();
$params = array(
    'menu_action' => 'getfunctions',
    'page_id' => 1,
    'page_size' => 1000
);
$url = URL_API.'/geniespeech/adminmenu';
$response = curlposttoken($url, $params, $token);
if ($response['code'] == 200) {
    $data = $response['result']['data'];
//    $_SESSION[OFFICE]['ROLE'][0]['function'] = $data;
    $permission = $_SESSION[OFFICE]['ROLE'][0]['function'];
}

