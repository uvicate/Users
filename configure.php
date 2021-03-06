<?php
$r = __DIR__.'/'; // root
$vendor = $r.'vendor/';
include_once $vendor.'autoload.php';

#Databases
$GLOBALS['oauth2_db'] = "mysql:host=localhost;dbname=oauth_users";

$GLOBALS['oauth2_db_user'] = "root";
$GLOBALS['oauth2_db_password'] = "passowrd";

$GLOBALS['user_pdo'] = new PDO($GLOBALS['oauth2_db'], $GLOBALS['oauth2_db_user'], $GLOBALS['oauth2_db_password']);
$GLOBALS['user_pdo'] -> exec("SET CHARACTER SET utf8");
$GLOBALS['user_fpdo'] = new FluentPDO($GLOBALS['user_pdo']);

#Authetication cookies
$GLOBALS['auth_cookie'] = "U"; //Cookie's name that will hold the iduser
$GLOBALS['pass_cookie'] = "U_key"; //Cookie's name that will hold the encrypted keypass (not the password)
$GLOBALS['auth_time'] = 1000 * 3600 * 24 * 365; //Time in milliseconds (default time is 365 days)
$GLOBALS['pass_time'] = 1000 * 3600 * 24 * 28; //Time in milliseconds (default time is 28 days)
$GLOBALS['cookie_domains'] = array('yourdomain');
$GLOBALS['secure_cookie'] = false;

?>