<?php

class TimeSheetButton {
	
	private $text;
	private $value;
	private $function;
	
	public function __construct($text,$value,$function) {
	
		$this->text  = $text;
		$this->value = $value;
		$this->function = $function;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function getFunction() {
		return $this->function;
	}
}

?>
