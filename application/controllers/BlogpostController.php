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
		$this->user(); // workaround for not being created from bootstrap
	}

	public function setArgs($args) {
		$this->args = $args;
	}

	public function view($blogName, $postName) {
		$this->loadComments = (isset($this->args[3]) && $this->args[3] == 'comments');
		$commentID = isset($this->args[4]) ? $this->args[4] : false;
		$post = $this->model->getPost($blogName, $postName);
		$isOwner = $this->correctUser($post[0]['userID']);
		$this->view->setVar('isOwner', $isOwner);
		$this->view->setVar('blogPosts', $post);
		if($this->loadComments) { // TODO: Fix this
			$this->view->renderFooter = false;
		}
		$this->view->viewFile = 'blog/blogPost';
		$this->model->updatePostViewCount($this->model->postID);

		if($this->loadComments) {
			$url = 'blog/view/'. $blogName . '/' . $postName;
			$commentsController = new CommentsController();
			$commentsController->loadComments($this->model->postID, $url, $commentID, $isOwner);
		}
	}

	public function create() {
		// umh Should it be here???
		$form = new Form('blogPost', 'blogpost/createDo', 'POST');
		$form->addInput('text', 'title', 'Title: ');
		$form->addTextArea('postIngress', 5, 100, 'Ingress');
		$form->addTextArea('postText', 30, 100, 'Post text');
		$form->addInput('submit', 'Submit');
		$this->view->setVar('form', $form->genForm());
		$this->view->setVar('title', 'New post');
		$this->view->viewFile = 'blog/createPost';

	}

	public function createDo() {
		try {
			$url = $this->model->createPost($_POST, $this->user->model->userID);
			HTML::redirect('blog/view/' . $this->user->model->userName . '/' . $url);
		} catch(Exception $excpt) {
			$this->view->setError($excpt);	
			$this->create();
		}
	}

	public function update() {
		//$this->create(true);
	}
	
	public function delete() {
		try {
			$this->model->deletePost($this->args[1]);
			HTML::redirect('');
		} catch(Exception $excpt){
			$this->view->setError($excpt);
		}	
	}

	public function flag() {
		echo 'Mark no-good';
	}
	
	public function correctUser($userID){
		return ($this->user && $this->user->model->userID == $userID);
	}
}
