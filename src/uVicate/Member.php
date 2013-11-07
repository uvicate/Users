<?php namespace uVicate;
include_once __DIR__.'/Object.php';

class Member extends User {

	/**
	 * Does the user exists? this function will look for it in the DB
	 * @param  string $username Username
	 * @param  string $password Password
	 * @return bool           true if the user is correct
	 */
	private function exists($username, $password){
		$query = $this->_fdb->from('users')->select(null)->select('password')->where('username = ?' $username);
		$data = $query->fetch();
		if(count($data) == 0){
			return false;
		}

		$hash = $data['password'];
		$verif2 = $this->verify_password($password);

		return $verif2;
	}
	
	public function login($username, $password){

		if($this->exists()){
			$time = time() + ($GLOBALS['auth_time'] / 1000);
			$passtime = time() + ($GLOBALS['pass_time'] / 1000);

			foreach ($GLOBALS['cookie_domains'] as $domain) {
				//Set the identification cookie
				setcookie($GLOBALS['auth_cookie'], $this->id, $time, "/", $domain, $GLOBALS['secure_cookie']);
				setcookie($GLOBALS['auth_cookie'], $this->id, $time, "/", "", $GLOBALS['secure_cookie']);

				//Set the keypass cookie
				setcookie($GLOBALS['pass_cookie'], "keypass", $passtime, "/", $domain, $GLOBALS['secure_cookie']);
				setcookie($GLOBALS['pass_cookie'], "keypass", $passtime, "/", "", $GLOBALS['secure_cookie']);
			}
		}
	}

	public function logout(){
		$time = time() - $GLOBALS['auth_time'];
		$passtime = time() - $GLOBALS['pass_time'];

		foreach ($GLOBALS['cookie_domains'] as $domain) {
			//Set the identification cookie
			setcookie($GLOBALS['auth_cookie'], "", $time, "/", $domain, $GLOBALS['secure_cookie']);
			setcookie($GLOBALS['auth_cookie'], "", $time, "/", "", $GLOBALS['secure_cookie']);	

			//Set the keypass cookie
			setcookie($GLOBALS['pass_cookie'], "", $passtime, "/", $domain, $GLOBALS['secure_cookie']);
			setcookie($GLOBALS['pass_cookie'], "", $passtime, "/", "", $GLOBALS['secure_cookie']);
		}
	}

	public function activate(){

	}

	public function deactivate(){

	}
}

?>