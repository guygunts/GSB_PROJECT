<?php
require_once __DIR__.'/../vendor/autoload.php';

use Laminas\Http\PhpEnvironment\Request as MainRequest;
$request = new MainRequest();


interface Request
{
    public function foo();
}

class MyRequest extends MainRequest implements Request
{
    protected $request;

    public function foo(): self
    {
        $this->request = new MainRequest();
        return $this;
    }

}



header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");




$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
