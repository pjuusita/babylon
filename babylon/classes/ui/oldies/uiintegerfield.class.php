<?php

class UIIntegerField extends UIField {
	
	private $title;
	private $data;
	private $defaultvalueID;
	private $datavariablename;
	private $returnparameter;
	
	public function __construct($title, $datavariablename,$returnparameter) {

		parent::__construct();

		$this->title 			= $title;
		$this->datavariablename = $datavariablename;
		$this->returnparameter	= $returnparameter;
	}
		
	/*
	public function setEditActive($isactive) {
	}
	*/
		
	public function getTitle() {
		return $this->title;
	}
	
	private function createField($sectiondata) {
		$this->createShowDiv($sectiondata);
		$this->createEditDiv($sectiondata);
		$this->createScripts();
	}

	private function createShowDiv($sectiondata) {

		$title 	 		= $this->title;
		$fieldID 		= $this->getID();
		$datavariable 	= $this->datavariablename;
		$defaultvalue = $sectiondata->$datavariable;
	
		echo "<div id='show_div_".$fieldID."'>";

		echo "<table style='width:100%'>";

		echo "	<td style='width:26%'>";
		echo "		".$title;
		echo "	</td>";

		echo "	<td style='width:75%'>";
		echo "		".$defaultvalue;
		echo "	</td>";

		echo "<td></td>";

		echo "</table>";
		echo "</div>";
	}

	private function createEditDiv($sectiondata) {

		$title   = $this->title;
		$fieldID = $this->getID();

		echo "<div id='edit_div_".$fieldID."' style='display:none'>";
		echo "<table style='width:100%'>";

		echo "	<td style='width:26%'>";
		echo "		".$title;
		echo "	</td>";

		echo "	<td style='width:75%'>";
			$this->createItem($sectiondata);
		echo "	</td>";

		echo "<td></td>";

		echo "</table>";
		echo "</div>";
	}

	private function createItem($sectiondata) {

		$datavariable = $this->datavariablename;
		$fieldID	  = $this->getID();
		$value		  = $sectiondata->$datavariable;

		 echo "<input type='text' class='uitextfield' id='".$datavariable."_".$fieldID."' value='".$value."'><br>";
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
		echo "			var elementID 		= '".$datavariable."_".$fieldID."';											";
		echo "			var element  	  	= document.getElementById(elementID);										";
		echo "			var param_string 	= '".$returnparameter."=';													";
		echo "																											";
		echo "			param_string += element.value;																	";
		echo "			return param_string;																			";
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
		echo "			var elementID 	= '".$datavariable."_".$fieldID."';												";
		echo "			var element  	= document.getElementById(elementID);											";
		echo "			var value		= element.value;																";
		echo "			var rounded		= Math.round(value);															";
		echo "																											";
		echo "			if ((value!=rounded) || (value=='')) alert('Invalid integer value '+ value);					";	
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
		
		$this->createField($sectiondata);
	}
}
?>