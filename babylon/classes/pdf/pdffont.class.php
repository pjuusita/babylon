<?php

class PDFFont {

	// Courier
	// Helvetica
	// Arial
	// Times
	// Symbol
	// ZapfDingbats
	public $name;
	
	
	public $size;
	
	// B - bold
	// I - italic
	// U - underline
	public $style;
	public $color;

	
	
	public function __construct($name = 'Times',$size = 8, $style = "",$color = null) {
		$this->name  = $name;
		$this->size	 = $size;
		$this->style = $style;
		$this->color = $color;
	}
}

