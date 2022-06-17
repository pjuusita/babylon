<?php


class PDFNewPage {


	private $text;
	private $left;
	private $top;
	private $font;
	private $align;
	private $parent;
	
	
	public function __construct() {
		
	}
	
	public function setParent($parent) {
		$this->parent = $parent;
	}
	
	
	public function setAlign($align) {
		$this->align = $align;		
	}
	
	public function show($fpdf) {
		$this->parent->addPage(true,true);
	}
}
?>