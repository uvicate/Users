<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
include_once $src.'User.php';

use uVicate;

$users = new \uVicate\Users;

$method = $_SERVER['REQUEST_METHOD'];

$success = false;

$usrs;
switch($method){
	case 'GET':
		$validkeys = array(
			'basic' => '', 
			'name'=> '', 
			'fullname' => ''
		);

		if(count($_GET) === 0){
			$_GET['fullname'] = 1;
		}

		//Get all information
		//---------------------
		foreach ($validkeys as $key => $value) {
			if(array_key_exists($key, $_GET)){
				$param = array($key);

				$usrs = $users->get_all($param);
				$usrs = $users->handleResponse($usrs);

				$success = true;
				break;
			}
		}				
	break;
	default:
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
	//$usr = "<p>Sorry, this method is not available, please read the manuals of this API</p>";
}

echo $usrs;

?>