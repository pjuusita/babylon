<?php

class UILimitSolver {
	
	private $itemcount;
	private $itemarray;
	
	public function __construct($itemcount) {
		
		$itemarray = array();
		
	}
	
	public function getDefaultArray() {

		$itemarray[0] = 5;
		$itemarray[1] = 10;
		$itemarray[2] = 25;
		$itemarray[3] = 50;
		$itemarray[4] = 100;
		$itemarray[5] = 250;
		$itemarray[6] = 500;
		
		return $itemarray;
		
	}

}

?>