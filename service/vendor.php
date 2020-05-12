<?php
require_once __DIR__.'/../vendor/autoload.php';

use Laminas\Http\PhpEnvironment\Request as Request;
$request = new Request();

class Vendor
{
    public static $instance;

    public function __construct(){
        global $request;
        self::$instance = $request;
        return self::$instance;
    }
}



header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");




$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
