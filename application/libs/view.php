<?php

class View {
	private $title;
	public function setTitle($title) {
		$this->title = $title;
	}
	public function render($name) {
		require(__SITE_PATH . '/application/views/header.php');
		require(__SITE_PATH . '/application/views/' . $name . '.php');
		require(__SITE_PATH . '/application/views/footer.php');
	}
} 
