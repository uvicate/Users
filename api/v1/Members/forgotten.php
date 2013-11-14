<?php

$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
include_once $src.'Member.php';

use uVicate;

$method = $_SERVER['REQUEST_METHOD'];
$success = false;

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

?>