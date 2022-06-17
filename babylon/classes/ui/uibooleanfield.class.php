<?php

class UIBooleanField extends UIField {
	
	private $title;
	private $data;
	private $defaultvalueID;
	private $datavariablename;
	private $urlparametername;
	private $defaultvalue = 0;
	
	public function __construct($title, $datavariablename,$urlparametername) {
		parent::__construct($title, $datavariablename);
		$this->title = $title;
		$this->datavariablename = $datavariablename;
		$this->urlparametername = $urlparametername;
	}
	
	
	public function getTitle() {
		return $this->title;
	}
	
	

	private function createShowDiv($data) {

		$var = $this->datavariablename;
		
		$display = "";
		if ($this->editactive) $display = "display:none";
		echo "<div id='show_div_" . $this->getID() . "' style='" . $display . "'>";
		echo "		<table style='width:100%'>";
		echo "			<tr>";
		echo "				<td  class=field-text  style='width:26%'>" . $this->title . "</td>";
		if ($data != null) {
			if ($data->$var == 1) {
				echo "				<td  class='iu-middle-block field-value' style='width:74%'>Kyllä</td>";
			} else {
				echo "				<td  class='iu-middle-block field-value' style='width:74%'>Ei</td>";
			}
		} else {
			echo "				<td  class='iu-middle-block field-value' style='width:74%'></td>";
		}
		//echo "				<td></td>";
		echo "			</tr>";
		echo "		</table>";
		echo "	</div>";
	}

	
	private function createEditDiv($data) {

		$var = $this->datavariablename;
		
		$display = "";
		if (!$this->editactive) $display = "display:none";
		
		echo "	<div id='edit_div_" . $this->getID() . "' style='" . $display . "'>";
		echo "		<table style='width:100%'>";
		echo "			<td style='width:26%'>";
		echo "			</td>";
		echo "			<td style='width:74%;'>";
		$checked = "";
		if ($data != null) {
			if ($data->$var == '1') $checked = " checked";
		}
		echo "<label style='display:block;'><input type='checkbox' class='uitextfield' style='vertical-align:middle;' id='editfield-" . $this->getID() . "' " . $checked . "> " . $this->title . "</label>";
		echo "			</td>";
		//echo "			<td></td>";
		echo "		</table>";
		echo "	</div>";
	}

	

	public function getVariable() {
		return $this->urlparametername;
	}
	
	

	function setValueJSFunction() {
		return "setFieldValue_" . $this->getID() . "";
	}
	
	
		
	private function createScripts() {


		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		echo "			console.log('setfieldvalue boole - '+value);";
		echo "			if (value == '1') {";
		echo "				$('#editfield-" . $this->getID() . "').attr('checked','checked');";
		echo "			} else {";
		echo "				$('#editfield-" . $this->getID() . "').removeAttr('checked');";
		echo "			}";
		echo "		}";
		echo "	</script>";
		

		echo "<script>";
		echo "	function fieldCheckOnSave_" . $this->getID() . "() {";
		echo "	}";
		echo "</script>";
		
		
		echo "<script>";
		echo "	function fieldGetSaveParams_" . $this->getID() . "() {";
		echo "		if ($('#editfield-" . $this->getID() . "').is(':checked')) {";
		//echo "			alert('boolean checked');";
		echo "			return '" . $this->urlparametername . "=1';";
		echo "		} else {";
		//echo "			alert('boolean not checked');";
		echo "			return '" . $this->urlparametername . "=0';";
		echo "		}";
		echo "	}";
		echo "</script>";
	
	
		echo "<script>";
		echo "	function fieldActivateEdit_" . $this->getID() . "() {";
		echo " 		showShowDiv_" . $this->getID() . "(false);";
		echo " 		showEditDiv_" . $this->getID() . "(true);";
		echo "	}";
		echo "</script>";
	
	
		echo "<script>";
		echo "	function acceptEditAndClose_" . $this->getID() . "() {";
		echo " 		showShowDiv_" . $this->getID() . "(true);";
		echo " 		showEditDiv_" . $this->getID() . "(false);";
		echo "	}";
		echo "</script>";
	
		
		echo "<script>";
		echo "	function fieldCancelEdit_" . $this->getID() . "() {";
		echo " 		showShowDiv_" . $this->getID() . "(true);";
		echo " 		showEditDiv_" . $this->getID() . "(false);";
		echo "	}";
		echo "</script>";
	
		
		echo "<script>";
		echo "	function fieldFocus_" . $this->getID() . "() {";
		echo "	}";
		echo "</script>";
	
		
		echo "<script>";
		echo "	function fieldShowError_" . $this->getID() . "() {";
		echo "	}";
		echo "</script>";
	
		
		echo "<script>";
		echo "	function showShowDiv_" . $this->getID() . "(show) {";
		echo "		var show_div_id = '#show_div_" . $this->getID() . "';";
		echo "		if (show) $(show_div_id).show();";
		echo "		if (!show) $(show_div_id).hide();";
		echo "	}";
		echo "</script>";
	
		
		echo "<script>";
		echo "	function showEditDiv_" . $this->getID() . "(show) {";
		echo "		var edit_div_id = '#edit_div_" . $this->getID() . "';";
		echo "		if (show) $(edit_div_id).show();";
		echo "		if (!show) $(edit_div_id).hide();";
		echo "	}";
		echo "</script>";
	}

	
	public function show($data) {
		$this->createShowDiv($data);
		$this->createEditDiv($data);
		$this->createScripts();
	}
	
}
?>