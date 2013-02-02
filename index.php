<?php
error_reporting(E_ALL);
define ('__SITE_PATH', realpath(dirname(__FILE__)));
define ('__URL_PATH', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/'); 
//define('__URL_PATH', 'http://bmore.teamhenkars.com/www-tek/hig-eebs/');
//echo 'URL_PATH defined as: ' . __URL_PATH . '<br />';
// Go set up the app
include 'includes/init.php';
?>
