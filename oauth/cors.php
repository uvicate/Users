<?php
header('Access-Control-Allow-Origin: *');
if($_SERVER['REQUEST_METHOD'] == "OPTIONS"){
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: content-type, authorization');
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');

	exit();
}
?>