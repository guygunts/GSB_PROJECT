<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Laminas\Http\PhpEnvironment\Request as MainRequest;

$request = new MainRequest();


interface Request
{
    public function __construct();
}

class MyRequest extends MainRequest implements Request
{
    protected $request;

    public function __construct()
    {
        $this->request = new MainRequest();
    }

}


header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
