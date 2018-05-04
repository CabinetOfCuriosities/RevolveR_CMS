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

$site_data = site_host . $_SERVER['REQUEST_URI'];

?>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="<?php print $site_description; ?>" />
        <meta name="host" content="<?php print $site_data ;?>" />

        <title><?php print $site_title; ?> | <?php print $site_name; ?></title>

        <link rel="canonical" type="text/css" href="<?php print $site_data; ?>" />
        <link rel="alternate" type="application/rss+xml" href="<?php print site_host; ?>/rss.php" />
        <link rel="stylesheet" href="<?php print site_host; ?>/app/core.css" />

    </head>

<?php 

?>