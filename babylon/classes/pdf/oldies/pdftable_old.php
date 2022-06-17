<?php

class PDFTable extends PDFComponent {
	
	private $data;
	private $base_pdf = null;
	private $columns = null;

	// A4 dimensions 210x297
	//private $table_top = 30;
	//private $table_bottom = 250;
		
	private $odd_row_color  = _white;
	private $even_row_color = _lightgray;
	private $autosolve		= 0;
	
	public function __construct($left,$top,$width,$columns,$data,$base_pdf) {
		$this->left     = $left;
		$this->top      = $top;	
		$this->data     = $data;
		$this->width	= $width;
		// Necessary for $base_pdf->Footer() call used in tandem with $FPDF->addPage() for page switch if table floods over.
		$this->FPDF = $base_pdf->FPDF;
		$this->base_pdf = $base_pdf;
		$this->addColumns($columns,$data);

	}
	
	private function getTableWidth() {
	
		$table_width = 0;
		$columns     = $this->columns;
	
		foreach($columns as $index=>$column) {
			$table_width = $table_width + $column->column_width;
		}
		
		return $table_width;
	}
	
	public function addColumns($columns,$data) {
		
		$static_width = 0;
		
		if ($this->columns==null) $this->columns = array();
		
		$column_array = array();
		$column_array = explode(":",$columns);
		
		foreach($column_array as $index => $column_name) {
			$new_column = new PDFTableColumn($column_name,$static_width);
			$this->columns[] = $new_column;
		}
	}
	
	public function setColumnWidths($widths) {
		
		$columns = $this->columns;
		$width_array = explode(":",$widths);
		$width_index = 0;
		
		foreach($columns as $index => $column) {
		
			$column->setWidth($width_array[$width_index]);
			$width_index++;
		}
	}
	
	public function solveColumnWidths($data) {
		
		$columns = $this->columns;
		$data = $this->data;
		$fpdf = $this->FPDF;
		$table_width = $this->width;
		$padding = $this->content_padding;
		$margin = $this->content_margin;
		
		$font_name  = $this->content_font_name;
		$font_style = $this->content_font_style;
		$font_size  = $this->content_font_size;
		$font_color = $this->content_font_color;
		
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);
		
		$solver = new PDFColumnWidthSolver($table_width);
		$this->columns = $solver->solve($columns,$data,$fpdf,$table_width,$padding,$margin);
		
		$this->data = $solver->clampData($columns,$data,$fpdf);
		
	}

	private function drawTableHeader($left,$top) {
	
		$pdf        = $this->FPDF;
		$columns    = $this->columns;
		$table_left = $left;
		$table_top  = $top;
		
		$background_color = $this->header_background_color;
		$border_color     = $this->header_border_color;
		$border_width     = $this->header_border_width;
		
		$this->prepareDrawColor($border_color);
		$this->prepareLineWidth($border_width);
		$this->prepareFillColor($background_color);

		$item_left =  $this->component_margin;
		$item_top = $top;
		
		foreach($columns as $index => $column) {
			
			$header_left   = $table_left + $item_left;
			$header_top    = $table_top + $this->header_margin;
			$header_width  = $column->column_width;
			$header_margin = $this->header_margin;
			$header_height = $this->header_font_size / 2;
			$value         = $column->column_name;
			 
			$pdf->Rect($header_left,$header_top,$header_width,$header_height,"DF");
			
			$this->drawHeaderContent($item_left+$header_margin,$item_top,$value);
			
			$item_left = $item_left + $header_width;
		}
	}
	
	private function drawTableBackground($left,$top,$drawn_rows) {
	
		$pdf    = $this->FPDF;
		$line_height = $this->content_font_size / 2;
		$height = (count($this->data) *  $line_height) - ($drawn_rows * $line_height) + self::$page_header_height;
		$width  = $this->getTableWidth() + $this->content_padding * 2 + $this->component_padding * 2;
	
		$border_color     = $this->component_border_color;
		$border_width     = $this->component_border_width;
		$background_color = $this->component_background_color;
	
		$table_top = self::$page_header_height;
		$table_bottom = self::$page_height - self::$page_footer_height - self::$page_bottom_margin;
	
		if (($top + $height) > $table_bottom) $height = $table_bottom - $top;
	
		$this->prepareDrawColor($border_color);
		$this->prepareFillColor($background_color);
		$pdf->SetLineWidth($border_width);
		$pdf->Rect($left,$top,$width,$height,"DF");
	}
	
	private function drawHeaderContent($left,$top,$value) {
	
		$pdf = $this->FPDF;
	
		$font_name  = $this->header_font_name;
		$font_style = $this->header_font_style;
		$font_size  = $this->header_font_size;
		$font_color = $this->header_font_color;
	
		$left  = $left + $this->left + $this->header_padding + $this->header_margin;
		$top = $top + ($font_size / 3) + $this->component_padding + $this->header_padding + $this->header_margin;
	
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);
		$pdf->Text($left,$top,$value);
	}	
	
	private function switchPage($drawn_rows) {
		
		$fpdf     	 = $this->FPDF;
		$base_pdf 	 = $this->base_pdf;
		$top_margin  = self::$page_top_margin;
		$y       	 = self::$page_header_height + $this->component_padding + $top_margin;
		$x        	 = $this->left;
		
		$table_header_height = $this->header_font_size / 2 + $this->header_padding * 2 + $this->component_padding * 2;
		
		$fpdf->addPage();
		$base_pdf->showHeaderAndFooter(true,true);
		$this->drawTableBackground($x,$y,$drawn_rows);
		$this->drawTableHeader($x,$y);
		
		return $y + $table_header_height;
	}
	
	private function finalizeTable() {
		
		$fpdf     = $this->FPDF;
		$base_pdf = $this->base_pdf;
		
		$fpdf->addPage();
		$base_pdf->showHeaderAndFooter(true,true);
	}
	
	private function drawTableRows() {

		$row_number   		= 0;
		$y            		= $this->top + $this->header_font_size / 2 + $this->header_padding * 2 + $this->content_padding * 2 + $this->component_padding * 2;
		$data         		= $this->data;
		$columns     		= $this->columns;
		$table_top    		= self::$page_header_height;
		$table_bottom 		= self::$page_height - self::$page_footer_height;
		$line_height  		= $this->content_font_size / 2;
		$page_bottom_margin = self::$page_bottom_margin;
		
		// foreach datarow.
		foreach($data as $index => $item) {
			
			$x = $this->left;
			
			$this->drawRowBackground($x,$y,$row_number);
			
		// foreach selected column.
			foreach($columns as $index => $column) {
				
				$variable_name =  $column->column_datavariable;
				$width = $column->column_width;
				$value = $item->$variable_name;
				$this->drawTableContent($x,$y,$value);
				$x = $x + $width; 
			}
			
			$y = $y + $line_height;
			
			$row_number++;
			
			// If $table_bottom exceeded make page switch.
			if (($y + $line_height) > ($table_bottom - $page_bottom_margin)) $y = $this->switchPage($row_number);	
		}
	}
	
	private function drawRowBackground($x,$y,$row_number) {
		
		$pdf            = $this->FPDF;
		$width          = $this->getTableWidth();
		$line_height    = $this->content_font_size;
		$even_row_color = $this->even_row_color;
		$odd_row_color  = $this->odd_row_color;
		
		$x 				= $x + $this->header_padding + $this->component_padding;
		
		if ($row_number%2==0) {
			$this->prepareFillColor($even_row_color);
			$this->prepareDrawColor($even_row_color);
		}
		if ($row_number%2!=0) {
			$this->prepareFillColor($odd_row_color);
			$this->prepareDrawColor($odd_row_color);	
		}
		
		$pdf->Rect($x,$y,$width,$line_height/2,"DF");
	}
	
	private function drawTableContent($x,$y,$value) {
		
		$pdf = $this->FPDF;
		
		$font_name  = $this->content_font_name;
		$font_style = $this->content_font_style;
		$font_size  = $this->content_font_size;
		$font_color = $this->content_font_color;
		
		$left = $x + $this->content_margin + $this->content_padding;
		$top = $y + ($font_size / 3) + $this->content_padding;
			
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);	
		$pdf->Text($left,$top,$value);
	}
	
	public function show() {
		
		$table_left = $this->left;
		$table_top  = $this->top;
		$data 		= $this->data;
		$autosolve  = $this->autosolve;
		
		if ($autosolve) $this->solveColumnWidths($this);
		
		$this->drawTableBackground($table_left,$table_top,0);
		$this->drawTableHeader($table_left,$table_top);
		$this->drawTableRows();
		$this->finalizeTable();
	}
}
?>