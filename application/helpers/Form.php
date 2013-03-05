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
			$input .= '<label for="' . $name . '">' . $lead  .':</label>' . "\n";
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

	public function addTextArea($name, $rows, $cols, $lead=false, $value=false) {
		$html = '<p class="textarea">';
		if($lead !== false) {
			$html .= '<p>' . $lead . '</p>';
		}
		
		$html .= '<textarea name="' . $name . '" id="' . $name . '"  rows="' . $rows . '" cols="' . $cols . '">';
		
		if ($value !== false) {
			$html .= $value;
		}

		$html .= '</textarea></p>';
		array_push($this->inputFields, $html);
	} 


	public function addSelect($name, $lead, $values, $selected=false) {

			$html = '<tr><td colspan=2><p class="input"><label for="' .$name. '">' .$lead. ':</label><select name="' .$name. '">';

			foreach($values as $value) {
				$html .= '<option value="' .$value['value']. '"';

				if($value['value'] === $selected) {
					$html .= ' selected';
				}

				$html .= '>' .$value['view']. '</option>';
			}

			$html .= '</select></p></td></tr>';

			array_push($this->inputFields, $html);
	}

	public function genForm() {
		$html = '<form name="' . $this->name . '" id="' . $this->name . '" action="' . $this->action . '" method="' . $this->method . '" enctype="multipart/form-data">';   
		foreach($this->inputFields as $input) {
			$html .= "\n\t" . $input;
		}
		$html .= "\n" . '</form>';

		return $html;
	}
}
