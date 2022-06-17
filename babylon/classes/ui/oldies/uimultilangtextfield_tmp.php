<?php


/**
 * 
 * TODO
 *   - notemptya ei testattu
 *   - fixed length ei toteutettu, voi olla etta ei toteutetakkaan
 *   - onlynumbers ei toteutettu
 *   
 * @author Kapteeni
 *
 */

class UIMultiLangTextField extends UIField {
	
	
	private $title;
	private $datavariablename;
	private $urlparametername;
	private $maxValue = null;
	private $minValue = null;
	
	private $savecallback;
	
	// TODO yhdista acceptempty ja not empty, toinen naista on turha
	private $acceptEmpty;
	private $notEmpty = false;
	
	public function __construct($title, $datavariablename, $urlparametername) {
		parent::__construct();
		$this->title = $title;
		$this->datavariablename = $datavariablename;
		$this->urlparametername = $urlparametername;
		$this->acceptEmpty = true;
	}
	
	
	public function getTitle() {
		return $this->title;
	}
	
	public function acceptEmpty($accept) {
		$this->acceptEmpty = $accept;		
	}
	
	public function setMaxLength($length) {
		$this->maxValue = $length;		
	}

	// tekstikentan tapauksessa tama on merkkijonon pituus
	public function setMinLength($length) {
		$this->minValue = $length;
	}
	
	// ei toteutettu
	function setSaveCallback($functionname) {
		$this->savecallback = $functionname;
	}
	
	
	function getCheckOnSaveJSFunction() {
		return "fieldCheckOnSave_" . $this->getID() . "()";
	}

	function getSaveParamsJSFunction() {
		return "fieldGetSaveParams_" . $this->getID() . "()";
	}

	function getActivateEditJSFunction() {
		return "fieldActivateEdit_" . $this->getID() . "()";
	}
	
	function getCancelEditJSFunction() {
		return "fieldCancelEdit_" . $this->getID() . "()";
	}
	
	function getSaveAcceptedJSFunction() {
		return "acceptEditAndClose_" . $this->getID() . "()";
	}
	
	function getShowErrorJSFunction() {
		return "fieldShowError_" . $this->getID() . "()";
	}
	
	function getSetFocusJSFunction() {
		return "fieldFocus_" . $this->getID() . "()";
	}
	
	private function createEditField($value) {
		
		$var = $this->datavariablename;
		$value = $data->$var;
		
		echo "<table style='width:100%;'>";
		echo "	<tr>";
		echo "		<td style='width:26%;'>" . $this->title . "</td>";
		echo "		<td style='width:36%;'>";
		if ($value == '') {
			echo "<div id=fieldvalue-" . $this->getID() . " class=iu-middle-block style='height:100%;'><font size=-1 style='font-style:italic;'>Ei asetettu</font></div>";
		} else {
			echo "<div id=fieldvalue-" . $this->getID() . " class=iu-middle-block style='height:100%;'>" . $value . "</div>";
		}
		echo "<div id='editdiv-". $this->getID() ."' class=iu-middle-block style='height:100%;display:none'>";
		
		echo "<input id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged" . $this->getID() . "()' onfocusin='editfieldactivated(".$this->getID().",". $this->getID() .")' onfocusout='editfielddeactivated(".$this->getID()."," . $this->getID() . ")' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
		echo "</div>";
		echo "		</td>";
		echo "		<td id='fieldmessage-" . $this->getID() . "' style='width:36%;'>";
		echo "			<div class=errordiv id='errordiv-" . $this->getID() . "' style='display:none'>";
		echo "			</div>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";	
	}
		
	private function createJavaScripts() {
		
		//***************************************************************************************************************
		// FIELDCHECKONSAVE
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		//echo "			alert('fieldCheckOnSave_" . $this->getID() . "()');";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		//echo "			alert('fieldvalue - '+value+' - '+value.length+' - " . $this->maxValue . "');";
		if ($this->maxValue != null) {
			echo "		if (value.length >" . $this->maxValue . ") return false;";
		}
		if ($this->minValue != null) {
			echo "		if (value.length <" . $this->minValue . ") return false;";
		}
		if ($this->notEmpty == true) {
			echo "		if (value == '') return false;";
		}
		echo "			return true;";
		echo "		}";
		echo "	</script>";

		//***************************************************************************************************************
		// EDITFIELDCHANGED
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function editfieldChanged" . $this->getID() . "() {";
		//echo "			alert('fieldonchange');";
		echo "			if (fieldCheckOnSave_" . $this->getID() . "() == true) {";
		//echo "				alert('fieldonchange - '+fieldCheckOnSave_" . $this->getID() . "());";
		echo "				$('#errordiv-"  .$this->getID() . "').hide();";
		echo "			} else {";
		echo "				fieldShowError_" . $this->getID() . "();";
		echo "			}";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		//***************************************************************************************************************
		// FIELDGETSAVEPARAMS
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function fieldGetSaveParams_" . $this->getID() . "() {";
		echo "			return '" . $this->urlparametername . "=' + $('#editfield-" . $this->getID() . "').val();";
		//echo "			alert('calling getSaveParams_" . $this->getID() . "');";
		//echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		//***************************************************************************************************************
		// FIELDCACTIVATEEDIT
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		//echo "			alert('calling activateEdit_" . $this->getID() . "');";
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			$('#editdiv-" . $this->getID() . "').show();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		//***************************************************************************************************************
		// FIELDCANCELEDIT
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function fieldCancelEdit_" . $this->getID() . "() {";
		//echo "			alert('calling cancelEdit_" . $this->getID() . "');";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		//***************************************************************************************************************
		// ACCEPTEDITANDCLOSE
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		//echo "			alert('calling acceptEditAndClose_" . $this->getID() . "');";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(value);";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		//***************************************************************************************************************
		// FIELDSHOWERROR
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		//echo "			alert('calling showError_" . $this->getID() . "');";
		
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		//echo "			alert('fieldvalue - '+value+' - '+value.length+' - " . $this->maxValue . "');";

		if ($this->maxValue != null) {
			echo "		if (value.length >" . $this->maxValue . ") {";
			echo "			showerror_" . $this->getID() . "('Maksimipituus " . $this->maxValue . " merkkia');";
			echo "			return true;";
			echo "		}";
		}
		
		if ($this->minValue != null) {
			echo "		if (value.length <" . $this->minValue . ") {";
			echo "			showerror_" . $this->getID() . "('Minimipituus " . $this->minValue . " merkkia');";
			echo "			return true;";
			echo "		}";
		
		}
		if ($this->notEmpty == true) {
			echo "		if (value == '') {";
			echo "			showerror_" . $this->getID() . "('Ei saa olla tyhja');";
			echo "			return true;";
			echo "		}";
		}
		
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		//***************************************************************************************************************
		// FIELDCFOCUS
		//***************************************************************************************************************
		
		echo "	<script>";
		echo "		function fieldFocus_" . $this->getID() . "() {";
		echo "			alert('calling showError_" . $this->getID() . "');";
		echo "			return false;";
		echo "		}";
		echo "	</script>";

		//***************************************************************************************************************
		// SHOWERROR
		//***************************************************************************************************************
		
		echo "<script>";
		echo "	function showerror_" . $this->getID() . "(errormessage) {";
		//echo "		alert('errormessage - '+errormessage);";
		echo "		var textnode = document.createTextNode(errormessage);";
		echo "		textnode.id='errortext-" . $this->getID() . "';";
		echo "		$('#errordiv-" . $this->getID() . "').html('');";
		echo "		$('#errordiv-" . $this->getID() . "').append(textnode);";
		echo "		$('#errordiv-" . $this->getID() . "').show();";
		echo "	}";
		echo "</script>";
	}
	
	public function show($data) {
	
		$this->createTable();
		$this->createJavaScripts();
		
	}
}

?>