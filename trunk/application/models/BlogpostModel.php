<?php

class BlogpostModel extends BaseModel {
	public function __construct() {
		parent::__construct();
	}	

	public function createPost($data) {
		// Do some validation shit and check for XSS
		
		$url = $this->makePostUrl($data['title']);
		$title = $data['title'];
		$contents = $data['postText'];
		echo "Url should be $url <br>";
		echo "Should insert blogPost with...<br>Title: $title <br>Text: $contents";
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

}
