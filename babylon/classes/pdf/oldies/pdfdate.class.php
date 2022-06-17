<?php
class PDFDate extends PDFComponent {

	private $dateFormat = 'Y-m-d H:i:s';
	
	public function __construct($left,$top,$font_name,$font_style,$font_size,$font_color) {
		
		$this->content_font_name = $font_name;
		$this->content_font_style = $font_style;
		$this->content_font_size = $font_size;
		$this->content_font_color = $font_color;
		
		$this->left = $left;
		$this->top = $top;
	}
	
	public function setDateFormat($dateFormat) {
		
		$this->dateFormat = $dateFormat;
		
	}

	public function show() {
		
		$pdf 		= $this->FPDF;
		$dateFormat = $this->dateFormat;
		$date 		= date($dateFormat);
		$left 		= $this->left;
		$top 		= $this->top;
		
		$font_name 	= $this->content_font_name;
		$font_style = $this->content_font_style;
		$font_size 	= $this->content_font_size;
		$font_color = $this->content_font_color;
		
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);
		$pdf->Text($left,$top,$date);
		
	}
	
}
?>