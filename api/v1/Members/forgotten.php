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
		if(!array_key_exists('username', $_POST)){
			break;
		}

		$mem = $member->forgotten_password($_POST['username']);
		$success = true;
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

echo $mem;

?>