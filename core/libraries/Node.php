<?php

/** 
  * 
  * RevolveR Node class with 
  * array structure based syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

class Node {

	public static function singleNode($nodes) {

		foreach ($nodes as $node => $fg) {
			foreach ($fg as $k => $v) {
				if( ROUTE['route'] === $v ) {

					$node_params = [
						'route'  => $v,			 // path
						'node'   => $fg['node'],	 // node
						'id'     => $fg['id'],    // node id
						'title'  => $fg['title'], // node title
						'params' => $fg['param_check']
					];

				}
				
			}
		} 

		return self::nodePrepare($node_params);

	}

	public static function nodePrepare($node) {
		

		/* Access Levels Statuses */
		$main_access_levels = [
			'200' => [
				'headers' => 'HTTP/2.0 200 OK'
			],
			'403' => [
				'headers' => 'HTTP/2.0 403 Forbidden'
			],
			'404' => [
				'headers' => 'HTTP/2.0 404 Not Found'
			]
		];

		// set page header
		if( (int)$node['params']['auth'] === (int)$_COOKIE['authorization'] ) {
			$node['header']    = $main_access_levels['200']['headers'];
			$node['canonical'] = site_host . $node['route'];
		} 
		else {
			$node['header'] = $main_access_levels['403']['headers'];
			$node['canonical'] = site_host;
		}

		unset( $node['params']['auth'] );

		// set blockings for non admin
		if( !empty($node['params']['isAdmin']) ) {

			if( (int)USERID === 0 && (int)$_COOKIE['authorization'] === 1 ) {
				$node['header'] = $main_access_levels['200']['headers'];
				$node['canonical'] = site_host . $node['route'];
			} 
			else {
				$node['header'] = $main_access_levels['403']['headers'];
				$node['canonical'] = site_host;  
			}

			unset( $node['params']['isAdmin'] );

		}

		unset( $node['params'] );

		return $node;
	}

}



?>