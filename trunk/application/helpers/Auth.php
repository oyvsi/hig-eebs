<?php
class Auth {

	/**
	* Fuction returns trur if a user is logged in.
	* @return bool
	*/
	public static function checkLogin() {
		if(isset($_SESSION['userID'])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	* Fucntions checks if a user id admin and returns true if so.
	* @return bool
	*/
	public static function checkAdmin() {
		if(isset($_SESSION['userLevel']) && $_SESSION['userLevel'] == 1) {
			return true;
		} else {
			return false;
		}
	}
}
