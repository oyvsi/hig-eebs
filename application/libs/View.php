<?php

class View {
	private $vars;
	public $renderHeader = true;
	public $renderFooter = true;

	public function setVar($key, $value) {
		$this->vars[$key] = $value;
	}
	public function render($name) {
		if($this->renderHeader === true) {
			require(__SITE_PATH . '/application/views/header.php');
		}
		require(__SITE_PATH . '/application/views/' . $name . '.php');
		if($this->renderFooter === true) {
			require(__SITE_PATH . '/application/views/footer.php');
		}
	}
} 
