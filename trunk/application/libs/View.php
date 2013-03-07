<?php
/**
 * General view class
 * @author Team Henkars
 */
class View {
	private $vars;
	private $error = false;
	public $renderHeader = true;
	public $renderFooter = true;
	protected $viewFile = array();
	public $renderSideBar = false;

	/**
	 * Stores a variable in the view 
	 * @param $key
	 * @param $value 
	 */
	public function setVar($key, $value) {
		$this->vars[$key] = $value;
	}

	/**
	 * Set an error for the view
	 * @param Exception $exception
	 */
	public function setError($exception) {
		$this->error = $exception;
	}

	/**
	 * Renders out to HTML 
	 */
	public function render() {
		if($this->renderHeader === true) {
		  require(__SITE_PATH . '/application/views/header.php');
		}

		if($this->error !== false) {
	         require(__SITE_PATH . '/application/views/error.php');
		} 	
		
		if(!empty($this->viewFile)) {
			foreach($this->viewFile as $views) {
					require(__SITE_PATH . '/application/views/' . $views . '.php');
			}
		} 
		
		if($this->renderFooter === true) {
		 require(__SITE_PATH . '/application/views/footer.php');
		}
	}

	/**
	 * Add a view file to be rendered
	 * @param string $viewFile 
	 */
	public function addViewFile($viewFile) {
		array_push($this->viewFile, $viewFile);
	}	
} 
