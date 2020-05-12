<?php
require_once __DIR__.'/../vendor/autoload.php';

use Laminas\Http\PhpEnvironment\Request as Requests;
use Laminas\Http\Request as Request;
$request = new Requests();

class Vendor
{
    public function __construct(){

        if ($_GET) {
            $this->setQuery(new Parameters($_GET));
        }
        if ($_POST) {
            $this->setPost(new Parameters($_POST));
        }
        if ($_COOKIE) {
            $this->setCookies(new Parameters($_COOKIE));
        }
        if ($_FILES) {
            // convert PHP $_FILES superglobal
            $files = $this->mapPhpFiles();
            $this->setFiles(new Parameters($files));
        }
    }
}



header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");




$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
