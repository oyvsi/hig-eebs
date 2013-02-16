<?php

class Form {
	protected $name;
	protected $action;
	protected $method;
	protected $inputFields;

	public function __construct($name, $action, $method) {
		$this->inputFields = array();
		$this->name = $name;
		$this->action = __URL_PATH . $action;
		$this->method = $method;

	}

	public function addInput($type, $name, $lead=false, $value=false, $readOnly=false, $id=false) {
		$input = '<p class="input">';

		if($lead !== false) {
			$input .= '<label for="' . $name . '">' . $lead  .'</label>' . "\n";
		}

		$input .= "\t" . '<input type="' . $type .'" name="' . $name . '"';

		if($id !== false) {
			$input .= ' id="' . $id . '"';
		}
		if($value !== false) {
			$input .= ' value="' . $value . '"';
		}
		if($readOnly !== false) {
			$input .= ' readonly="true"';
		}
		$input .= ' /> </p>';

		array_push($this->inputFields, $input);
	}
	public function addTextArea($name, $rows, $cols, $lead=false) {
		$html = '<p class="textarea">';
		if($lead !== false) {
			$html .= '<p>' . $lead . '</p>';
		}
		$html .= '<textarea name="' . $name . '" id="' . $name . '"  rows="' . $rows . '" cols="' . $cols . '"></textarea></p>';
		array_push($this->inputFields, $html);
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
