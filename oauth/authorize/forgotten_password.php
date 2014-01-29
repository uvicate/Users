<?php
$root = __DIR__.'/../../../';
$src = $root.'src/uVicate/';

// include our OAuth2 Server object
require_once __DIR__.'/../2/server.php';

$member = new \uVicate\Member;
$verify = $member->validate_forgotten($_GET['id'], $_GET['key']);

$initial = 'error';
if($verify === true){
	$initial = 'change_password';
}

$dom = file_get_contents(__DIR__.'/../authorize/login.html');

$dom = str_replace('#forgotten_key#', $_GET['key'], $dom);
$dom = str_replace('#id#', $_GET['id'], $dom);
$dom = str_replace("#initial_file#", $initial, $dom);

echo $dom;

?>