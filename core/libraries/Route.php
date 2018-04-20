<?php

/** 
  * 
  * RevolveR Route Constructor class with 
  * array structure based syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */


// Base routes
class Route {

	function __construct($nodes) {

		$passway = self::explodeFragment( $_SERVER['REQUEST_URI'] );
		$rebase_route = '/';

		$routes = [];
		$counter = 0;

		foreach ($passway as $way) {
		
			if( $counter > 0 && self::checkFragment($way) ) $routes[] = (string)$way;
	
			$counter++;

		}

		foreach ($routes as $fk) {
			if( self::checkArguments($fk) ) {
				$rebase_route .= $fk .'/';
			}

		}

		foreach ($nodes as $r) {

			if( self::matchFragment($r['route'], $rebase_route) ) { 

				define( 'ROUTE', ['route' => $r['route'], 'node'  => $r['node']] );

			}
		}
	}

	public static function explodeFragment( $str ) {
		return explode('/', $str);
	}

	public static function checkFragment( $frg ) {
		return strlen($frg) > 0 ? true : false;
	}

	public static function checkArguments( $frg ) {
		
		preg_match('/\?/i', $frg, $m);

		return isset( $m[0] ) ? false : true;   

	}

	public static function matchFragment( $f1, $f2 ) {
		return $f1 === $f2 ? true : false;
	}

}

?>