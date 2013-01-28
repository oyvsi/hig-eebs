<?php

abstract class baseView {
	public function render($name) {
		require(__SITE_PATH . '/application/views/header.php');
		require(__SITE_PATH . '/application/views/' . $name);
		require(__SITE_PATH . '/application/views/footer.php');
	}
} 
