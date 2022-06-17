<?php
class DOCSection extends DOCElement {
	
	private $elements;
	private $DOC;
	private $isheader = false;
	private $isfooter = false;
	private $contenttop; // page-content-top.
	private $contentbottom; //page-content-bottom
	private $startpage;
	private $endpage;
	private $master;
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION __CONSTRUCT($x,$y)
//*** Constructor.
//************************************************************************************************************************************
	
	public function __construct($x,$y,$width,$height,$startpage,$endpage,$doc,$master) {
	
		$this->x 	  = $x;
		$this->y 	  = $y;
		
		$this->width  = $width;
		$this->height = $height;
		
		$this->startpage = $startpage;
		$this->endpage	 = $endpage;
		
		$this->master = $master;
		$this->DOC	  = $doc;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION ADDELEMENT($ELEMENT)
//*** Adds element to DOCSection.
//************************************************************************************************************************************
	
	public function addElement($element) {
		
		if ($this->elements==null) $this->elements = array();
		
		$this->elements[] = $element;
	
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION ISONPAGE($PAGE)
//*** Returns true if is on page.
//************************************************************************************************************************************
	
	public function isOnPage($page) {
		
		$start = $this->startpage;
		$end   = $this->endpage;
		
		if (($start<=$page) && ($page<=$end)) return true;
		
		return false;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAWELEMENTS()
//*** Draws elements contained in DOCSection.
//************************************************************************************************************************************
	
	private function drawElements($cursor) {
	
		$elements = $this->elements;
		$DOC	  = $this->DOC;
		
		$x		  = $this->x;
		$y		  = $this->y;
		
		if ($elements==null) return;
		
		foreach($elements as $index => $element) {
		
			$element->moveXY($x,$y);
			$bottom = $this->drawElement($element,$cursor,$DOC); 
			if ($bottom>$cursor) $cursor = $bottom;
		}
		
		return $cursor;
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAWELEMENTES()
//*** Draws elements contained in DOCSection.
//************************************************************************************************************************************
	
	public function drawElement($element,$cursor,$DOC) {
		
		$class 		= get_class($element);
		$master 	= $this->master;
		$isheader	= $this->isheader;
		$isfooter 	= $this->isfooter;
	
		switch($class) {
			
			case 'DOCTable' :
				
				$contentbottom = $this->contentbottom;
				
				return $element->drawTable($master,$DOC,$cursor,$contentbottom,$isheader,$isfooter);
				
				break;
				
			default : 
				
				return $element->draw($DOC);
				break;
		}
		
		
	}
	
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETASFOOTER()
//*** Sets if section is footer or not.
//************************************************************************************************************************************
	
	public function setAsFooter($isfooter) {
	
		$this->isfooter = $isfooter;
	
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETASHEADER()
//*** Sets if section is header or not.
//************************************************************************************************************************************
	
	public function setAsHeader($isheader) {
	
		$this->isheader = $isheader;
	
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION ISFOOTER()
//*** Returns if section is footer or not.
//************************************************************************************************************************************
	
	public function isFooter() {
	
		return $this->isfooter;
	
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION ISHEADER()
//*** Returns if section is header or not.
//************************************************************************************************************************************
	
	public function isHeader() {
	
		return $this->isheader;
	
	}
	
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAW($DOC)
//*** Stub. Not used.
//************************************************************************************************************************************
	
	public function draw($DOC) {
			
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAWSECTION($CURSOR,$DOC)
//*** Draw section.
//************************************************************************************************************************************

	public function drawSection($contenttop,$contentbottom,$cursor,$DOC) {
		
		$this->contenttop 		= $contenttop;
		$this->contentbottom 	= $contentbottom;
	
		$this->y 				= $cursor;
		$cursor 				= $this->drawElements($cursor);
		
		return $cursor;
		
	}
}

?>
