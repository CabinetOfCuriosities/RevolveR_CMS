<?php 

/**
 ** RevolveR :: head template
 **
 **/

$site_title = !empty(TITLE) ? TITLE : $site_title;
foreach (main_nodes as $mn => $val) {
	if( $_SERVER['REQUEST_URI'] === $val['route'] ) {
		if( $_SERVER['REQUEST_URI'] !== '/' ) {
			$site_title = $val['title'];
		}
	} 
}

$site_name = !empty(BRAND) ? BRAND : $site_name;
$site_description = !empty(DESCRIPTION) ? DESCRIPTION : $site_description;

?>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title><?php print $site_title; ?> | <?php print $site_name; ?></title>

        <meta name="description" content="<?php print $site_description; ?>">
        
        <link rel="stylesheet" href="<?php print site_host; ?>/app/core.css" />
    </head>

<?php 

?>