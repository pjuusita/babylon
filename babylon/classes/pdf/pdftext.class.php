<?php


class PDFText {


	private $text;
	private $left;
	private $top;
	private $font;
	private $align;
	private $parent;
	
	
	public function __construct($left, $top, $text, $font = null) {
		$this->text = $text;
		$this->left = $left;
		$this->top = $top;
		$this->font = $font;
		$this->parent = null;
		$this->align = null;
	}
	
	public function setParent($parent) {
		$this->parent = $parent;
	}
	
	
	public function setAlign($align) {
		$this->align = $align;		
	}
	
	public function show($fpdf) {

		$top = $this->parent->getYPos();
		$left = $this->parent->getXPos();
		
		if ($this->font == null) {
			$this->font = PDF::$defaultfont;
		}
		
		if ($this->font != null) {
			$fpdf->SetFont($this->font->name, $this->font->style, $this->font->size);
			if ($this->font->color != null) {
				$fpdf->SetTextColor($this->font->color->r, $this->font->color->g, $this->font->color->b);
			}
		}

		if ($this->align == "R") {
			//$fpdf->SetXY($left,$top);
			$width = $fpdf->GetStringWidth($this->text);
			$fpdf->SetXY($left+$this->left-$width, $top+$this->top);
		} else {
			$fpdf->SetXY($left+$this->left,$top+$this->top);
		}
		
		$encoding = mb_detect_encoding($this->text);
		$text = iconv($encoding, 'Windows-1252', $this->text);

		$fpdf->Write(5,$text);
		
		/*
		if ($this->align == "R") {
			
			$fpdf->GetStringWidth($text);
			
			
			//$fpdf->SetFillColor(200,200,200);
			//$fpdf->Cell(1,5, $text, "TBRL", 0, 0, 'L');
		} else {
			$fpdf->Write(5,$text);
		}
		*/
		
		
		/*
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
		*/
		
	}
}
?>