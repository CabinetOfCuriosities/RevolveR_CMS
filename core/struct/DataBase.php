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
	]
];

?>