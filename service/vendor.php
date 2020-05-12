<?php
require __DIR__.'/../vendor/autoload.php';

use Laminas\Http\PhpEnvironment\Request as MainRequest;

$moderequest = new MainRequest();
$switchmode = $moderequest->getQuery('mode');

interface Request
{
    public function foo();

}

class MyRequest extends MainRequest implements Request
{
    public $request;

    public function foo()
    {
        $request = new MainRequest();
        $this->request = $request;
    }
}

$x = new MyRequest();



header("Access-Control-Allow-Origin: ".URL." ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");




$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';