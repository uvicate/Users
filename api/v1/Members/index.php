<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

include_once $oauth.'cors.php';
include_once $oauth.'2/server.php';
include_once $src.'Member.php';
$member = new \uVicate\Member;


$method = $_SERVER['REQUEST_METHOD'];
$success = false;

$resp;
switch($method){
	case 'GET':
		if(array_key_exists($GLOBALS['auth_cookie'], $_COOKIE)){
			$id = $_COOKIE[$GLOBALS['auth_cookie']];
			$key = $_COOKIE[$GLOBALS['pass_cookie']];
			$resp = $member->verify_credentials($id, $key);
		}else{
			include_once $oauth.'2/scope_setter.php';
			$r = $server->verifyResourceRequest($request);
			$re = array('success' => $r);

			$t = $server->getAccessTokenData($request);
			if(array_key_exists('user_id', $t)){
				$re['user_id'] = $t['user_id'];
			}

			$resp = json_encode($re);
		}
		$success = true;
	break;
	case 'POST':
		$resp = $member->login($_POST['username'], $_POST['password']);
		$success = true;
	break;
	case 'DELETE':
		$member->logout();

		$t = $server->getAccessTokenData($request);
		$token = $t['0d1030265298206fb518115fea43e1873621cadc'];

		$query = $oauth_fpdo->deleteFrom('oauth_access_tokens')->where('acces_token', $token);
		$query->execute();

		$success = true;
	break;
	default:
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

echo $resp;

?>