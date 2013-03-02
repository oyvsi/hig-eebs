<?php

class View {
	private $vars;
	private $error = false;
	public $renderHeader = true;
	public $renderFooter = true;
	protected $viewFile = array();


	public function setVar($key, $value) {
		$this->vars[$key] = $value;
	}

	public function setError($exception) {
		$this->error = $exception;
	}

	public function render() {
		if($this->renderHeader === true) {
		  require(__SITE_PATH . '/application/views/header.php');
		}
		
		if($this->error !== false) {
	         require(__SITE_PATH . '/application/views/error.php');
		} 	
		
		if(!empty($this->viewFile)) {
			foreach($this->viewFile as $views) {
					require(__SITE_PATH . '/application/views/' . $views . '.php');
			}
		} 
		
		if($this->renderFooter === true) {
		 require(__SITE_PATH . '/application/views/footer.php');
		}
	}


	public function addViewFile($viewFile) {
		array_push($this->viewFile, $viewFile);
	}	
} 
