<?php namespace uVicate;

$r = __DIR__.'/../../'; // root
require_once $r.'configure.php';

abstract class Object {

	protected $_db;
	protected $_fdb;
	protected $_xmlnode = '<object/>';
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
		return $this->{$prop};
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

	public function handleResponse($data){
		$content = $_SERVER['HTTP_CONTENT_TYPE'];

		$response;
		switch ($content) {
			case 'application/xml':
				header('Content-type: application/xml');
				/*$xml = new SimpleXMLElement("<?xml version=\"1.0\"?><object></object>");*/
				$xml = new SimpleXMLElement($this->_xmlnode);
				$data = array_flip($data);
				array_walk_recursive($data, array ($xml, 'addChild'));
				$response = $xml->asXML();
			break;
			case 'application/json':
			case 'application/x-javascript':
				header('Content-type: application/json');
				$response = json_encode($data);
			break;
			default:
				$response = json_encode($data);
			break;
		}

		return $response;
	}
	
	protected function newData($data){
		foreach ($data as $key => $value) {
			$this->{$key} = $value;
		}
	}

	abstract protected function initdb();
}


?>