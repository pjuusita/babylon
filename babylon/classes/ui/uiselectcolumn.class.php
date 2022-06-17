<?php

class UISelectColumn extends UIColumn {

	public $name;
	public $showvariable;
	public $datavariable;
	public $link;
	public $width;
	public $selection;

	/**
	 *  TODO: korvaa shovariable ja datavariable järjestys yhdenmukaiseksi
	 */
	public function __construct($name, $showvariable, $datavariable, $selection = NULL, $link = "", $width = NULL) {
		parent::__construct($name, $datavariable);
		
		$this->showvariable = $showvariable;
		$this->datavariable = $datavariable;
		$this->link = $link;
		$this->selection = $selection;
		$this->width = $width;
	}

	
}


?>