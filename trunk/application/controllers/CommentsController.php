<?php
 
class CommentsController extends BaseController	{

	public function __construct() {
		parent::__construct();
	}

	public function loadComments() {
		$userInput = new Form('comment', '', 'post');
		if($this->user()) {
			$userInput->addInput('Name', 'text', 'Name', $this->user->model->userName, true);
		} else {
			$userInput->addInput('Name', 'text', 'Name');
		}	
		$userInput->addTextArea('Comment', 10, 20);
		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('commentForm', $userInput->genForm());
		$this->view->render('comments', true);
	}
}
