<?php


class UIMultilangColumn extends UIColumn {

	public $languageID;
	public $width = '';
	
	public function __construct($name, $datavariable, $languageID) {
		
		parent::__construct($name, $datavariable);
		$this->languageID = $languageID;
	}
}
?>