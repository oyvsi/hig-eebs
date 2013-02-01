<?php
 
class UserController extends BaseController	{

	protected $userFields = array('firstName','lastName', 'email', 'userName', 'password', 'password2');

	public function __construct() {
		parent::__construct();
		$this->model = new UserModel();
	}

	public function fetchUserInfo($userID) {
		$this->model->fetchUserInfo($userID);
	}

	public function insertUser() {
		$this->model->insertUser($_REQUEST);

	}

	public function createAccount() {
		$userInput = new Form('userInfo', 'user/insertUser', 'post');
		foreach($this->userFields as $userField) {
			$userInput->addInput('text', $userField, $userField);
		}
		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('createAccount', $userInput->genForm());

		$this->view->render('user/createAccount');
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
		if(Auth::checkLogin()) {
			echo "Logged in already...";
		} else {
			$this->view->render('login');
		}	
	}
	public function loginDo() {
		try {
			$this->model->checkLogin($_POST);
			$_SESSION['userID'] = $this->model->userID;
			header('location: /');
		} catch(Exception $excpt) {
			echo 'Error ' . $excpt->getMessage();
			header('location: login');
		}	
	}
	public function logOut() {
		session_destroy();
	}

}
