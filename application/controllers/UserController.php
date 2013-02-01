<?php
class UserController extends BaseController {

	public function __construct() {
		parent::__construct();
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

	public function login() {
		$this->view->render('login');
	}
	public function loginDo() {
		print_r($_POST);
	}

}




