<?php

class Form {
	protected $name;
	protected $action;
	protected $method;
	protected $inputFields;

	public function __construct($name, $action, $method) {
		$this->inputFields = array();
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;

	}

	public function addInput($type, $name, $lead, $id=false, $value=false) {
		$input = '<label for="' . $name . '">' . $lead  .'</label>' . "\n";
		$input .= "\t" . '<input type="' . $type .'" name="' . $name . '"';

		if($id != false) {
			$input .= ' id="' . $id . '"';
		}
		if($value != false) {
			$input .= ' value="' . $value . '"';
		}
		$input .= ' />';
		
		array_push($this->inputFields, $input);
	}

	public function genForm() {
		$html = '<form name="' . $this->name . '" action="' . $this->action . '" method="' . $this->method . '">';   
		foreach($this->inputFields as $input) {
			$html .= "\n\t" . $input;
		}
		$html .= "\n" . '</form>';
	
		return $html;
	}
}