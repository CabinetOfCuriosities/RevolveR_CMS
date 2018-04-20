<?php

/* Connect Cipher Library */
$cipher = new Cipher();

/* Read db config file */
$dbConfig = file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/private/db_config.ini', true);

/* Check installation */
if( strlen($dbConfig) > 0 ) {
	
	define('INSTALLED', true);

	$dbx_data = explode('|', $cipher::crypt('decrypt', $dbConfig));

	$dbx = new DBX($dbx_data);


	$dbx::query('s|field_id|asc', 'revolver__users', $STRUCT_USER);
	$users = $dbx::$result['result'];
	unset($dbx::$result['result']);

	if( isset( $_COOKIE['usertoken']) ) {
		foreach( $users as $user ) {

			$token_explode = explode('|', $cipher::crypt('decrypt', $_COOKIE['usertoken']));

			if( (string)$user['field_email'] === (string)$token_explode[0] ) {
				if( (string)$user['field_password'] === (string)$token_explode[1] ) {
					
					define('ACCESS', $user['field_permissions']);

				}
			} 

		}

	} 
	else {
		define('ACCESS', 'none');
	}

} 
else {
	
	define('INSTALLED', false);

}


/* Connect authorization module */
$auth = new Auth();

	//$GLOBALS["AUTH"] = 0;
	define('CONTENTS', false);

/* Revolver Menu templater Init */
$menu = new Menu();

/* Revolver Route Init */
$route = new Route($main_nodes);

/* RevolveR Node Init */
$node = new Node();

/* RevolveR Variables */
$vars = new Variables();

$mail = new Mail();


?>