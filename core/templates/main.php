<?php
/**
 ** MAIN TEMPLATE PHP :: contents area
 **
 **/

// Load internal routing and nodes data
include($_SERVER["DOCUMENT_ROOT"] .'/core/libraries/NodeData.php');

// Check render is allowed
function allowRender($route) {
	foreach (main_nodes as $k => $v) {
		if( $v['route'] === $route ) {
			return true;
		}
	}
}

?>

<!-- RevolveR :: main -->
<section class="revolver__main-contents">

<?php $render_node = ''; foreach ($node_data as $n) { 

		if( !$n['editor_mode'] ) { // Render Node

			if(  $_SERVER['REQUEST_URI'] === $n['route'] || allowRender(ROUTE['route']) ) {

				define('node_id', preg_replace("/[^0-9]/", '', $n['id']));

				if( !isset($n['time']) ) {
					$n['time'] = date('d/m/Y');
				}

				$render_node .= '<article class="revolver__article article-id-'. $n['id'] .'">';
				$render_node .= '<header class="revolver__article-header">'; 
				
				if( $n['teaser'] ) {
					$render_node .= '<h1><a href="'. $n['route'].'" rel="bookmark">'. $n['title'] .'</a></h1>';
				} 
				else {
					$render_node .= '<h1>'. $n['title'] .'</h1>';
				}

				if( $n['time'] ) {
					$render_node .= '<time>'. $n['time'] .'</time>';
				}

				$render_node .= '</header>';
				$render_node .= '<div class="revolver__article-contents">'. $n['contents'] .'</div>';

				if( $n['footer'] ) {
				
					$render_node .= '<footer class="revolver__article-footer"><nav>';
					$render_node .= '<ul>';

					if( $n['editor'] ) {
						$render_node .= '<li><a title="'. $n['title'] .' edit" href="'. $n['route'] .'edit/">Edit</a></li>';
					} 
					else {
						$render_node .= '<li><a title="'. $n['title'] .'" href="'. $n['route'] .'">Read More</a></li>';
					}

					$render_node .= '</nav></footer>';

				}

				$render_node .= '</article>';

				if( !allowRender($_SERVER['REQUEST_URI']) ) {

					$render_node .= '<section class="revolver__advanced-contents">';
					$render_node .= '<h1>Comments</h1>';
				
					// comments show
					foreach( $node_comments as $c ) {
						$render_node .= '<article id="'. $c['comment_id'] .'" class="revolver__comments comments-'. $c['comment_id'] .'">';
						$render_node .= '<header class="revolver__comments-header">'; 
						$render_node .= '<h2><a href="#'. $c['comment_id'] .'">#'. $c['comment_id'] .'</a> by '. $c['comment_user_name'] .'</h2>';
						$render_node .= '<time>'. $c['comment_time'] .'</time>';
						$render_node .= '</header>';
						$render_node .= '<div class="revolver__comments-contents">'. $c['comment_contents'] .'</div>';
						
						if( $n['editor'] ) {
							$render_node .= '<footer class="revolver__comments-footer"><nav><ul>';
							$render_node .= '<li><a title="'. $c['comment_id'] .' edit" href="/comment/'. $c['comment_id'] .'/edit/">Edit</a></li>';
							$render_node .= '</ul></nav></footer>';
						} 
						
						$render_node .= '</article>';
					}

					
					$render_node .= '</section>';

					// comments form
					if(ACCESS === 'Admin' || ACCESS === 'User') {
						$render_node .= '<div class="revolver__comments_add">';
				 		$render_node .= '<form method="post" accept-charset="utf-8" />';
					 	$render_node .= '<fieldset>';
					 	$render_node .= '<legend>Add a review as: '. USER['name'] .'</legend>';
					 	$render_node .= '<label>Comment:';
					 	$render_node .= '<textarea id="textarea" rows="5" name="revolver_comment_content" type="text" placeholder="Comment contents"></textarea>';
					 	$render_node .= '<input type="hidden" name="revolver_node_id" value="'. preg_replace("/[^0-9]/", '', $n['id']) .'" />';
					 	$render_node .= '<input type="hidden" name="revolver_comment_user_id" value="'. USER['id'] .'" />';
					 	$render_node .= '<input type="hidden" name="revolver_comment_user_name" value="'. USER['name'] .'" />';
					 	$render_node .= '<input type="hidden" name="revolver_comment_time" value="'. date('d/m/Y') .'" />';
					 	$render_node .= '</label>';
					 	$render_node .= '</fieldset>';
					 	$render_node .= '<input type="submit" value="Post comment" />';
					 	$render_node .= '</form>';
						$render_node .= '</div>';
					}
				}
			
			}

		} 
		else { 

			// Render node edit
			if( $_SERVER['REQUEST_URI'] === $n['route'] . 'edit/' ) {

			 	$render_node  = '';
			 	$render_node .= '<article class="revolver__article article-id-'. $n['id'] .'-edit">';
				$render_node .= '<header class="revolver__article-header">'; 
				$render_node .= '<h1>'. $n['title'] .' :: Edit</h1>';
				$render_node .= '</header>';
			 	$render_node .= '<form method="post" accept-charset="utf-8" />';
			if( isset($n['warning']) ) {
				$render_node .= $n['warning'];
			}
			 	$render_node .= '<fieldset>';
			 	$render_node .= '<legend>#'. $n['id'] .' Editor:</legend>';
			 	$render_node .= '<label>Node title:';
			 	$render_node .= '<input name="revolver_node_edit_title" type="text" placeholder="Node title" value="'. $n['title'] .'" />';
			 	$render_node .= '</label>';
			 	$render_node .= '<label>Node description:';
			 	$render_node .= '<input name="revolver_node_edit_description" type="text" placeholder="Node description" value="'. $n['description'] .'" />';
			 	$render_node .= '</label>';
			 	$render_node .= '<label>Node route:';
			 	$render_node .= '<input name="revolver_node_edit_route" type="text" placeholder="Node address" value="'. $n['route'] .'" />';
			 	$render_node .= '</label>';
			 	$render_node .= '<label>Node contents:';
			 	$render_node .= '<textarea id="textarea" rows="20" name="revolver_node_edit_contents" type="text" placeholder="Node contents">'. $n['contents'] .'</textarea>';
			 	$render_node .= '<input type="hidden" name="revolver_node_edit_id" value="'. preg_replace("/[^0-9]/", '', $n['id']) .'" readonly required />';
			 	$render_node .= '</label>';
			 	$render_node .= '<label>Delete node:';
			 	$render_node .= '&nbsp;&nbsp;<input type="checkbox" name="revolver_node_edit_delete" value="delete" />';
			 	$render_node .= '</label>';
			 	$render_node .= '</fieldset>';
			 	$render_node .= '<input type="submit" value="Submit" />';
			 	$render_node .= '</form>';
			 	$render_node .= '</article>';

			 }
		}

		// Comments edit
		$route = explode('/', $_SERVER['REQUEST_URI']);

		if( $route[1] === 'comment' && $route[3] === 'edit') { 
			if(ACCESS === 'Admin' || ACCESS === 'User') { 

				$cid = $route[2];
				unset( $dbx::$result['result'] );
				$dbx::query('s|field_id|asc', 'revolver__comments', $STRUCT_COMMENTS);

				if( isset($dbx::$result['result']) ) {
					foreach ($dbx::$result['result'] as $c => $v) {

						if( $v['field_id'] === $cid ) {
				
							$render_node .= '<div class="revolver__comments_add">';
					 		$render_node .= '<form method="post" accept-charset="utf-8" />';
						 	$render_node .= '<fieldset>';
						 	$render_node .= '<legend style="width:40%">Edit review #'. $v['field_id'] .' by: '. $v['field_user_name'] .'</legend>';
						 	$render_node .= '<label>Comment:';
						 	$render_node .= '<input type="hidden" name="revolver_comments_action_edit" value="1" />';
						 	$render_node .= '<textarea id="textarea" rows="5" name="revolver_comment_content" type="text" placeholder="Comment contents">'. $v['field_content'] .'</textarea>';
						 	$render_node .= '<input type="hidden" name="revolver_comment_id" value="'. $cid .'" />';
						 	$render_node .= '<input type="hidden" name="revolver_node_id" value="'. $v['field_id'] .'" />';
						 	$render_node .= '<input type="hidden" name="revolver_comment_user_id" value="'. $v['field_user_id'] .'" />';
						 	$render_node .= '<input type="hidden" name="revolver_comment_user_name" value="'. $v['field_user_name'] .'" />';
						 	$render_node .= '<input type="hidden" name="revolver_comment_time" value="'. date('d/m/Y') .'" />';
						 	$render_node .= '</label>';
						 	$render_node .= '<label>Delete comment:';
						 	$render_node .= '&nbsp;&nbsp;<input type="checkbox" name="revolver_comments_action_delete" value="1" />';
						 	$render_node .= '</label>';
						 	$render_node .= '</fieldset>';
						 	$render_node .= '<input type="submit" value="Submit" />';
						 	$render_node .= '</form>';					
							$render_node .= '</div>';


						}

					}

				}

			}	
		}


} /* end foreach */ ?>

<?php print $render_node; ?>


</section><!-- RevolveR :: #main -->

<?php 

?>