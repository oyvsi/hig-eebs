<?php

class HTML {
	public static function appLink($link, $desc) {
		return '<a href="http://' . __URL_PATH . $link . '/">' . $desc . '</a>'; 
	}
	public static function cssLink($name) {
		return '<link rel="stylesheet" type="text/css" href="' . $_SERVER['REQUEST_URI'] . 'media/style/' . $name . '.css" />';
	}
}

