<?php

class DOCIndexValueList extends DOCElement {
	
		private $rowY = 0;
		private $indexwidth = 35;
		private $valuewidth = 35;
		private $drawindex 	= true;
		
	public function __construct($x,$y,$data) {
		
		$this->x 		  = $x;
		$this->y 		  = $y;
		$this->data 	  = $data;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETINDEXWIDTH($INDEXWIDTH)
//*** Sets width of the index row.
//************************************************************************************************************************************

	public function setIndexWidth($indexwidth) {
		
		$this->indexwidth = $indexwidth;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETVALUEWIDTH($VALUEWIDTH)
//*** Sets width of the value row.
//************************************************************************************************************************************
	
	public function setValueWidth($valuewidth) {
		
		$this->valuewidth = $valuewidth;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETDRAWINDEX($DRAWINDEX)
//*** Sets if index is drawn.
//************************************************************************************************************************************
	
	public function setDrawIndex($drawindex) {
	
		$this->drawindex = $drawindex;
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWPAIRS($DOC)
//*** Draws index-value pairs.
//************************************************************************************************************************************
	
	private function drawPairs($DOC) {
		
		$data 	   = $this->data;
		$drawindex = $this->drawindex;
		
		foreach($data as $index => $value) {
		
			if ($value!='') {
				
				if (!$drawindex) $this->drawValue($DOC,$value);
				if ($drawindex) $this->drawPair($DOC,$index,$value);
				
				$font		= $this->getContentFont();
				$this->rowY = $this->rowY + $font->size / 2 - 1;
			
			}		
		}		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWVALUE($DOC,$VALUE)
//*** Draws index-value pair.
//************************************************************************************************************************************
	
	private function drawValue($DOC,$value) {
	
		$x			= $this->x;
		$y			= $this->y;
		$rowY		= $this->rowY;
		$indexwidth = $this->indexwidth;
		$valuewidth = $this->valuewidth;
	
		$DOC->setXY(($x),($y+$rowY));
		$DOC->MultiCell($valuewidth,25,$value,0,"L",false);
	
	}
	
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWPAIR($DOC,$INDEX,$VALUE)
//*** Draws index-value pair.
//************************************************************************************************************************************
	
	private function drawPair($DOC,$index,$value) {
		
		$x			= $this->x;
		$y			= $this->y;
		$rowY		= $this->rowY;
		$indexwidth = $this->indexwidth;
		$valuewidth = $this->valuewidth;
		
		$DOC->setXY($x,$y+$rowY);
		
		$DOC->MultiCell($indexwidth,25,$index,0,"L",false);
		
		$DOC->setXY(($x+$indexwidth),($y+$rowY));
		
		$DOC->MultiCell($valuewidth,25,$value,0,"L",false);
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAW($DOC)
//*** Draws component.
//************************************************************************************************************************************
	
	public function draw($DOC) {

		$font   = $this->contentfont;
		
		$fontname 	= $font->name;
		$fontsize 	= $font->size;
		$fontstyle  = $font->style;
		$fontcolor	= $font->color;
		
		$DOC->setTextColor($fontcolor->r,$fontcolor->g,$fontcolor->b);
		$DOC->setFont($fontname,$fontstyle,$fontsize);
		
		$this->drawPairs($DOC);
		
		return $DOC->getY();
		
	}
}
?>