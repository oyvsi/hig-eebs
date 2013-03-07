<?php

class HTML {
	
	/**
	* fuction returns a link to use in html.
	* @param string $link
	* @param string $desc
	* @return string
	*/	
	public static function appLink($link, $desc) {
		return '<a href="' . __URL_PATH . $link . '/">' . $desc . '</a>'; 
	}
	
	/**
	* fuction returns correct javascript tag with requested scriptfile.
	* @param string $name
	* @return string
	*/
	public static function jsLink($name) {
		return '<script type="text/javascript" src="' . __URL_PATH . 'media/js/' . $name . '.js"></script>';
	}
	
	/**
	* fuction returns correct stylesheet tag.
	* @param string $name
	* @return string
	*/	
	public static function cssLink($name) {
		return '<link rel="stylesheet" type="text/css" href="' . __URL_PATH . 'media/style/' . $name . '.css" />';
	}
	
	/**
	* fuction returns tag for link to get pictures in a fancy box
	* when clicked.
	* @param string $image
	* @param string $thumb
	* @return string
	*/	
	public static function fancyBoxImage($image, $thumb) {
		return '<a class="fancybox" rel="group" href="' . $image . '"><img src="' . $thumb . '" alt="" /></a>';
	}
	
	/**
	* fuction redirects to parameter location.
	* @param string $url
	*/	
	public static function redirect($url) {
		header('Location: ' . __URL_PATH . $url);
	}
}


