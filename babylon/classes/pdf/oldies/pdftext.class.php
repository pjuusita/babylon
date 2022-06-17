<?php
class PDFText extends PDFComponent {

	private $text = null;
	private $text_align = 'L';
	private $line_spacing = 0.35;
	
	public function __construct($text,$left,$top,$width,$height,$font_name,$font_style,$font_size,$font_color,$border_color,$border_width,$background_color) {

		$this->text = $text;
		$this->left = $left;
		$this->top = $top;
		$this->width = $width;
		$this->height = $height;

		$this->content_font_name = $font_name;
		$this->content_font_style = $font_style;
		$this->content_font_size = $font_size;
		$this->content_font_color = $font_color;
		
		$this->content_border_color = $border_color;
		$this->content_background_color = $background_color;
		$this->content_border_width = $border_width;
	}
	
	// $text_align = (L)eft,(C)enter,(R)ight,(J)ustification.
	public function setTextAlign($text_align) {
		$this->text_align = $text_align;
	}

	public function show() {

		$pdf = $this->FPDF;
	
		$text = $this->text;
		$text_align = $this->text_align;
		
		$left = $this->left;
		$top = $this->top;
		$width = $this->width;
		//In this context height means line height.
		$height = $this->content_font_size * $this->line_spacing;

		$font_name = $this->content_font_name;
		$font_style = $this->content_font_style;
		$font_size = $this->content_font_size;
		$font_color = $this->content_font_color;
		$border_color = $this->content_border_color;
		$background_color = $this->content_background_color;
		
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);
		$this->prepareDrawColor($border_color);
		$this->prepareFillColor($background_color);

		//FPDF uses ISO-8859-1 and Windows-1252 encoding. UTF8 needs to be converted to ISO-8859-1 or Windows-1252 (which contains Euro-symbol).
		$encoding = mb_detect_encoding($text);
		$text = iconv($encoding, 'Windows-1252', $text);
			
		//if (mb_detect_encoding($text)=='UTF-8') $text = iconv('UTF-8', 'Windows-1252', $text);
		
		$pdf->setXY($left,$top);
		$pdf->MultiCell($width,$height,$text,0,$text_align,true);
		
	}
}
?>