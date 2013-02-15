<?php
/**
* 
*
*/

class BlogpostController extends BaseController {
	private $loadComments = false;

	public function __construct() {
		parent::__construct();
		$this->model = new BlogpostModel();
	}

	public function setArgs($args) {
		$this->args = $args;
	}

	public function view($blogName, $postName) {
		$this->loadComments = (isset($this->args[3]) && $this->args[3] == 'comments');
		$this->view->setVar('blogPosts', $this->model->getPost($blogName, $postName));
		if($this->loadComments) { // TODO: Fix this
			$this->view->renderFooter = false;
		}
		$this->view->render('blog/blogPost');
		$this->model->updatePostViewCount($this->model->postID);

		if($this->loadComments) {
			$url = 'blog/view/'. $blogName . '/' . $postName;
			$commentsController = new CommentsController();
			$commentsController->loadComments($this->model->postID, $url);
		}
	}

	public function create() {
		// umh Should it be here???
		$form = new Form('blogPost', 'blogpost/createDo', 'POST');
		$form->addInput('text', 'title', 'Title: ');
		$form->addTextArea('postText', 30, 100);
		$form->addInput('submit', 'Submit');
		$this->view->setVar('form', $form->genForm());

		$this->view->render('blog/createPost');
	}

	public function createDo() {
		$this->model->createPost($_POST, $this->user->model->userID);
	}

	public function update() {
		echo 'add some shit';
	}
	public function delete() {
		echo 'Well this sucked, remove it!';
	}

	public function flag() {
		echo 'Mark no-good';
	}
	
	public function correctUser($userID){
		return ($this->user->model->userID == $userID);
	}
}
