<?php
$r = __DIR__.'/'; // root
$vendor = $r.'vendor/';
include_once $vendor.'autoload.php';

$GLOBALS['user_pdo'] = new PDO("mysql:host=localhost;dbname=users", "root", "mnkDazul}mysql");
$GLOBALS['user_fpdo'] = new FluentPDO($GLOBALS['user_pdo']);

?>