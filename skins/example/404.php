<?php
/**
 ** 404 TEMPLATE PHP :: contents area
 **
 **/

header("HTTP/1.0 404 Not Found");

?>

<!DOCTYPE html>
<html>
    <!-- RevolveR :: head -->
    <?php include('head.php'); ?><!-- RevolveR:: #head-->
    <body>

        <!-- RevolveR :: root -->
        <main id="RevolverRoot">

            <?php include('header.php'); ?>
           
            <?php if(isset($site_sidebars['left'])) {
                if( $site_sidebars['left'] ) include('sidebar-left.php');
            } ?>

			<!-- RevolveR :: main -->
			<section class="revolver__main-contents">

			<?php if( CONTENTS_FLAG && $_SERVER['REQUEST_URI'] !== '/') {

				$render_node .= '<article class="revolver__article article-id-404">';
				$render_node .= '<header class="revolver__article-header">';
				$render_node .= '<h1>Route not found</h1>';
				$render_node .= '</header>';
				$render_node .= '<div class="revolver__article-contents"><p>Route <b>'. $_SERVER['REQUEST_URI'] .'</b> was not found on this host!</p> <p><a href="/">Begin at homepage!</a></p></div>';
				$render_node .= '<footer class="revolver__article-footer"><nav><ul><li><a title="Homepage" href="/">Homepage</a></li></ul></nav></footer></article>';
			}

			print $render_node; ?>


			</section><!-- RevolveR :: #main -->

			<?php ?>

            <?php if(isset($site_sidebars['right'])) {
                if( $site_sidebars['right'] ) include('sidebar-right.php');
            } ?>

            <?php include('footer.php'); ?>

        </main><!-- RevolveR :: #root -->

    <!-- RevolveR :: footer -->
    <?php include('bottom.php'); ?><!-- RevolveR :: #footer -->
    </body>
</html>