<?php

class UIFloatingSelection {
	
	private $title;
	private $selectionID;
	private $nameVariable;
	private $draggableClass;
	private $draggableItems = null;
	
//********************************************************************************************************************************************************************
//** PUBLIC FUNCTION __CONSTRUCT(TITLE,NAMEVARIABLE,DATA,DRAGGABLECLASS,TOP,LEFT,ISPRIMARY)
//** Constructor.
//********************************************************************************************************************************************************************
	
	public function __construct($title,$nameVariable,$data,$draggableClass,$top,$left,$isPrimary) {
		
		$this->title 			= $title;
		$this->selectionID		= "selection".$title; 
		$this->nameVariable 	= $nameVariable;
		$this->draggableClass 	= $draggableClass;
		
		echo "<script>																															";
		echo "	var floatingSelectionElement = new floatingSelection('".$title."','".$isPrimary."');											";
		echo "	document.body.appendChild(floatingSelectionElement.element);																	";
		echo "																																	";
		echo "	$(floatingSelectionElement.element).offset({top : ".$top.", left : ".$left."});													";
		echo "																																	";
		echo "</script>																															";
		
		$this->createSelection($data);
		$this->createDraggableItems();
		
	}
	
//********************************************************************************************************************************************************************
//** PUBLIC FUNCTION CREATESELECTION(DATA)
//** Creates selection data.
//********************************************************************************************************************************************************************
		
	public function createSelection($data) {
		
		$selectionID 	= $this->selectionID;
		$varName 		= $this->title;
		
		echo "<script>																																	";
		echo "	var selectionElement = document.getElementById('".$selectionID."'); 																	";
		echo "	selectionElement.object.selectionData = [];																								";
		echo "</script>																																	";
		
		foreach($data as $rowIndex => $dataRow) {
		
			echo "<script>																																";
			echo "	var selectionItem = [];																												";
			echo "</script>																																";

			$dataVariables = $dataRow->getDataVariables();
			
			foreach($dataVariables as $varname => $varvalue) {
			
				echo "<script>																															";
				echo "	selectionItem['".$varname."'] = '".$varvalue."';																				";
				echo "</script>																															";
						
			}
			
			echo "<script>																																";			
			echo "	selectionElement.object.selectionData.push(selectionItem);																			";
			echo "</script>																																";
			
			
		}
		
		//$this->logData();
		
	}

//********************************************************************************************************************************************************************
//** PUBLIC FUNCTION CREATEDRAGGABLEITEMS()
//** 
//********************************************************************************************************************************************************************
		
	public function createDraggableItems() {
	
		$selectionID 	= $this->selectionID;
		$nameVariable	= $this->nameVariable;
		$draggableClass = $this->draggableClass;
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var selectionElement = document.getElementById('".$selectionID."');																		";
		echo "	selectionElement.object.createDraggableElements('".$nameVariable."','".$draggableClass."');												";
		echo "																																			";
		echo "</script>																																	";
	
	}
	
//********************************************************************************************************************************************************************
//**
//**
//********************************************************************************************************************************************************************
		
	public function logData() {
		
		$selectionID = $this->selectionID;
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var selectionElement = document.getElementById('".$selectionID."');																		";
		echo "	var selectionData	 = selectionElement.object.selectionData;																			";
		echo "																																			";
		echo "	console.log('Displaying logdata ' + selectionData.length);																				";
		echo "																																			";
		echo "																																			";
		echo "	var rowCount = selectionData.length;																									";
		echo "																																			";
		echo "	for(var row=0;row<rowCount;row++) {																										";
		echo "																																			";
		echo "		item		= 	selectionData[row];																									";
		echo "																																			";
		echo "		for(var index in item)	{																											";
		echo "			console.log(index + '=' + item[index]);																							";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
	}

}

?>