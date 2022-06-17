<?php
class PDFLine extends PDFComponent {
	

	private $x1;
	private $x2;
	private $y1;
	private $y2;
	
	
	public function __construct($x1,$y1,$x2,$y2) {
		
		$this->x1 = $x1;
		$this->y1 = $y1;
		
		$this->x2 = $x2;
		$this->y2 = $y2;
		
	}
	
	public function show() {
		
		$pdf = $this->FPDF;
		
		$x1 = $this->x1;
		$y1 = $this->y1;
		$x2 = $this->x2;
		$y2 = $this->y2;
	
		$this->prepareDrawColor(_black);
		
		$pdf->Line($x1,$y1,$x2,$y2);
	
	}
}


?>