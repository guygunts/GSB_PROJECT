<?php
require_once __DIR__.'/../vendor/autoload.php';

//use Laminas\Http\PhpEnvironment\Request;
//use Laminas\EventManager\EventInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


Request::setFactory(function (
    array $query = [],
    array $request = [],
    array $attributes = [],
    array $cookies = [],
    array $files = [],
    array $server = [],
    $content = null
);

$request = Request::createFromGlobals();
$request->getPathInfo();
$request->duplicate();
echo $request->query->get('mode');
exit;

$request = new Request();

$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
