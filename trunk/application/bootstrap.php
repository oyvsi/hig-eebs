<?php

if(isset($_GET['rc'])) {
	$url = rtrim($_GET['rc'], '/'); // We don't want no empty arg
	$args = explode('/', $url);
} else {
	$url = NULL;
	$args = NULL;
}

// Load index controller by default, or first arg if specified
$controller = ($url === null) ? 'null' : array_shift($args); 
$controller = ucfirst($controller);
$postfix = 'Controller';

if(class_exists($controller . $postfix)) {	
	$controllerName = $controller . $postfix;
	$controllerClass = new $controllerName;
	$controllerClass->setUp();
//	echo "Created controller...";
	if(count($args > 1)) { // Pass args that are not controller class
		$controllerClass->setArgs($args);
	}
	// Second arg in url is our "action", try that as a method-call
	if(isset($args[0]) && method_exists($controllerClass, $args[0])) {
		$controllerClass->$args[0]();
	} 	
} else {
	$controllerClass = new IndexController();
	$controllerClass->setUp();
	$method = strtolower($controller);
//	echo $method;
	if(method_exists($controllerClass, $method)) {
//		echo "Calling method $method";
		$controllerClass->$method();
	} else {
		$controllerClass->loadIndex();
	}
}
	$controllerClass->render();


