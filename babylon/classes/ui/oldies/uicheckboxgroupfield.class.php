<?php

class UICheckBoxGroupField extends UIField {

	private $title;
	private $fielditems;
	private $defaultvalueID;
	private $datavariablename;
	private $returnparameter;

	/**
	 * 
	 * 
	 */
	public function __construct($title, $datavariablename,$returnparameter,$fielditems) {

		parent::__construct();

		$this->title 			= $title;
		$this->datavariablename = $datavariablename;
		$this->returnparameter	= $returnparameter;
		$this->fielditems 		= $fielditems;
	
	}

	/*
	public function setEditActive($isactive) {
	}
	*/

	public function getTitle() {
		return $this->title;
	}

	private function createField($sectiondata,$fielditems) {
		$this->createShowDiv($sectiondata,$fielditems);
		$this->createEditDiv($sectiondata,$fielditems);
		$this->createScripts();
	}

	private function createShowDiv($sectiondata,$fielditems) {

		$title 	 			= $this->title;
		$fieldID 			= $this->getID();
		$datavariable 		= $this->datavariablename;		
		$datavariablearray  = $this->createDefaultValueArray($sectiondata->$datavariable);
		$isactive			= $this->editactive;
		
		if ($isactive) echo "<div id='show_div_".$fieldID."' style='display:none'>";
		if (!$isactive) echo "<div id='show_div_".$fieldID."'>";
		
		echo "<table style='width:100%'>";

		echo "	<td style='width:26%;vertical-align:top;'>";
		echo "		".$title;
		echo "	</td>";

		echo "	<td style='width:75%'>";
					$this->createFixedItems($sectiondata,$fielditems);
		echo "	</td>";

		echo "<td></td>";
		echo "</table>";
		echo "</div>";
	}

	private function createEditDiv($sectiondata,$fielditems) {

		$title   	= $this->title;
		$fieldID 	= $this->getID();
		$isactive 	= $this->editactive;
		 
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

	
	private function createFixedItems($sectiondata,$fielditems) {
	
		foreach($fielditems as $index => $row) {
			$this->createFixedItem($index,$sectiondata,$fielditems);
		}
	}
	
	private function createFixedItem($index,$sectiondata,$fielditems) {
	
		$datavariable 		= $this->datavariablename;
		$value 		  		= $fielditems[$index];
		//$default_values = $this->createDefaultValueArray('1:2:6');
		$default_values 	= $this->createDefaultValueArray($sectiondata->$datavariable);
		$fieldID	  		= $this->getID();
		
		if ($this->isChecked($index,$default_values)) echo "<div id='".$index."_".$fieldID."_checked' name='".$fieldID."_checked'>".$value."</div>";
		if (!$this->isChecked($index,$default_values)) echo "<div id='".$index."_".$fieldID."_checked' name='".$fieldID."_checked' style='display:none'>".$value."</div>";
	}
	
	
	private function createItems($sectiondata,$fielditems) {

		foreach($fielditems as $index => $row) {
			$this->createItem($index,$sectiondata,$fielditems);
		}
	}

	private function createItem($index,$sectiondata,$fielditems) {

		$datavariable 		= $this->datavariablename;
		$value 		  		= $fielditems[$index];
		//$default_values 	= $this->createDefaultValueArray('1:2:6');
		$default_values 	= $this->createDefaultValueArray($sectiondata->$datavariable); 
		$fieldID	  		= $this->getID();

		if ($this->isChecked($index,$default_values)) echo "<input type='checkbox' id='".$index."' name='".$datavariable."_".$fieldID."' value='".$value."' checked='checked'>".$value."<br>";
		if (!$this->isChecked($index,$default_values)) echo "<input type='checkbox' id='".$index."' name='".$datavariable."_".$fieldID."' value='".$value."'>".$value."<br>";
	}

	private function isChecked($item_index,$default_values) {
		
		foreach($default_values as $default_value_index => $default_value) {
			if ($item_index == $default_value) return true;
		}
		
		return false;
	}
	
	private function createDefaultValueArray($default_value_string) {
		
		$default_values = array();
		$default_values = explode(":",$default_value_string);
		return $default_values; 
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
		echo "			var element_name 	= '".$datavariable."_".$fieldID."';											";
		echo "			var elements	    = document.getElementsByName(element_name);									";
		echo "			var length 			= elements.length;															";
		echo "			var param_string 	= '".$returnparameter."=';													";
		echo "																											";
		echo "			for(var n=0;n<length;n++) {																		";
		echo "																											";
		echo "				var element = elements[n];																	";
		echo "																											";
		echo "				if (element.checked) {																		";
		echo "																											";
		echo "					param_string += element.id + ':';														";
		echo "				}																							";
		echo "			}																								";
		echo "																											";
		echo "		string_length = param_string.length;																";
		echo "		param_string  = param_string.substring(0,string_length-1);											";
		echo "																											";;
		echo "		return param_string;																				";
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
		echo "																											";
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

		
		//id='".$index."_".$fieldID."_checked'
		$fieldID		= $this->getID();

		echo "<script>																									";
		echo "																											";
		echo "	function acceptEditAndClose_".$fieldID."() {															";
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(true);																		";
		echo " 		showEditDiv_".$fieldID."(false);																	";
		echo "																											";
		echo "		var element_name 	= '".$datavariable."_".$fieldID."';												";
		echo "		var elements	    = document.getElementsByName(element_name);										";
		echo "		var length 			= elements.length;																";
		echo "																											";
		echo "			for(var n=0;n<length;n++) {																		";
		echo "																											";
		echo "				var element = elements[n];																	";
		echo "				var element_id = element.id; 																";
		echo "				var fixed_element_name = '#'+element_id+'_'+".$fieldID."+'_checked';						";
		echo "																											";
		echo "				if (element.checked) $(fixed_element_name).show();											";
		echo "				if (!element.checked) $(fixed_element_name).hide();											";
		echo "			}																								";
		echo "																											";
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
		echo "		var show_div = '#show_div_".$fieldID."';															";
		echo "																											";
		echo "		if (show) $(show_div).show();																		";
		echo "		if (!show) $(show_div).hide();																		";
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
		echo "		var edit_div = '#edit_div_".$fieldID."';															";
		echo "																											";
		echo "		if (show) $(edit_div).show();																		";
		echo "		if (!show) $(edit_div).hide();																		";
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
