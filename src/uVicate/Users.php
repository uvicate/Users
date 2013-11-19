<?php namespace uVicate;
include_once __DIR__.'/Object.php';
include_once __DIR__.'/User.php';

class Users extends Object {
	protected $users;
	
	public function __construct($data = null){
		parent::__construct();

		$this->newData($data);
	}

	protected function initdb(){
		$this->_db = $GLOBALS['user_pdo'];
		$this->_fdb = $GLOBALS['user_fpdo'];
	}

	protected function get_valid_keys(){
		$keys = array('name' => '', 'fullname' => '', 'basic' => '');
	}

	protected function get_ids(){
		$query = $this->_fdb->from('users')->select(null)->select('idUser AS id');

		return $query;
	}

	protected function fetch_ids($query, array $where = null){
		if($where != null && count($where > 0)){
			$query->where($where);
		}

		return $query->fetchAll();
	}

	protected function generate_users($data, $params){
		$length = count($data);

		$users = array();
		for($i = 0; $i < $length; $i++){
			$id = $data[$i]['id'];
			$user = new \uVicate\User;
			$users[] = $user->getby_id($id, $params);
		}

		return $users;
	}

	public function get_all($params){
		$query = $this->get_ids();
		$data = $this->fetch_ids($query);

		return $this->generate_users($data, $params);
	}

	public function search($key, $params){

	}
}

?>