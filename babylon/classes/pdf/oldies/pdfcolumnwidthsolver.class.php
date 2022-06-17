<?php

function sortWidthsDesc($WA, $WB) {
	if ($WA > $WB) return -1;
	if ($WA < $WB) return 1;
	return 0;
}

class SolverRow {
	
	public $widths;
	
	public function __construct() {
		$this->widths = array();
	}
}

class SolverColumn {
	
	public $column_name;
	public $widths;
	
	public function __construct($column_name) {
		$this->widths = array();
		$this->column_name = $column_name;
	}
}

class PDFColumnWidthSolver extends PDFComponent {
	
	private $solver_rows;
	private $solver_columns;
	private $str_len = 3;
	
	public function __construct($table_width) {
		$this->solver_rows = array();
		$this->solver_columns = array();
		$this->table_width = $table_width;
	}

	// Constructs solver-rows.
	public function constructRowWidths($columns,$data,$padding,$margin) {
		
		$fpdf = $this->FPDF;
		
		// For each datarow
		foreach($data as $index => $item) {

			$row = new SolverRow();
			
			// For each item
			foreach($columns as $index => $column) {
							
				$value_name 				= $column->column_name;
				$string 					= $item->$value_name;
				$lenght 					= $fpdf->GetStringWidth($string) + $padding + $margin;
					
				$row->widths[$value_name]   = $lenght; 
			}
			
			$this->solver_rows[] = $row;
		}
	}
	
	// Constructs solver-columns.
	public function constructColumnWidths($columns,$data) {
		
		$solver_rows = $this->solver_rows;
		
		foreach($columns as $index=>$column_item) {
			
			$index_ = 0;
			$column_name = $column_item->column_name;
			$solver_column = new SolverColumn($column_name);
			
			foreach($solver_rows as $index=>$row) {
				$value =  $row->widths[$column_name];
				$solver_column->widths[$index_] = $value;
				$index_++;
			}
			
			$this->solver_columns[] = $solver_column;
		}
	}
	
	// Reconstructs solver-rows from sorted solver-columns.
	public function reconstructRows($columns) {
		
		$solver_rows = $this->solver_rows;
		$solver_columns = $this->solver_columns;
		$index_ = 0;
		
		foreach($solver_rows as $index => $solver_row) {

			foreach($solver_columns as $index => $solver_column) {
				$column_name = $solver_column->column_name;
				$solver_row->widths[$column_name] = $solver_column->widths[$index_];
			}
			
			$index_++;
		}
	}
	
	// Sorts solver-columns by descending order.
	public function sortColumns() {
		
		foreach($this->solver_columns as $index => $column) {
			usort($column->widths,'sortWidthsDesc');
		}
	}
	
	// Checks if row will fit in given table width multiplied by decreasing percentage.
	public function check($percentage) {
		
		$solver_rows = $this->solver_rows;
		
		$table_width = $this->table_width;
		
		foreach($solver_rows as $index => $row) {
			
			$length = 0;
			
			foreach($row->widths as $index => $width) {
				$length = $length + ($width * $percentage);		
			}

			if ($length<$table_width) {
				
				if ($this->debug) echo"<br>ROW LENGHT<br>".$length."<br>";
				
				return $row;
			}
		}
		
		return null;
	}
	
	// Sets column widths.
	public function setWidths($columns,$selected,$percentage) {
		
		$row = $selected;
		
		if ($this->debug) echo "<br><br>WIDTHS WITH ".($percentage*100)."%<br>";
		
		foreach($columns as $index => $column) {
			$column_name = $column->column_name;
			$column->column_width = $row->widths[$column_name] * $percentage;
	
			if ($this->debug) echo "".$column_name.":".$column->column_width."<br>";
		}
		
		return $columns;
	}
	
	// Clamps data into the boundaries dictated by column widths.
	public function clampData($columns,$data) {
			
		$fpdf = $this->FPDF;
	
		foreach($data as $index => $row) {
				
			foreach($columns as $index => $column) {
	
				$column_name = $column->column_name;
				$column_width = $column->column_width;
				$value = $row->$column_name;
				$length = $fpdf->GetStringWidth($value);
	
				while($length>$column_width) {
					$len = strlen($value) - 1;
					$value = substr($value,0,$len);
					$length = $fpdf->GetStringWidth($value);
				}
	
				$row->$column_name = $value;
			
			}
		}
	
		return $data;
	}
	

	public function solve($columns,$data,$fpdf,$page_width,$padding,$margin) {
	
		$this->FPDF = $fpdf;
		
		$this->debug=false;
		
		$this->constructRowWidths($columns,$data,$padding,$margin);
		
		if ($this->debug) $this->debugRows();
		
		$this->constructColumnWidths($columns,$data);
		
		if ($this->debug) $this->debugColumns();
		
		$this->sortColumns();
		
		if ($this->debug) $this->debugColumns();
		
		$this->reconstructRows($columns);
		
		if ($this->debug) $this->debugRows();
		
		$selected = null;
		$percentage = 1;
		
		while($selected==null) {
			$selected = $this->check($percentage);
			if ($selected==null) $percentage = $percentage - 0.01;
			if ($selected!=null) $columns = $this->setWidths($columns,$selected,$percentage);
		}
		
		return $columns;
	}
		
	public function show() {
		
	}
	
	public function debugRows() {
	
		$solver_rows = $this->solver_rows;
	
		echo "<br><br> DEBUG ROW DATA <br>";
	
		foreach($solver_rows as $index => $row) {
				
			$length = 0;
				
			foreach($row->widths as $index => $item) {
	
				echo ":".$item;
				$length = $length + $item;
			}
				
			echo " = ".$length."<br>";
		}
	}
	
	public function debugColumns() {
	
		$solver_columns = $this->solver_columns;
	
		echo "<br><br> DEBUG COLUMN DATA <br>";
	
		foreach($solver_columns as $index => $column) {
	
			echo "<br>".$column->column_name."<br>";
				
			foreach($column->widths as $index => $item) {
	
				echo $item.":";
	
			}
		}
	}
}
?>