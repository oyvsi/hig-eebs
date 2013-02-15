<?php
 
class CommentsController extends BaseController	{
	private $fb;
   private $fbUser;

	public function __construct() {
		parent::__construct();
		$this->model = new CommentsModel();
		$this->fb  = new FacebookLogin();
		$this->fbUser = $this->fb->checkLogin();
	}

	public function loadComments($postID, $insertRedirect) {
		$comments = $this->model->getComments($postID);
		$this->view->setVar('comments', $comments);
		$user = false;

		$userInput = new Form('comment', 'comments/commentDo/' . $postID, 'post');
		$userInput->addInput('hidden', 'redirect', false, $insertRedirect);
		if($this->user()) {
			$user = $this->user->model->userName;
		} else {
			if($this->fbUser) {
				$user = $this->fbUser['username'];
				$this->view->setVar('fbLogoutURL', $this->fb->getLogoutURL());
			} else {
				$this->view->setVar('loginError', true);
				$this->view->setVar('fbLoginURL', $this->fb->getLoginURL());
			}
		}	
		$userInput->addTextArea('comment', 10, 60);
		$userInput->addInput('submit', 'submit', false, 'Submit');

		$this->view->renderHeader = false;
		$this->view->setVar('commentForm', $userInput->genForm());
		$this->view->setVar('userName', $user);
		
		$this->view->render('comments');
	}

	public function commentDo() {
		if($this->user() || $this->fbUser) { 
			if(isset($this->args[1]) && isset($_POST['name']) && isset($_POST['comment'])) {
				$this->model->insertComment($this->args[1], $_POST);	
				header('Location: '. __URL_PATH . $_POST['redirect'] . '/comments');
			} else {
				echo 'NOGO!'; print_r($_POST);
			}
		}
	}
}
