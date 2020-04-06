<?php
/*================================================*\
*  Author : OCS
*  Created Date : 29/07/14 13:27
*  Module : Office
*  Description : Office
*  Involve People : Boy
*  Last Updated : 29/07/14 13:27
\*================================================*/

define("CREATION","Creation Group");					

define("OFFICE", "OFFICE_DEV77");

define("TITLE", "");
define("DESCRIPTION", "");
define("KEYWORDS", "");
define("ROBOTS", "noindex, nofollow");
define("COPYRIGHT", "Online creation soft");
define("AUTHOR", "Creation");

/* :: Log Admin :: */
define('LOGADD', '1');
define('LOGEDIT', '2');
define('LOGDEL', '3');
define('LOGLOAD', '4');
define('LOGLOGIN', '5');
define('LOGLOGOUT', '6');

define('ADD', '1');
define('EDIT', '2');
define('DEL', '3');
define('PRINT', '4');

/* :: http://creation.ctdserver.com :: */
$c_site = "SITE.txt";
$c_site_open = fopen($c_site, 'r');
if ($c_site_open) {
    $c_site_file = fgets($c_site_open, 4096);
}else{
    $c_site_file = 'TESTER';
}
fclose($c_site_open);
$c_url = "URL.txt";
$c_url_open = fopen($c_url, 'r');
if ($c_url_open) {
    $c_file = fgets($c_url_open, 4096);
}else{
    $c_file = 'https://gsb.devtool77.com';
}
fclose($c_url_open);
$c_api = "URL_API.txt";
$c_urlapi_open = fopen($c_api, 'r');
if ($c_urlapi_open) {
    $c_api_file = fgets($c_urlapi_open, 4096);
}else{
    $c_api_file = 'https://wso2ei.snapz.mobi';
}
fclose($c_urlapi_open);


define("SITE", $c_site_file);
define("URL", $c_file);
define("BASEURL", $c_file);
define("URL_API", $c_api_file);
//define("URL", "http://localhost");
//define("BASEURL", "http://localhost");
//define("URL_API", "http://localhost/api");
define('FILEPATH', $_SERVER['DOCUMENT_ROOT']);
define("DEBUG", false);
define("FRIENDLY_URL", true);
define("FRIENDLY_ADMIN", true);
define("FRIENDLY_API", true);
define("TYPEURL", "php");
define("SENDMAIL", true);
?>