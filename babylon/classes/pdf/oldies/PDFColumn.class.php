<?php
class PDFTableColumn {

	public $column_name = null;
	public $column_width = null;
	public $column_height = null;

	public function __construct($column_name,$column_width) {

		$this->column_name = $column_name;
		$this->column_width = $column_width;

	}
}
?>