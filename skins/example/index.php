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
    
    <body itemscope itemtype="http://schema.org/WebPage">

        <!-- RevolveR :: root -->
        <main id="RevolverRoot" itemscope itemprop="mainContentOfPage">

            <?php include('header.php'); ?>
           
            <?php if( SKIN_LEFT === '1' ) {
                include('sidebar-left.php');
            } ?>

            <?php include('main.php'); ?>

            <?php if( SKIN_RIGHT === '1' ) {
                include('sidebar-right.php');
            } ?>

            <?php include('footer.php'); ?>

        </main><!-- RevolveR :: #root -->

    <!-- RevolveR :: footer -->
    <?php include('bottom.php'); ?><!-- RevolveR :: #footer -->
    </body>
</html>