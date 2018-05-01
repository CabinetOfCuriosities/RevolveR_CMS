<?php

header('Content-Type: application/rss+xml; charset=utf-8');

// Config
require_once('./private/config.php');
require_once('./core/struct/DataBase.php');
require_once('./core/libraries/DBX.php');
require_once('./core/libraries/Cipher.php');
require_once('./core/libraries/SafeHTML.php');
require_once('./core/libraries/Variables.php');

/* Connect Cipher & Captcha libraries */
$cipher = new Cipher();

/* Read db config file */
$dbConfig = file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/private/db_config.ini', true);

/* Check installation */
if( strlen($dbConfig) > 0 ) {
	
	define('INSTALLED', true);

	$dbx_data = explode('|', $cipher::crypt('decrypt', $dbConfig));
	$dbx = new DBX($dbx_data);

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

		}
	}

} 
else {
	define('INSTALLED', false);
}

/* Safe HTML module Init */
$safe = new SafeHTML();

/* RevolveR Variables */
$vars = new Variables();

$rss = '<?xml version="1.0" encoding="UTF-8" ?>'. "\r\n";
$rss .= '<rss version="2.0">'. "\r\n";
$rss .= '<channel>' . "\r\n";
$rss .= ' <title>'. BRAND .'</title>'. "\r\n";
$rss .= ' <description>'. DESCRIPTION .'</description>'. "\r\n";
$rss .= ' <link>http://'. $_SERVER['HTTP_HOST'] .'/</link>'. "\r\n" . "\r\n";

$dbx::query('s|field_id|asc|10', 'revolver__nodes', $STRUCT_NODES);

if( isset($dbx::$result['result']) ) {
	foreach($dbx::$result['result'] as $node => $n) {

			$rss .= ' <item>'. "\r\n";
			
			if( !empty($n['field_title']) ) {
				$rss .= '  <title>'.  $n['field_title'] .'</title>'. "\r\n";
			}

			if( !empty($n['field_description']) ) {
				$rss .= '  <description>'. $n['field_description'] .'</description>'. "\r\n";
			}

			if(!empty($n['field_route'])) {
				$rss .= '  <link>http://'. $_SERVER['HTTP_HOST'] . $n['field_route'] .'</link>'. "\r\n";
			} 


			if(!empty($n['field_route'])) {

				$date = explode('/', $n['field_time']);
				$date = new DateTime( $date[2] .'-'. $date[1] .'-'. $date[0] );

				$rss .= '  <pubDate>'. $date->format(DateTime::RFC822) .'</pubDate>'. "\r\n";
			}  

			$rss .= ' </item>'. "\r\n". "\r\n";

	}
}

$rss .= '</channel>'. "\r\n";
$rss .= '</rss>';

print $rss;

?>