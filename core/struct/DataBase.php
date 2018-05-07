<?php

/** 
  * 
  * RevolveR DataBase Structure
  * array structure based syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

$STRUCT_SITE = [
	'field_id' => [
		'type'   => 'num', // varchar
		'auto'	 => true,
		'length' => 255,
		'fill'   => true,
		'value'  => 0
	],
	'field_site_brand' => [
		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true
	],
	'field_site_title' => [
		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true
	],
	'field_site_description' => [
		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true
	],
	'field_site_skin' => [
		'type'	 => 'text',
		'length' => 50,
		'fill'   => true
	],
	'field_site_sidebar_left' => [
		'type'	 => 'num',
		'length' => 1,
		'fill'   => true
	],
	'field_site_sidebar_right' => [
		'type'	 => 'num',
		'length' => 1,
		'fill'   => true
	]
];

$STRUCT_FILES = [
	'field_id' => [
		'type'   => 'num', // varchar
		'auto'	 => true,
		'length' => 255,
		'fill'   => true,
		'value'  => 0
	],
	'field_name' => [
		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true
	],
	'field_node' => [
		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true
	]
]; 

$STRUCT_CATEGORIES = [
	'field_id' => [
		'type'   => 'num', // varchar
		'auto'	 => true,
		'length' => 255,
		'fill'   => true,
		'value'  => 0
	],
	'field_title' => [
		'type'   => 'text', // varchar
		'length' => 500,
		'fill'   => true
	],
	'field_description' => [
		'type'   => 'text', // varchar
		'length' => 2500,
		'fill'   => true
	]
];

$STRUCT_USER = [
	'field_id' => [
		'type'   => 'num', // int
		'auto'   => true,  // auto increment
		'length' => 255,
		'value'  => 0
	],
	'field_nickname' => [
		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true
	],
	'field_email' => [
		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true
	],
	'field_password' => [
		'type'   => 'text', // varchar
		'length' => 150,
		'fill'   => true
	],
	'field_permissions' => [
		'type'   => 'text',
		'length' => 20,
		'fill'	 => true
	],
	'field_avatar' => [
		'type'   => 'text',
		'length' => 255,
		'fill'	 => true
	],
];


$STRUCT_NODES = [
	'field_id' => [
		'type'   => 'num', // int
		'auto'   => true,  // auto increment
		'length' => 255,
		'value'  => 0
	],
	'field_title' => [
		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true
	],
	'field_content' => [
		'type'   => 'text', // varchar
		'length' => 100000,
		'fill'   => true
	],
	'field_description' => [
		'type'   => 'text', // varchar
		'length' => 100000,
		'fill'   => true
	],
	'field_user' => [
		'type'   => 'text',
		'length' => 50,
		'fill'	 => true
	],
	'field_time' => [
		'type'   => 'text',
		'length' => 50,
		'fill'	 => true
	],
	'field_route' => [
		'type'   => 'text',
		'length' => 100,
		'fill'	 => true
	],
	'field_category' => [
		'type'   => 'num',
		'length' => 100,
		'fill'	 => true
	]
];

$STRUCT_COMMENTS = [
	'field_id' => [
		'type'   => 'num', // int
		'auto'   => true,  // auto increment
		'length' => 255,
		'value'  => 0
	],
	'field_node_id' => [
		'type'   => 'num',
		'length' => 50,
		'fill'	 => true
	],
	'field_user_id' => [
		'type'   => 'num',
		'length' => 50,
		'fill'	 => true
	],
	'field_user_name' => [
		'type'   => 'text',
		'length' => 100,
		'fill'	 => true
	],
	'field_content' => [
		'type'   => 'text', // varchar
		'length' => 30000,
		'fill'   => true
	],
	'field_time' => [
		'type'   => 'text',
		'length' => 100,
		'fill'	 => true
	],
];

?>