<?php

/** 
  * 
  * RevolveR Captcha Class
  * .........................version 0.5
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class Captcha {
 	public static $patterns = [
		'0:0:0|0:25:0|0:50:0|0:75:0|1:0:25|1:25:25|0:50:25|0:75:25|1:0:50|1:25:50|0:50:50|0:75:50|0:0:75|0:25:75|0:50:75|0:75:75',
		'0:0:0|0:25:0|0:50:0|0:75:0|0:0:25|1:25:25|0:50:25|0:75:25|0:0:50|1:25:50|1:50:50|0:75:50|0:0:75|0:25:75|0:50:75|0:75:75',
		'0:0:0|0:25:0|0:50:0|0:75:0|0:0:25|1:25:25|1:50:25|0:75:25|0:0:50|1:25:50|1:50:50|0:75:50|0:0:75|0:25:75|0:50:75|0:75:75',
		'0:0:0|0:25:0|0:50:0|0:75:0|0:0:25|1:25:25|1:50:25|0:75:25|0:0:50|0:25:50|1:50:50|0:75:50|0:0:75|0:25:75|0:50:75|0:75:75',
		'0:0:0|0:25:0|0:50:0|1:75:0|0:0:25|1:25:25|0:50:25|0:75:25|0:0:50|0:25:50|1:50:50|0:75:50|1:0:75|0:25:75|0:50:75|0:75:75',
		'1:0:0|0:25:0|0:50:0|1:75:0|0:0:25|0:25:25|1:50:25|0:75:25|0:0:50|1:25:50|0:50:50|0:75:50|1:0:75|0:25:75|0:50:75|1:75:75',
		'1:0:0|0:25:0|0:50:0|1:75:0|0:0:25|1:25:25|1:50:25|0:75:25|0:0:50|1:25:50|1:50:50|0:75:50|1:0:75|0:25:75|0:50:75|1:75:75',
		'1:0:0|0:25:0|0:50:0|1:75:0|0:0:25|0:25:25|0:50:25|0:75:25|0:0:50|0:25:50|0:50:50|0:75:50|1:0:75|0:25:75|0:50:75|1:75:75',
		'1:0:0|0:25:0|0:50:0|1:75:0|0:0:25|0:25:25|0:50:25|0:75:25|0:0:50|0:25:50|0:50:50|0:75:50|1:0:75|0:25:75|0:50:75|1:75:75',
		'0:0:0|1:25:0|1:50:0|0:75:0|0:0:25|0:25:25|0:50:25|0:75:25|0:0:50|0:25:50|0:50:50|0:75:50|0:0:75|1:25:75|1:50:75|0:75:75',
 	];

 	public static function generate() {

 		$pattern = array_rand(self::$patterns, 1);
 		return $pattern.'*'. self::$patterns[$pattern];

 	}

 	public static function check( $val1, $id ) {
 		
 		return $val1 === self::$patterns[$id] ? true : false; 
 	
 	}

}

?>