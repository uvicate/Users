<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';
$oauth = $root.'oauth/';

include_once $oauth.'cors.php';
include_once $src.'Member.php';

$member = new \uVicate\Member;

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'PUT'){
	parse_str(file_get_contents("php://input"), $PUT);
}

$success = false;

$mem = '';
switch($method){
	case 'POST':
		if(!array_key_exists('username', $_GET)){
			break;
		}

		$mem = $member->forgotten_password($_GET['username']);
		$success = true;
	break;
	case 'GET':
		$mem = $member->validate_forgotten($_GET['id'], $_GET['key']);

		if(!$mem){
		}else{
			$success = true;
		}
	break;
	case 'PUT':
		//Update information
		//---------------------
		$id = $_GET['id'];
		$key = $_GET['key'];

		$arr = array('id' => $id);
		$user = new \uVicate\User($arr);
		$edit = array();
		if(array_key_exists('password', $PUT)){
			$validate = $member->complete_forgotten($id, $key);

			if($validate !== false){
				$edit['password'] = $PUT['password'];
				$mem = $user->edit($edit);

				$success = true;
			}
		}
	break;
}

if($success === false){
	header("HTTP/1.0 404 Not Found", TRUE, 404);
}

echo $mem;

?>