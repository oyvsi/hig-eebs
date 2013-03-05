<?php
error_reporting(E_ALL);

define ('__SITE_PATH', realpath(dirname(__FILE__)));
define ('__URL_PATH', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/'); 
define('__UPLOAD_DIR', 'public/');

// Go set up the app
include __SITE_PATH . '/includes/init.php';
include __SITE_PATH . '/application/bootstrap.php';
//include __SITE_PATH . '/application/Router.php';
//new Router();

?>
