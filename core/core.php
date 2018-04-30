<?php

/* Rebase routes */
if( $_SERVER['REQUEST_URI'] === '/index.php' ) {
	header('Location: '. site_host . '');
}

/* Connect Cipher & Captcha libraries */
$captcha = new Captcha();
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
					
					define('USER', [
						'name'  => $user['field_nickname'],
						'email' => $user['field_email'],
						'id'	=> $user['field_id']
					]);

					define('ACCESS', $user['field_permissions']);

				}
			} 

		}

	} 
	else {
		define('ACCESS', 'none');
	}

	$passway = $_SERVER['REQUEST_URI'];
	$not_found = true;

	$dbx::query('s|field_id|asc', 'revolver__nodes', $STRUCT_NODES);

	if( isset($dbx::$result['result']) ) {
		foreach($dbx::$result['result'] as $node => $n) {

			if( $passway === $n['field_route'] ) {			
				if( !empty($n['field_title']) ) {
					define('TITLE', $n['field_title']);
				}

				if( !empty($n['field_description']) ) {
					define('DESCRIPTION', $n['field_description']);
				}

				if(!empty($n['field_id'])) {
					define('NODE_ID', $n['field_id']);
				} 

				$not_found = false;

			} 
			else if( $passway !== '/' ) {

				foreach (main_nodes as $key) {

					if( $key['route'] === $passway ) {
					
						$not_found = false;
					
					} 
				}
			}
		}

		$dbx::query('s|field_id|asc', 'revolver__settings', $STRUCT_SITE);

		if( isset( $dbx::$result['result'] ) ) {
			foreach ($dbx::$result['result'] as $k => $v) {

				if( !empty($v['field_site_brand']) ) {
					define('BRAND', $v['field_site_brand']);
				}

				if( !empty($v['field_site_title']) ) {
					define('TITLE', $v['field_site_title']);
				}

				if( !empty($v['field_site_description']) ) {
					define('DESCRIPTION', $v['field_site_description']);
				}

				if( !empty($v['field_site_skin']) ) {
					define('SKIN', $v['field_site_skin']);
				}

			}
		}

	} 
} 
else {
	define('INSTALLED', false);
	define('SKIN', 'revolver core template');
}

/* Connect authorization module */
$auth = new Auth();

/* Safe HTML module Init */
$safe = new SafeHTML();

/* Revolver Menu templater Init */
$menu = new Menu();

/* Revolver Route Init */
$route = new Route(main_nodes);

/* RevolveR Node Init */
$node = new Node();

/* RevolveR Variables */
$vars = new Variables();

$mail = new Mail();

// render site
if( $not_found &&  $_SERVER['REQUEST_URI'] !== '/' ) {

	$edit_path_check = explode('/', $_SERVER['REQUEST_URI']);

	if( $edit_path_check[ count($edit_path_check) - 2 ] === 'edit' ) {

		require_once('./skins/'. SKIN .'/index.php');

	} 
	else {
	
		require_once( './skins/'. SKIN .'/404.php');
	
	}

} 
else {

	require_once('./skins/'. SKIN .'/index.php');

}


?>