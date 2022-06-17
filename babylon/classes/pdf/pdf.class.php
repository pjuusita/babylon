<?php



include_once "fpdf.class.php";
include_once "pdftext.class.php";
include_once "pdfline.class.php";
include_once "pdffont.class.php";
include_once "pdfcolor.class.php";
include_once "pdfsection.class.php";
include_once "pdfnewpage.class.php";


class PDF {
	
	public static $defaultfont;
	public static $dateFormat = 'Y-m-d H:i:s';
	
	public 	$FPDF;

	private $components = null; 
	private $pages = null;
	
	private $footer = null;
	private $header = null;
	
	private $width;
	private $height;
	private $pageSize; 						// A3,A4,A5,Letter,Legal.
	private $pageOrientation = "P";			//(P)ortrait, (L)andscape.
	
	private $debug = false;
	
	public function __construct() {

		$this->width = 210;
		$this->height = 290;
		$this->pageSize = "A4";
		$this->pageOrientation = "P";
		$this->components = array();
		
		$this->FPDF = new FPDF();
		$this->FPDF->SetAutoPageBreak(false,0.0);
		$this->FPDF->AliasNbPages();
		$this->FPDF->setTextColor(0,0,0);
		$this->FPDF->SetFont('Arial','',14);
		
		if (PDF::$defaultfont == null) PDF::$defaultfont = new PDFFont('Arial',14,'',new PDFColor(PDFColor::BLACK));
	}
	
	public function SetAutoPageBreak($boolean) {
		$this->FPDF->SetAutoPageBreak(false);
	}
	
	
	public function setPageSize($pageSise) {
		if ($pageSise == "A4") {
			$this->width = 210;
			$this->height = 290;
			$this->pageSize = "A4";
		}
	}
	
	
	public function setOrientation($pageOrientation) {
		$this->pageOrientation = $pageOrientation;
	}
	
	
	public function setHeader($component) {
		$this->header = $component;
	}

	
	public function setFooter($component) {
		$this->footer = $component;
	}
		
	
	public function addComponent($component) {
		$this->components[] = $component;
		$component->setParent($this);
	}

	
	
	public function addPage($showHeader,$showFooter) {
		//$newpage = new PDFPage($show_header,$show_footer);
		//$this->pages[] = $newpage;
		$this->FPDF->AddPage($this->pageOrientation,$this->pageSize,0);
	}
	
	
	
	private function showHeader() {
		if ($this->header != null) {
			$this->header->show();
		}
	}

	
	private function showFooter() {
		if ($this->footer != null) {
			$this->footer->show();
		}
	}
	
	
	public function getXPos() {
		return 0;
	}
	
	public function getYPos() {
		return 0;
	}
	
	
	private function showContent() {
		foreach($this->components as $index => $component) {
			$component->show($this->FPDF);
		}
	}
	
	
	public function setDebug($boolean) {
		$this->debug = $boolean;
	}
	
	
	public function isDebug() {
		return $this->debug;
	}
	
	
	private function printGrid() {
		
		$this->FPDF->SetDrawColor(10,10,10);
		$this->FPDF->SetLineWidth(0.02);
		
		for($xpos = 0; $xpos < $this->width; $xpos = $xpos+10) {
			$this->FPDF->Line($xpos, 0, $xpos, $this->height);
		}
		
		for($ypos = 0; $ypos < $this->height; $ypos = $ypos+10) {
			$this->FPDF->Line(0, $ypos, $this->width, $ypos);
		}
	}
	
	
	
	
	public function show() {
		$this->FPDF->AddPage($this->pageOrientation,$this->pageSize,0);
		$this->showHeader();
		$this->showContent();
		$this->showFooter();
		
		if ($this->debug == true) {
			$this->printGrid();
		}
		
		$this->FPDF->Output();
	}
}

?>