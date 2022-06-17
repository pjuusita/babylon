<?php
class DOCLine extends DOCElement {
	

	private $x1;
	private $x2;
	private $y1;
	private $y2;
	private $linewidth = 0.4;


	public function __construct($x1,$y1,$x2,$y2) {
		
		$this->x1 	 = $x1;
		$this->y1 	 = $y1;
		
		$this->x2	 = $x2;
		$this->y2 	 = $y2;
		
	}
	
	public function moveXY2($x,$y) {
		
		$this->x1 = $this->x1 + $x;
		$this->y1 = $this->y1 + $y;
		
		$this->x2 = $this->x2 + $x;
		$this->y2 = $this->y2 + $y;
		
	}
	
	public function draw($DOC) {
		
		$x1    = $this->x1;
		$y1    = $this->y1;
		$x2    = $this->x2;
		$y2    = $this->y2;
	 	
		$width = $this->linewidth;
	 	
		$color = $this->drawcolor;
				
		$DOC->SetLineWidth($width);
		
		$DOC->SetDrawColor($color->r,$color->g,$color->b);
		$DOC->Line($x1,$y1,$x2,$y2);
		
		return -1;
	}
}


?>