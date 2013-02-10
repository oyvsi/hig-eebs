<?php

abstract class BaseController {
	protected $user;
	protected $args;
	protected $view;
	protected $model;
	protected $viewFile;

	public function __construct() {
		@session_start();
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

	public function user() {
		if($_SESSION['userID']) {
			$this->user = new UserController();
			$this->user->fetchUserInfo($_SESSION['userID']);
			return true;
		}
		return false;
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

