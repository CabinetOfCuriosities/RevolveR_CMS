<?php

/** 
  * 
  * Secure Route
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

require_once('./core/libraries/Captcha.php');
$captcha = new Captcha();

print json_encode(array('key' => $captcha::generate()));


?>

