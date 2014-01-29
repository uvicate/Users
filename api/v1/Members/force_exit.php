<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

include_once $oauth.'cors.php';
include_once $src.'Member.php';

$method = $_SERVER['REQUEST_METHOD'];
$success = false;
switch($method){
	case 'DELETE':
		$email = $_GET['email'];
		$arr = array('email' => $email);
		$member = new \uVicate\Member($arr);
		$member->force_logout();
	break;
	default:
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

?>