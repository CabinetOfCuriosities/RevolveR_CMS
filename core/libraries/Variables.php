<?php

/** 
  * 
  * RevolveR Variables Router
  * array structure based syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class Variables {

	public static $variables = [];

	function __construct() {

		$variables = [];

		if( $_SERVER["REQUEST_METHOD"] === "POST" ) {
			foreach ($_POST as $var => $value) {
				if( !empty($value) ) {
					self::$variables[] = [self::escape($var) => self::escape($value)];
				}
			}
		}

	}

	public static function getVars() {
		return self::$variables;
	}

	public static function escape( $var ) {

		$var = trim( $var );

		$var = htmlspecialchars($var, ENT_IGNORE, 'utf-8');
		$var = strip_tags($var);
		$var = stripslashes($var);

		return $var;

	}
}

?>