<?php
error_reporting(E_ALL);
define ('__SITE_PATH', realpath(dirname(__FILE__)));
define ('__URL_PATH', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); 

// Go set up the app
include 'includes/init.php';
?>
