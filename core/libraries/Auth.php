<?php

/** 
  * 
  * RevolveR Authentification class
  *
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class Auth {

	public static function login($token) {
		setcookie('authorization', 1, time() + 10600, '/');
		setcookie('usertoken', $token, time() + 10600, '/');
	}

	public static function logout() {
		setcookie('authorization', 0, time() + 10600, '/');
		setcookie('user_token', '', time() + 10600, '/');
	}

}



?>