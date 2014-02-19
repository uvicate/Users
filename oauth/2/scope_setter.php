<?php
$root = __DIR__.'/../../';
include_once $root.'oauth/2/server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

//Adjusting allowed scopes
$default_scope = 'basic_profile';

$oauth_fpdo = $GLOBALS['oauth_fpdo'];

$accestoken = $server->getAccessTokenData($request);
if($accestoken == null){
	$accestoken = $request->getAllQueryParameters();
}

$query = $oauth_fpdo->from('oauth_clients AS a')->leftJoin('oauth_client_type_scopes AS b ON b.id_client_type=a.id_client_type')->leftJoin('oauth_scopes AS c ON c.id_oauth_scope=b.id_oauth_scope')->select(null)->select('c.scope')->where("a.client_id = ?", $accestoken['client_id']);

$client_scopes_raw = $query->fetchAll();
$client_scopes = array();

foreach ($client_scopes_raw as $key => $scope) {
	$client_scopes[] = $scope['scope'];
}

$scopes_memory['supported_scopes'] = $client_scopes;
$memory = new OAuth2\Storage\Memory($scopes_memory);

$scopeUtil = new OAuth2\Scope($memory);

$server->setScopeUtil($scopeUtil);

?>