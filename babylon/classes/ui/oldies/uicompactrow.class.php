<?php

class UICompactRow extends UIField {
	
	private static 	$compactrow_count = 0;
	private			$title;
	private 		$compactrowID;
	private			$data;
	private 		$columns;
	private			$buttons;
	
	public function __construct($title) {
	
		$this->compactrowID = self::$compactrow_count;
		$this->title 		= $title;
	
		self::$compactrow_count++;
	
	}
	
	public function getTitle() {
	
		return $this->title;
	}
	
	public function getID() {
	
		return $this->compactrowID;
	}
	
	public function setData($data) {
	
		$this->data = $data;
	}
	
	public function setColumns($columns) {
		
		$this->columns = $columns;
	}
	
	public function setButtons($buttons) {
		
		$this->buttons = $buttons;
		$this->setButtonRowIDs();
		
	}
	
	
	private function setButtonRowIDs() {
		
		$buttons = $this->buttons;
		$data	 = $this->data;
		$rowID 	 = $data->getID();
		
		foreach($buttons as $index => $button) {

			$button->setItemID($rowID);
		}
	}
	
	public function setItemForwardAction($forward) {
		
		$this->forward = $forward;
	}
	
	private function createRow() {

		$columns = $this->columns;
		$buttons = $this->buttons;
		$rowdata = $this->data;
		
		echo "<table style='width:100%'>";
			
		foreach($columns as $column_index => $column) {

			$datavariable = $column->datavariable;
			
			echo "<tr>";
			
			echo "<td style='width:30%'>";
			echo "<b>";
			echo "".$datavariable;
			echo "</b>";
			echo "</td>";
				
			echo "<td style='width:30%'>";
			echo "".$rowdata->$datavariable;
			echo "</td>";
			
			echo "<td style='text-align:right'>";
				
			echo "</td>";
			
			echo "</tr>";
		}
		
			echo "<tr>";
			echo "<td colspan=4 style='text-align:right'>";

				if ($buttons!=null) {
		
					foreach($buttons as $button_index => $button) {
						$button->show();
					}
				}
				
			echo "</td>";
			echo "</tr>";
			
		echo "</table>";
		
		
		echo "<div style='border-style:solid none none none;border-color:lightgray'>";
		echo "</div>";
				
	}
	
	public function show($data = null) {

		$this->createRow();
	}
	
}

?>