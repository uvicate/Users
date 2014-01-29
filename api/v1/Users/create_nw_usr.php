<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

include_once $oauth.'cors.php';
include_once $src.'User.php';
include_once $oauth.'2/server.php';
include_once $oauth.'2/verifier.php';

$user = new \uVicate\User;

$method = $_SERVER['REQUEST_METHOD'];

$success = false;
$usr;

switch($method){
	case 'POST':
		$usr = $user->create($_POST);
		$success = true;
	break;
	default:
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
	//$usr = "<p>Sorry, this method is not available, please read the manuals of this API</p>";
}

echo $usr;

?>