<?php

class View {
	private $vars;
	public function setVar($key, $value) {
		$this->vars[$key] = $value;
	}
	public function render($name, $standalone=false) {
		if($standalone === true) {
			require(__SITE_PATH . '/application/views/' . $name . '.php');
		} else {
			require(__SITE_PATH . '/application/views/header.php');
			require(__SITE_PATH . '/application/views/' . $name . '.php');
			require(__SITE_PATH . '/application/views/footer.php');
		}
	}
} 
