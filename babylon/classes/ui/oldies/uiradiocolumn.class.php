<?php

class UIRadioColumn extends UIColumn {

	public $name;
	public $datavariable;
	public $data;
	public $link;

	public function __construct($name, $datavariable, $data, $link) {
		$this->name = $name;
		$this->datavariable = $datavariable;
		$this->data = $data;
		$this->link = $link;
	}
}
?>