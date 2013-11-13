<?php namespace uVicate;
include_once __DIR__.'/Object.php';
//include_once __DIR__.'/User.php';

class Member extends User {

	/**
	 * Does the user exists? this function will look for it in the DB
	 * @param  string $username Username
	 * @param  string $password Password
	 * @return bool           true if the user is correct
	 */
	private function exists($username, $password){
		$query = $this->_fdb->from('users')->select(null)->select('idUser AS id, password')->where('username = ?', $username);
		$data = $query->fetch();
		if(!$data){
			return false;
		}

		$id = $data['id'];
		$this->id = $id;

		$hash = $data['password'];
		$verif2 = $this->verify_password($password, $hash);

		return $verif2;
	}

	private function create_key_pass(){
		$abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#$/.,;@&%![]{}';
		$k =  substr(str_shuffle($abc), 0, 60);

		//Verifies that it doesn't exist
		$query = $this->_fdb->from('login')->select(null)->select('keypass')->where('keypass = ?', $k);
		$data = $query->fetch();
		if(!$data){
			return $k;
		}else{
			return $this->create_key_pass();
		}
	}

	private function register_login($time, $keypass){
		$now = date('Y-m-d H:i:s');
		$data = array(
			'idUser' => $this->id,
			'date' => $now,
			'expiracy' => date('Y-m-d H:i:s', $time['pass']),
			'keypass' => $keypass,
			'active' => 1
			);

		$query = $this->_fdb->insertInto('login')->values($data);
		return $query->execute();
	}

	private function calculate_times($plus = 1){
		$time = array();
		$time['auth'] = time() + (($GLOBALS['auth_time'] / 1000) * $plus);
		$time['pass'] = time() + (($GLOBALS['pass_time'] / 1000) * $plus);

		return $time;
	}

	public function is_loged($keypass){

	}
	
	public function login($username, $password){

		if($this->exists($username, $password)){
			$t = $this->calculate_times();
			$k = $this->create_key_pass();

			foreach ($GLOBALS['cookie_domains'] as $domain) {
				//Set the identification cookie
				setcookie($GLOBALS['auth_cookie'], $this->id, $t['auth'], "/", $domain, $GLOBALS['secure_cookie']);
				setcookie($GLOBALS['auth_cookie'], $this->id, $t['auth'], "/", "", $GLOBALS['secure_cookie']);

				//Set the keypass cookie
				setcookie($GLOBALS['pass_cookie'], $k, $t['pass'], "/", $domain, $GLOBALS['secure_cookie']);
				setcookie($GLOBALS['pass_cookie'], $k, $t['pass'], "/", "", $GLOBALS['secure_cookie']);
			}

			return $this->register_login($t, $k);
		}
	}

	public function logout(){
		$t = $this->calculate_times(-1);

		$key = $_COOKIE[$GLOBALS['pass_cookie']];

		if($key != ''){
			$query = $this->_fdb->update('login')->set(array('active' => 0))->where('keypass', $key);
		}

		foreach ($GLOBALS['cookie_domains'] as $domain) {
			//Set the identification cookie
			setcookie($GLOBALS['auth_cookie'], "", $t['auth'], "/", $domain, $GLOBALS['secure_cookie']);
			setcookie($GLOBALS['auth_cookie'], "", $t['auth'], "/", "", $GLOBALS['secure_cookie']);	

			//Set the keypass cookie
			setcookie($GLOBALS['pass_cookie'], "", $t['pass'], "/", $domain, $GLOBALS['secure_cookie']);
			setcookie($GLOBALS['pass_cookie'], "", $t['pass'], "/", "", $GLOBALS['secure_cookie']);
		}

		if($query){
			return $query->execute();
		}
	}

	public function activate(){

	}

	public function deactivate(){

	}
}

?>