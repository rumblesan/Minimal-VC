<?php

ini_set("include_path", ".:". $_SERVER['DOCUMENT_ROOT'] . "/dtest");

define('BASE',  '/dtest/');
define('CPATH', 'app/controllers/');
define('VPATH', 'app/views/');


function __autoload($class_name)
{
    require_once "app/core/" . $class_name . ".class.php";
}


$controller = New Controller($_SERVER['REQUEST_URI'], BASE, CPATH);



