<?php

$root = __DIR__.'/../../';
$src = $root.'src/uVicate/';
include_once $src.'User.php';

use uVicate;

$user = new \uVicate\User;

$method = $_SERVER['REQUEST_METHOD'];

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
		parse_str(file_get_contents("php://input"), $PUT);
		$user = new \uVicate\User($arr);
		$user->edit($PUT);

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