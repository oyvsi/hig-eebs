<?php

abstract class BaseController {
	protected $user;
	protected $args;
	protected $view;
	protected $model;
	protected $viewFile;

	public function __construct() {
		$this->view = new View();
		$this->viewFile = NULL;

	}
	public function setUp() {
		if(Auth::checkLogin()) {
			$this->user = new UserController();
			$this->user->fetchUserInfo($_SESSION['userID']);
		} else {
			$this->user = NULL;
		}
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

