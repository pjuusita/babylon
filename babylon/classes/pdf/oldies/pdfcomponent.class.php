<?php
abstract class PDFComponent {

	abstract public function show();
	
	protected $FPDF;
	
	protected $left;
	protected $top;
	protected $width;
	protected $height;
	
	protected $header_background_color		= _white;
	protected $header_border_color			= _white;
	protected $header_border_width 			= 0;
	protected $header_padding 				= 0;
	protected $header_margin				= 0;
	protected $header_align  				= "left";
	
	protected $content_background_color 	= _white;
	protected $content_border_color	 		= _white;
	protected $content_border_width 		= 0;
	protected $content_padding 				= 0;
	protected $content_margin 				= 0;
	protected $content_align 				= "left";
	
	protected $component_background_color 	= _white;
	protected $component_border_color 		= _white;
	protected $component_border_width 		= 0;
	protected $component_padding 			= 0;
	protected $component_margin 			= 0;
	protected $component_align 				= "left";
	
	protected $header_font_name 			= "Arial";
	protected $header_font_style 			= "";
	protected $header_font_size 			= 12;
	protected $header_font_color 			= _black;
	
	protected $content_font_name 			= "Arial";
	protected $content_font_style 			= "";
	protected $content_font_size 			= 12;
	protected $content_font_color 			= _black;
	
	protected static $page_header_height 	= 0;
	protected static $page_header_width  	= 0;
	protected static $page_header_top 	 	= 0;
	protected static $page_header_left 	 	= 0;
	
	protected static $page_footer_height 	= 0;
	protected static $page_footer_width  	= 0;
	protected static $page_footer_top 	 	= 290;
	protected static $page_footer_left 	 	= 290;
		
	protected static $page_width;
	protected static $page_height;
	protected static $page_top_margin	 	= 0;
	protected static $page_bottom_margin 	= 0;
	
	protected static $border = 0;
	protected static $borderstyle = 'solid';
	protected static $borderwidth = 1;
	
	public function setFPDF($fpdf) {
		$this->FPDF = $fpdf;
	}
	
	public function setComponentStyle($font_name,$font_style,$font_size,$font_color,$background_color,$border_color,$border_width,$padding,$margin,$align) {
	
		$this->component_font_name = $font_name;
		$this->component_font_style = $font_style;
		$this->component_font_size = $font_size;
		$this->component_font_color = $font_color;
	
		$this->component_background_color = $background_color;
		$this->component_border_color = $border_color;
		$this->component_border_width = $border_width;

		$this->component_padding = $padding;
		$this->component_margin = $margin;
		$this->component_align  = $align;
		
	}
	
	public function setHeaderStyle($font_name,$font_style,$font_size,$font_color,$background_color,$border_color,$border_width,$padding,$margin,$align) {
		
		$this->header_font_name = $font_name;
		$this->header_font_style = $font_style;
		$this->header_font_size = $font_size;
		$this->header_font_color = $font_color;
		
		$this->header_background_color = $background_color;
		$this->header_border_color = $border_color;
		$this->header_border_width = $border_width;

		$this->header_padding = $padding;
		$this->header_margin = $margin;
		$this->header_align  = $align;
	}
	
	public function setContentStyle($font_name,$font_style,$font_size,$font_color,$background_color,$border_color,$border_width,$padding,$margin,$align) {
	
		$this->content_font_name = $font_name;
		$this->content_font_style = $font_style;
		$this->content_font_size = $font_size;
		$this->content_font_color = $font_color;
	
		$this->content_background_color = $background_color;
		$this->content_border_color = $border_color;
		$this->content_border_width = $border_width;
		
		
		$this->content_padding = $padding;
		$this->content_margin = $margin;
		$this->content_align  = $align;
	}
	

	public function setHeaderFont($font_name,$font_style,$font_size,$font_color) {
		$this->header_font_name = $font_name;
		$this->header_font_style = $font_style;
		$this->header_font_size = $font_size;
		$this->header_font_color = $font_color;
	}
	
	public function setContentFont($font_name,$font_style,$font_size,$font_color) {
		$this->content_font_name = $font_name;
		$this->content_font_style = $font_style;
		$this->content_font_size = $font_size;
		$this->content_font_color = $font_color;
	}
	
	public function setLeftTop($left,$top) {
		$this->left = $left;
		$this->top = $top;
	}

	public function prepareFont($font_name,$font_style,$font_size,$font_color) {
		$fpdf = $this->FPDF;
		$rgb = $this->getRGBValues($font_color);
		$fpdf->setTextColor($rgb["r"],$rgb["g"],$rgb["b"]);
		$fpdf->SetFont($font_name,$font_style,$font_size);

	}
	
	public function prepareFillColor($color) {
		$fpdf = $this->FPDF;
		$rgb = $this->getRGBValues($color);
		$fpdf->setFillColor($rgb["r"],$rgb["g"],$rgb["b"]);
	}
	
	public function prepareDrawColor($color) {
		$fpdf = $this->FPDF;
		$rgb = $this->getRGBValues($color);
		$fpdf->setDrawColor($rgb["r"],$rgb["g"],$rgb["b"]);
	}
	
	public function prepareLineWidth($width) {
		$fpdf = $this->FPDF;
		$fpdf->setLineWidth($width);
	}
	
	public function getRGBValues($color) { 
		
		$rgb_values = array();
	
		$color = str_replace("#","",$color);
		$rgb_values["r"] = hexdec(substr($color,0,2));
		$rgb_values["g"] = hexdec(substr($color,2,2));
		$rgb_values["b"] = hexdec(substr($color,4,2));
	
		return $rgb_values;
	}
	
	public function drawComponentBackground() {
			
		$pdf = $this->FPDF;
	
		$left = $this->left;
		$top = $this->top;
		$width = $this->width;
		$height = $this->height;
		$border_width = $this->component_border_width;
		
		$background_color = $this->component_background_color;
		$border_color = $this->component_border_color;
	
		$this->prepareFillColor($background_color);
		$this->prepareDrawColor($border_color);
	
		$pdf->SetLineWidth($border_width);
	
		if ($border_width>0.0) $pdf->Rect($left,$top,$width,$height,"DF");
		if ($border_width==0) $pdf->Rect($left,$top,$width,$height,"F");
	
	}
}
?>