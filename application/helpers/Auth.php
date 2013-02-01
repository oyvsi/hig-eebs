<?php
class Auth {
	public static function checkLogin() {
		if(isset($_SESSION['userID'])) {
			return true;
		} else {
			return false;
		}
	}
}
