<?php

class UIEditableTable extends UIField {
	
	private $title;
	private $defaultvalueID;
	private $datavariables;
	private $editables;
	private $returnparameters;
	private $columns;
	private $sortingdirection = 'ascanding';
	private $sortingcolumn;
	private $table_data;
	private $updateaction;
	
	public function __construct($title) {
	
		parent::__construct();

		$this->title 			= $title;
		
	}
	
	public function getTitle() {
		return $this->title;
	}

	public function addColumn($column) {
		if ($this->columns==null) $this->columns = array();
		$this->columns[]=$column;
	}
	
	public function setSortingColumn($column) {
		$this->sortingcolumn = $column;
	}
	
	public function setTableData($table_data) {
			
		$this->tabledata = $table_data;
	}
	
	public function setUpdateAction($updateaction) {
		
		$this->updateaction = $updateaction;
	}

//************************************************************************************************************
//*** FUNCTION CREATECOLUMNHEADER($column)
//*** Creates table header with column.
//************************************************************************************************************
	
	public function createEditableRows($columns,$tabledata) {
	
		$componentID 	= 0;
		$fieldID 		= $this->getID();
		$updateaction 	= $this->updateaction;
		
		foreach($tabledata as $index => $row) {
			$editable_row = new UIEditableRow($fieldID,$columns,$row,$updateaction);
			$editable_row->show();
		}
	}
	
	public function createTable($columns,$tabledata) {

		echo "<table>";
	
		foreach($columns as $index => $column) {
			$this->createColumnHeader($column);
			$column->setColumnID($index);
		}
	
		$this->createEditableRows($columns,$tabledata);
		UIEditableRow::createScripts();
	
		echo "</table>";
	}
	
//************************************************************************************************************
//*** FUNCTION CREATECOLUMNHEADER($column)
//*** Creates table header with column.
//************************************************************************************************************
	
	private function createColumnHeader($column) {
		
		$class = get_class($column);
		
		switch($class) {
			
			case 'UISortColumn':
				$this->createUISortColumnHeader($column);
				break;
				
			case 'UIArrayColumn':
				$this->createUIArrayColumnHeader($column);
				break;
			
			case 'UISelectColumn':
				$this->createUISelectColumnHeader($column);
				break;
						
			case 'UIFixedColumn':
				$this->createUIFixedColumnHeader($column);
				break;
				
			default : 
				$this->createUIFixedColumnHeader($column);
				break;
		}
	}

	private function createUISortColumnHeader($column) {
		
		$sorting_column = $this->sortingcolumn;
		
		echo "<th style='text-align:left'>";
		
			if (($column->sorticonup!=null) && ($column->sorticondown!=null)) {
		
				if ($sorting_column->name==$column->name) {
					if ($this->sortingdirection=='ascending') {
						echo "<img height=".$column->iconsize." width=".$column->iconsize." src='".$column->sorticonup."'>";
						$this->sortingdirection = 'descending';
					} else {
						echo "<img height=".$column->iconsize." width=".$column->iconsize." src='".$column->sorticondown."'>";
						$this->sortingdirection = 'ascending';
					}
				}
			}
			
		echo "<a href='". getUrl($column->sortlink) . "&sortdirection=".$this->sortingdirection."'>" . $column->name ."</a>";
		echo "</th>";
		
	}
	
	private function createUIArrayColumnHeader($column) {
		echo "<th style='text-align:left'>".$column->name."</th>";
	}
	
	private function createUISelectColumnHeader($column) {
	
		$dropDownMenuID = "place_holder_id";
	
		echo "<th  style='text-align:left' onmouseout=\"$('#" . $dropDownMenuID . "').hide()\" onMouseOver=\"$('#" . $dropDownMenuID . "').show()\" style=''>";
		echo "".$column->name;
		echo "	<div class='listtable-dropdownmenu' id=" . $dropDownMenuID . ">";
		echo "		<a href='".getUrl($column->link, array($column->datavariable =>  0))."'> kaikki </a><br>";
	
			foreach($column->data as $contentID => $content) {
				echo "<a href='".getUrl($column->link, array($column->datavariable =>  $contentID))."'>" . $content . "</a><br>";
			}
			
		echo "	</div>";
		echo "</th>";
	}
	
	
	private function createUIFixedColumnHeader($column) {
		echo "<th  style='text-align:left'>".$column->name."</th>";
	}
		
	public function show($data=null) {

		$columns 	= $this->columns;
		$tabledata	= $this->tabledata;
		
		$this->createTable($columns,$tabledata);
	}
}
?>