<?php
/**
 ** MAIN TEMPLATE PHP :: contents area
 **
 **/

// Load internal routing and nodes data
include($_SERVER["DOCUMENT_ROOT"] .'/core/libraries/NodeData.php');

?>

<!-- RevolveR :: main -->
<section class="revolver__main-contents">

<?php $render_node = ''; foreach ($node_data as $n) { 

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
		
		$render_node .= '<li><a title="'. $n['title'] .'" href="'. $n['route'] .'">Read More</a>';

		$render_node .= '</nav></footer>';

	}

	$render_node .= '</article>';

} /* end foreach */ ?>

<?php print $render_node; ?>


</section><!-- RevolveR :: #main -->

<?php 

?>