<?php

class UICompactList {
	
	private static 	$compactlist_count = 0;
	private			$title;
	private 		$compactlistID;
	private			$columns;
	private 		$buttons;
	private			$data;
	
	public function __construct($title) {
	
		$this->compactlistID = self::$compactlist_count;
		$this->title 		= $title;
		
		self::$compactlist_count++;
		
	}

	public function getTitle() {

		return $this->title;
	}
	
	public function getID() {
		
		return $this->compactlistID;
	}
	
	public function setData($data) {
		
		$this->data = $data;
	}

	public function addColumn($column) {
		
		if ($this->columns==null) $this->columns = array();
		
		$this->columns[] = $column;
	}
	
	public function addButton($button) {
		
		if ($this->buttons==null) $this->buttons = array();
	
		$this->buttons[] = $button;
	}
	
	private function createRows() {
		
		$data 	 = $this->data;
		$columns = $this->columns;
		$buttons = $this->buttons;
		
		foreach($data as $index => $item) {
			$row = new UICompactRow('compact_row');
			$row->setData($item);
			$row->setColumns($columns);
			if ($buttons!=null) $row->setButtons($buttons);
			$row->show();	
		}
	}
	
	private function createScripts() {
		
		//****************************************************************************************************************************
		//***FUNCTION DOACTION($action,$parameter)
		//***Implements UIButtons required JavaScript-function do action. $action = url; $itemID = param.
		//****************************************************************************************************************************
		
		echo "<script>																												";
		echo "																														";
		echo "	function doAction(action,datavariable,parameter) {																	";
		echo "																														";
		echo "		var url = action + '&id='+parameter; 																			";
		echo "		alert(url);																										";
		//echo "		window.location	= url;																							";
		echo "																														";
		echo "	}																													";
		echo "</script>																												";
		
	} 
	
	
	public function show($data) {
		
		$this->createRows();
		$this->createScripts();
		
	}	
}
?>