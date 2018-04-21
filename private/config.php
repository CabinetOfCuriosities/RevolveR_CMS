<?php

/** 
  * 
  * ............... RevolveR Base Config 
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

error_reporting(E_ALL & ~E_NOTICE);

/* Domain configuration */
$site_name = 'CyberX';     // global sitename
$site_title = "Homepage";  // homepage title

//site description
$site_description = "Homepage index";

/* Layout Config */
$site_sidebars = [
	'left' => false, 
	'right' => true
];

/* Main menu */
define('main_nodes', [
	'home' => [
		'title' => 'Home',
		'class' => 'revolver__menu-item',
		'route' => '/',
		'node'	=> '#homepage',
		'id'	=> 0,
		'param_check' => [
			'menu' => 1,
		]
	],
	'create' => [
		'title' => 'Create node',
		'route' => '/node/create/',
		'node'  => '#create',
		'id'	=> 'create',
		'param_check' => [
			'auth'    => 1,
			'menu'    => 1,
			'isAdmin' => 1
		]
	],
	'user' => [
		'title' => 'Account profile',
		'rel' => 'bookmark',
		'param_check' => [
			'auth' => 1,
			'menu' => 1
		],
		'route' => '/user/',
		'node'  => '#user',
		'id'	=> 1

	],
	'login' => [
		'title' => 'Account sign in',
		'param_check' => [
			'auth' => 0,
			'menu' => 1
		],
		'route' => '/user/login/',
		'node'  => '#user',
		'id'	=> 2
	],
	'register' => [
		'title' => 'Account registration',
		'param_check' => [
			'auth' => 0,
			'menu' => 1
		],
		'route' => '/user/register/',
		'node'  => '#user',
		'id'	=> 3
	],
	'recovery' => [
		'title' => 'Account recovery',
		'param_check' => [
			'auth' => 0,
			'menu' => 1
		],
		'route' => '/user/recovery/',
		'node'  => '#user',
		'id'	=> 4
	],
	'preferences' => [	
		'title' => 'Prefernces panel',
		'param_check' => [
			'auth' => 1,
			'isAdmin' => 1,
			'menu' => 1
		],
		'route'=> '/preferences/',
		'node' => '#preferences',
		'id'   => 5
	],
	'logout' => [
		'title' => 'Account logout',
		'param_check' => [
			'auth' => 1,
			'menu' => 1
		],
		'route' => '/logout/',
		'node'  => '#user',
		'id'	=> 6
	],
	'install' => [
		'title' => 'RevolveR CMS Setup',
		'param_check' => [
			'auth' => 0,
			'menu' => 0
		],
		'route' => '/setup/',
		'node'  => '#setup',
		'id'	=> 'setup'
	]
]);

/* Domain globals */
define('site_host', strtolower(explode("/", $_SERVER['SERVER_PROTOCOL'])[0]) .'://'. $_SERVER['HTTP_HOST']);

?>