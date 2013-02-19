<?php
class Auth {
	public static function checkLogin() {
		if(isset($_SESSION['userID'])) {
			return true;
		} else {
			return false;
		}
	}
	public static function checkAdmin() {
		if(isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 1) {
			return true;
		} else {
			return false;
		}
	}
}
