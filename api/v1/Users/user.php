<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
include_once $src.'User.php';
include_once $root.'oauth/2/server.php';

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'PUT'){
	parse_str(file_get_contents("php://input"), $PUT);
}

// Handle a request for an OAuth2.0 Access Token and send the response to the client
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
	$server->getResponse()->send();
	header("HTTP/1.0 401 Unauthorized", TRUE, 404);

	die;
}

use uVicate;

$user = new \uVicate\User;

$success = false;

switch($method){
	case 'GET':
		$validkeys = array(
			'id' => '', 
			'basic' => '', 
			'name'=> '', 
			'fullname' => '', 
			'email' => '', 
			'phone' => ''
		);

		//Get all information
		//---------------------
		foreach ($validkeys as $key => $value) {
			if(array_key_exists($key, $_GET)){

				$param = null;
				if($key != 'id'){
					$param = array($key);
				}

				$id = $_GET[$key];
				$usr = $user->getby_id($id, $param);
				$usr = $user->handleResponse($usr);

				$success = true;
				break;
			}
		}				
	break;
	case 'PUT':
		//Update information
		//---------------------
		$id = $_GET['id'];
		$arr = array('id' => $id);
		$user = new \uVicate\User($arr);
		$usr = $user->edit($PUT);

		$success = true;
	break;
	case 'DELETE':
		$id = $_GET['id'];
		$arr = array('id' => $id);
		$user = new \uVicate\User($arr);
		$usr = $user->delete();

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