<?php 

/**
 ** RevolveR :: bottom template
 **
 **/

?>

<script src="<?php print site_host; ?>/app/revolver.js?production=1.0.8" async id="revolver">       
    
    // charging weapons with namespace
    const revolver = new Revolver('$');

    $.storage(['Revolver=1.0.8'],'set');

</script>

<?php 

?>