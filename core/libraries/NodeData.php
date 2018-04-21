<?php

/** 
  * 
  * RevolveR Node Data Constructor
  * array structure based syntax
  * .........................version 0.2
  *
  * Developer: CyberX
  * License: MIT
  *
  */

switch( ROUTE['node'] ) {
	
	default:
	case '#homepage':
		
		if( !INSTALLED ) {
			
			$node_data[] = [
				'title'     => 'RevolveR CMS :: Welcome',
				'header'    => 'HTTP/2.0 200 OK',
				'id'	    => 'welcome-message',
				'route'     => '/setup/',
				'contents'  => '<p>Revolver CMS not installed!</p><p>Follow <a title="Install RevolveR CMS Now!" href="/setup/">setup</a> process now!</p>',
				'teaser'    => true,
				'footer'    => true
			];

		} 
		else {
			if( CONTENTS ) {

				//$node_data[] = $node::singleNode( $main_nodes );
			
			} 
			else {

				$dbx::query('s|field_id|asc', 'revolver__nodes', $STRUCT_NODES);
				$nodes = $dbx::$result['result'];

				unset($dbx::$result['result']);

				$counter = 0;

				foreach ($nodes as $node) {

						$node_data[$counter] = [
							'title'       => $node['field_title'],
							'header'      => 'HTTP/2.0 200 OK',
							'id'	      => 'node-'. $node['field_id'],
							'description' => $node['field_description'],
							'route'       => $node['field_route'],
							'contents'    => html_entity_decode(htmlspecialchars_decode($node['field_content'])),
							'teaser'      => true,
							'footer'      => true,
						];

						if( count(ROUTE) === 1 ) {

							$node_data[$counter]['teaser'] = false;


							if( isset( $_COOKIE['usertoken']) ) { 

								$token_explode = explode('|', $cipher::crypt('decrypt', $_COOKIE['usertoken']));

								if( $token_explode[2] === $node['field_user'] || ACCESS === 'Admin' ) {
									$node_data[$counter]['footer'] = true;
									$node_data[$counter]['editor'] = true;


									$test_uri = explode('/', (string)$_SERVER['REQUEST_URI']);

									if( $test_uri[count($test_uri) - 2] === 'edit' ) {

										$node_data[$counter]['editor_mode'] = true;
										
										$struct_1 = [];
										$struct_2 = [];
										$struct_3 = [];
										$struct_4 = [];

										$action = false;
									
										if( count($vars::getVars()) > 0 ) {
											foreach ($vars::getVars() as $k) {

												if( isset($k['revolver_node_edit_title']) ) {
													$struct_1['field_title']['new_value'] = $k['revolver_node_edit_title'];
													$struct_1['field_title']['criterion_field'] = 'field_id';
												}

												if( isset($k['revolver_node_edit_contents']) ) {
													$struct_2['field_content']['new_value'] = nl2br($k['revolver_node_edit_contents']);
													$struct_2['field_content']['criterion_field'] = 'field_id';
												}

												if( isset($k['revolver_node_edit_description']) ) {
													$struct_3['field_description']['new_value'] = $k['revolver_node_edit_description'];
													$struct_3['field_description']['criterion_field'] = 'field_id';
												}

												if( isset($k['revolver_node_edit_route']) ) {
													$struct_4['field_route']['new_value'] = $k['revolver_node_edit_route'];
													$struct_4['field_route']['criterion_field'] = 'field_id';
												}

												if( isset($k['revolver_node_edit_delete']) ) {
													$action = 'delete';
												} 
												else {
													$action = 'update';
												}

											}

											if( $action === 'update' ) {

												if( $k['revolver_node_edit_id'] === $node['field_id']) {


													// TODO :: OPTIMIZE F*CKING DBX ENGINE [*]

													$struct_1['field_id']['value'] = $k['revolver_node_edit_id'];
													$struct_2['field_id']['value'] = $k['revolver_node_edit_id'];
													$struct_3['field_id']['value'] = $k['revolver_node_edit_id'];
													$struct_4['field_id']['value'] = $k['revolver_node_edit_id'];

													$struct_1['field_title']['criterion_value'] = $k['revolver_node_edit_id'];
													$struct_2['field_content']['criterion_value'] = $k['revolver_node_edit_id'];
													$struct_3['field_description']['criterion_value'] = $k['revolver_node_edit_id'];
													$struct_4['field_route']['criterion_value'] = $k['revolver_node_edit_id'];

													$dbx::query('u', 'revolver__nodes', $struct_1);
													$dbx::query('u', 'revolver__nodes', $struct_2);
													$dbx::query('u', 'revolver__nodes', $struct_3);
													$dbx::query('u', 'revolver__nodes', $struct_4);

													header('Location: '. site_host . $data_4['field_route']['new_value']);

												}	
											
											} 
											else {

												if( count($vars::getVars()) > 0 ) {
													foreach ($vars::getVars() as $k) {

														if( $k['revolver_node_edit_id'] === $node['field_id']) {

															$struct_1['field_id']['value'] = $k['revolver_node_edit_id'];
															$struct_1['field_title']['criterion_field'] = 'field_id';
															$struct_1['field_title']['criterion_value'] = $node['field_id'];

															$dbx::query('x', 'revolver__nodes', $struct_1);
														
															header('Location: '. site_host );
															
														}
													}
												}
											
											}

										}
									}
								}
							} 
							else {
								$node_data[$counter]['footer'] = false;
							}
						
						} $counter++;
					} 
			}
		}

		break;

	case '#create':

		if( !isset( $_COOKIE['usertoken']) ) { 
			header('Location: '.  site_host . '/user/login/');
		}

		if( isset( $_COOKIE['usertoken']) ) { 

			$token_explode = explode('|', $cipher::crypt('decrypt', $_COOKIE['usertoken']));

			if( ACCESS === 'Admin' ) {

				if( count($vars::getVars()) > 0 ) {
					foreach ($vars::getVars() as $k) { 

						if( isset($k['revolver_node_edit_id']) ) {
							$STRUCT_NODES['field_id']['value'] = 0;
						}

						if( isset($k['revolver_node_edit_title']) ) {
							$STRUCT_NODES['field_title']['value'] = $k['revolver_node_edit_title'];
						}

						if( isset($k['revolver_node_edit_contents']) ) {
							$STRUCT_NODES['field_content']['value'] = nl2br($k['revolver_node_edit_contents']);
						}

						if( isset($k['revolver_node_edit_description']) ) {
							$STRUCT_NODES['field_description']['value'] = $k['revolver_node_edit_description'];
						}

						if( isset($k['revolver_node_edit_route']) ) {
							$STRUCT_NODES['field_route']['value'] = $k['revolver_node_edit_route'];
						}
					}
				
					$STRUCT_NODES['field_user']['value'] = $token_explode[2];
					$STRUCT_NODES['field_time']['value'] = date('d/m/Y');

					$dbx::query('i', 'revolver__nodes', $STRUCT_NODES);

					header('Location: '. site_host . $STRUCT_NODES['field_route']['value']);

				}

				$title = 'Create Node';

			 	$contents  = '';
			 	$contents .= '<form method="post" accept-charset="utf-8" />';
			 	$contents .= '<fieldset>';
			 	$contents .= '<legend>New node editor:</legend>';
			 	$contents .= '<label>Node title:';
			 	$contents .= '<input name="revolver_node_edit_title" type="text" placeholder="Node title" />';
			 	$contents .= '</label>';
			 	$contents .= '<label>Node description:';
			 	$contents .= '<input name="revolver_node_edit_description" type="text" placeholder="Node description" />';
			 	$contents .= '</label>';
			 	$contents .= '<label>Node route:';
			 	$contents .= '<input name="revolver_node_edit_route" type="text" placeholder="Node address" />';
			 	$contents .= '</label>';
			 	$contents .= '<label>Node contents:';
			 	$contents .= '<textarea rows="20" name="revolver_node_edit_contents" type="text" placeholder="Node contents"></textarea>';
			 	$contents .= '</label>';
			 	$contents .= '</fieldset>';
			 	$contents .= '<input type="submit" value="Submit" />';
			 	$contents .= '</form>';

				$node_data[] = [
					'title'     => $title,
					'header'    => 'HTTP/2.0 200 OK',
					'id'	    => 'create',
					'route'     => '/node/create/',
					'contents'  => $contents,
					'teaser'    => false,
					'footer'    => false
				];
			}
		}

		break;

	case '#setup':

		if( !INSTALLED ) {
		 	$contents  = '';
		 	$contents .= '<p>Welcome to RevolveR CMS Installer. Next step you have to define some dababase and admin settings.</p>';
		 	$contents .= '<p>Please choose database name and create it first!';
		 	$contents .= '<h2>Type Data Base options and Administartor Account data:</h2>';
		 	$contents .= '<form method="post" accept-charset="utf-8" />';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend>Administrator Setup:</legend>';
		 	$contents .= '<label>Admin name:';
		 	$contents .= '<input name="revolver_setup_admin_name" type="text" placeholder="RevolveR admin name" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Admin email:';
		 	$contents .= '<input name="revolver_setup_admin_email" type="email" placeholder="RevolveR admin email" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Admin password:';
		 	$contents .= '<input name="revolver_setup_admin_password" type="password" placeholder="admin password repeat" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Confirm password:';
		 	$contents .= '<input name="revolver_setup_admin_password_confirm" type="password" placeholder="RevolveR admin password" />';
		 	$contents .= '</label>';
		 	$contents .= '</fieldset>';

		 	$contents .= '<fieldset>';
		 	$contents .= '<legend>Database Setup:</legend>';
		 	$contents .= '<label>Database Name:';
		 	$contents .= '<input name="revolver_setup_database_name" type="text" value="revolver_dbx" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Database MySQL server host:';
		 	$contents .= '<input name="revolver_setup_database_host" type="text" value="localhost" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Database MySQL server port:';
		 	$contents .= '<input name="revolver_setup_database_port" type="number" value="8889" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Database MySQL user:';
		 	$contents .= '<input name="revolver_setup_database_user" type="text" placeholder="Database user name" value="root" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Database MySQL password:';
		 	$contents .= '<input name="revolver_setup_database_password" type="password" placeholder="Database password" value="root" />';
		 	$contents .= '</label>';
		 	$contents .= '</fieldset>';
		 	$contents .= '<input type="submit" value="Submit" /><input type="reset" />';
		 	$contents .= '</form>';

		 	$title = 'RevolveR CMS :: Setup';
		} 
		else {

			$contents  = '<p>RevolveR CMS setup process done! Welcome to you <a href="/" title="homepage">new site homepage</a>!</p>';
			$title = 'RevolveR CMS :: Setup Done!';
		
		}

		$node_data[] = [
			'title'     => $title,
			'header'    => 'HTTP/2.0 200 OK',
			'id'	    => 'setup',
			'route'     => '/setup/',
			'contents'  => $contents,
			'teaser'    => false,
			'footer'    => false
		];

		if( count($vars::getVars()) > 0 ) {

			$dbx_data = [];
			
			foreach ($vars::getVars() as $k) {

				if( isset($k['revolver_setup_admin_name']) ) {
					$user_data_name = $k['revolver_setup_admin_name'];
				}

				if( isset($k['revolver_setup_admin_email']) ) {
					$user_data_email = $k['revolver_setup_admin_email'];
				}

				if( isset($k['revolver_setup_admin_password']) ) {
					$user_data_password = $k['revolver_setup_admin_password'];
				}

				if( isset($k['revolver_setup_admin_password_confirm']) ) {
					$user_data_password_confirm = $k['revolver_setup_admin_password_confirm'];
				}

				if( isset($k['revolver_setup_database_name']) ) {
					$dbx_data[3] = $k['revolver_setup_database_name'];
				}

				if( isset($k['revolver_setup_database_host']) ) {
					$dbx_data[0] = $k['revolver_setup_database_host'];
				}

				if( isset($k['revolver_setup_database_port']) ) {
					$dbx_data[4] = $k['revolver_setup_database_port'];
				}

				if( isset($k['revolver_setup_database_user']) ) {
					$dbx_data[1] = $k['revolver_setup_database_user'];
				}

				if( isset($k['revolver_setup_database_password']) ) {
					$dbx_data[2] = $k['revolver_setup_database_password'];
				}

			}

			// Form Passed
			$passed = false;

			// Crypt and write database config
			$db_cfgs = $_SERVER["DOCUMENT_ROOT"] .'/private/db_config.ini';
			$db_data = $dbx_data[0] .'|'. $dbx_data[1] .'|'. $dbx_data[2] .'|'. $dbx_data[3] .'|'. $dbx_data[4];

			if( strlen($user_data_name) >= 4 && strlen($user_data_email) > 0 && strlen($user_data_password) > 5 && strlen($user_data_password_confirm) > 5 ) {
				if( $user_data_password === $user_data_password_confirm ) {
					$passed = true;
				}
			} 
			else {
				$title    = 'RevolveR CMS Warning!';
				$contents = '<p class="revolver__warning">Unable to complite setup! Insufucient data. Check all fields again and repeat submit!</p>'. $contents;
			}

			$node_data[0] = [
				'title'     => $title,
				'header'    => 'HTTP/2.0 200 OK',
				'id'	    => 'setup',
				'route'     => '/setup/',
				'contents'  => $contents,
				'teaser'    => false,
				'footer'    => false
			];


			// redirect to done page
			if( $passed ) {

				file_put_contents($db_cfgs, $cipher::crypt('encrypt', $db_data));

				/* Installer TEST */
				$dbTest = file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/private/db_config.ini', true);

				/* Check installation */
				if( strlen($dbTest) > 0 ) {

					$dbx_data = explode('|', $cipher::crypt('decrypt', $dbTest));
					$dbx = new DBX($dbx_data);

					$STRUCT_USER['field_nickname']['value'] = $user_data_name;
					$STRUCT_USER['field_email']['value'] = $user_data_email;
					$STRUCT_USER['field_password']['value'] = $cipher::crypt('encrypt', $user_data_password_confirm);
					$STRUCT_USER['field_permissions']['value'] = 'Admin';

					// create nodes
					$dbx::query('c', 'revolver__nodes', $STRUCT_NODES);

					$STRUCT_NODES['field_title']['value'] = 'Welcome your new site based on RevolveR CMS!';
					$STRUCT_NODES['field_content']['value'] = '<p>Revolver CMS installed but no any contents yet!</p> <p><a href="/node/create/">Create it first!</a></p>';
					$STRUCT_NODES['field_description']['value'] = 'RevolveR CMS Homepage';
					$STRUCT_NODES['field_user']['value'] = $user_data_name;
					$STRUCT_NODES['field_route']['value'] = '/welcome/';
					$STRUCT_NODES['field_time']['value'] = date('d/m/Y');

					$dbx::query('i', 'revolver__nodes', $STRUCT_NODES);
					
					// create users
					$dbx::query('c', 'revolver__users', $STRUCT_USER);
					$dbx::query('i', 'revolver__users', $STRUCT_USER);

					header('Location: '. site_host . '/setup/');
				} 
			}
		}

		break;

	case '#preferences':

		if( ACCESS !== 'Admin' ) {
			header('Location: '. site_host . '/');
		}

		$node_data[] = [
			'title'     => 'RevolveR CMS v.0.2 Preferences',
			'header'    => 'HTTP/2.0 200 OK',
			'id'	    => 'preferences',
			'route'     => '/preferences/',
			'contents'  => '<p>This route under development now</p>',
			'teaser'    => false,
			'footer'    => false,
			'time'		=> false
		];

		break;

	case '#user':

		if( ROUTE['route'] === '/user/' && (int)$_COOKIE['authorization'] <= 0) {
			header('Location: '. site_host . '/user/login/');
		}

		if( ROUTE['route'] === '/user/login/' && (int)$_COOKIE['authorization'] <= 0 ) {

			$contents  = '';
		 	$contents .= '<form method="post" accept-charset="utf-8" />';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend style="width: 40%">Welcome, guest! Please confirm your person:</legend>';
		 	$contents .= '<label>User email:';
		 	$contents .= '<input name="revolver_login_user_email" type="email" placeholder="user email" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>User password:';
		 	$contents .= '<input name="revolver_login_user_password" type="password" placeholder="user password" />';
		 	$contents .= '</label>';
		 	$contents .= '</fieldset>';
		 	$contents .= '<input type="submit" value="Submit" /><input type="reset" />';
		 	$contents .= '</form>';

		 	$title = 'Account login';

		} 
		else {
			if( (int)$_COOKIE['authorization'] > 0 ) {
				if( ROUTE['route'] === '/user/login/' || ROUTE['route'] === '/user/register/' ) {
					header('Location: '. site_host . '/user/');
				}
			}
		}

		if( ROUTE['route'] === '/user/' && (int)$_COOKIE['authorization'] > 0 ) {
			
			$dbx::query('s|field_id|asc', 'revolver__users', $STRUCT_USER);

			$users = $dbx::$result['result'];

			unset($dbx::$result['result']);

			foreach( $users as $user ) {

				$token_explode = explode('|', $cipher::crypt('decrypt', $_COOKIE['usertoken']));

				if( (string)$user['field_email'] === (string)$token_explode[0] ) {
					if( (string)$user['field_password'] === (string)$token_explode[1] ) {
						$user_name  = $user['field_nickname'];
						$user_email = $user['field_email'];
						$user_id    = $user['field_id'];
						$user_permissions = $user['field_permissions'];
					}
				} 

			}

			$title = 'User profile #'. $user_id;
			
			$contents = '<p>User Name: <i>'. $user_name .'</i></p>';
			$contents .= '<p>User Email: <i>'. $user_email  .'</i></p>';
			$contents .= '<p>Permissions: <i>'. $user_permissions  .'</i></p>';

		}

		if( ROUTE['route'] === '/user/login/' && (int)$_COOKIE['authorization'] <= 0 ) {

			if( count($vars::getVars()) > 0 ) { 
				foreach ($vars::getVars() as $k) { 

					if(isset($k['revolver_login_user_email'])) {
						$email = $k['revolver_login_user_email'];
					} 

					if(isset($k['revolver_login_user_password'])) {
						$pass = $cipher::crypt('encrypt', $k['revolver_login_user_password'] );
					}
				
				}

				$dbx::query('s|field_id|asc', 'revolver__users', $STRUCT_USER);
				$users = $dbx::$result['result'];
				unset($dbx::$result['result']);

				foreach( $users as $user ) {

					if( (string)$user['field_email'] === (string)$email ) {
						if( (string)$user['field_password'] === (string)$pass ) {

							// secure session
							$token = $cipher::crypt('encrypt', (string)$user['field_email'] .'|'. (string)$user['field_password'] .'|'.  (string)$user['field_nickname']);

							$auth::login($token);
							
							header('Location: '. site_host . '/');
						
						}
					} 
				}
			}
		}

		if( ROUTE['route'] === '/logout/' && (int)$_COOKIE['authorization'] > 0 ) {

			$title = 'See you soon!';
			$contents = '<p>You are signed out of the system. Goodbye.</p>';

			$auth::logout();
		
		}

		if( ROUTE['route'] === '/logout/' && (int)$_COOKIE['authorization'] <= 0 ) { 
			header('Location: '. site_host . '/');
		}

		if( ROUTE['route'] === '/user/recovery/' && (int)$_COOKIE['authorization'] <= 0 ) {

			$title = 'Account recovery';
			$contents = '<p>Lost password? Try to recovery it to email.</p>';
		 	$contents .= '<form method="post" accept-charset="utf-8" />';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend style="width: 40%">Welcome, guest! Please confirm your person:</legend>';
		 	$contents .= '<label>User email:';
		 	$contents .= '<input name="revolver_recovery_user_email" type="email" placeholder="user email" />';
		 	$contents .= '</label>';
		 	$contents .= '</fieldset>';
		 	$contents .= '<input type="submit" value="Submit" />';
		 	$contents .= '</form>';

			if( count($vars::getVars()) > 0 ) { 
				foreach ($vars::getVars() as $k) { 

					if( isset($k['revolver_recovery_user_email']) ) {
						$recovery_email = $k['revolver_recovery_user_email'];
						$STRUCT_USER['field_email'] = $k['revolver_recovery_user_email'];
					}

				}

				$dbx::query('s|field_id|asc', 'revolver__users', $STRUCT_USER);
				$users = $dbx::$result['result'];
				unset($dbx::$result['result']);

				foreach( $users as $user ) {

					if( (string)$user['field_email'] === (string)$recovery_email ) {
						$recovery_password = $cipher::crypt( 'decrypt', $user['field_password'] );

						$message = 'To sign in use Email: '. $recovery_email .' and Password: '. $recovery_password;

						$mail::send($recovery_email, 'RevolveR CMS :: Account recovery system', $message);

						$contents = '<p>Your Account successfully recovered! Check your email.</p>';


					} 
					else {

						$contents = '<p class="revolver__warning">Account not recovered! Unable to find user with this email.</p>' . $contents;
					}

				}


			}

		}


		if( ROUTE['route'] === '/user/register/' && (int)$_COOKIE['authorization'] <= 0 ) { 

			$title = 'Account registration';
			$contents = '<p>Follow Account creation process.</p>';

		 	$contents .= '<form method="post" accept-charset="utf-8" />';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend>Account data:</legend>';
		 	$contents .= '<label>User name:';
		 	$contents .= '<input name="revolver_registration_name" type="text" placeholder="User name" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Account email:';
		 	$contents .= '<input name="revolver_registration_email" type="email" placeholder="User email" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Account password:';
		 	$contents .= '<input name="revolver_registration_password" type="password" placeholder="User password" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Confirm password:';
		 	$contents .= '<input name="revolver_registration_password_confirm" type="password" placeholder="Confirm user password" />';
		 	$contents .= '</label>';
		 	$contents .= '</fieldset>';

		 	$contents .= '<input type="submit" value="Submit" /><input type="reset" />';
		 	$contents .= '</form>';

			if( count($vars::getVars()) > 0 ) {
			
				foreach ($vars::getVars() as $k) {

					if( isset($k['revolver_registration_name']) ) {
						$user_data_name = $k['revolver_registration_name'];
					}

					if( isset($k['revolver_registration_email']) ) {
						$user_data_email = $k['revolver_registration_email'];
					}

					if( isset($k['revolver_registration_password']) ) {
						$user_data_password = $k['revolver_registration_password'];
					}

					if( isset($k['revolver_registration_password_confirm']) ) {
						$user_data_password_confirm = $k['revolver_registration_password_confirm'];
					}

				}

				if( strlen($user_data_name) >= 4 && strlen($user_data_email) > 0 && strlen($user_data_password) > 5 && strlen($user_data_password_confirm) > 5 ) {
					if( $user_data_password === $user_data_password_confirm ) {

						$STRUCT_USER['field_nickname']['value'] = $user_data_name;
						$STRUCT_USER['field_email']['value'] = $user_data_email;
						$STRUCT_USER['field_password']['value'] = $cipher::crypt('encrypt', $user_data_password_confirm);
						$STRUCT_USER['field_permissions']['value'] = 'User';

						$dbx::query('i', 'revolver__users', $STRUCT_USER);

						$title = 'Account Created';
						$contents = '<p>'. $user_data_name .', welcome! Now you can <a href="/user/login" title="Account login">login</a>!</p>';		

					}
				} 
				else {
					$title    = 'RevolveR CMS Warning!';
					$contents = '<p class="revolver__warning">Unable to create Account! Insufucient data. Check all fields again and repeat submit!</p>'. $contents;
				}
			}
		}

		$node_data[0] = [
			'title'     => $title,
			'header'    => 'HTTP/2.0 200 OK',
			'id'	    => 'user-login',
			'route'     => '/user/login/',
			'contents'  => $contents,
			'teaser'    => false,
			'footer'    => false,
			'time'		=> false
		];
		
		break;

}

?>
