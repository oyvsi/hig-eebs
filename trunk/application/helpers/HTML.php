<?php

class HTML {
	public static function appLink($link, $desc) {
		return '<a href="' . __URL_PATH . $link . '/">' . $desc . '</a>'; 
	}
	public static function cssLink($name) {
		return '<link rel="stylesheet" type="text/css" href="' . __URL_PATH . '/media/style/' . $name . '.css" />';
	}
}

