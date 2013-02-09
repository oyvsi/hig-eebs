<?php
 
class CommentsController extends BaseController	{

	public function __construct() {
		parent::__construct();
		$this->loadComments();
	}

	public function loadComments() {
		$userInput = new Form('comment', '', 'post');
		$userInput->addInput('Name', 'text', 'Name');
		$userInput->addInput('Comment', 'text', 'Comment');
		$userInput->addInput('submit', 'button', false, 'Submit');
		$this->view->setVar('commentForm', $userInput->genForm());
		$this->view->render('comments', true);
	}
}
