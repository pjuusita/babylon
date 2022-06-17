<?php

class PDFSection extends PDFComponent {
	
	private $components = null;
	
	
	
	public function __construct($background_color,$border_color,$border_width,$left,$top,$width,$height) {
		
		$this->component_background_color = $background_color;
		$this->component_border_color = $border_color;
		$this->component_border_width = $border_width;
		$this->left = $left;
		$this->top = $top;
		$this->width = $width;
		$this->height = $height;
	}
		
	
	public function setWidth($width) {
		$this->width = $width;
	}
	
	
	public function setHeight($height) {
		$this->height = $height;
	}
	
	
	public function setSize($width, $height) {
		$this->width = $width;
		$this->height = $height;
	}
	
	
	public function setPos($xPos, $yPos) {
		$this->left = $xPos;
		$this->top = $yPos;
	}
	
	
	
	
	public function addComponent($component) {
		
		if ($this->components==null) $this->components = array();
		
		//Calculate mean position from PDFSection x,y and relative component x,y
		$left = $this->left + $component->left;
		$top = $this->top + $component->top;
		$component->setLeftTop($left,$top);
		
		$this->components[] = $component;
	
	}
	
	public function showComponents() {
		
		$fpdf = $this->FPDF;
		$components = $this->components;
		
		if ($components==null) return;
			
		foreach($components as $index => $component) {
			$component->setFPDF($fpdf);
			$component->show();
		}
	}

	public function show() {

		$this->drawComponentBackground();
		$this->showComponents();
	}
}

?> 