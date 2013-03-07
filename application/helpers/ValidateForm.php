<?php

class ValidateForm {
	private $form;
	private $requiredFields = false;
	private $errors  = array();
	public $ignoreFields = array();
		
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
				if(array_key_exists($key, $this->form) && !in_array($key, $this->ignoreFields)) {
				 	$this->xss_clean($form[$key]);
					if(!isset($this->form[$key])) {
						array_push($this->errors, $key . ' is not properly filled out');
					}
						
					if(strlen($this->form[$key]) < $value['minLength']) {
						array_push($this->errors, $value['view'] . ' must be atleast ' . $value['minLength'] . ' characters');
					}
				

					if(strlen($this->form[$key]) > $value['maxLength']) {
						array_push($this->errors, $value['view'] . ' must be below ' . $value['maxLength'] . ' characters');
					} 
						
					if(array_key_exists('regex', $value)) {
						if(!preg_match($value['regex'], $this->form[$key])) {
							array_push($this->errors, $value['view'] . ' is not in a valid form, please use username@domain');
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


	function xss_clean($data) {
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		// we are done...
		return $data;
}
}
