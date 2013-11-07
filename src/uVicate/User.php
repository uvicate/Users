<?php namespace uVicate;
include_once __DIR__.'/Object.php';

class User extends Object {
	protected $id;
	protected $username;
	protected $_password;
	protected $_supporteddata = array('all', 'basic', 'emails', 'password');
	
	public function __construct($data = null){
		parent::__construct();

		$this->newData($data);
	}

	/**
	 * Defines databases to use:
	 * _db is a regular PHP's PDO
	 * _fdb is a fluentPDO Object
	 * @return null No response
	 */
	protected function initdb(){
		$this->_db = $GLOBALS['user_pdo'];
		$this->_fdb = $GLOBALS['user_fpdo'];
	}

	/**
	 * Generates a basic query for users
	 * @return fluentPDO Returns the query before where
	 */
	private function genericSearch(){
		$query = $this->_fdb->from('users')->select(null)->select('idUser AS id');
		return $query;
	}

	/**
	 * Using the "genericSearch" method it appends a "where clause" to search for an id
	 * @param  int $id   idUser
	 * @param  array $data user parameters to get (all, basic, address, phones, emails)
	 * @return array       user data obtained
	 */
	public function getby_id($id, $params = null){
		$query = $this->genericSearch();
		$query = $query->where('idUser = ?', $id);
		
		return $this->fetchGeneric($query, $params);
	}

	public function getby_username($username, $params = null){
		$query = $this->genericSearch();
		$query = $query->where('username = ?', $username);

		return $this->fetchGeneric($query, $params);
	}

	private function fetchGeneric($query, $params = null){
		$params = ($params == null) ? array('all') : $params;

		$data = $query->fetch();
		$d = $this->fetchPrivateData($data, $params);

		$this->newData($d);

		$d = $this->handleResponse($d);

		return $d;
	}

	private function fetchPrivateData($data, $params = null){

		//Generates all the possible parameters
		if($params === array('all')){
			$params = array();
			$methods = get_class_methods($this);
			$reg = '(^fetch_([a-z]+))';
			foreach ($methods as $method) {
				preg_match_all($reg, $method, $matches);
				if(count($matches[0]) > 0){
					$params[] = $matches[1][0];
				}
			}
		}

		$banned = array();

		foreach ($params as $key) {
			$func = 'fetch_'.$key;
			if(method_exists($this, $func) && array_key_exists($key, $banned) === false){
				$data[$key] = $this->$func();
			}
		}

		return $data;
	}

	private function fetch_basic(){
		$query = $this->_fdb->from('users')->select(null)->select('username, name, lastname');
		$data = $query->fetch();

		return $data;
	}

	private function fetch_name(){
		$query = $this->_fdb->from('users')->select(null)->select('name, name2');
		$data = $query->fetch();

		$name = '';
		foreach ($data as $key => $value) {
			$name .= $value.' ';
		}
		$name = substr($name, 0, -1);

		return $name;
	}

	private function fetch_fullname(){
		$query = $this->_fdb->from('users')->select(null)->select('lastname, lastname2');
		$data = $query->fetch();

		$name = '';
		foreach ($data as $key => $value) {
			$name .= $value.' ';
		}
		$name = substr($name, 0, -1);

		$name = $this->fetch_name() .' '.$name;

		return $name;
	}

	protected function verify_password($password){
		return password_verify($password, $hash);
	}

	protected function encrypt_passwd($password){
		return password_hash($password);
	}

	public function setData($toset){

	}

	public function create(){

	}

	public function delete(){

	}

	public function getName(){
		return $this->fetch_name();
	}

	public function getFullName(){
		return $this->fetch_fullname();
	}
}

?>