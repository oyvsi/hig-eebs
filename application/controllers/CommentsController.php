<?php
 
class CommentsController extends BaseController	{
	private $fb;
   	private $fbUser;
	private $blogpostModel;
	private $commentModel;
	public function __construct() {
		parent::__construct();
		$this->commentModel = new CommentsModel();
		$this->blogpostModel = new BlogpostModel();
		$this->fb  = new FacebookLogin();
		$this->fbUser = $this->fb->checkLogin();
		$this->user(); // workaround for not being created from bootstrap
	}

	public function view() {
		$blogName = $this->args[1];
		$postID = $this->args[2];
		$id = isset($this->args[4]) ? $this->args[4] : false;
		$post = $this->blogpostModel->getPost($blogName, $postID);
		$isOwner = $this->correctUser($post[0]['userID']);
		if($id !== false) {
			$comments = $this->commentModel->getComment($id);
		} else {
			$comments = $this->commentModel->getComments($this->blogpostModel->postID);
		}
		$this->view->setVar('isOwner', $isOwner);	
		$this->view->setVar('comments', $comments);

		$user = false;

		$userInput = new Form('comment', 'comments/commentDo/' . $postID, 'post');
		#$userInput->addInput('hidden', 'redirect', false, $insertRedirect);
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
		
		$this->view->viewFile = 'comments';
	}

	public function commentDo() {
		if($this->user() || $this->fbUser) { 
			if(isset($this->args[1]) && isset($_POST['comment'])) {
				$userName = ($this->user()) ? $this->user->model->userName : $this->fbUser['username'];
				$_POST['name'] = $userName;
				$this->commentModel->insertComment($this->args[1], $_POST);	
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
			$this->view->viewFile = 'reportComment';
		} 
		
		if(isset($_POST['reportComment'])) {
			try {
				$this->commentModel->flagComment($_POST['commentID'], $_POST);
				$this->view->setVar('message', 'Thank you. Your report will be brought to the administrators');
			} catch(Exception $excpt) {
				$this->view->setError($excpt);
			}
			$this->view->viewFile = 'reportComment';
		}
	}

	public function getFlagged() {
		try {
		$data = $this->commentModel->getFlagged();
		$this->view->setVar('flagged', $data);
		$this->view->viewFile = 'admin/flaggedComments';
		} catch(Exception $excpt) {
			$this->view->setError($excpt);
				
		}
	}

	public function delete() {
		if(isset($this->args[1])) {
			$this->commentModel->delete($this->args[1]);
		}
	}
	
         public function correctUser($userID){
                return ($this->user && $this->user->model->userID == $userID);
        }

}
