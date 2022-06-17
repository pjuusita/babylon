<?php

class PDFPage {

	private $FPDF;
	private $page_header;
	private $page_footer;
	private $show_page_header;
	private $show_page_footer;
	
	// params, obj,bool,bool.
	public function __construct($show_page_header,$show_page_footer) {
		
		$this->show_page_header = $show_page_header;
		$this->show_page_footer = $show_page_footer;
	}
	
	public function setPDF($FPDF) {
		$this->FPDF = $FPDF;
	}

	public function setHeader($header) {
		$this->page_header = $header;
	}
	
	public function setFooter($page_footer) {
		$this->page_footer = $page_footer;
	}

	/*public function getHeader($page_header) {
		$this->page_header = $page_header;
	}
	
	public function getFooter($page_footer) {
		$footer = $this->page_footer;
		return $footer;
	}*/
	
	public function showHeader() {
		$show_header = $this->show_header;
		return $show_header;
	}
	
	public function showFooter() {
		$show_footer = $this->show_footer;
		return $show_footer;
	}
	
	public function show() {
		$pdf = $this->FPDF;
		$pdf->AddPage();
	}
}

?>