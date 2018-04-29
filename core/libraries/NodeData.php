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
			
				$dbx::query('s|field_id|asc', 'revolver__nodes', $STRUCT_NODES);
				
				$nodes = $dbx::$result['result'];

				unset($dbx::$result['result']);

				$counter = 0;

				if( CONTENTS_FLAG ) {

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
								'category'	  => $node['field_category'],
							];

							if( count($vars::getVars()) > 0 ) {

								$struct_1 = [];
								$struct_2 = [];
								$struct_3 = [];
								$struct_4 = [];
								$struct_5 = [];

								$advanced_action = 'update';

								foreach ($vars::getVars() as $k) {

									if( !empty( $k['revolver_comments_action_edit'] ) ) {
										$action = 'edit';

									} 

									if(  !empty( $k['revolver_comments_action_delete'] ) ) {
										$advanced_action = 'delete';
									}

									if( isset($k['revolver_comment_content']) ) {
										$STRUCT_COMMENTS['field_content']['value'] = $k['revolver_comment_content'];

										$struct_1['field_content']['new_value'] = $safe::safe( nl2br($k['revolver_comment_content']) );
										$struct_1['field_content']['criterion_field'] = 'field_id';
									}

									if( isset($k['revolver_comment_time']) ) {
										$STRUCT_COMMENTS['field_time']['value'] = $k['revolver_comment_time'];

										$struct_2['field_time']['new_value'] = $k['revolver_comment_time'];
										$struct_2['field_time']['criterion_field'] = 'field_id';
									}

									if( isset($k['revolver_comment_user_id']) ) {
										$STRUCT_COMMENTS['field_user_id']['value'] = $k['revolver_comment_user_id'];
									}

									if( isset($k['revolver_node_id']) ) {
										$STRUCT_COMMENTS['field_node_id']['value'] = $k['revolver_node_id'];
									}

									if( isset($k['revolver_comment_user_name']) ) {
										$STRUCT_COMMENTS['field_user_name']['value'] = $k['revolver_comment_user_name'];
									}

									if( isset($k['revolver_comment_id']) ) {

										// TODO :: OPTIMIZE F*CKING DBX ENGINE [*]

										$struct_1['field_id']['value'] = $k['revolver_comment_id'];
										$struct_2['field_id']['value'] = $k['revolver_comment_id'];

										$struct_1['field_content']['criterion_value'] = $k['revolver_comment_id'];
										$struct_2['field_time']['criterion_value'] = $k['revolver_comment_id'];

									}
								}


								if( $action === 'edit' ) {

									if( $advanced_action === 'delete' ) {
					
										$dbx::query('x', 'revolver__comments', $struct_1);
										header('Location: '. site_host);
									
									} 
									else {

										$dbx::query('u', 'revolver__comments', $struct_1);
										$dbx::query('u', 'revolver__comments', $struct_2);
										$dbx::query('u', 'revolver__comments', $struct_5);
									
									}

								} 
								else {

									$STRUCT_COMMENTS['field_id']['value'] = 0;
									if( $counter <= 0 ) {
										$dbx::query('i', 'revolver__comments', $STRUCT_COMMENTS);	
									}
								}

							}

							if( count(ROUTE) === 1 ) {

								$node_data[$counter]['teaser'] = false;
								$node_comments = [];

								$dbx::query('s', 'revolver__comments', $STRUCT_COMMENTS);

								if( isset( $dbx::$result['result'] ) ) {
									foreach($dbx::$result['result'] as $comment => $c) {

										if( $c['field_node_id'] === NODE_ID) {
											$node_comments[] = [
												'comment_id' => $c['field_id'],
												'comment_uid' => $c['field_user_id'],
												'comment_time' => $c['field_time'],
												'comment_contents' => $safe::safe(html_entity_decode(htmlspecialchars_decode($c['field_content']))),
												'comment_user_name' => $c['field_user_name'],
											];
										}
									}
								}


								if( isset( $_COOKIE['usertoken']) ) { 

									$token_explode = explode('|', $cipher::crypt('decrypt', $_COOKIE['usertoken']));

									if( $token_explode[2] === $node['field_user'] || ACCESS === 'Admin' ) {
										$node_data[$counter]['footer'] = true;
										$node_data[$counter]['editor'] = true;


										$test_uri = explode('/', (string)$_SERVER['REQUEST_URI']);

										if( $test_uri[count($test_uri) - 2] === 'edit' ) {

											$node_data[$counter]['editor_mode'] = true;

											//$node_data[$counter]['category'] = 
											
											$struct_1 = [];
											$struct_2 = [];
											$struct_3 = [];
											$struct_4 = [];
											$struct_5 = [];

											$action = false;
										
											if( count($vars::getVars()) > 0 ) {
												foreach ($vars::getVars() as $k) {

													if( isset($k['revolver_node_edit_title']) ) {
														$struct_1['field_title']['new_value'] = $k['revolver_node_edit_title'];
														$struct_1['field_title']['criterion_field'] = 'field_id';
														$node_title = $k['revolver_node_edit_title'];
													}

													if( isset($k['revolver_node_edit_contents']) ) {
														$struct_2['field_content']['new_value'] = $safe::safe(nl2br($k['revolver_node_edit_contents']));
														$struct_2['field_content']['criterion_field'] = 'field_id';
														$node_contents = nl2br($k['revolver_node_edit_contents']);
													}

													if( isset($k['revolver_node_edit_description']) ) {
														$struct_3['field_description']['new_value'] = $k['revolver_node_edit_description'];
														$struct_3['field_description']['criterion_field'] = 'field_id';
														$node_description = $k['revolver_node_edit_description'];
													}

													if( isset($k['revolver_node_edit_route']) ) {
														$struct_4['field_route']['new_value'] = $k['revolver_node_edit_route'];
														$struct_4['field_route']['criterion_field'] = 'field_id';
														$node_route = $k['revolver_node_edit_route'];
													}

													if( isset($k['revolver_node_edit_category']) ) {
														$struct_5['field_category']['new_value'] = (int)$k['revolver_node_edit_category'];
														$struct_5['field_category']['criterion_field'] = 'field_id';
													}

													if( isset($k['revolver_node_edit_id']) ) {

														// TODO :: OPTIMIZE F*CKING DBX ENGINE [*]

														$struct_1['field_id']['value'] = $k['revolver_node_edit_id'];
														$struct_2['field_id']['value'] = $k['revolver_node_edit_id'];
														$struct_3['field_id']['value'] = $k['revolver_node_edit_id'];
														$struct_4['field_id']['value'] = $k['revolver_node_edit_id'];
														$struct_5['field_id']['value'] = $k['revolver_node_edit_id'];

														$struct_1['field_title']['criterion_value'] = $k['revolver_node_edit_id'];
														$struct_2['field_content']['criterion_value'] = $k['revolver_node_edit_id'];
														$struct_3['field_description']['criterion_value'] = $k['revolver_node_edit_id'];
														$struct_4['field_route']['criterion_value'] = $k['revolver_node_edit_id'];
														$struct_5['field_category']['criterion_value'] =  $k['revolver_node_edit_id'];

														$node_id = $k['revolver_node_edit_id'];

													}

													if( isset($k['revolver_node_edit_delete']) ) {
														$action = 'delete';
													} 
													else {
														$action = 'update';
													}

												}


												if( $action === 'update' ) {

													if( (int)$node_id === (int)$node['field_id']) {

														// check route
														$passed = true;

														// check for system routes
														foreach (main_nodes as $k => $v) {
															if( trim($v['route']) === trim($struct_4['field_route']['new_value']) ) {
																$passed =  false;
															}
														}

														// check for exists contents
														$dbx::query('s', 'revolver__nodes', $STRUCT_NODES);
														foreach ($dbx::$result['result'] as $k => $v) {	
															if( trim( $v['field_route'] ) === trim($struct_4['field_route']['new_value']) ) {
																
																// if same node id allow update
																if( $v['field_id'] === $node_id ) {
																	$passed = true;
																} 
																else {
																	$passed = false;
																}
															}
														}

														// check for route is correct
														$route_fix = ltrim(rtrim($struct_4['field_route']['new_value'], "/"), "/");
														$route_fix_check = explode('/', $route_fix);

														foreach ($route_fix_check as $k) {
															if( strlen($k) <= 0) {
																$passed = false;
															} 
														}

														$struct_4['field_route']['new_value'] = '/'. $route_fix .'/';


														if( $passed ) {

															$dbx::query('u', 'revolver__nodes', $struct_1);
															$dbx::query('u', 'revolver__nodes', $struct_2);
															$dbx::query('u', 'revolver__nodes', $struct_3);
															$dbx::query('u', 'revolver__nodes', $struct_4);
															$dbx::query('u', 'revolver__nodes', $struct_5);

															header('Location: '. site_host . $struct_4['field_route']['new_value']);

														} 
														else {
															$node_data[$counter]['warning'] = '<p class="revolver__warning">Defined route exist. Please change other route.</p>';
														}
													}	
												
												} 
												else if( $action === 'delete' ) {

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
							$STRUCT_NODES['field_content']['value'] = $safe::safe( nl2br($k['revolver_node_edit_contents']) );
						}

						if( isset($k['revolver_node_edit_description']) ) {
							$STRUCT_NODES['field_description']['value'] = $k['revolver_node_edit_description'];
						}

						if( isset($k['revolver_node_edit_route']) ) {
							$STRUCT_NODES['field_route']['value'] = $k['revolver_node_edit_route'];
						}

						if( isset($k['revolver_node_edit_category']) ) {
							$STRUCT_NODES['field_category']['value'] = $k['revolver_node_edit_category']; 
						}
					}
				
					$STRUCT_NODES['field_user']['value'] = $token_explode[2];
					$STRUCT_NODES['field_time']['value'] = date('d/m/Y');


					// check route
					$passed = true;

					// check for system routes
					foreach (main_nodes as $k => $v) {
						if( trim($v['route']) === trim($STRUCT_NODES['field_route']['value']) ) {
							$passed =  false;
						}
					}

					// check for exists contents
					$dbx::query('s', 'revolver__nodes', $STRUCT_NODES);
					foreach ($dbx::$result['result'] as $k => $v) {	
						if( trim( $v['field_route'] ) === trim($STRUCT_NODES['field_route']['value']) ) {
							$passed = false;
						}
					}

					// check for route is correct
					$route_fix = ltrim(rtrim($STRUCT_NODES['field_route']['value'], "/"), "/");
					$route_fix_check = explode('/', $route_fix);

					foreach ($route_fix_check as $k) {
						if( strlen($k) <= 0) {
							$passed = false;
						} 
					}

					$STRUCT_NODES['field_route']['value'] = '/'. $route_fix .'/';


					if( $passed ) {

						$dbx::query('i', 'revolver__nodes', $STRUCT_NODES);
						header('Location: '. site_host . $STRUCT_NODES['field_route']['value']);
					
					} 
					else {
						
						$contents = '<p class="revolver__warning">Carefully check route path! It need to be accessible with pattern <i>/way/way/</i>.</p>';
					}


				}

				$title = 'Create Node';

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
			 	$contents .= '<fieldset>';
			 	$contents .= '<legend>Category:</legend>';
			 	$contents .= '<label>Chose node category:';
			 	$contents .= '&nbsp;&nbsp;<select name="revolver_node_edit_category">';

		 		$dbx::query('s|field_id|asc', 'revolver__categories', $STRUCT_CATEGORIES);

		 		if( isset( $dbx::$result['result'] ) ) {
		 			foreach ($dbx::$result['result'] as $k => $v) {
		 				$contents .= '<option value="'. $v['field_id'] .'">'. $v['field_title'] .'</option>';
		 			}
		 		}

			 	$contents .= '</select>';
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

				// create key
				file_put_contents($_SERVER["DOCUMENT_ROOT"] .'/private/key.ini', uniqid());

				// create db config
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

					$STRUCT_NODES['field_id']['value'] = 0;
					$STRUCT_NODES['field_title']['value'] = 'Welcome your new site based on RevolveR CMS!';
					$STRUCT_NODES['field_content']['value'] = '<p>Revolver CMS installed but no any contents yet!</p> <p><a href="/node/create/">Create it first!</a></p>';
					$STRUCT_NODES['field_description']['value'] = 'RevolveR CMS Homepage';
					$STRUCT_NODES['field_user']['value'] = $user_data_name;
					$STRUCT_NODES['field_route']['value'] = '/welcome/';
					$STRUCT_NODES['field_time']['value'] = date('d/m/Y');
					$STRUCT_NODES['field_category']['value'] = 1;

					$dbx::query('i', 'revolver__nodes', $STRUCT_NODES);

					// create categories
					$dbx::query('c', 'revolver__categories', $STRUCT_CATEGORIES);

					$STRUCT_CATEGORIES['field_id']['value'] = 0;
					$STRUCT_CATEGORIES['field_title']['value'] = 'Welcome';
					$STRUCT_CATEGORIES['field_description']['value'] = 'Welcome to RevolveR CMS';

					$dbx::query('i', 'revolver__categories', $STRUCT_CATEGORIES);

					// create users
					$dbx::query('c', 'revolver__users', $STRUCT_USER);
					$dbx::query('i', 'revolver__users', $STRUCT_USER);

					$dbx::query('c', 'revolver__settings', $STRUCT_SITE); // create if now exist

					$STRUCT_SITE['field_id']['value'] = 0;
					$STRUCT_SITE['field_site_brand']['value'] = 'CyberX';
					$STRUCT_SITE['field_site_title']['value'] = 'RevolveR CMS';
					$STRUCT_SITE['field_site_description']['value'] = 'Revolver CMS homepage';
					
					$STRUCT_SITE['field_site_skin']['value'] = 'revolver core template';

					$dbx::query('i', 'revolver__settings', $STRUCT_SITE);

					// create comments
					$dbx::query('c', 'revolver__comments', $STRUCT_COMMENTS);

					// Access files fix
					chmod('/robots.txt', 644);
					chmod('/index.php', 644);
					chmod('/private/config.php', 644);
					chmod('/core/struct/DataBase.php', 644);
					chmod('/core/libraries/DBX.php', 644);
					chmod('/core/libraries/Cipher.php', 644);
					chmod('/core/libraries/Auth.php', 644);
					chmod('/core/libraries/Menu.php', 644);
					chmod('/core/libraries/Route.php', 644);
					chmod('/core/libraries/Node.php', 644);
					chmod('/core/libraries/Variables.php', 644);
					chmod('/core/libraries/Mail.php', 644);

					chmod('/app', 755);
					chmod('/core', 755);
					chmod('/core/struct', 755);
					chmod('/core/templates', 755);
					chmod('/core/libraries', 755);

					chmod('/private', 755);
					chmod('/private/config.php', 644);
					chmod('/private/db_config.ini', 755);


					header('Location: '. site_host . '/setup/');
				} 
			}
		}

		break;

	case '#preferences':

		if( ACCESS !== 'Admin' ) {
			header('Location: '. site_host . '/');
		}

		unset( $dbx::$result['result'] );


		if( count($vars::getVars()) > 0 ) {		
			foreach ($vars::getVars() as $k) {

				if( isset($k['revolver_site_settings_name']) ) {
					$logotype = $k['revolver_site_settings_name'];
				}

				if( isset($k['revolver_site_settings_homepage_title']) ) {
					$homepage_title = $k['revolver_site_settings_homepage_title'];
				}

				if( isset($k['revolver_site_settings_homepage_description']) ) {
					$homepage_description = $k['revolver_site_settings_homepage_description'];
				}

				if( isset($k['revolver_site_settings_skin']) ) {
					$active_skin = $k['revolver_site_settings_skin'];
				}

			}

			$STRUCT_SITE_1['field_site_brand']['new_value'] = $logotype;
			$STRUCT_SITE_2['field_site_title']['new_value'] = $homepage_title;
			$STRUCT_SITE_3['field_site_description']['new_value'] = $homepage_description;
			$STRUCT_SITE_4['field_site_skin']['new_value'] = $active_skin;

			$STRUCT_SITE_1['field_site_brand']['criterion_field'] = 'field_id';
			$STRUCT_SITE_2['field_site_title']['criterion_field'] = 'field_id';
			$STRUCT_SITE_3['field_site_description']['criterion_field'] = 'field_id';
			$STRUCT_SITE_4['field_site_skin']['criterion_field'] = 'field_id';

			$STRUCT_SITE_1['field_site_brand']['criterion_value'] = 1;
			$STRUCT_SITE_2['field_site_title']['criterion_value'] = 1;
			$STRUCT_SITE_3['field_site_description']['criterion_value'] = 1;
			$STRUCT_SITE_4['field_site_skin']['criterion_value'] = 1;

			$dbx::query('u', 'revolver__settings', $STRUCT_SITE_1);
			$dbx::query('u', 'revolver__settings', $STRUCT_SITE_2);
			$dbx::query('u', 'revolver__settings', $STRUCT_SITE_3);
			$dbx::query('u', 'revolver__settings', $STRUCT_SITE_4);
			
		}

		$dbx::query('s|field_id|asc', 'revolver__settings', $STRUCT_SITE);

		foreach ($dbx::$result['result'] as $k => $v) {
			
		 	$contents  = '';
		 	$contents .= '<form method="post" accept-charset="utf-8" />';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend>Site settings editor:</legend>';
		 	$contents .= '<label>Site brand(displays as logotype):';
		 	$contents .= '<input name="revolver_site_settings_name" type="text" placeholder="site brand text" value="'. $v['field_site_brand'] .'" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Homepage meta &lt;title&gt;:';
		 	$contents .= '<input name="revolver_site_settings_homepage_title" type="text" placeholder="homepage title text" value="'. $v['field_site_title'] .'" />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Homepage meta description:';
		 	$contents .= '<input name="revolver_site_settings_homepage_description" type="text" placeholder="homepage description text" value="'. $v['field_site_description'] .'" />';
		 	$contents .= '</fieldset>';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend>Other site settings:</legend>';
		 	$contents .= '<label>Site skin(template render):';
		 	$contents .= '&nbsp;&nbsp;<select name="revolver_site_settings_skin">';

			$skins = scandir('./skins/', 1);
			if( count($skins > 0) ) {
				foreach ($skins as $skin) {
					if( $skin !== '.DS_Store' ) {
						if( $skin !== '.' ) {
							if( $skin !== '..' ) {

								if(  $v['field_site_skin'] === $skin ) {
									$contents .= '<option value="'. $skin .'" selected>'. $skin .'</option>';
								} 
								else {
									$contents .= '<option value="'. $skin .'">'. $skin .'</option>';
								}
								
							}
						}
					}
				}
			}
		 				
			$contents .= '</select>';
		 	$contents .= '</fieldset>';		 	
		 	$contents .= '<input type="submit" value="Submit" />';
		 	$contents .= '</form>';

			$version = 'v.0.3.0';
			$title    = 'RevolveR CMS '. $version .' Preferences';

		}

		$node_data[] = [
			'title'     => $title,
			'header'    => 'HTTP/2.0 200 OK',
			'id'	    => 'preferences',
			'route'     => '/preferences/',
			'contents'  => $contents,
			'teaser'    => false,
			'footer'    => false,
			'time'		=> date('d/m/y')
		];

		break;

	case '#categories':

		$title = 'Contents catrgories';

		if( ACCESS === 'Admin' ) {


			if( count($vars::getVars()) > 0 ) { 
				foreach ($vars::getVars() as $k) { 

					if( isset($k['revolver_category_title']) ) {
						$STRUCT_CATEGORIES['field_title']['value'] = $k['revolver_category_title'];
					}

					if( isset($k['revolver_category_description']) ) {
						$STRUCT_CATEGORIES['field_description']['value'] = $k['revolver_category_description'];
					}


				}

				$STRUCT_CATEGORIES['field_id']['value'] = 0;

				$dbx::query('i', 'revolver__categories', $STRUCT_CATEGORIES);

			}

		 	$contents .= '<form method="post" accept-charset="utf-8" />';
		 	$contents .= '<fieldset>';
		 	$contents .= '<legend style="width: 40%">Add category:</legend>';
		 	$contents .= '<label>Category title:';
		 	$contents .= '<input name="revolver_category_title" type="text" placeholder="Type category name" required />';
		 	$contents .= '</label>';
		 	$contents .= '<label>Category description:';
		 	$contents .= '<input name="revolver_category_description" type="text" placeholder="Type category description" required />';
		 	$contents .= '</label>';
		 	$contents .= '</fieldset>';
		 	$contents .= '<input type="submit" value="Submit" />';
		 	$contents .= '</form>';
		}

		$dbx::query('s|field_id|asc', 'revolver__categories', $STRUCT_CATEGORIES);

		if( isset($dbx::$result['result']) ) {
			
			$contents .= '<dl class="revolver__categories">';

			foreach ($dbx::$result['result'] as $cat => $val) {

				
				$contents .= '<dt>#' . $val['field_id'] . ' :: '.  $val['field_title'] .'</dt>';
				$contents .= '<dd><p>'. $val['field_description'] .'</p>';

				$dbx::query('s|field_id|asc', 'revolver__nodes', $STRUCT_NODES);

				if( isset($dbx::$result['result']) ) {

					$contents .= '<ul>';

					foreach ($dbx::$result['result'] as $node => $v) {

						if( $v['field_category'] === $val['field_id'] ) {

							$contents .= '<li><a href="'. $v['field_route'] .'" title="'. $v['field_description'] .'">#'. $v['field_id'] .' '. $v['field_title'] .'</a></li>';				

						}

					}
				
					$contents .= '</ul></dd>';

				} 
				else {
					$contents .= 'No any documents was found on this category for now';
				}


			}

			$contents .= '</dl>'; 

		} 
		else {
			$contents = '<p>No any categories find at now.</p>';
		}

		$node_data[] = [
			'title'     => $title,
			'header'    => 'HTTP/2.0 200 OK',
			'id'	    => 'categories',
			'route'     => '/categories/',
			'contents'  => $contents,
			'teaser'    => false,
			'footer'    => false,
			'time'		=> date('d/m/y')
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

						// test for user exist
						$dbx::query('s|field_id|asc', 'revolver__users', $STRUCT_USER);
						
						$passed = true;

						foreach ($dbx::$result['result'] as $u => $v) {
							if($v['field_email'] === $user_data_email ) {
								$passed = false;
							}
						}

						if( $passed ) {
							
							$dbx::query('i', 'revolver__users', $STRUCT_USER);
							
							$title = 'Account Created';
							$contents = '<p>'. $user_data_name .', welcome! Now you can <a href="/user/login" title="Account login">login</a>!</p>';	
						
						} 
						else {

							$title = 'Account not created!';
							$contents = '<p>Account with email '. $user_data_email .' already exist! <a title="Repeat registration" href="/user/register/">Try another email</a> or do <a title="Account recovery page" href="/user/recovery/">account recovery</a> if it\'s yours!</p>';

						}
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

		case '#404':

			$title = 'Route not found.';
			$contents = '<p>Route you want to render not found.</p>';

			$node_data[0] = [
				'title'     => $title,
				'id'	    => 'lost',
				'route'     => '/node/404/',
				'contents'  => $contents,
				'teaser'    => false,
				'footer'    => false,
				'time'		=> false
			];

		break;

}

?>
