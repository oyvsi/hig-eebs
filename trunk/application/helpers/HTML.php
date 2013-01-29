<?php

class HTML {
	public static function appLink($desc, $link) {
		return '<a href="http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $link . '/">' . $desc . '</a>'; 
	}
}
