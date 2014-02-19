<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

// include our OAuth2 Server object
require_once __DIR__.'/server.php';
$member = new \uVicate\Member;

$id = null;
if(array_key_exists($GLOBALS['auth_cookie'], $_COOKIE)){
  $id = $_COOKIE[$GLOBALS['auth_cookie']];  
}

$key = null;
if(array_key_exists($GLOBALS['pass_cookie'], $_COOKIE)){
  $key = $_COOKIE[$GLOBALS['pass_cookie']];
}

$accesseduser = $member->verify_credentials($id, $key);
$accesseduser = json_decode($accesseduser, true);

if (array_key_exists('password', $_POST)) {
	$accesseduser = $member->login($_POST['username'], $_POST['password']);
	$accesseduser = json_decode($accesseduser, true);
}

//Loads the login
if(!$accesseduser['success']){
	$dom = file_get_contents(__DIR__.'/../authorize/login.html');
  $initial = 'login';
  $dom = str_replace("#initial_file#", $initial, $dom);
	echo $dom;

	exit();
}

include_once __DIR__.'/scope_setter.php';


// validate the authorize request
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die;
}

// display an authorization form
if (!array_key_exists('authorized', $_POST)) {
  $dom = file_get_contents(__DIR__.'/../authorize/authorize.html');

  //Include the scopes
  $raw_scopes = $request->query['scope'];
  $dom = str_replace("#scopes#", $raw_scopes, $dom);

  //Inclide the client
  $raw_client = $request->query('client_id');
  $dom = str_replace("#client#", $raw_client, $dom);

  echo $dom;
  exit();
}

// print the authorization code if the user has authorized your client
$is_authorized = ($_POST['authorized'] === 'Authorize') ? true : false;
$server->handleAuthorizeRequest($request, $response, $is_authorized, $id);
$code = '';
if ($is_authorized === true) {
  // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
}else{
  $code = $response->getHttpHeader('Location');
}

header( 'Location: http://'.$code ) ;

$response->send();

?>