<?php

$root = __DIR__.'/../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

include_once $oauth.'2/server.php';
include_once __DIR__.'/scope_setter.php';

// Handle a request for an OAuth2.0 Access Token and send the response to the client
if (!$server->verifyResourceRequest($request)) {
	$response = $server->getResponse();
	$response->send();
	die;
}

?>