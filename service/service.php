<?php
ini_set('session.cookie_httponly', 1);
session_start();
require_once "define.php";
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Origin: " . URL);
header("X-Frame-Options: DENY");
header("Set-Cookie: id=a3fWa; Expires=Wed, 21 Oct 2015 07:28:00 GMT; Secure; HttpOnly");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net d3js.org canvasjs.com cdn.datatables.net cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' 'style-src-elem' cdn.datatables.net cdnjs.cloudflare.com fonts.googleapis.com; font-src 'self' fonts.gstatic.com; img-src * data:");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: same-origin");
header("Feature-Policy: vibrate 'self'; usermedia *; sync-xhr 'self'");
header("Strict-Transport-Security: max-age=631138519; includeSubDomains");
header("Content-Type: text/html; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: origin, x-requested-with, content-type, authorization, access-control-allow-headers");
require_once "func.php";

