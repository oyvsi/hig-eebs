<?php

class View {
	private $vars;
	private $error = false;
	public $renderHeader = true;
	public $renderFooter = true;

	public function setVar($key, $value) {
		$this->vars[$key] = $value;
	}

	public function setError($exception) {
		$this->error = $exception;
	}

	public function render($name) {
		if($this->renderHeader === true) {
			require(__SITE_PATH . '/application/views/header.php');
		}
		
		if($this->error !== false) {
			require(__SITE_PATH . '/application/views/error.php');
		}
	
		require(__SITE_PATH . '/application/views/' . $name . '.php');
		
		if($this->renderFooter === true) {
			require(__SITE_PATH . '/application/views/footer.php');
		}
	
	}
} 
