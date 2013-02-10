<?php
 
class CommentsController extends BaseController	{

	public function __construct() {
		parent::__construct();
		$this->model = new CommentsModel();
	}

	public function loadComments($postID, $insertRedirect) {
		$comments = $this->model->getComments($postID);
		$this->view->setVar('comments', $comments);

		$userInput = new Form('comment', 'comments/commentDo/' . $postID, 'post');
		if($this->user()) {
			$userInput->addInput('text', 'name', 'Name', $this->user->model->userName, true);
		} else {
			$userInput->addInput('text', 'name', 'Name');
		}	
		$userInput->addTextArea('comment', 10, 60);
		$userInput->addInput('submit', 'submit', false, 'Submit');
		$userInput->addInput('hidden', 'redirect', false, $insertRedirect); // So dirty. TODO: Fix
		$this->view->renderHeader = false;
		$this->view->setVar('commentForm', $userInput->genForm());
		$this->view->render('comments');
	}

	public function commentDo() {
		// TODO: validate post info
		if(isset($this->args[1]) && isset($_POST['name']) && isset($_POST['comment'])) {
			$this->model->insertComment($this->args[1], $_POST);	
		}
	}
}