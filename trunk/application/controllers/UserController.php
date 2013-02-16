<?php
 
class UserController extends BaseController	{

	protected $userFields = array('firstName','lastName', 'email', 'userName', 'password', 'password2');

	public function __construct() {
		parent::__construct();
		$this->model = new UserModel();
		$this->view->setVar('title', 'User');
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
			$this->view->setVar('message', 'Updated profile');
		} catch(Exception $excpt) {
			//die($excpt->getMessage());
			//echo $excpt->getMessage();
			$this->view->setError($excpt);
		}
	  $this->profile();	// No finally in php until 5.5 :(
	}

	public function createAccount() {
		$userInput = new Form('userInfo', 'user/insertUser', 'post');
		foreach($this->userFields as $userField) {
			$userInput->addInput('text', $userField, $userField);
		}
		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('createAccount', $userInput->genForm());
		$this->view->setVar('title', 'Register');
		$this->viewFile = 'user/createAccount';
	}

	public function forgotPassword() {
		$userInput= new Form('userInfo', 'user/forgotPassword', 'post');
		$userInput->addInput('text', 'userName', 'Insert username');
		$userInput->addInput('submit', 'submit', false, 'Submit');
		$this->view->setVar('forgotPassword', $userInput->genForm());
		$this->view->setVar('title', 'Forgot password');
		$this->viewFile = 'user/forgotPassword';
		try {
			$this->model->forgotPassword($_REQUEST);
		} catch (Exception $excpt){
			$this->view->setError($excpt);
		}
	}

	public function profile() {
		if(isset($this->args[1])) {
			echo 'Show info for user ' . $this->args[1];
			$this->model->fetchUserProfile($this->args[1]);
			$this->view->setVar('userProfile', $this->model->getUserProfile());
			$this->view->setVar('title', $this->model->userName);
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
			$this->view->setVar('title', $this->model->userName);
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
			$this->view->setError($excpt);
			$this->login();
		}	
	}
	public function logOut() {
		session_destroy();
		header('location: ' . __URL_PATH);
	}
}
