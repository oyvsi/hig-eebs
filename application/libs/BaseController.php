<?php
/**
 * Abstract class for controllers
 * Implements common functionality
 * 
 * @author Team Henkars
 */
abstract class BaseController {
   protected $render = true;
	protected $user;
	protected $args;
	protected $view;
	protected $model;
	protected $viewFile;

	/**
	 *  Default construction. Starts session and creates view
	 */
	public function __construct() {
		@session_start();
		$this->view = new View();
		$this->viewFile = NULL;
					
	}

	/**
	 * Sets up an user object is user is authenticated
	 */
	public function setUp() {
		if(Auth::checkLogin()) {
			$this->user = new UserController();
			$this->user->fetchUserInfo($_SESSION['userID']);
		} else {
			$this->user = NULL;
		}
	 }

	/**
	 * Workaround function. Depricated 
	 */
	public function user() {
		if(Auth::checkLogin()) {
			$this->user = new UserController();
			$this->user->fetchUserInfo($_SESSION['userID']);
			return true;
		}
		return false;
	}

	/**
	 * Set the argument-array
	 * @param array $args
	 */
	public function setArgs($args) {
		$this->args = $args;
	}

	/**
	 * Automagic php function to set unknown properties
	 */
	public function __set($key, $value) {
		$this->key = $value;
	}

	/**
	 * Displays to the end user 
	 */
	public function render() {
      if($this->render) {
         $this->view->render();
      }
	}
}