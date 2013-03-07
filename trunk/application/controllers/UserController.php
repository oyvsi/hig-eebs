<?php
/**
 * @author Team Henkars
 *
 * This class holds functions for a user
 */

class UserController extends BaseController	{
	/**
	 * Default constructor
	 * sets up the user info
	 */
	public function __construct() {
		parent::__construct();
		$this->model = new UserModel();
		$this->view->setVar('title', 'Welcome!');
	}

	/**
	 * Fuction that fetches user info
	 * based on its userID
	 * @param string $userID
	 */
	public function fetchUserInfo($userID) {
		$this->model->fetchUserInfo($userID);
	}

	/**
	 * Fuction to insert a new user into the database
	 */
	public function insertUser() {
		try {
			$id = $this->model->insertUser($_REQUEST);
			HTML::redirect('blog/view/' . $_REQUEST['userName']);
			$_SESSION['userID'] = $id;
			$this->view->setVar('message', 'Success! You can now log in.');
		} catch(Exception $excpt) {
			$this->view->setError($excpt);
			$this->createAccount();
		}
	}

	/**
	 * Fuction to update user profile
	 * Only accesseble by autenticated users
	 */
	public function updateUser() {
		if($this->user) {
			$this->model->fetchUserInfo($this->user->model->userID);
			try {
				$this->model->updateUser($_REQUEST);
				$this->view->setVar('message', 'Updated profile');
			} catch(Exception $excpt) {
				$this->view->setError($excpt);
			}
			$this->profile();	// No finally in php until 5.5 :(
		} else {	
			$this->view->setError(new Exception('Not accessible when not authed...'));
		}
	}

	/**
	 * Fuction to generate form when creating 
	 * a new account.
	 */
	public function createAccount() {

		$userInput = new Form('userInfo', 'user/insertUser', 'post');
		foreach($this->model->getUserFields() as $userField) {
			$userInput->addInput($userField['fieldType'], $userField['table'], $userField['view']);
		}

		$userInput->addSelect('theme', 'Theme', $this->model->getThemes());

		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('createAccount', $userInput->genForm());
		$this->view->setVar('title', 'Register');
		$this->view->addViewFile('user/createAccount');

	}

	/**
	 * Function that generates a form so
	 * users can request a new password
	 */
	public function forgotPassword() {
		$userInput= new Form('userInfo', 'user/forgotPassword', 'post');
		$userInput->addInput('text', 'userName', 'Insert username');
		$userInput->addInput('submit', 'submit', false, 'Submit');
		$this->view->setVar('forgotPassword', $userInput->genForm());
		$this->view->setVar('title', 'Forgot password');
		$this->view->addViewFile('user/forgotPassword');

		if(isset($_POST['userName'])) {
			try {
				$this->model->forgotPassword($_REQUEST);
				$this->view->setVar('message', 'We sent you an email with a new password');
			} catch (Exception $excpt){
				$this->view->setError($excpt);
			}
		}
	}

	/**
	 * Function that views a users profile.
	 * optional arguments from url: userName
	 * shows either a requested user or 
	 * the logged in users profileinfo.
	 *
	 * url is blog/view/$userName/
	 */
	public function profile() {
		if(isset($this->args[1])) {
			$this->view->addViewFile('user/profile');
			$blog = new BlogController();
			$blog->view($this->args[1]);
		}
		elseif($this->user()) {

			$userData = $this->model->fetchUserInfo($_SESSION['userID']);
			$userData['password2'] = $userData['password'] = $userData['picture'] = $userData['background'] = '';

			$userInput = new Form('userInfo', 'user/updateUser', 'post');
			foreach($this->model->getUserFields() as $userField) {
				$userInput->addInput($userField['fieldType'], $userField['table'], $userField['view'], $userData[$userField['table']]);
			}

			$userInput->addSelect('theme', 'Theme', $this->model->getThemes(), $userData['theme']);
			$userInput->addInput('submit', 'submit', false, 'Submit');

			$this->view->setVar('userInfo', $userData);
			$this->view->setVar('title', $this->model->userName);
			$this->view->setVar('createAccount', $userInput->genForm());
			$this->view->addViewFile('user/createAccount');	
		}
		else {
			echo 'No username specified and you\'re not authed. Goodbye from userController';
		}
	}

	/**
	 * function sends unautenticated user
	 * to loginscreen.
	 */
	public function login() {
		if(Auth::checkLogin()) {
			echo "Logged in already...";
		} else {
			$this->view->addViewFile('login');
		}	
	}

	/**
	 * Function checks login and sets session variables.
	 */	
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

	/**
	 * Function to log out a user.
	 */
	public function logOut() {
		session_destroy();
		HTML::redirect('');
	}
}
