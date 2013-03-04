<?php
/**
* @author Team Henkars
*
* This class holds functions for a blog post
*/

class BlogpostController extends BaseController {
	//private $loadComments = false;

   /**
    * Default constructor
    * Sets up the model
    */
	public function __construct() {
		parent::__construct();
		$this->model = new BlogpostModel();
	}

	public function setArgs($args) {
		$this->args = $args;
	}

   /**
    * Function to show a blog post
    * Arguments from url: postName the post name to show 
    * 
    * @url blogpost/view/$userName/$postName/
    */
	public function view() {
   
      if(isset($this->args[1]) && isset($this->args[2])) {
         $this->view->addViewFile('blog/blogPost');
         //$this->loadComments = (isset($this->args[3]) && $this->args[3] == 'comments');
         //$commentID = isset($this->args[4]) ? $this->args[4] : false;  // see specific comment

         /*if($this->loadComments) { // TODO: Fix this
            $this->view->renderFooter = false;
         }*/

         try {
            $post = $this->model->getPostFromURL($this->args[1], $this->args[2]);
            $isOwner = $this->correctUser($post['userID']);
            $this->view->setVar('isOwner', $isOwner);
            $this->view->setVar('blogPosts', array($post));
            $this->model->updateViewCount($this->model->postID, 'postID', 'postViews');

            /*if($this->loadComments) {
               $url = 'blogpost/view/'. $this->args[1] . '/' . $this->args[2];
               $commentsController = new CommentsController();
               try {
                  $commentsController->loadComments($this->model->postID, $url, $commentID, $isOwner);
               } catch(Exception $excpt) {
                  $this->view->setError($excpt);
               }
            }*/

         }
         catch(Exception $excpt) {
            $this->view->setError($excpt);
         }
      } else {
         HTML::redirect('');  // No username and postname is URL, show index
      }
	}

	public function create($updateID = false) {
		if ($updateID !== false){
			$post = $this->model->getPostFromID($updateID);

			$form = new Form('blogPost', 'blogpost/updateDo/' . $updateID, 'POST');
			$form->addInput('text', 'title', 'Title: ', $post['postTitle']);
			$form->addTextArea('postIngress', 5, 100, 'Ingress', $post['postIngress']);
			$form->addTextArea('postText', 30, 100, 'Post text', $post['postText']);
			//print_r($post);
		} else {
			$form = new Form('blogPost', 'blogpost/createDo', 'POST');
			$form->addInput('text', 'title', 'Title: ');
			$form->addTextArea('postIngress', 5, 100, 'Ingress');
			$form->addTextArea('postText', 30, 100, 'Post text');
		}
		$form->addInput('submit', 'Submit');
		$this->view->setVar('form', $form->genForm());
		$this->view->setVar('title', 'New post');
		$this->view->addViewFile('blog/createPost');
	}

	public function createDo() {
		try {
			$url = $this->model->createPost($_POST, $this->user->model->userID);
			HTML::redirect('blogpost/view/' . $this->user->model->userName . '/' . $url);
		} catch(Exception $excpt) {
			$this->view->setError($excpt);	
			$this->create();
		}
	}

	public function update() {
		$postID = $this->args[1];
		$this->create($postID);
	}

	public function updateDo() {
		try {
			$url = $this->model->createPost($_POST, false, $this->args[1]);
			HTML::redirect('blogpost/view/' . $this->user->model->userName . '/' . $url);
		} catch(Exception $excpt) {
			$this->view->setError($excpt);	
			$this->update();
		}
	}

	public function delete() {
		try {
			$this->model->deletePost($this->args[1]);
			HTML::redirect('');
		} catch(Exception $excpt){
			$this->view->setError($excpt);
		}	
	}

	public function getFlagged() {
		try {
			$data = $this->model->getFlagged();
			$this->view->setVar('flagged', $data);
			$this->view->addViewFile('admin/flaggedPosts');
		} catch(Exception $excpt) {
			$this->view->setError($excpt);

		}
	}

	public function correctUser($userID){
		return ($this->user && $this->user->model->userID == $userID);
	}
}