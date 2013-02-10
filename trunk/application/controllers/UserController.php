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
	
	public function updateUser() {
		try {
			$this->model->fetchUserInfo($this->user->model->userID);
			$this->model->updateUser($_REQUEST);
			header('location: profile');
		} catch(Exception $excpt) {
			echo 'Error ' . $excpt->getMessage();
			header('location: profile'); //feilmelding må være med
		}	


	}

	public function createAccount() {
		$userInput = new Form('userInfo', 'user/insertUser', 'post');
		foreach($this->userFields as $userField) {
			$userInput->addInput('text', $userField, $userField);
		}
		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('createAccount', $userInput->genForm());
		$this->viewFile = 'user/createAccount';
	}

	public function resetPassword() {
		echo "Forgot pw =( or got hackedlol";
	}

	public function profile() {
		if(isset($this->args[1])) {
			echo 'Show info for user ' . $this->args[1];
			$this->model->fetchUserProfile($this->args[1]);
			$this->view->setVar('userProfile', $this->model->getUserProfile());
			$this->viewFile = 'user/profile';
		}
		elseif($this->user()) {
			$userData = $this->model->fetchUserInfo($_SESSION['userID']);
			$userData = $userData[0];
			$userData['password2'] = $userData['password'] = '';

			$userInput = new Form('userInfo', 'user/updateUser', 'post');
			foreach($this->userFields as $userField) {
				$userInput->addInput('text', $userField, $userField, $userData[$userField]);
			}
			$userInput->addInput('submit', 'submit', false, 'Submit');
			$this->view->setVar('createAccount', $userInput->genForm());
			$this->viewFile = 'user/createAccount';	
		}
		else {
			echo 'No username specified and you\'re not authed. Goodbye from userController';
		}
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
			header('location: ' . __URL_PATH);
		} catch(Exception $excpt) {
			echo 'Error ' . $excpt->getMessage();
			header('location: login');
		}	
	}
	public function logOut() {
		session_destroy();
		header('location: ' . __URL_PATH);
	}
}