<?php
 
class UserController extends BaseController	{
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
		if($this->user) {
			$this->model->fetchUserInfo($this->user->model->userID);
			try {
				$this->model->updateUser($_REQUEST);
				$this->view->setVar('message', 'Updated profile');
			} catch(Exception $excpt) {
				//die($excpt->getMessage());
				//echo $excpt->getMessage();
				$this->view->setError($excpt);
			}
			$this->profile();	// No finally in php until 5.5 :(
		} else {	
			$this->view->setError(new Exception('Not accessible when not authed...'));
		}
	}

	public function createAccount() {
		$userFields = $this->model->getUserFields();

		$userInput = new Form('userInfo', 'user/insertUser', 'post');
		foreach($userFields as $userField) {
			$userInput->addInput($userField['fieldType'], $userField['table'], $userField['view']);
		}
		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('createAccount', $userInput->genForm());
		$this->view->setVar('title', 'Register');
		$this->view->viewFile = 'user/createAccount';
	}

	public function forgotPassword() {
		$userInput= new Form('userInfo', 'user/forgotPassword', 'post');
		$userInput->addInput('text', 'userName', 'Insert username');
		$userInput->addInput('submit', 'submit', false, 'Submit');
		$this->view->setVar('forgotPassword', $userInput->genForm());
		$this->view->setVar('title', 'Forgot password');
		$this->view->viewFile = 'user/forgotPassword';

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
			$this->view->viewFile = 'user/profile';
		}
		elseif($this->user()) {
			$userData = $this->model->fetchUserInfo($_SESSION['userID']);
			$userData['password2'] = $userData['password'] = $userData['picture'] = '';

			$userInput = new Form('userInfo', 'user/updateUser', 'post');
			foreach($this->model->getUserFields() as $userField) {
				$userInput->addInput($userField['fieldType'], $userField['table'], $userField['view'], $userData[$userField['table']]);
			}
			$userInput->addInput('submit', 'submit', false, 'Submit');
			$this->view->setVar('title', $this->model->userName);
			$this->view->setVar('createAccount', $userInput->genForm());
			if($userData['pictureURL'] != null) {
				$this->view->setVar('profilePicture', $userData['pictureURL']);
				$this->view->setVar('profilePictureThumb', ImageUpload::thumbURLfromURL($userData['pictureURL']));
			}
			$this->view->viewFile = 'user/createAccount';	
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
			$this->view->viewFile = 'login';
		}	
	}
	public function loginDo() {
		try {
			$this->model->checkLogin($_POST);
			$_SESSION['userID'] = $this->model->userID;
			$_SESSION['userLevel'] = $this->model->userLevel;
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
