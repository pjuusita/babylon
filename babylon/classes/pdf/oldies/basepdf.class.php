<?php


include_once "pdfcomponent.class.php";
include_once "fpdf.class.php";
include_once "pdfsection.class.php";
include_once "pdftext.class.php";
include_once "pdfpagenumber.class.php";
include_once "pdftable.class.php";
include_once "pdftablecolumn.class.php";
include_once "pdftabledatecolumn.class.php";


class BasePDF extends PDFComponent {
	
	public 	$FPDF;
	private $components = null; 
	private $component_count = 0;

	private $page_footer = null;
	private $page_header = null;
	
	private $page_size = "A4"; // A3,A4,A5,Letter,Legal.
	private $page_orientation = "P"; //(P)ortrait, (L)andscape. 
	
	public function __construct($page_size = "A4") {
	
		if ($page_size=="A4") {
			self::$page_width = 210;
			self::$page_height = 290;
		}
		
		$this->FPDF = new FPDF();
		$this->FPDF->SetAutoPageBreak(false,0.0);	
	}
	
	public function setPageSize($page_size) {
		$this->page_size = $page_size;
	}
	
	public function setPageOrientation($page_orientation) {
		$this->page_orientation = $page_orientation;
	}
	
	public function setPageHeader($component) {
		$this->page_header = $component;
		self::$page_header_height  = $component->height;
		self::$page_header_width  = $component->width;
		self::$page_header_left = $component->left;
		self::$page_header_top = $component->top;
	}
	
	public function setPageFooter($component) {
		$this->page_footer = $component;
		self::$page_footer_height  = $component->height;
		self::$page_footer_width  = $component->width;
		self::$page_footer_left = $component->left;
		self::$page_footer_top = $component->top;
		
	}
	
	public function setPageTopMargin($margin) {
		self::$page_top_margin = $margin;
	}
	
	public function setPageBottomMargin($margin) {
		self::$page_bottom_margin = $margin;
	}
	
	public function getCursorY() {
	
		return $this->FPDF->GetY();
	}
	
	public function getCursorX() {
	
		return $this->FPDF->GetX();
	}
	
	public function getPDF() {
		
		return $this->FPDF;
	}
	
	public function addComponent($component) {
		
		if ($components=null) $components = array();
		
		$this->components[] = $component;
		$component->setFPDF($this->FPDF);
		$this->component_count++;
	}
	
	public function addPage($show_header,$show_footer) {
		
		$pdf = $this->FPDF;
		$newpage = new PDFPage($show_header,$show_footer);
		$this->addComponent($newpage);
	}
			
	public function showPDFSection($PDFSection) {
		$pdf = $this->FPDF;
		$PDFSection->setFPDF($pdf);
		$PDFSection->show();
	}
	
	public function showPDFPage($PDFPage) {
		
		$pdf = $this->FPDF;
		$show_page_header = $PDFPage->showPageHeader();
		$show_page_footer = $PDFPage->showPageFooter();
		$PDFPage->setFPDF($pdf);
		$PDFPage->show();
		
		$this->showHeaderAndFooter($show_page_header,$show_page_footer);
	}
	
	public function showPDFTable($PDFTable) {
		$pdf = $this->FPDF;
		$PDFTable->setFPDF($pdf);
		$PDFTable->show();
		
	}
		
	// Params bool,bool.
	public function ShowHeaderAndFooter($show_header,$show_footer) {
	
		$header = $this->page_header;
		$footer = $this->page_footer;
	
		if (($header!=null) && ($show_header)) {
			$pdf = $this->FPDF;
			$header->setFPDF($pdf);
			$header->show();
		}
		if (($footer!=null) && ($show_footer)) {
			$pdf = $this->FPDF;
			$footer->setFPDF($pdf);
			$footer->show();
		}
	}
		
	public function showComponents() {
		
		if ($this->components == null) {
			return;
		}
		
		foreach($this->components as $index => $component) {

			switch (true) {
					
				/*case $component instanceof PDFSection :
					$this->showPDFSection($component);	
					break;
				*/
				
				case $component instanceof PDFPage  :
					$this->showPDFPage($component);
					break;
					
				/*case $component instanceof PDFTable :
					$this->showPDFTable($component);
					break;
				*/		
				case 'default' :
					
					$component->show();
					
					break;
			}
		}
	}
	
	public function show() {
		
		$PDF = $this->FPDF;
		$PDF->AliasNbPages();
		
		$page_size = $this->page_size;
		$page_orientation = $this->page_orientation;
		
		$PDF->AddPage($page_orientation,$page_size,0);
			$this->ShowHeaderAndFooter(true,true);
			$this->showComponents();
		$PDF->Output();

	}
}

?>