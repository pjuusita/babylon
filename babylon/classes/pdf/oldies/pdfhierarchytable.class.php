<?php

class PDFHierarchyLevel {
	
	public $columns;
	public $widths;
	public $height;
	public $padding;
	public $fontName;
	public $fontStyle;
	public $fontSize;
	public $fontColor;
	
	public function __construct($columns,$widths,$height,$padding,$fontName,$fontStyle,$fontSize,$fontColor) {
		
		$this->columns 		= explode(",",$columns);
		$this->widths		= explode(",",$widths);
		$this->height		= $height;
		$this->padding		= $padding;
		$this->fontName		= explode(",",$fontName);
		$this->fontStyle	= explode(",",$fontStyle);
		$this->fontSize		= explode(",",$fontSize);
		$this->fontColor 	= explode(",",$fontColor);
		
	}
	
}

class PDFHierarchyTable extends PDFComponent {
	
	
	private $root;
	private $basePDF;
	public $FPDF;
	private $levels;
	
	public function __construct($left,$top,$root,$basePDF) {
		
		$this->left 	= $left;
		$this->top		= $top + $basePDF->page_header_top + $basePDF->page_header_height;
		$this->root 	= $root;
		$this->basePDF 	= $basePDF;	
		$this->FPDF		= $basePDF->FPDF;
		
	}
	
	public function addLevel($level) {
		
		if ($this->levels==null) $this->levels = array();
		
		$this->levels[] = $level;
		
	}
	
	private function drawColumn($text,$column,$level,$left,$top) {
		
		$pdf        = $this->FPDF;
		
		$fontName	= $level->fontName[$column];
		$fontStyle	= $level->fontStyle[$column];
		$fontSize	= $level->fontSize[$column];
		$fontColor	= $level->fontColor[$column];
		
		$this->prepareFont($fontName,$fontStyle,$fontSize,$fontColor);
		
		$pdf->Text($left,$top,$text);
		
		
	}
	
	private function handleRow($item) {
		
		$levels 	= $this->levels;
		
		$currentLevel = $levels[$item->depthlevel];
		$columns	  = $currentLevel->columns;
		$widths		  = $currentLevel->widths;
		$padding	  = $currentLevel->padding;

		$left		= $padding;
		
		foreach($columns as $columnIndex => $column) {
				
			$this->drawColumn($item->$column,$columnIndex,$currentLevel,$left,$this->top);
			$left = $left + $widths[$columnIndex];
			
		}
		
		$this->top = $this->top + $currentLevel->height;
		
		$this->checkPageChange($currentLevel->height);
					
	}
	
	private function handleChildren($item) {
		
		$this->handleRow($item);
		
		$children = $item->getChildren();
		
		if ($children==null) return;
		
		foreach($children as $childIndex => $child) {
			
			$this->handleChildren($child);	
			
		}
	
	}
	 
	
	private function drawRoot() {
		
		$levels = $this->levels;
		$root	= $this->root;
		
			foreach($root as $rootIndex => $rootItem) {
				
				$this->handleChildren($rootItem);
				
				$this->top = $this->top + $levels[0]->height;
				
				$this->checkPageChange($levels[0]->height);
				
			}
	}
	
	private function checkPageChange($height) {
		
		$basePDF 	= $this->basePDF;
		$pageBottom = $basePDF->page_footer_top;
		
		if ($this->top>$pageBottom - $height) {
			
			$FPDF = $this->FPDF;
			$FPDF->AddPage("P","A4",0);
			$basePDF->showHeaderAndFooter(true,true);
			$this->top = $basePDF->page_header_top + $basePDF->page_header_height + 5;
			
		}
		
	}
	
	public function show()  {
		
		$this->drawRoot();
		
	}
	
}


?>