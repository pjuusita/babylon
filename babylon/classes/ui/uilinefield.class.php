<?php

/**
 * Tällä elementillä saadaan sectioniin väliviiva, tämän avulla voidaan sectionia jakaa selkeämmin osiin
 * 
 * 
 * @author pjuusita
 *
 */

class UILineField extends UIField {
	
	private $title;
	private $data;
	private $defaultvalueID;
	private $datavariablename;
	private $urlparametername;
	private $defaultvalue = 0;
	
	public function __construct() {
		parent::__construct("", null);
		$this->title = "";
		$this->datavariablename = null;
		$this->urlparametername = null;
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
		echo "				<td style='width:100%;border-top:2px solid;border-color:#ccc;'>";
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
		echo "			<tr>";
		echo "			<td style='width:100%;border-top:2px solid;border-color:#ccc;'>";
		echo "			</tr>";
		echo "		</table>";
		echo "	</div>";
	}

		
	private function createScripts() {


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