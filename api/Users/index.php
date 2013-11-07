<?php
$root = __DIR__.'/../../';
$src = $root.'src/';
require $src.'User.php';

$user = new User();

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
	default:
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
	//$usr = "<p>Sorry, this method is not available, please read the manuals of this API</p>";
}

echo $usr;

?>