<?php
 /**
* @author Team Henkars
*
* This class holds functions for comments
*/
class CommentsController extends BaseController	{
	private $fb;
	private $fbUser;
	private $blogpostModel;
	private $commentsModel;
	private $postID;
	private $post;

	/**
	* constructor. sets up the basic info for comments.
	*/
	public function __construct() {
		parent::__construct();
		$this->commentsModel = new CommentsModel();
		$this->blogpostModel = new BlogpostModel();
		$this->fb  = new FacebookLogin();
		$this->fbUser = $this->fb->checkLogin();
	}

   /**
   *  Function to comment on blogposts. Supports both 
   * logged in users and facebook-comments.
   * Arguments from url: blogName and blogposts tilte (URL)
   * 
   * url is comments/view/$blogName/$postURL/
   */
	public function view() {
		$blogName = $this->args[1];
		$postURL = $this->args[2];
		$id = isset($this->args[4]) ? $this->args[4] : false;
		$this->post = $this->blogpostModel->getPostFromURL($blogName, $postURL);
      $this->postID = $this->post['postID'];
		$isOwner = ($this->user && $this->post['userID'] == $this->user->model->userID);

		if($id !== false) {
			$comments = $this->commentsModel->getComment($id);
		} else {
			$comments = $this->commentsModel->getComments($this->postID);
		}
		$this->view->setVar('isOwner', $isOwner);	
		$this->view->setVar('comments', $comments);

		$user = false;

		$userInput = new Form('comment', 'comments/commentDo/' . $this->postID, 'post');
		$userInput->addInput('hidden', 'redirect', false, 'blogpost/view/' . $blogName . '/' . $postURL);
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

		$this->view->setVar('commentForm', $userInput->genForm());
		$this->view->setVar('userName', $user);
		$this->view->setVar('title', 'Comments');
		$this->view->addViewFile('comments');
	}

   /**
   *  function process comment and sends it to CommentsModel.
   * 
   * Arguments from url: blogID
   * url is comments/commentDo/$postID
   */
	public function commentDo() {
		if($this->user() || $this->fbUser) { 
			if(isset($this->args[1]) && isset($_POST['comment']) && isset($_POST['redirect'])) {
				$userName = ($this->user()) ? $this->user->model->userName : $this->fbUser['username'];
				$_POST['name'] = $userName;

				try {
					$this->commentsModel->insertComment($this->args[1], $_POST);	
					HTML::redirect($_POST['redirect']);
				} catch(Exception $excpt) {
					$this->view->setError($excpt);
				}
			} else {
				$this->view->setError(new Exception('Unable to set up function'));
			}
		}
	}

   /**
   * function flags the given comment.
   * 
   */
	public function getFlagged() {
		try {
			$data = $this->commentsModel->getFlagged();
			$this->view->setVar('flagged', $data);
			$this->view->setVar('title', 'Flagged Comments');
			$this->view->addViewFile('admin/flaggedComments');
		} catch(Exception $excpt) {
			$this->view->setError($excpt);

		}
	}

   /**
   *  function deletes a comment.
   * 
   * Arguments from url: commentID, comment to delete
   * url is comments/delete/$commentID/
   */
	public function delete() {
		if(isset($this->args[1]) && $this->user()) {
			try {
				$this->commentsModel->delete($this->args[1], $this->user->model->userID);
				$this->view->setVar('message', 'Deleted comment');
			} catch(exception $excpt) {
				$this->view->setError($excpt);
			}
		}
	}
}
