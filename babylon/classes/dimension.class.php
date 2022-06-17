<?php


class Dimension {

	const DIMENSION_COMPANY = 1;
	const DIMENSION_BRANCH = 2;
	const DIMENSION_OFFICE = 3;
	const DIMENSION_DEPARTMENT = 4;
	const DIMENSION_LANGUAGE = 5;
	const DIMENSION_GRAMMAR = 6;
	const DIMENSION_SUBSYSTEM = 7;		// tätä käytetään ainakin tilitoimistoissa, asiakkuudet
	
	public $dimensionID = 0;
	public $name = null;
	public $plural = null;
	public $databasetable = null;
	
	public function __construct($dimensionID, $name, $plural, $databasetable) {
		$this->dimensionID = $dimensionID;
		$this->name = $name;
		$this->plural = $plural;
		$this->databasetable = $databasetable;
	}
}
?>