<?php
 
class UserController extends BaseController	{


	protected $userFields = array('firstName','lastName', 'email', 'userName', 'password', 'password2')

	public function __construct() {
		parent::__construct();
		$this->model = new UserModel();
		
	}


	public function insertUser() {
		$this->model->insertUser($_POST);


	}

	public function createAccount() {
		$userInput = new Form('userInfo', '../insertUser', 'post');
		foreach($this->userFields as $userField) {
			$userInput->addInput('text', $userField, $userField);
		}
		$userInput->addInput('submit', 'button', null, null, 'Submit');
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
		$this->view->render('login');
	}
	public function loginDo() {
		print_r($_POST);
	}

}




