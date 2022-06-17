<?php


// Kopioitu suoraan tiedostoon UIFixedColumn.class.php

class UIFixedColumn extends UIColumn {

	public $name;
	public $datavariable;
	public $link;

	public function __construct($name, $datavariable) {
		$this->name = $name;
		$this->datavariable = $datavariable;
	}
	
	public function setLink($url,$linkvariable) {
		$this->link = $url;
	}
}
?>