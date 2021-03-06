<?php 

/**
 ** RevolveR :: left sidebar template
 **
 **/

?>
<!-- RevolveR :: sidebar left -->
<aside itemscope itemtype="http://schema.org/WPSideBar" class="revolver__sidebar-left">

<?php 

if( INSTALLED ) {

	unset( $dbx::$result['result'] );	

	$dbx::query('s|field_id|asc|5', 'revolver__categories', $STRUCT_CATEGORIES);

	if( isset($dbx::$result['result']) ) {

		$cats = $dbx::$result['result'];
		$render = "";

		foreach ($cats as $k => $v) {

			$render .= '<div class="revolver__sidebar-category-'. $v['field_id'] .'">';
			$render .= '<h4 itemprop="name">'. $v['field_title'] .'</h4>';

			unset( $dbx::$result['result'] );

			$dbx::query('s|field_id|asc|15', 'revolver__nodes', $STRUCT_NODES);

			if(isset($dbx::$result['result'])) {

				$pages = $dbx::$result['result'];
				$render .= '<ul>';

				foreach ($pages as $p => $val) {
					if( $val['field_category'] === $v['field_id'] ) {

						$render .= '<li><a href="'. $val['field_route'] .'">'. $val['field_title'] .'</a></li>';
					}
				}

				$render .= '</ul>';
			}

			$render .= '</div>';
		}
	}

	unset( $dbx::$result['result'] );
	$dbx::query('s|field_id|asc|10', 'revolver__comments', $STRUCT_COMMENTS);

	if( isset($dbx::$result['result']) ) {

		$comments = $dbx::$result['result'];

		$render .= '<div class="revolver__sidebar-comments">';
		$render .= '<h4 itemprop="name">Latest reviews</h4>';
		$render .= '<ul>';

		foreach ($comments as $c => $val) {

			$comment = substr(strip_tags( html_entity_decode(htmlspecialchars_decode( $val['field_content'] ) ) ), 0, 50);
			$comment = rtrim($comment, "!,.-");

			$render .= '<li>#'. $val['field_id'] .' :: '. $comment .'<br /><span>by '. $val['field_user_name'] .'</span></li>';

		}

		$render .= '</ul></div>';
	}

	print $render;
}

?>
	
</aside><!-- RevolveR :: #sidebar left -->
<?php 

?>

