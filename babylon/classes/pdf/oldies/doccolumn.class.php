<?php

class DOCColumn extends DOCElement {
		
	public $datavariable;
	public $width;
	public $header;
	public $decimals;
	public $columntype;
	public $sumcolumn 		= 0;
	public $sum				= 0.0;
		
	public function __construct($header,$datavariable,$width) {

		$this->header 		= $header;
		$this->datavariable = $datavariable;
		$this->width		= $width;
				
	}

//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETDECIMALS($decimals)
//*** Sets how many decimals column uses.
//************************************************************************************************************************************
		
	public function setDecimals($decimals) {
			
		$this->decimals = $decimals;
			
	}
		
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETCOLUMNTYPE($COLUMNTYPE)
//*** Sets column-type (string,integer,float)
//************************************************************************************************************************************
		
	public function setColumnType($columntype) {
			
		$this->columntype = $columntype;
			
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETSUMCOLUMN($ISSUM)
//*** Sets if column is sum column.
//************************************************************************************************************************************
	
	public function setSumColumn($issumcolumn) {
		
		$this->sumcolumn = $issumcolumn;	
	}
		
//************************************************************************************************************************************
//*** PUBLIC FUNCTION ISSUMCOLUMN()
//*** Sets if column is sum column.
//************************************************************************************************************************************
	
	public function isSumColumn() {
		
		return $this->sumcolumn;
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAW($DOC)
//*** Stub, not used.
//************************************************************************************************************************************
		
	public function draw($DOC) {
						
	}		
}
?>