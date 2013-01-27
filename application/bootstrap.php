<?php
	$url = isset($_GET['rc']) ? $_GET['rc'] : null;
	$args = explode('/', $url);
	$controller = ($url === null) ? 'index' : array_shift($args);
	$controllerFile = __SITE_PATH . '/application/controllers/' . $controller . 'Controller.php';
	if(file_exists($controllerFile)) {
		include($controllerFile);
		$controller = ucfirst($controller . 'Controller');
		if(class_exists($controller)) {	
			$controllerClass = new $controller;
			if(isset($args[0]) && method_exists($controllerClass, $args[0])) {
				$controllerClass->$args[0];
			}

			if(count($args > 1)) {
				$controllerClass->setArgs($args);
			}
		} else {
			echo 'No such class';
			echo 'Tried: ' . __SITE_PATH . '/application/controllers/' . $controller;
		}
	} else { echo "urag"; 
			echo 'Tried: ' . __SITE_PATH . '/application/controllers/' . $controller . 'Controller.php';
	}

