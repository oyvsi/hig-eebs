<?php

abstract class BaseController {
	protected $args;
	protected $view;
	protected $model;
	protected $viewFile;

	public function __construct() {
		@session_start(); // dunno where it belongs
		$this->view = new View();
		$this->viewFile = NULL;
	}
	public function setArgs($args) {
		$this->args = $args;
	}
	public function __set($key, $value) {
		$this->key = $value;
	}
	public function render() {
		if($this->viewFile) {
			$this->view->render($this->viewFile);
		}
	}
}

