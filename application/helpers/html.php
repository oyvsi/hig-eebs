<?php

class HTML {
	public static function appLink($desc, $link) {
		return '<a href="' . $_SERVER['HTTP_HOST'] . $link . '">' . $desc . '</a>'; 
	}
}
