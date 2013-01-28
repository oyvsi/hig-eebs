<?php

abstract class BaseController {
	protected $args;
	protected $view;

	public function setArgs($args) {
		$this->args = $args;
	}
}
