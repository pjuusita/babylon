<?php

class DOCTable extends DOCElement {
	
	private $columns 		= null;
	private $datavariable;
	
	private $inheader;
	private $infooter;
	
	private $cellX 			= 0;
	private $cellY 			= 0;
	
	// Master = member of DOC.class (do not confuse with $DOC-variable (FPDF)).
	private $master;
	private $doc;
	
	public function __construct($x,$y,$datavariable) {
			
			$this->x 			= $x;
			$this->y 			= $y;
			$this->datavariable = $datavariable;
			
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION ADDCOLUMNS($COLUMNS)
//*** Adds columns to the table.
//************************************************************************************************************************************
	
	public function addColumns($columns) {
		
		$this->columns = $columns;
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWHEADERROW($DOC)
//*** Draws header-row.
//************************************************************************************************************************************
	
	private function drawHeaderRow($DOC) {
		
		$columns 		= $this->columns;
		$contentbottom 	= $this->contentbottom;
		
		$font   		= $this->headerfont;
		$fontname 		= $font->name;
		$fontsize 		= $font->size;
		$fontstyle  	= $font->style;
		$fontcolor		= $font->color;
		
		
		$DOC->setTextColor($fontcolor->r,$fontcolor->g,$fontcolor->b);
		$DOC->setFont($fontname,$fontstyle,$fontsize);
		
		if (($this->cellY + $font->size / 2)>$contentbottom) {
			// No content rows draws = 0.
			$this->setSaveState(0);
			return;
		}
		
		foreach($columns as $columnIndex => $column) {
			
			$this->drawHeaderCell($column,$DOC);
				
		}
		
		$this->cellY = $this->cellY + $font->size / 2;
		$this->cellX = 0;
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWHEADERCELL($COLUMN,$DOC)
//*** Draws header-cell.
//************************************************************************************************************************************
	
	private function drawHeaderCell($column,$DOC) {
		
		$x 		= $this->x;
		$y 		= $this->y;
		$cx 	= $this->cellX;
		$value 	= getMultilangString($column->header);
		
		$DOC->setXY(($x+$cx),($y+0.0));
		
		$DOC->MultiCell(100,25,$value,0,"L",false);
		
		$cx = $cx + $column->width;
		
		$this->cellX = $cx;
	
	}
	

//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWHEADERANDFOOTERHEADERROW($DOC)
//*** Draws table-header-row in header or footer.
//************************************************************************************************************************************
	
	private function drawHeaderAndFooterHeaderRow($DOC) {
	
		$columns 		= $this->columns;
		$contentbottom 	= $this->contentbottom;
	
		$font   		= $this->headerfont;
		$fontname 		= $font->name;
		$fontsize 		= $font->size;
		$fontstyle  	= $font->style;
		$fontcolor		= $font->color;
	
		$DOC->setTextColor($fontcolor->r,$fontcolor->g,$fontcolor->b);
		$DOC->setFont($fontname,$fontstyle,$fontsize);
	
		foreach($columns as $columnIndex => $column) {
				
			$this->drawHeaderCell($column,$DOC);
	
		}
	
		$this->cellY = $this->cellY + $font->size / 2;
		$this->cellX = 0;
	
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWHEADERANDFOOTERCONTENTROWS($DOC)
//*** Draws table in header or foooter-rows.
//************************************************************************************************************************************
	
	private function drawHeaderAndFooterContentRows($DOC) {
		
		$columns = $this->columns;
		$data 	 = $this->data;
		$master  = $this->master;
		
		$font   = $this->contentfont;
		
		$fontname 	= $font->name;
		$fontsize 	= $font->size;
		$fontstyle  = $font->style;
		$fontcolor	= $font->color;
		
		$DOC->setTextColor($fontcolor->r,$fontcolor->g,$fontcolor->b);
		$DOC->setFont($fontname,$fontstyle,$fontsize);
		
		foreach($data as $rowIndex => $row) {
					
			foreach($columns as $columnIndex => $column) {
						
				if ($column->isSumColumn()) {
					$column->sum = $column->sum + floatval($this->getCellValue($column,$row));
				}
					
					$this->drawContentCell($column,$row,$DOC);
				}
				
				$font	 = $this->getContentFont();
				$rowstep = $font->size / 2;
		
				$this->cellY = $this->cellY + $rowstep;
				$this->cellX = 0;
		
		}
		
		$this->drawSums($DOC);
	}	
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWROWS($DOC)
//*** Draws content-rows.
//************************************************************************************************************************************
	
	private function drawContentRows($DOC,$from) {
	
		$columns = $this->columns;
		$data 	 = $this->data;
		$master  = $this->master;
		
		$font   = $this->contentfont;
		
		$fontname 	= $font->name;
		$fontsize 	= $font->size;
		$fontstyle  = $font->style;
		$fontcolor	= $font->color;
		
		
		$DOC->setTextColor($fontcolor->r,$fontcolor->g,$fontcolor->b);
		$DOC->setFont($fontname,$fontstyle,$fontsize);
		
		$passed  = 0;
			
		foreach($data as $rowIndex => $row) {
			
			$passed++;
			
			if ($passed>=$from) {

				$font		 = $this->getContentFont();
				$rowstep = $font->size / 2;
				$cy 			= $this->cellY;
				$y				= $this->y;
				
				$contentbottom  = $this->contentbottom;
					
				if (($cy+$y+$rowstep)>($contentbottom)) {
				
					$this->setSaveState($passed);
					$master->addElementToStoppedQue($this);
				
					break;
				}
				
				foreach($columns as $columnIndex => $column) {

					if ($column->isSumColumn()) {
						$column->sum = $column->sum + floatval($this->getCellValue($column,$row));
					}
					
					$this->drawContentCell($column,$row,$DOC);

				}
				
				$this->cellY = $this->cellY + $rowstep;
				$this->cellX = 0;

			}
		}
		
		$this->drawSums($DOC);
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWCONTENTCELL($COLUMN,$ROW,$DOC)
//*** Draws content-cell.
//************************************************************************************************************************************
	
	private function drawContentCell($column,$row,$DOC) {
		
		$columntype = $column->columntype;
		
		switch($columntype) {
			
			case 'integer' :
				$this->drawIntegerCell($column,$row,$DOC);
				
				break;
			
			case 'float' :
				$this->drawFloatCell($column,$row,$DOC);
				
				break;
				
			case 'string' :
				$this->drawStringCell($column,$row,$DOC);	
				break;
		}
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWINTEGERCELL($COLUMN,$ROW,$DOC)
//*** Draws a cell which contains an integer value.
//************************************************************************************************************************************
	
	private function drawIntegerCell($column,$row,$DOC) {
	
		$datavariable = $column->datavariable;
		$value		  = $row->$datavariable;
		
		$x 			  = $this->x;
		$y 			  = $this->y;
		$cx			  = $this->cellX;
		$cy			  = $this->cellY;
		
		$DOC->setXY(($x+$cx),($y+$cy));
		
		$DOC->MultiCell(100,25,$value,0,"L",false);
		
		$cx = $cx + $column->width; 
		
		$this->cellX = $cx;
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWFLOATCELL($COLUMN,$ROW,$DOC)
//*** Draws a cell which contains a float value.
//************************************************************************************************************************************
	
	private function drawFloatCell($column,$row,$DOC) {
		
		$datavariable = $column->datavariable;
		$value		  = $row->$datavariable;
		
		$value		  = number_format($value,$column->decimals);
		
		$x 			  = $this->x;
		$y 			  = $this->y;
		$cx			  = $this->cellX;
		$cy			  = $this->cellY;
		
		$DOC->setXY(($x+$cx),($y+$cy));
		
		$DOC->MultiCell(100,25,$value,0,"L",false);
		
		$cx = $cx + $column->width;
		
		$this->cellX = $cx;
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWSTRINGCELL($COLUMN,$ROW,$DOC)
//*** Draws a cell which contains a string.
//************************************************************************************************************************************
	
	private function drawStringCell($column,$row,$DOC) {
	
	
		$datavariable = $column->datavariable;
		$value		  = $row->$datavariable;
		
		$x 			  = $this->x;
		$y 			  = $this->y;
		$cx			  = $this->cellX;
		$cy			  = $this->cellY;
		
		$DOC->setXY(($x+$cx),($y+$cy));
		
		$DOC->MultiCell(100,25,$value,0,"L",false);
		
		$cx = $cx + $column->width;
		
		$this->cellX = $cx;
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWSUMS($DOC)
//*** Draws sums.
//************************************************************************************************************************************
	
	private function drawSums($DOC) {
		
		$columns = $this->columns;
		
		$x 			   = $this->x;
		$y 			   = $this->y;
		$cx			   = $this->cellX;
		$cy			   = $this->cellY;
			
		$hassumcolumns = false;
		
		foreach($columns as $index => $column) {

			$DOC->setXY(($x+$cx),($y+$cy+0.5));
			
			if ($column->sumcolumn) {
				$DOC->MultiCell(100,25,$column->sum,0,"L",false);
				$hassumcolumns = true;
			}
		
			$cx = $cx + $column->width;
		} 
		
		if ($hassumcolumns) $DOC->Line($x,$y+$cy + 10.5,$x+$cx,$y+$cy+10.5);
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION GETCELLVALUE($COLUMN,$ROW)
//*** Returns value from $column / $row.
//************************************************************************************************************************************
	
	private function getCellValue($column,$row) {
		
		$datavariable = $column->datavariable;
		$value		  = $row->$datavariable;
		
		return $value;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETSAVESTATE()
//*** Draws element.
//************************************************************************************************************************************
	
	public function setSaveState($stopped) {
	
		$this->stopped = $stopped;
	}
	
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION CONTINUEFROMSAVESTATE($DOC,$TOP,$BOTTOM)
//*** Continues drawing table from savestate. Usually called from DOC.CLASS STOPPEDQUE.
//************************************************************************************************************************************
	
	public function continueFromSaveState($DOC,$top,$bottom) {
	
		$from = $this->stopped;
		
		$this->y = $top;
		
		$this->cellY = 0;
		$this->cellX = 0;
		
		$this->drawHeaderRow($DOC);
		$this->drawContentRows($DOC,$from);
		
	}
	
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAWTABLE($DOC)
//*** Draws element.
//************************************************************************************************************************************
	
	public function drawTable($master,$DOC,$contenttop,$contentbottom,$inheader,$infooter) {
	
		$this->contenttop		= $contenttop;
		$this->contentbottom 	= $contentbottom;
		$this->master			= $master;
		$this->inheader			= $inheader;
		$this->infooter			= $infooter;
		
		$this->drawHeaderRow($DOC);
	
		$from 					= 0;
		
		if (($inheader) || ($infooter)) {
			$this->drawHeaderAndFooterHeaderRow($DOC);
			$this->drawHeaderAndFooterContentRows($DOC);
			return $contenttop;
			
		} else {
			
			$this->drawContentRows($DOC,$from);
			return $DOC->GetY();
			
		}
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAW($DOC)
//*** Stub. Not used.
//************************************************************************************************************************************
	
	public function draw($DOC) {

		
	}
	
}

?>