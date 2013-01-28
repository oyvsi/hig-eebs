<?php

abstract class BaseController {
	protected $args;
	protected $view;

	public function __construct() {
		$this->view = new View();
	}
	public function setArgs($args) {
		$this->args = $args;
	}
}
