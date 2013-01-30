<?php

class View {
	private $vars;
	public function setVar($key, $value) {
		$this->vars[$key] = $value;
	}
	public function render($name) {
		require(__SITE_PATH . '/application/views/header.php');
		require(__SITE_PATH . '/application/views/' . $name . '.php');
		require(__SITE_PATH . '/application/views/footer.php');
	}
} 
