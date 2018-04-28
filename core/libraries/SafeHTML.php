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
	public static $allowed_1 = '<h1><h2><h3><h4><h5><h6><strong><em><img><figure><figcaption><table><th><tr><td><thead><tfoot><a><b><i><code><pre><p><br><dl><dt><dd><s><u>';
	public static function safe($string) {

	    preg_match_all( "/<([^\/]\w*)>/", $string, $tags );

	    for ( $i = count( $tags[1] ) - 1; $i >= 0; $i-- ) {
	        
	        $tag = $tags[1][$i];

	        if ( substr_count( $string, "</$tag>" ) < substr_count( $string, "<$tag>" ) ) {
	            $string .= "</$tag>";
	        }

	    } return strip_tags( $string, self::$allowed_1 );
	}
}


?>