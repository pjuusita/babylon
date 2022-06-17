<?php


class PDFImage extends PDFComponent {

	private $image_name;
	// Server path to images = /home/babelsoftf/domains/babelsoft.fi/public_html/babylon/images/
	private $image_path =  "/home/babelsoftf/domains/babelsoft.fi/public_html/babylon/images/";
	
	public function __construct($image_name,$left,$top,$width,$height) {
		$this->image_name = $image_name;
		$this->width = $width;
		$this->height = $height;
		$this->left = $left;
		$this->top = $top;
	}
	
	public function show() {

		$pdf = $this->FPDF;
		$image = $this->image_path.$this->image_name;
		$width = $this->width;
		$height = $this->height;
		$left = $this->left;
		$top = $this->top;

		$pdf->Image($image,$left,$top,$width,$height);
	}
}

?>
