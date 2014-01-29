<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

include_once $oauth.'cors.php';
include_once $src.'User.php';
include_once $oauth.'2/server.php';
include_once $oauth.'2/verifier.php';

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'PUT'){
	parse_str(file_get_contents("php://input"), $PUT);
}

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

		$requiredScope = 'full_profile';
		$response = $server->getResponse();
		if (!$server->verifyResourceRequest($request, $response, $requiredScope)) {
			$response = $server->getResponse();
			$response->send();

			die;
		}

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