<?php
require_once __DIR__.'/../vendor/autoload.php';

use Laminas\Http\Request;

$request = new Request();

$token = isset($_SESSION[OFFICE]['TOKEN']) ? $_SESSION[OFFICE]['TOKEN'] : '';
