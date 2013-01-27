<?php

abstract class BaseController {
	protected $args;

	public function setArgs($args) {
		$this->args = $args;
	}
}
