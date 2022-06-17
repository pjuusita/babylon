<?php

class UIRadioField extends UIField {
	
	private $title;
	private $data;
	private $defaultvalueID;
	private $datavariablename;
	private $returnparameter;
	
	public function __construct($title, $datavariablename,$returnparameter,$fielditems) {

		parent::__construct();

		$this->title 			= $title;
		$this->datavariablename = $datavariablename;
		$this->returnparameter	= $returnparameter;
		$this->fielditems 		= $fielditems;
	}
				
	public function getTitle() {
		return $this->title;
	}
	
	private function createField($sectiondata,$fielditems) {
		$this->createShowDiv($sectiondata,$fielditems);
		$this->createEditDiv($sectiondata,$fielditems);
		$this->createScripts();
	}

	private function createShowDiv($sectiondata,$fielditems) {

		$title 	 		= $this->title;
		$fieldID 		= $this->getID();
		$datavariable 	= $this->datavariablename;
		$defaultvalue	= $fielditems[$sectiondata->$datavariable];
		$isactive		= $this->editactive;
		
		if (!$isactive) echo "<div id='show_div_".$fieldID."'>";
		if ($isactive) echo "<div id='show_div_".$fieldID."' style='display:none'>";
		
		echo "<table style='width:100%'>";

		echo "	<td style='width:26%;vertical-align:top;'>";
		echo "		".$title;
		echo "	</td>";

		echo "	<td style='width:75%'>";
		echo "		".$defaultvalue;
		echo "	</td>";

		echo "<td></td>";

		echo "</table>";
		echo "</div>";
	}

	private function createEditDiv($sectiondata,$fielditems) {

		$title   = $this->title;
		$fieldID = $this->getID();
		$isactive		= $this->editactive;
		
		
		if ($isactive) echo "<div id='edit_div_".$fieldID."'>";
		if (!$isactive) echo "<div id='edit_div_".$fieldID."' style='display:none'>";
		
		echo "<table style='width:100%'>";

		echo "	<td style='width:26%;vertical-align:top;'>";
		echo "		".$title;
		echo "	</td>";

		echo "	<td style='width:75%'>";
			$this->createItems($sectiondata,$fielditems);
		echo "	</td>";

		echo "<td></td>";

		echo "</table>";
		echo "</div>";
	}

	private function createItems($sectiondata,$fielditems) {

		foreach($fielditems as $index => $row) {
			$this->createItem($index,$sectiondata,$fielditems);
		}
	}

	private function createItem($index,$sectiondata,$fielditems) {

		$datavariable = $this->datavariablename;
		$value 		  = $fielditems[$index];
		$defaultID	  = $sectiondata->$datavariable;
		$fieldID	  = $this->getID();

		if ($this->isChecked($index,$defaultID))  echo "<input type='radio' id='".$index."' name='".$datavariable."_".$fieldID."' value='".$value."' checked='checked'>".$value."<br>";
		if (!$this->isChecked($index,$defaultID)) echo "<input type='radio' id='".$index."' name='".$datavariable."_".$fieldID."' value='".$value."'>".$value."<br>";
	}
	
	private function isChecked($item_index,$default_value) {
	
		if ($item_index == $default_value) return true;
		return false;
	}
	
	private function createScripts() {
	

		//***************************************************************************************************************
		//*** FUNCTION FIELDGETSAVEPARAMS()
		//*** Returns paramater-part of url required by controller to handle updating data.
		//***************************************************************************************************************
	
		$fieldID 			= $this->getID();
		$datavariable		= $this->datavariablename;
		$returnparameter	= $this->returnparameter;
		
		echo "<script>																									";
		echo "																											";
		echo "	function fieldGetSaveParams_".$fieldID."() {															";
		echo "																											";
		echo "			var radio_name 		= '".$datavariable."_".$fieldID."';											";
		echo "			var radio_buttons   = document.getElementsByName(radio_name);									";
		echo "			var length 			= radio_buttons.length;														";
		echo "			var param_string 	= '".$returnparameter."=';													";
		echo "																											";
		echo "			for(var n=0;n<length;n++) {																		";
		echo "																											";	
		echo "				var radiobutton = radio_buttons[n];															";
		echo "																											";
		echo "				if (radiobutton.checked) {																	";
		echo "																											";
		echo "					param_string += radiobutton.id;															";
		echo "					return param_string;																	";
		echo "				}																							";
		echo "			}																								";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION FIELDACTIVAEDIT()
		//*** Implements what happens when Edit-button is pressed on UISection
		//***************************************************************************************************************
	
		$fieldID		= $this->getID();
	
		echo "<script>																									";
		echo "																											";
		echo "	function fieldActivateEdit_".$fieldID."() {																";
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(false);																	";
		echo " 		showEditDiv_".$fieldID."(true);																		";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION CHECKONSAVE_()
		//*** Validate data when Save-button is pressed.
		//***************************************************************************************************************
	
		$fieldID		= $this->getID();
	
		echo "<script>																									";
		echo "																											";
		echo "	function fieldCheckOnSave_".$fieldID."() {																";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION ACCEPTEDITANDCLOSE_()
		//*** Hides edit-div and shows show-div.
		//***************************************************************************************************************
	
		$fieldID		= $this->getID();
	
		echo "<script>																									";
		echo "																											";
		echo "	function acceptEditAndClose_".$fieldID."() {															";
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(true);																		";
		echo " 		showEditDiv_".$fieldID."(false);																	";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
			
		//***************************************************************************************************************
		//*** FUNCTION FIELDCANCELEDIT_()
		//*** Cancels edit when Cancel-button is pressed.
		//***************************************************************************************************************
	
		$fieldID		= $this->getID();
	
		echo "<script>																									";
		echo "																											";
		echo "	function fieldCancelEdit_".$fieldID."() {																";
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(true);																		";
		echo " 		showEditDiv_".$fieldID."(false);																	";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION FIELDFOCUS_()
		//*** What happens when field is focused.
		//***************************************************************************************************************
	
		echo "<script>																									";
		echo "																											";
		echo "	function fieldFocus_".$fieldID."() {																	";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION FIELDSHOWERROR_()
		//*** Shows error.
		//***************************************************************************************************************
	
		echo "<script>																									";
		echo "																											";
		echo "	function fieldShowError_".$fieldID."() {																";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION SHOWSHOWDIV_(bool)
		//*** Shows or hides Show-div.
		//***************************************************************************************************************
	
		$fieldID = $this->getID();
	
		echo "<script>																									";
		echo "																											";
		echo "	function showShowDiv_".$fieldID."(show) {																";
		echo "																											";
		echo "		var show_div_id = '#show_div_".$fieldID."';															";
		echo "																											";
		echo "		if (show) $(show_div_id).show();																	";
		echo "		if (!show) $(show_div_id).hide();																	";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
			
		//***************************************************************************************************************
		//*** FUNCTION SHOWEEDITDIV_(bool)
		//*** Shows or hides Edit-div.
		//***************************************************************************************************************
	
		$fieldID = $this->getID();
	
		echo "<script>																									";
		echo "																											";
		echo "	function showEditDiv_".$fieldID."(show) {																";
		echo "																											";
		echo "		var edit_div_id = '#edit_div_".$fieldID."';															";
		echo "																											";
		echo "		if (show) $(edit_div_id).show();																	";
		echo "		if (!show) $(edit_div_id).hide();																	";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	}
	
	public function show($sectiondata) {
		
		$fielditems = $this->fielditems;
		$this->createField($sectiondata,$fielditems);
	}
}
?>