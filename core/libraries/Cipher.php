<?php

/** 
  * 
  * RevolveR Cipher Class
  * for encrypt end decrypt data
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class Cipher {
	
	public static function crypt($method, $data) {
		
		$key = file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/private/key.ini', true);

		/* Check installation */
		if( strlen($key) > 0 ) {
			$secret_key = $key . 'ReVoLvEr#x346!@*';
		}

		$output = false;
		$encrypt_method = 'AES-256-CBC';
		$secret_iv = '!IV@_$2';

		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		switch( $method ) {
			case 'encrypt': 

				$output = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
				$output = base64_encode($output);
				
				break;
			case 'decrypt':

				$output = openssl_decrypt(base64_decode($data), $encrypt_method, $key, 0, $iv);
			
				break;
		}

		return $output;

	}
}

?>