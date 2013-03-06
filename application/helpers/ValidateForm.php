<?php

class ValidateForm {
	private $form;
	private $requiredFields = false;
	private $minLength = false;
	private $errors  = array();
		
	public function __construct($form) {
		$this->form = $form;
	}

	public function setRequired($fields) {
		$this->requiredFields = $fields;

		
	}
	


	public function setMinLength($fields) {
		$this->minLength = $fields;	
	}
	public function getErrors() {
		return $this->errors;
	}
	
	public function check() {
		if($this->requiredFields !== false) {
			foreach($this->requiredFields as $key => $value) {
				if(array_key_exists($key, $this->form)) {
					$this->xss_cleaner($this->form[$key]);
				
					if(!isset($this->form[$key])) {
						array_push($this->errors, $key . ' is not properly filled out');
					}
						
					if(strlen($this->form[$key]) < $value['minLength']) {
						array_push($this->errors,  $key . ' must be atleast ' . $value['minLength'] . ' characters');
					}
				

					if(strlen($this->form[$key]) > $value['maxLength']) {
						array_push($this->errors,  $key . ' must be atleast ' . $value['maxLength'] . ' characters');
					} 
						
					if(array_key_exists('regex', $value)) {
						print($value['regex']);
						if(!preg_match($value['regex'], $this->form[$key])) {
							array_push($this->errors, $key . ' is SHIT ' . $key .  ' characters');
					}

					}
					}
			}
				
		if(count($this->errors) > 0) {
			return false;
		}
		
		return true;
	}
}

	function xss_cleaner($input_str) {
    	$return_str = str_replace( array('<','>',"'",'"',')','('), array('&lt;','&gt;','&apos;','&#x22;','&#x29;','&#x28;'), $input_str );
    	$return_str = str_ireplace( '%3Cscript', '', $return_str );
    	return $return_str;
}	

}
