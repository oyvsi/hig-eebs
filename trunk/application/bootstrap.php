<?php

$url = isset($_GET['rc']) ? $_GET['rc'] : null;
$args = explode('/', $url);
$controller = ($url === null) ? 'Index' : array_shift($args);
$controller = ucfirst($controller) . 'Controller';

if(class_exists($controller)) {	
	$controllerClass = new $controller;
	if(count($args > 1)) {
		$controllerClass->setArgs($args);
	}

	if(isset($args[0]) && method_exists($controllerClass, $args[0])) {
		$controllerClass->$args[0]();
	}

} else {
	echo 'Class does not exist! Tried: ' . __SITE_PATH . '/application/controllers/' . $controller;
}

