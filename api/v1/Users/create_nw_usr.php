<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
include_once $src.'User.php';
include_once $root.'oauth/2/server.php';

// Handle a request for an OAuth2.0 Access Token and send the response to the client
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
	$server->getResponse()->send();
	header("HTTP/1.0 401 Unauthorized", TRUE, 404);

	die;
}

use uVicate;

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