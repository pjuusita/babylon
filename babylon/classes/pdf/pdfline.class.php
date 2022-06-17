<?php



class PDFLine {
	
	private $components = null;
	
	private $left;
	private $top;
	private $width;
	private $height;
	
	private $parent;

	private $backgroundColor;
	private $borderColor;
	private $borderWidth;
	private $borderRadius;
	//private $transparent;
	
	public function __construct($left,$top,$width,$height) {
		
		$this->left = $left;
		$this->top = $top;
		$this->width = $width;
		$this->height = $height;
		
		//$this->transparent = false;
		$this->borderColor = null;
		$this->borderWidth = 0.02;
		$this->backgroundColor = null;
		$this->borderRadius = null;
		
		$this->components = array();
	}
		
	
	public function setBackgroundColor($colorcode) {
		$color = new PDFColor($colorcode);
		$this->backgroundColor = $color;
	}
	

	public function setBorderRadius($radius) {
		$this->borderRadius = $radius;
	}
	
	public function setBorderColor($colorcode) {
		$color = new PDFColor($colorcode);
		$this->borderColor = $color;
	}
	
	
	public function setBorderWidth($width) {
		$this->borderWidth = $width;
	}
	

	public function setParent($parent) {
		$this->parent = $parent;
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
	
	
	public function getXPos() {
		$xPos = $this->left;
		if ($this->parent != null) {
			$xPos = $this->parent->getXPos() + $xPos;
		}
		return $xPos;
	}
	
	
	public function getYPos() {
		$yPos = $this->top;
		if ($this->parent != null) {
			$yPos = $this->parent->getYPos() + $yPos;
		}
		return $yPos;
	}
	
	
	public function addComponent($component) {
		
		if ($this->components==null) $this->components = array();
		
		//Calculate mean position from PDFSection x,y and relative component x,y
		//$left = $this->left + $component->left;
		//$top = $this->top + $component->top;
		//$component->setLeftTop($left,$top);
		
		$this->components[] = $component;
		$component->setParent($this);
	}
	
	
	private function showContent($fpdf) {
		foreach($this->components as $index => $component) {
			$component->show($fpdf);
		}
	}
	
	
	private function drawBackground($color) {
		
	}
	
	
	public function show($fpdf) {

		$backgroundColor = $this->backgroundColor;
		$borderColor = $this->borderColor;
		
		$fpdf->SetLineWidth($this->borderWidth);
		if ($borderColor != null) $fpdf->SetDrawColor($borderColor->r, $borderColor->g, $borderColor->b);
		if ($backgroundColor != null) $fpdf->SetFillColor($backgroundColor->r,$backgroundColor->g,$backgroundColor->b);
		$fpdf->Line($this->left, $this->top, $this->width, $this->height);
		$this->showContent($fpdf);
	}
}

?> 