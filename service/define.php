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
define("SITE", "GENIE");
$c_url = "config/URL.txt";
$c_url_open = fopen($c_url, 'r');
if ($c_url_open) {
    $c_file = fgets($c_url_open, 4096);
}
fclose($c_url_open);
define("URL", $c_file);
define("BASEURL", $c_file);
define("URL_API", "https://wso2ei.snapz.mobi");
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