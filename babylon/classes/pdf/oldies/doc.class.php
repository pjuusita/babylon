<?php

class DOC {
	
	private $DOC;
	private $elements;
	private	$data;
	private $elementdata;
	private $stoppedque;
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION __CONSTRUCT()
//*** Constructor.
//************************************************************************************************************************************
	
	public function __construct($elementrows,$data) {
	
		$this->DOC = new FPDF();
		$this->DOC->setAutoPageBreak(false);
		$this->setElementData($elementrows);
		$this->setData($data);
		
		$this->DOC->setFont('Arial','b',12);
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION ADDELEMENTS($ELEMENTS)
//*** Adds elements to document
//************************************************************************************************************************************
	
	private function setData($data) {
	
		$this->data = $data;
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION ADDELEMENTS($ELEMENTS)
//*** Adds elements to document
//************************************************************************************************************************************
	
	private function setElementData($elementrows) {
	
		$this->elementdata = $elementrows;
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATEELEMENTS()
//*** Creates elements from database rows and returns last page.
//************************************************************************************************************************************
		
	private function createElements() {
		
		$section 	 = 10;
		$elementdata = $this->elementdata;
		$lastpage	 = 0;
		
		foreach($elementdata as $index => $row) {
				
				if ($row->endpage>$lastpage) $lastpage = $row->endpage;
			
				$element = $this->createElement(null,$row);

				if ($row->getChildren()!=null) $this->createChildren($element,$row);
				
				$this->addElement($element);
		}
		
		return $lastpage;
		
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATECHILDREN($PARENT,$ROW)
//*** Creates child elements for parent from database rows.
//************************************************************************************************************************************
	
	private function createChildren($parent,$row) {
		
		$children = $row->getChildren();
			
		foreach($children as $index => $child) {
				$parent->addElement($this->createElement($parent,$child));
		}
		
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATESECTION($ELEMENT,$ROW)
//*** Creates section from $element
//************************************************************************************************************************************
	
	private function createElement($parent,$row) {
		
		$section 		= 10;
		$text	 		= 13;
		$line	 		= 7;
		$multilangtext  = 11;
		$table			= 14;
		$column			= 15;
		$indexvaluelist = 24;
		$header			= 18;
		$footer			= 19;
		$fixedtext		= 25;
		
		switch($row->typeID) {

			//************************************************************************************************************************
			//*** SECTION
			//************************************************************************************************************************
					
			case $section :
						
				$element = $this->createSection($row); 
				
				if ($row->getChildren!=null) $this->createChildren($element,$row);
			
				$this->settings($element,$row);	
				if ($element->getContentFont()==null) $element->setContentFont($parent->getContentFont());
					
				return $element;
				
				break;
				
			//************************************************************************************************************************
			//*** HEADER
			//************************************************************************************************************************
					
				case $header :
				
					$element = $this->createHeader($row);
				
					if ($row->getChildren!=null) $this->createChildren($element,$row);
						
					$this->settings($element,$row);
					if ($element->getContentFont()==null) $element->setContentFont($parent->getContentFont());
						
					return $element;
				
					break;
					
			//************************************************************************************************************************
			//*** FOOTER
			//************************************************************************************************************************
						
				case $footer :
					
					$element = $this->createFooter($row);
					
					if ($row->getChildren!=null) $this->createChildren($element,$row);
							
					$this->settings($element,$row);
					if ($element->getContentFont()==null) $element->setContentFont($parent->getContentFont());
							
					return $element;
					
					break;
					
			//************************************************************************************************************************
			//*** TEXT
			//************************************************************************************************************************
						
			case $text :

				$element = $this->createTextElement($row);
				
				$this->settings($element,$row);	
				if ($element->getContentFont()==null) $element->setContentFont($parent->getContentFont());
		
				return $element;
				
				break;
				
				
			//************************************************************************************************************************
			//*** TEXT
			//************************************************************************************************************************
				
				case $fixedtext :
				
					$element = $this->createFixedTextElement($row);
				
					$this->settings($element,$row);
					if ($element->getContentFont()==null) $element->setContentFont($parent->getContentFont());
				
					return $element;
				
					break;
					
			//************************************************************************************************************************
			//*** MULTILANGTEXT
			//************************************************************************************************************************
						
			case $multilangtext :

				$element = $this->createMultilangTextElement($row);
				$this->settings($element,$row);	
				if ($element->getContentFont()==null) $element->setContentFont($parent->getContentFont());
		
				return $element;
	
				break;
				
			//************************************************************************************************************************
			//*** LINE
			//************************************************************************************************************************
				
			case $line :
				
				$element = $this->createLineElement($row);
				$this->settings($element,$row);
				
				return $element;
				
				break;
						
						
			//************************************************************************************************************************
			//*** TABLE
			//************************************************************************************************************************
					
			case $table :
					
				$element = $this->createTableElement($row);
				$this->settings($element,$row);
					
				return $element;
					
				break;

			//************************************************************************************************************************
			//*** TABLE
			//************************************************************************************************************************
					
			case $indexvaluelist :
						
				$element = $this->createIndexValueListElement($row);
				$this->settings($element,$row);
						
				return $element;
						
				break;

			//************************************************************************************************************************
			//*** DEFAULT
			//************************************************************************************************************************
						
			default : 
			
				return null;
				break; 
		}
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATESECTION($ROW)
//*** Creates section-element.
//************************************************************************************************************************************
	
	private function createSection($row) {
		
		$x 	 = $row->xpos;
		$y 	 = $row->ypos;
		
		$width 	 = $row->width;
		$height  = $row->height;
		
		$startpage	 = $row->startpage;
		$endpage	 = $row->endpage;
		
		$DOC = $this->DOC;
		
		$section = new DOCSection($x,$y,$width,$height,$startpage,$endpage,$DOC,$this);
				
		return $section;
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATEHEADER($ROW)
//*** Creates header-section-element.
//************************************************************************************************************************************
	
	private function createHeader($row) {
	
		$x 	 	 = $row->xpos;
		$y 	 	 = $row->ypos;
		
		$width 	 = $row->width;
		$height  = $row->height;
		
		$startpage	 = $row->startpage;
		$endpage	 = $row->endpage;
		
		$DOC 	 = $this->DOC;
	
		$section = new DOCSection($x,$y,$width,$height,$startpage,$endpage,$DOC,$this);
		$section->setAsHeader(true);
			
		return $section;
	
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATEFOOTER($ROW)
//*** Creates footer-section-element.
//************************************************************************************************************************************
	
	private function createFooter($row) {
	
		$x 	 = $row->xpos;
		$y 	 = $row->ypos;
		
		$width 	 = $row->width;
		$height  = $row->height;
		
		$startpage	 = $row->startpage;
		$endpage	 = $row->endpage;
		
		$DOC 	 = $this->DOC;
	
		$section = new DOCSection($x,$y,$width,$height,$startpage,$endpage,$DOC,$this);
		$section->setAsFooter(true);
			
		return $section;
	
	}
		
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATETEXTELEMENT($ROW)
//*** Creates text-element.
//************************************************************************************************************************************
	
	private function createTextElement($row) {
		
		$data = $this->data;
		
		$x 			= $row->xpos;
		$y 			= $row->ypos;
		$width 		= $row->width;
		$height 	= $row->height;
		
		$pointers	 = explode("->",$row->value);

		if (count($pointers)==2) $value =  $data->$pointers[0]->$pointers[1];
		if (count($pointers)==1) $value =  $data->$pointers[0];
			
		$element 	 = new DOCText($width,$height,$x,$y,$value);
		
		return $element;
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATEFIXEDTEXTELEMENT($ROW)
//*** Creates fixedtext-element.
//************************************************************************************************************************************
	
	private function createFixedTextElement($row) {
	
		$data = $this->data;
	
		$x 			= $row->xpos;
		$y 			= $row->ypos;
		$width 		= $row->width;
		$height 	= $row->height;
		$value		= $row->value;
		
		$element 	 = new DOCText($width,$height,$x,$y,$value);
	
		return $element;
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATEMULTILANGTEXTELEMENT($ROW)
//*** Creates multilangtext-element.
//************************************************************************************************************************************
	
	private function createMultilangTextElement($row) {
	
		$x			= $row->xpos;
		$y 			= $row->ypos;
		$width 		= $row->width;
		$height 	= $row->height;
		
		$value 	 	=  getMultilangString($row->value);
			
		$element 	= new DOCText($width,$height,$x,$y,$value);
	
		return $element;
	
	}
	

//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATELINEELEMENT($ROW)
//*** Creates line-element.
//************************************************************************************************************************************
	
	private function createLineElement($row) {
		
		$coords = explode(",",$row->value);
		$xy1 = explode(":",$coords[0]);
		$xy2 = explode(":",$coords[1]);
		
		$element = new DOCLine($xy1[0],$xy1[1],$xy2[0],$xy2[1]);
		
		return $element;
	}	
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATETABLEELEMENT($ROW)
//*** Creates table-element.
//************************************************************************************************************************************
	
	private function createTableElement($row) {
	
		$data 	 			= $this->data;
		$datavariable 		= $row->datavariable;
		$x 					= $row->xpos;
		$y 					= $row->ypos;
		
		$element 			= new DOCTable($x,$y,$datavariable);
		$columns			= $this->createColumnElements($row); 
		
		$element->addColumns($columns);
	
		$element->setData($data->$datavariable);
		
		return $element;
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATEINDEXVALUELISTELEMENET($ROW)
//*** Creates indexvaluelist-element.
//************************************************************************************************************************************
	
	private function createIndexValueListElement($row) {
	
		$data 	 			= $this->data;
		$datavariable 		= $row->datavariable;
		$x 					= $row->xpos;
		$y 					= $row->ypos;
		
		$pairs = $data->$datavariable;
		
		$element 			= new DOCIndexValueList($x,$y,$pairs);
	
		return $element;
	}
	
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATECOLUMNELEMENTS($ROW)
//*** Adds element to document
//************************************************************************************************************************************
	
	private function createColumnElements($row) {
	
		$datavariable = $row->datavariable;
		$elementdata  = $this->elementdata;
		$columns	  = array();
		
		$columndata	  = $row->getChildren();

		foreach($columndata as $index => $columnrow) {
			
			$header		  =   getMultilangString($columnrow->value);
			$width		  = $columnrow->width;
			$datavariable = $columnrow->datavariable;
			$column 	  = new DOCColumn($header,$datavariable,$width);
			$this->settings($column,$row);
			$this->settings($column,$columnrow);
			$columns[] 	  = $column;
		}
		
		return $columns;
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION ADDELEMENT($ELEMENT)
//*** Adds element to document
//************************************************************************************************************************************
	
	private function addElement($element) {
		
		if($this->elements==null) $this->elements = array();
		$this->elements[] = $element;
	}

//************************************************************************************************************************************
//*** PUBLIC FUNCTION ADDELEMENTTOSTOPPEDQUE($ELEMENT)
//*** Adds element which went over content-bottom to stoppedque to be drawn on next page.
//************************************************************************************************************************************
	
	public function addElementToStoppedQue($element) {
		
		if($this->stoppedque=null) $this->stoppedque = array();
		$this->stoppedque[] = $element;
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWSTOPPEDQUE($TOP,$BOTTOM)
//*** Draws elements on stoppedque.
//************************************************************************************************************************************
	
	private function drawStoppedQue($top,$bottom) {
	
		$stoppedque = $this->stoppedque;
		$DOC		= $this->DOC;
		
		if ($stoppedque==null) return $top;
		
		foreach($stoppedque as $index => $table) {
			
			$cursor = $table->continueFromSaveState($DOC,$top,$bottom);
		
		}
		
		return $cursor;
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION DRAWELEMENTS()
//*** Draws elements in document.
//************************************************************************************************************************************
	
	private function drawElements($onpage) {
	
		$elements = $this->getElementsOnPage($onpage);
		
		$DOC	  = $this->DOC;
		
		$top 	= $this->drawHeader($onpage);
		$bottom = $this->drawFooter($onpage);
		$top 	= $this->drawStoppedQue($top,$bottom);

		$this->drawContent($elements,$top,$bottom);
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETTINGS($ELEMENT,$SETTINGS)
//*** Sets element settings.
//************************************************************************************************************************************
	
	private function drawHeader($onpage) {
		
		$elements = $this->elements;
		$DOC	  = $this->DOC;
		
		foreach($elements as $index => $element) {

			if (get_class($element)=='DOCSection') {

				if (($element->isHeader()) && ($element->isOnPage($onpage))) {
					//0.0 0.0  dummy data not used for header.
					$element->drawSection(0.0,0.0,$element->getY(),$DOC);
					$contenttop = $element->getY() + $element->getHeight();
					
					return $contenttop;
				}
			}
		}
	
		return 0;
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETTINGS($ELEMENT,$SETTINGS)
//*** Sets element settings.
//************************************************************************************************************************************
	
	private function drawFooter($onpage) {
	
		$elements = $this->elements;
		$DOC	  = $this->DOC;
		
		foreach($elements as $index => $element) {
			
			if (get_class($element)=='DOCSection') {
				
				if (($element->isFooter()) && ($element->isOnPage($onpage))) {
					//0.0 0.0 dummy data not used for footer.
					$element->drawSection(0.0,0.0,$element->getY(),$DOC);
					$contentbottom = $element->getY();
					return $contentbottom;
				}
			}
		}
	
		// Paper size as bottom if footer doesn't exist?!?!?!
		return 290.0;
		
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETTINGS($ELEMENT,$SETTINGS)
//*** Sets element settings.
//************************************************************************************************************************************
	
	private function drawContent($elements,$top,$bottom) {
		
		$DOC	  = $this->DOC;
		$cursor	  = $top;
		
		foreach($elements as $index => $element) {
				if ((!$element->isFooter()) && (!$element->isHeader())) {
					$cursor = $element->drawSection($top,$bottom,$cursor,$DOC);	
				}
		}	
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETTINGS($ELEMENT,$SETTINGS)
//*** Sets element settings.
//************************************************************************************************************************************
	
	private function getElementsOnPage($onpage) {

		$elements 		= $this->elements;
		$onpageelements = array();
		
		foreach($elements as $index => $element) {
			
			if ($element->isOnPage($onpage)) {
				
				$onpageelements[] = $element;
				
			}
		}
		
		return $onpageelements;
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETTINGS($ELEMENT,$SETTINGS)
//*** Sets element settings.
//************************************************************************************************************************************
		
	private function settings($element,$row) {
		
		if ($row->settings==null) return;
		
		$settings = explode(",",$row->settings);
		$pairs	  = array();
		
		
		
		foreach($settings as $index => $pair) {
			
				$setting = explode(":",$pair);	
				$settingpairs[$setting[0]] = $setting[1];			
		
		}
		
		foreach($settingpairs as $index => $pair) {

			switch($index) {
							
				case 'header-font-name' :
					$this->setElementHeaderFont($element,$settingpairs);
					break;
				
				case 'header-font-size' :
					$this->setElementHeaderFont($element,$settingpairs);
					break;
						
				case 'header-font-style' :
					$this->setElementHeaderFont($element,$settingpairs);
					break;
				
				case 'header-font-color' :
					$this->setElementHeaderFont($element,$settingpairs);
					break;
				
				case 'content-font-name' :
					$this->setElementContentFont($element,$settingpairs);
					break;
					
				case 'content-font-size' :
					$this->setElementContentFont($element,$settingpairs);
					break;
							
				case 'content-font-style' :
					$this->setElementContentFont($element,$settingpairs);
					break;
					
				case 'content-font-color' :
					$this->setElementContentFont($element,$settingpairs);
					break;
					
				case 'draw-color' :
					$this->setDrawColor($element,$settingpairs);
				break;
				
				case 'decimals' :
					$this->setDecimals($element,$settingpairs);
				break;
			
				case 'column-type' :
					$this->setColumnType($element,$settingpairs);
					break;
					
				case 'text-align' :
					$this->setTextAlign($element,$settingpairs);
					break; 
	
				case 'list-index-width' :
					$this->setListIndexWidth($element,$settingpairs);
					break;
					
				case 'list-value-width' :
					$this->setListValueWidth($element,$settingpairs);
					break;
					
				case 'list-draw-index' :
					$this->setListDrawIndex($element,$settingpairs);
					break;
					
				case 'sum-column' :
					$this->setSumColumn($element,$settingpairs);
					break;
			}	
		}		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETELEMENTCONTENTFONT($ELEMENT,$SETTINGPAIRS)
//*** Sets element's content-font.
//************************************************************************************************************************************
	
	private function setElementContentFont($element,$settingpairs) {
		
		$name = $settingpairs['content-font-name'];
		$size = $settingpairs['content-font-size'];
		$style= $settingpairs['content-font-style'];
		$color= $settingpairs['content-font-color'];
		
		$color = new DOCColor($color);
		
		$element->setContentFont(new DOCFont($name,$size,$style,$color));
	} 

//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETELEMENTHEADERFONT($ELEMENT,$SETTINGPAIRS)
//*** Sets element's header-font.
//************************************************************************************************************************************
	
	private function setElementHeaderFont($element,$settingpairs) {
	
		$name = $settingpairs['header-font-name'];
		$size = $settingpairs['header-font-size'];
		$style= $settingpairs['header-font-style'];
		$color= $settingpairs['header-font-color'];
	
		$color = new DOCColor($color);
		
		$element->setHeaderFont(new DOCFont($name,$size,$style,$color));
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setDrawColor($element,$settingpairs) {
		
		$color = $settingpairs['draw-color'];
		$color = new DOCColor($color);
		$element->setDrawColor($color);
		
	} 

//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setColumnType($element,$settingpairs) {
	
		$columntype = $settingpairs['column-type'];
		$element->setColumnType($columntype);
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setDecimals($element,$settingpairs) {
	
		$decimals = $settingpairs['decimals'];
		$element->setDecimals($decimals);
	
	}

//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setTextAlign($element,$settingpairs) {
	
		
		$class = get_class($element);

		$textalign = $settingpairs['text-align'];
		
		
		switch($class) {
			
			case 'DOCText' :
				$element->setTextAlign($textalign);
			break;
		}
		
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setListIndexWidth($element,$settingpairs) {
	
		$width = $settingpairs['list-index-width'];
		$element->setIndexWidth($width);
	
	}
	
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setListValueWidth($element,$settingpairs) {
	
		$width = $settingpairs['list-value-width'];
		$element->setValueWidth($width);
	
	}
	
//************************************************************************************************************************************
//*** PRIVATE FUNCTION SETDRAWCOLOR($ELEMENT,$SETTINGPAIRS)
//*** Sets element's draw-color.
//************************************************************************************************************************************
	
	private function setListDrawIndex($element,$settingpairs) {
	
		$draw = $settingpairs['list-draw-index'];
		if ($draw=='false') $element->setDrawIndex(false);
		if ($draw=='true') $element->setDrawIndex(true);
	}
	
	//************************************************************************************************************************************
	//*** PRIVATE FUNCTION SETSUMCOLUMN($ELEMENT,$SETTINGPAIRS)
	//*** Sets if element is sumcolumn.
	//************************************************************************************************************************************
	
	private function setSumColumn($element,$settingpairs) {
	
		$issum = $settingpairs['sum-column'];
		
		if ($issum=='true') $element->setSumColumn(true);
		if ($issum=='false') $element->setSumColumn(false);
		
	}
	
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAW()
//*** Draws document.
//************************************************************************************************************************************
	
	public function draw() {
	
		$lastpage = $this->createElements();
		$DOC = $this->DOC;
		
		$DOC->AddPage("P","A4",0);
	
		for($page=1;$page<=$lastpage;$page++) {
				
			$this->drawElements($page);
			$DOC->AddPage();
			
		}
		
		$DOC->Output();
	
	}
}


?>