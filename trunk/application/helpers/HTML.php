<?php

class HTML {
	public static function appLink($link, $desc) {
		return '<a href="' . __URL_PATH . $link . '/">' . $desc . '</a>'; 
	}
	public static function jsLink($name) {
		return '<script type="text/javascript" src="' . __URL_PATH . 'media/js/' . $name . '.js"></script>';
	}
	public static function cssLink($name) {
		return '<link rel="stylesheet" type="text/css" href="' . __URL_PATH . 'media/style/' . $name . '.css" />';
	}
}

