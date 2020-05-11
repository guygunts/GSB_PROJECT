<?php
require_once __DIR__.'/../vendor/autoload.php';

//use Laminas\Http\PhpEnvironment\Request;
//use Laminas\EventManager\EventInterface;
//use App\Http\SpecialRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//header("Access-Control-Allow-Origin: * ");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$request = new Request(
    $_GET,
    $_POST,
    [],
    $_COOKIE,
    $_FILES,
    $_SERVER
);

$request->overrideGlobals();
//$request->getPathInfo();
//$request->duplicate();
echo $request->getPathInfo();
exit;

$request = new Request();

$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
