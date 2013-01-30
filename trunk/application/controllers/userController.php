<?php
class UserController extends BaseController {

	public function __construct() {
		parent::__construct();
		echo "UserController here<br>\n<br>\n";
	}

	public function createAccount() {
		echo "Create me an blugg";
	}

	public function resetPassword() {
		echo "Forgot pw =( or got hackedlol";
	}

	public function editInformation() {
		echo "I have aged or become a girl";
	}

	public function changeDisplayName() {
		echo "Ive hated on some peeps";
	}

}




