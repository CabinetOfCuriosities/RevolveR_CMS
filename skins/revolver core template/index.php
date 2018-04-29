<?php 

/**
 ** ROOT TEMPLATE PHP
 **
 **/

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

            <?php include('main.php'); ?>

            <?php if(isset($site_sidebars['right'])) {
                if( $site_sidebars['right'] ) include('sidebar-right.php');
            } ?>

            <?php include('footer.php'); ?>

        </main><!-- RevolveR :: #root -->

    <!-- RevolveR :: footer -->
    <?php include('bottom.php'); ?><!-- RevolveR :: #footer -->
    </body>
</html>