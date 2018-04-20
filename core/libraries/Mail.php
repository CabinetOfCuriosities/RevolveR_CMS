<?php
/** 
  * 
  * RevolveR Mailing class
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class Mail {

    public static $status;

    public static function send($to, $title, $message) {

        $from = self::escape('zenposter@gmail.com');
        $to = self::escape($to);

        $title   = self::escape($title);
        $message = self::escape($message);


        if( self::checkEmail($from) && self::checkEmail($to) ) {
            
            mail($to, $title, $message, "From:". $from);
            self::$status = 'false';
       
        } 
        else {

            self::$status = 'true';

        }

    }

    public static function checkEmail($email) {
        
        if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $email)) {
            return false;
        } 
        else {
            return $email;
        }

    }

    public static function escape($str) {
        return substr(htmlspecialchars(trim($str)), 0, 100000); 
    }

}

?>
 