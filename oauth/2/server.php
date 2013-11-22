<?php
$r = __DIR__.'/../../';
include_once $r.'configure.php';

$dsn      = $GLOBALS['oauth2_db_oauth'];
$username = $GLOBALS['oauth2_db_user'];
$password = $GLOBALS['oauth2_db_password'];

// error reporting (this is a demo, after all!)
//ini_set('display_errors',1);error_reporting(E_ALL);

OAuth2\Autoloader::register();

$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

// Pass a storage object or array of storage objects to the OAuth2 server class
$config = array('allow_implicit' => true);
$server = new OAuth2\Server($storage, $config);

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

// Add the "Authorization Code" grant type (this is where the oauth magic happens)
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

?>