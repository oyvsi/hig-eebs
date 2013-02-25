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

	public function loadComments($postID, $insertRedirect, $id = false, $isOwner = false) {
		if($id !== false) {
			$comments = $this->model->getComment($id);
		} else {
			$comments = $this->model->getComments($postID);
		}
		$this->view->setVar('isOwner', $isOwner);	
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
			if(isset($this->args[1]) && isset($_POST['comment'])) {
				$userName = ($this->user()) ? $this->user->model->userName : $this->fbUser['username'];
				$_POST['name'] = $userName;
				$this->model->insertComment($this->args[1], $_POST);	
				//header('Location: '. __URL_PATH . $_POST['redirect'] . '/comments');
			} else {
				echo 'NOGO!'; print_r($_POST);
			}
		}
	}
	public function flag() {
		if(isset($this->args[1])) {
			$form = new Form('reportComment', 'comments/flag/' . $this->args[1], 'post');
			$form->addTextArea('reportComment', 10, 60, 'Report comment because');
			$form->addInput('hidden', 'commentID', false, $this->args[1]);
			$form->addInput('submit', 'submit');
			$this->view->setVar('form', $form->genForm());
			$this->viewFile = 'reportComment';
		} 
		
		if(isset($_POST['reportComment'])) {
			try {
				$this->model->flagComment($_POST['commentID'], $_POST);
				$this->view->setVar('message', 'Thank you. Your report will be brought to the administrators');
			} catch(Exception $excpt) {
				$this->view->setError($excpt);
			}
			$this->viewFile = 'reportComment';
		}
	}

	public function getFlagged() {
		try {
		$data = $this->model->getFlagged();
		$this->view->setVar('flagged', $data);
		$this->viewFile = 'admin/flaggedComments';
		} catch(Exception $excpt) {
			$this->view->setError($excpt);
		}
		
	}

	public function delete() {
		if(isset($this->args[1])) {
			$this->model->delete($this->args[1]);
		}
	}
}
