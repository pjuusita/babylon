<?php

class DOCText extends DOCElement {


	private $textalign;
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION __CONSTRUCT($x,$y)
//*** Constructor.
//************************************************************************************************************************************
	
	public function __construct($width,$height,$x,$y,$text) {
	
		$this->width 	= $width;
		$this->height 	= $height;
		
		$this->x 		= 	$x;
		$this->y 		= 	$y;
		
		$this->text 	=  	$text;
		
	}
	
//************************************************************************************************************************************
//*** PUBLIC FUNCTION SETTEXTALIGN($TEXTALIGN)
//*** Sets text-align.
//************************************************************************************************************************************
	
	public function setTextAlign($textalign) {
		
		$this->textalign = $textalign;
		
	}	
		
//************************************************************************************************************************************
//*** PUBLIC FUNCTION DRAW()
//*** Draws element.
//************************************************************************************************************************************
	
	public function draw($DOC) {

		$x 			= $this->x;
		$y 			= $this->y;
		
		$height 	= $this->height;
		$width		= $this->width;
		
		$text 		= $this->text;
		$textalign 	= $this->textalign;
		
		$font   	= $this->contentfont;
		
		$fontname 	= $font->name;
		$fontsize 	= $font->size;
		$fontstyle  = $font->style;
		$fontcolor	= $font->color;
		
		$DOC->setTextColor($fontcolor->r,$fontcolor->g,$fontcolor->b);
		$DOC->setFont($fontname,$fontstyle,$fontsize);
		$DOC->setXY($x,$y);
		$DOC->MultiCell($width,$height,$text,0,$textalign,false);
		
		return $DOC->GetY();
		
	}
}


?>