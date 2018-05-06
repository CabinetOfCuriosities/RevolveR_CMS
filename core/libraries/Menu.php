<?php

/** 
  * 
  * RevolveR Menu Constructor class with 
  * array structure based syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */


class Menu {
	
	public static function render($nodes, $wrap_style) {

		$output = '';

		switch ($wrap_style) {
			case 'ul':
				
				$style = 'list';
				$list_wrap = "ul";
				$list_item_wrap = "li";

				break;

			case 'span': 

				$style =  'plain';
				$list_wrap = null;
				$list_item_wrap = 'span';

				break;
		}

		$output = '';

		foreach ($nodes as $link => $val) {

			$attr = '';
			$wrp_class = null;

			if( isset($val['param_check']['isAdmin']) ) {
				if( (int)$val['param_check']['isAdmin'] === 1 && ACCESS === 'Admin') {  
					$val['param_check']['menu'] = 1;
				} 
				else {
					$val['param_check']['menu'] = 0;
				}
			}

			if( (int)$val['param_check']['menu'] === 1 ) {
				
				foreach ($val as $x => $t) {
					if( $x !== 'param_check' || $x !== 'id' || $x !== 'node') {

						if( $x === 'route' ) {
							$x = 'href';
						}

						if( $x === 'class' ) {
							$wrp_class = self::attr($x, $t);
						} 
						else {
							if( $x !== 'node' ) {
								if( $x !== 'id' ) {
									if( $x !== 'param_check' ) {
										$attr .= self::attr($x, $t);
									}
								}
							}
						}
					}
				}
			
				if( (int)$val['param_check']['auth'] === (int)$_COOKIE['authorization'] || !isset( $val['param_check']['auth']) ) {
					$output .= self::wrap('<a itemprop="url" '. $attr .'>'. $link .'</a>', [$list_item_wrap, $wrp_class]);
				}

			}

		}

		if( $style === 'list' ) {
			$output = self::wrap($output, [$list_wrap, null]);
		}

		// render out
		print $output;

	}

	public static function attr($a, $v) {
		return $a .'="'. $v .'" ';
	}

	public static function wrap($str, $wrp) {
		return isset( $wrp[1] ) ? '<'. $wrp[0] .' '. $wrp[1] .'>'. $str .'</'. $wrp[0] .'>' : '<'. $wrp[0] .'>'. $str .'</'. $wrp[0] .'>';
	}

}

?>