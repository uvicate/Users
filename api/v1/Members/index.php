<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
include_once $src.'Member.php';

use uVicate;

$member = new \uVicate\Member;

$method = $_SERVER['REQUEST_METHOD'];
$success = false;

$response;
switch($method){
	case 'POST':
		$response = $member->login($_POST['username'], $_POST['password']);
		$success = true;
	break;
	case 'DELETE':
		$member->logout();
		$success = true;
	break;
	default:
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

echo $response;

?>