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
						$render_node .= '<li><a title="'. $n['title'] .' edit" href="'. $n['route'] .'edit/">Edit</a>';
					} 
					else {
						$render_node .= '<li><a title="'. $n['title'] .'" href="'. $n['route'] .'">Read More</a>';
					}

					$render_node .= '</nav></footer>';

				}

				$render_node .= '</article>';
			
			}

		} 
		else { // Render node edit

			if(  $_SERVER['REQUEST_URI'] === $n['route'] . 'edit/' ) {

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
	//}


} /* end foreach */ ?>

<?php print $render_node; ?>


</section><!-- RevolveR :: #main -->

<?php 

?>