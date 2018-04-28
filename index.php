<?php 
/**
 ** RevolveR core v.0.2
 ** 
 **/

// Config
require_once('./private/config.php');
require_once('./core/struct/DataBase.php');

// Libraries
require_once('./core/libraries/DBX.php');
require_once('./core/libraries/Cipher.php');
require_once('./core/libraries/SafeHTML.php');
require_once('./core/libraries/Auth.php');
require_once('./core/libraries/Menu.php');
require_once('./core/libraries/Route.php');
require_once('./core/libraries/Node.php');
require_once('./core/libraries/Variables.php');
require_once('./core/libraries/Mail.php');

// Init Core
require_once('./core/core.php');

// Site render
require_once('./core/templates/index.php');

?>
