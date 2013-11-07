<?php
$r = __DIR__.'/../'; // root
require_once $r.'configure.php';

abstract class Object {

	protected $_db;
	protected $_fdb;
	protected $_err = array('err' => 'err-mainserver', 'success' => false);

	public function __construct(){
		$this->initdb();
	}
	
	public function __destruct() {
		
	}
	
	public function __toString() {
		return var_export(get_object_vars($this));
	}
	
	public function __set($prop, $valor){
			 $this->{$prop} = $valor;
	}
	
	public function __get($prop){
		if(property_exists('Object', $prop)){
			return $this->{$prop};
		}		
	}
	
	public function getData($hidden = false){
		//Se agregan datos extras.
		$p = get_object_vars($this);
		if($hidden === false){
			foreach ($p as $key => $value) {
				preg_match_all('(^_)', $key, $e);
				if(count($e[0]) > 0){
					unset($p[$key]);
				}
			}
		}

		$p = $this->handleResponse($p);
		
		return $p;
	}

	protected function handleResponse($data){
		$content = $_SERVER['HTTP_CONTENT_TYPE'];

		$response;
		switch ($content) {
			case 'application/xml':
				$xml = new SimpleXMLElement('<User/>');
				$data = array_flip($data);
				array_walk_recursive($data, array ($xml, 'addChild'));
				$response = $xml->asXML();
			break;
			default:
			case 'application/json':
			case 'application/x-javascript':
				$response = json_encode($data);
			break;
		}

		return $response;
	}
	
	protected function newData($datos){
		foreach ($datos as $key => $value) {
			$this->{$key} = $value;
		}
	}

	abstract protected function initdb();
}


?>