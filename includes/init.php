<?php
include __SITE_PATH . '/application/conf/database.php';
include __SITE_PATH . '/application/bootstrap.php';

/**
* Autoloads the application's classfiles
* Is automagically called by php
*
* @param string $className - The name of the class  
*/
function __autoload($className) {
	$paths = array(array('path' => __SITE_PATH . '/application/controllers/', 'postfix' => '.php'),
				array('path' => __SITE_PATH . '/application/models/', 'postfix' => '.php'),
				array('path' => __SITE_PATH . '/application/libs/', 'postfix' => '.php'),
				array('path' => __SITE_PATH . '/application/helpers/', 'postfix' => '.php'));

	foreach($paths as $path) {
		$filename = $path['path'] . $className . $path['postfix'];
		if(is_readable($filename)) {
			include($filename);
			return true;	
		} 
	}
}

?>
