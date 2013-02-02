<?php
/**
* 
*
*/

class BlogpostController extends BaseController {
	public function __construct() {
		parent::__construct();
	}

	public function view() {
		echo 'Show a post';
	}
	public function create() {
		echo 'Creating post...';
		$title = 'Arne dro fisken på land!';
		echo 'Title was ' . $title;
		echo ' URL: '  . $this->makePostUrl($title);	// TODO: Make postURL in db as well

		$this->view->render('blog/createPost');
	}
	
	/**
	 * Creates pretty URL from string
	 * Covertes spaces to underscore and replaces NO-specific characters
	 * everything but alphanumeric is stripped after replace
	 * 
	 * @param String $title the string to convert
	 */
	public function makePostUrl($title) {
		$title = strtolower($title);
		$replace = array(' ' => '_', 'æ' => 'ae', 'ø' => 'oe', 'å' => 'aa');
		foreach($replace as $char => $sub) {
			$title = str_replace($char, $sub, $title);
		}
		$title = preg_replace('/[^0-9a-z_]/', "", $title);

		return $title;
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

}
