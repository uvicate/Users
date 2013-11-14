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

	/**
	 * To be able to register a login, a keypass is created, this will be stored in the database and cookie
	 * @param string $table Tablename to verify if keypass exists
	 * @return string 60 character-long string
	 */
	private function create_key_pass($table = 'login'){
		$abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_[]{}';
		$k =  substr(str_shuffle($abc), 0, 60);

		//Verifies that it doesn't exist
		$query = $this->_fdb->from($table)->select(null)->select('keypass')->where('keypass = ?', $k);
		$data = $query->fetch();
		if(!$data){
			return $k;
		}else{
			return $this->create_key_pass();
		}
	}

	/**
	 * Saves the login in the database
	 * @param  array $time    Array generated in calculate_times()
	 * @param  string $keypass String generated in create_key_pass()
	 * @return int          data taken from PDO execute()
	 */
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

	/**
	 * Calculates the time of duration for the cookies
	 * @param  integer $plus 1 if is for calculate time in future, -1 if is going to be used to destroy cookies
	 * @return array        Time for the userid and password cookies
	 */
	private function calculate_times($plus = 1){
		$time = array();
		$time['auth'] = time() + (($GLOBALS['auth_time'] / 1000) * $plus);
		$time['pass'] = time() + (($GLOBALS['pass_time'] / 1000) * $plus);

		return $time;
	}

	/**
	 * Register user's cookies
	 * @param  string $k Keypass generated by create_key_pass()
	 * @param  array $t Array generated by calculate_times()
	 * @return null    No response
	 */
	private function register_cookies($k, $t){
		foreach ($GLOBALS['cookie_domains'] as $domain) {
			//Set the identification cookie
			setcookie($GLOBALS['auth_cookie'], $this->id, $t['auth'], "/", $domain, $GLOBALS['secure_cookie']);
			setcookie($GLOBALS['auth_cookie'], $this->id, $t['auth'], "/", "", $GLOBALS['secure_cookie']);

			//Set the keypass cookie
			setcookie($GLOBALS['pass_cookie'], $k, $t['pass'], "/", $domain, $GLOBALS['secure_cookie']);
			setcookie($GLOBALS['pass_cookie'], $k, $t['pass'], "/", "", $GLOBALS['secure_cookie']);
		}
	}

	public function is_loged($keypass){

	}
	
	/**
	 * Calls the required methods to perform a succesful login
	 * @param  string $username user's username
	 * @param  string $password user's password
	 * @return int           data returned by register_login()
	 */
	public function login($username, $password){

		if($this->exists($username, $password)){
			$t = $this->calculate_times();
			$k = $this->create_key_pass();
			$this->register_cookies($k, $t);

			return $this->register_login($t, $k);
		}
	}

	/**
	 * Method called when user manually closes session (logout button) it destroys the current cookies and deactivates the DB keypass
	 * @return null No return
	 */
	public function logout(){
		$t = $this->calculate_times(-1);
		$this->deactivate_keypass();
		$this->register_cookies("", $t);
	}

	/**
	 * When user is having login issues, this method will force a user to logout.
	 * @return null No return
	 */
	public function force_logout(){
		$this->deactivate_keypass(true);
	}

	private function deactivate_keypass($force = false){
		$key = $_COOKIE[$GLOBALS['pass_cookie']];
		if($key != ''){
			$query = $this->_fdb->update('login')->set(array('active' => 0))->where('keypass', $key);
			return $query->execute();
		}
	}

	public function forgotten_password($username){
		//Get user id
		if(!$this->id){
			$query = $this->_fdb->from('users')->select(null)->select('idUser AS id')->where('username = ?', $username);
			$data = $query->fetch();
			if(!$data){
				return false;
			}

			$this->id = $data['id'];
		}

		$data = array();

		$now = date('Y-m-d H:i:s');
		$expiracy = strtotime($now) + 60 * 60 * 2; // Add two hours
		$expiracy = date('Y-m-d H:i:s', $expiracy);
		$data['date'] = $now;
		$data['expiracy'] = $expiracy;
		$data['idUser'] = $this->id;

		$key = $this->create_key_pass('forgotten_password');
		$data['keypass'] = $key;

		$query = $this->_fdb->insertInto('forgotten_password')->values($data);
		return $query->execute();
	}

	private function build_forgotten_url($key){

	}

	private function send_forgotten_email($url){

	}

	public function validate_forgotten($id, $keypass){
		$where = array('keypass' => $keypass, 'idUser' => $id);
		$query = $this->_fdb->from('forgotten_password')->select(null)->select('keypass, expiracy, recovered')->where($where);
		$data = $query->fetch();
		if(!$data){
			return false;
		}else{
			$now = date('Y-m-d H:i:s');
			$now = strtotime($now);
			$expiracy = $data['expiracy'];
			$expiracy = strtotime($expiracy);

			if($now > $expiracy){
				return false;
			}

			if((int)$data['recovered'] === 1){
				return false;
			}

			return true;
		}
	}

	public function complete_forgotten($id, $keypass){
		$validate = $this->validate_forgotten($id, $keypass);

		if($validate){
			$query = $this->_fdb->update('forgotten_password')->set(array('recovered' => 1))->where('keypass', $keypass);
			return $query->execute();
		}
	}

	public function activate(){

	}

	public function deactivate(){

	}
}

?>