<?php

/** 
  * 
  * RevolveR safe HTML Class
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */


class SafeHTML {
	public static $allowed_1 = '<h1><h2><h3><h4><h5><h6><strong><em><img><figure><figcaption><table><th><tr><td><thead><tfoot><tbody><ul><ol><li><a><b><i><code><pre><p><br><dl><dt><dd><s><u>';
	public static $allowed_2 = array('src','alt','title','data','style', 'class', 'id');

	public static function safe($string) {

	    preg_match_all( "/<([^\/]\w*)>/", $string, $tags );

	    for ( $i = count( $tags[1] ) - 1; $i >= 0; $i-- ) {
	        
	        $tag = $tags[1][$i];

	        if ( substr_count( $string, "</$tag>" ) < substr_count( $string, "<$tag>" ) ) {
	            $string .= "</$tag>";
	        }

	    } 

	    return self::stripAttrs( strip_tags( $string, self::$allowed_1 ) );
	}

	public static function stripAttrs($string) {

		if (preg_match_all("/<[^>]*\\s([^>]*)\\/*>/msiU", $string, $res, PREG_SET_ORDER)) {
			foreach ($res as $r) {
				
				$tag = $r[0];
				$attrs = array();
				
				preg_match_all("/\\s.*=(['\"]).*\\1/msiU", " " . $r[1], $split, PREG_SET_ORDER);
				
				foreach ($split as $spl) {
					$attrs[] = $spl[0];
				}
				
				$newattrs = array();
				
				foreach ($attrs as $a) {
					$tmp = explode("=", $a);
					if (trim($a) != "" && (!isset($tmp[1]) || (trim($tmp[0]) != "" && !in_array(strtolower(trim($tmp[0])), self::$allowed_2 )))) {

					} 
					else {
						$newattrs[] = self::protectXSS($a);
					}
				}
				
				$attrs = implode(" ", $newattrs);
				$rpl = str_replace($r[1], $attrs, $tag);
				$string = str_replace($tag, $rpl, $string);
			}
		}
		
		return $string;

	}

	public static function protectXSS($string) {

		$removes = [
			'javascript:',
			'JAVASCRIPT:',
			'JavaScript:'
		];

		foreach( $removes as $r ) {
			$string = str_replace('javascript:', '' , $string);
		}

		return $string;

	}
}


?>