<?php 

/**
 ** RevolveR :: main menu template
 **
 ** variables :
 **
 ** - $main_menu - global main menu array
 **
 **/

?>

<!-- RevolveR :: main menu -->
<nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="revolver__main-menu">
	<?php $menu::render(main_nodes, 'ul'); ?>
</nav>
<!-- RevolveR :: #main menu -->

<?php 

?>