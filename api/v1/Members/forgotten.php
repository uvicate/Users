<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
include_once $src.'Member.php';

use uVicate;

$member = new \uVicate\Member;

$method = $_SERVER['REQUEST_METHOD'];
$success = false;

$mem = '';
switch($method){
	case 'POST':
		if(!array_key_exists('username', $_GET)){
			break;
		}

		$mem = $member->forgotten_password($_GET['username']);
		$success = true;
	break;
	case 'GET':
		$mem = $member->validate_forgotten($_GET['id'], $_GET['key']);
		$success = true;
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

echo $mem;

?>