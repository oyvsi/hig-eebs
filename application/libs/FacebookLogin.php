<?php
require 'facebook/facebook.php';

class FacebookLogin {
	private $fb;
	public function __construct() {
		$this->fb = new Facebook(array(
			'appId'  => '288792191246167',
			'secret' => '9ded4d76eefe33ec8bc8d0795c7306c5',
		));
	}

	public function checkLogin() {
		$user = $this->fb->getUser();
		if ($user) {
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $this->fb->api('/me');
			} catch (FacebookApiException $e) {
				die($e);
			} 
			return $user_profile;
		} else {
			return false;
		}
	}

	public function getLogoutURL() {
		return $this->fb->getLogoutUrl();
	}		  
	public function getLoginURL() {
		return $this->fb->getLoginUrl();
	}	
}
