<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

// include our OAuth2 Server object
require_once __DIR__.'/server.php';
include_once $src.'Member.php';

use uVicate;

$member = new \uVicate\Member;
$id = $_COOKIE[$GLOBALS['auth_cookie']];
$key = $_COOKIE[$GLOBALS['pass_cookie']];

$accesseduser = $member->verify_credentials($id, $key);
$accesseduser = json_decode($accesseduser, true);

if (array_key_exists('password', $_POST)) {
	$accesseduser = $member->login($_POST['username'], $_POST['password']);
	$accesseduser = json_decode($accesseduser, true);
}

if(!$accesseduser['success']){
	$dom = file_get_contents(__DIR__.'/../authorize/login.html');
	echo $dom;

	exit();
}

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();


// validate the authorize request
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    die;
}
// display an authorization form
if (!array_key_exists('authorized', $_POST)) {
  exit('
<form method="post">
  <label>Do You Authorize TestClient?</label><br />
  <input type="submit" name="authorized" value="yes">
  <input type="submit" name="authorized" value="no">
</form>');
}

// print the authorization code if the user has authorized your client
$is_authorized = ($_POST['authorized'] === 'yes');
$server->handleAuthorizeRequest($request, $response, $is_authorized);
if ($is_authorized) {
  // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
  $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
  
  header( 'Location: http://'.$code ) ;
}

$response->send();

?>