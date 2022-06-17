<?php
class PDFTree extends PDFComponent {
	
	private $data;
	private $depth_tabbing = 5; 
	private $max_depth;
	private $primary_column_names;
	private $columns;
	
	private $parent_font_name;
	private $parent_font_style;
	private $parent_font_size;
	private $parent_font_color;
	
	private $child_font_name;
	private $child_font_style;
	private $child_font_size;
	private $child_font_color;
	
	public function __construct($left,$top,$base_pdf,$data,$raw_data,$column_names,$primary_column_names,$max_depth) {
			
		$this->data 				= $data;
		$this->raw_data				= $raw_data;
		$this->left 				= $left;
		$this->top 					= $top;
		$this->primary_column_names	= $primary_column_names;
		$this->max_depth 			= $max_depth;
		$this->FPDF 				= $base_pdf->FPDF;
		$this->base_pdf 			= $base_pdf;
		
		$this->addColumns($column_names);
		$this->setPrimaryColumns($primary_column_names);
	}
	
	private function validateDepth() {
		
		$depth = $this->max_depth;
		if ($depth<1) $this->max_depth = 1;
	}
	
	private function addColumns($column_names) {
		
		$this->columns = array();
		
		$columns = explode(":",$column_names);
		
		foreach($columns as $index => $column_name) {
			$width = 0;
			$new_column = new PDFTableColumn($column_name,$width);
			$this->columns[] = $new_column;
		}
	}
	
	private function setPrimaryColumns($primary_column_names) {
		
		$columns 		 = $this->columns;
		
		foreach($columns as $index => $column) {
			
			$column_name = $column->column_name;
			$pos 		 = strpos($primary_column_names,$column_name);
			
			if ($pos!== false) {
				$column->primary_column = true;
			} else {
				$column->primary_column = false;
			}
		}
	}
	
	private function solveColumnWidths() {
		
		$raw_data 			= $this->raw_data;
		$columns 			= $this->columns;
		$this->max_width 	= array();
			
		foreach($columns as $index => $column) {
				
			$max_width 		= 0;
			$current_width  = 0;
			
			foreach($raw_data as $index => $row) {
				
				$column_name = $column->column_name;
				$value = $row->$column_name;
				$current_width = $this->getWidth($value);
				if ($max_width < $current_width) $max_width = $current_width; 
				
			}

			$column->column_width = $max_width;
		}
	}
		
	private function getWidth($string) {

		$fpdf 				= $this->FPDF;
			
		$parent_font_size 	= $this->parent_font_size;
		$child_font_size	= $this->child_font_size;
		
		if ($parent_font_size > $child_font_size)  $this->prepareParentFont();
		if ($parent_font_size <= $child_font_size) $this->prepareChildFont();
		
		$string 	.= "_";
		$width 		= $fpdf->GetStringWidth($string);

		return $width;
	}
	
	private function solveSecondaryColumnLeft() {
	
		$columns 	 = $this->columns;
		$max_depth	 = $this->max_depth;
		$tabbing 	 = $this->depth_tabbing;
		$left		 = $this->left;
		
		$secondary_left	= $left + ($max_depth * $tabbing);
	
		foreach($columns as $index => $column) {
			if ($column->primary_column) $secondary_left = $secondary_left + $column->column_width;
		}
	
		return $secondary_left;
	} 
	
	private function drawTree() {
		
		$fpdf 		= $this->FPDF;
		$rows 		= $this->data;
		$left 		= $this->left;
		$top  		= $this->top;
		$font_size	= $this->prepareParentFont();
			
		foreach($rows as $index => $parent)  {
			
			$this->drawParent($left,$top,0,$parent);
			$top = $top + $font_size / 2;
			$top = $this->checkPageChange($top);
			$top = $this->drawChildren($left,$top,1,$parent);
		}
	}

	private function drawChildren($left,$top,$depth,$parent) {
			
		$max_depth = $this->max_depth;
		
		if (count($parent->childs)>0) {
			
			$depth++;
			
			foreach ($parent->childs as $index => $child) {
					
				if ((count($child->childs)==0) && ($depth<=$max_depth)) {
					$top = $this->drawChild($left,$top,$depth,$child);
				}

				if ((count($child->childs)>0) && ($depth<=$max_depth)) {
					$top = $this->drawParent($left,$top,$depth,$child);
					$top = $this->drawChildren($left,$top,$depth,$child);
				}
			}
		}
		
		return $top;
	}
	
	private function drawParent($left,$top,$depth,$parent) {
		
		$fpdf		= $this->FPDF;
		$columns 	= $this->columns;

		$left 			= $left + $depth * $this->depth_tabbing;
		$secondary_left = $this->solveSecondaryColumnLeft();
					
		foreach($columns as $index => $column) {

			$column_name 	= $column->column_name;
			$value 			= $parent->$column_name;
			$font_size		= 0;
			
			if ($column->primary_column)  {
				$font_size =  $this->prepareParentFont();
				$fpdf->Text($left,$top,$value);
				if ($value!="") $left = $left + $column->column_width;
				
			}
			if (!$column->primary_column){	
				$font_size = $this->prepareChildFont();
				$fpdf->Text($secondary_left,$top,$value);
				$secondary_left = $secondary_left + $column->column_width;
			}		
		}
	
		$top = $top + $font_size / 2;
		$top = $this->checkPageChange($top);
		return $top;
	}
	
	private function drawChild($left,$top,$depth,$child) {
		
		$fpdf				= $this->FPDF;
		$columns 			= $this->columns;		
		$left 				= $left + $depth * $this->depth_tabbing;
		$secondary_left 	= $this->solveSecondaryColumnLeft();
	
		foreach($columns as $index => $column) {

			$column_name 	= $column->column_name;
			$value 			= $child->$column_name;
			$font_size		= 0;
			
			if ($column->primary_column)  {
				$font_size = $this->prepareChildFont();
				$fpdf->Text($left,$top,$value);
				if ($value!="") $left = $left + $column->column_width;
			}
			if (!$column->primary_column){
				$font_size = $this->prepareChildFont();
				$fpdf->Text($secondary_left,$top,$value);
				$secondary_left = $secondary_left + $column->column_width;
			}
		}

		$top = $top + $font_size / 2;	
		$top = $this->checkPageChange($top);
		return $top;
	}
	
	private function checkPageChange($top) {
			
		$page_height 		= self::$page_height - self::$page_footer_height;
		$page_bottom_margin = self::$page_bottom_margin;
		
		if ($top>($page_height - $page_bottom_margin)) $top = $this->switchPage();
		
		return $top;
	}
	
	private function switchPage() {
	
		$fpdf     			= $this->FPDF;
		$base_pdf 			= $this->base_pdf;
		$page_header_height = self::$page_header_height;
		$page_top_margin	= self::$page_top_margin;
		
		$fpdf->addPage();
		$base_pdf->showHeaderAndFooter(true,true);
		
		return $page_header_height + $page_top_margin; 
	}
	
	private function prepareParentFont() {
		
		$font_name  = $this->parent_font_name;
		$font_style = $this->parent_font_style;
		$font_size  = $this->parent_font_size;
		$font_color = $this->parent_font_color;
		
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);
		
		return $font_size;
	}
	
	private function prepareChildFont() {

		$font_name 	= $this->child_font_name;
		$font_style = $this->child_font_style;
		$font_size  = $this->child_font_size;
		$font_color = $this->child_font_color;
		
		$this->prepareFont($font_name,$font_style,$font_size,$font_color);
		
		return $font_size;
	}
	
	public function setParentFont($parent_font_name,$parent_font_style,$parent_font_size,$parent_font_color) {

		$this->parent_font_name  = $parent_font_name;
		$this->parent_font_style = $parent_font_style;
		$this->parent_font_size  = $parent_font_size;
		$this->parent_font_color = $parent_font_color;
	}

	public function setChildFont($child_font_name,$child_font_style,$child_font_size,$child_font_color) {
		
		$this->child_font_name  = $child_font_name;
		$this->child_font_style = $child_font_style;
		$this->child_font_size  = $child_font_size;
		$this->child_font_color = $child_font_color;
	}
	
	private function debugColumnWidths() {
	
		$columns = $this->columns;
	
		foreach($columns as $index => $column) {
			echo "<br>".$column->column_name.":".$column->column_width;
		}
	
		echo "<br>";
	
		echo "Secondary left : ".$this->solveSecondaryColumnLeft()."<br>";
	}
	
	public function show() {
		$this->solveColumnWidths();
		$this->drawTree();
	}
}
?>