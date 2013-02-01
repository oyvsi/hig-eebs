<?php
class Auth {
	public static function checkLogin() {
		if(isset($_SESSION['uid'])) {
			return true;
		} else {
			return false;
		}
	}
}
