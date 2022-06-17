<?php

class PDFColor {
		
	const BLACK = "000000"; //new PDFColor("000000");
	const WHITE = "FFFFFF"; //new PDFColor("FFFFFF");
	
	const DEBUG_BORDER = "78e08f"; //new PDFColor("#78e08f");
	const DEBUG_FILL = "f8c291"; //new PDFColor("#b8e994");
	
	public $r;
	public $g;
	public $b;
		
	public function __construct($color) {
		$this->setRGBValues($color);
	}
		
	public function setRGBValues($color) {
		//$color = str_replace("#","",$color);
		$this->r = hexdec(substr($color,0,2));
		$this->g = hexdec(substr($color,2,2));
		$this->b = hexdec(substr($color,4,2));
	}
	
	public function getRGBValues() {
		$rgb = array();
		$rgb['r'] = $this->r;
		$rgb['g'] = $this->g;
		$rgb['b'] = $this->b;
		return $rgb;	
	}
}


?>