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

class UINoteField extends UIField {
	
	
	private $title;
	private $datavariablename;
	private $urlparametername;
	private $multiline;
	private $maxValue = null;
	private $minValue = null;
	private $dataformatter = null;
	private $dataformat = null;
	private $onblurcallback = null;
	
	private $savecallback;
	
	
	public function __construct($text) {
		parent::__construct();
		$this->text = $text;
	}
	
	
	
	function setValueJSFunction() {
		return "setFieldValue_" . $this->getID() . "";
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
	
	
	function getTitle() {
		return "";
	}
	
	
	// nullia käytetään silloin kun ollaan insertoimassa, jolloin arvot on tyhjiä (oletusarvot pitää ehkä jotenkin pystyä asettamaan)
	function show($data = null) {
		
		echo "<div  id=textline-" . $this->getID() . " style='display:block;'>";
		echo "<table id=textlinexxx-" . $this->getID() . " cellspacing='1' cellpadding='1'  style='width:100%;'>";
		
		echo "	<tr>";
		echo "		<td class=field-text style='width:100%;'>";
		echo "			<div class=notediv id='sectionerrordiv-" . $this->getID() . "' style='margin-bottom:15px;'>";
		echo ""  . $this->text;
		echo "			</div>";
		echo "		</td>";
	
		echo "	</tr>";
		echo "</table>";
		echo "</div>";
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";

		

		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		echo "		}";
		echo "	</script>";
		
		
		
		echo "	<script>";
		echo "		function editfieldChanged_" . $this->getID() . "() {";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldGetSaveParams_" . $this->getID() . "() {";
		//echo "			return '" . $this->urlparametername . "=' + $('#editfield-" . $this->getID() . "').val();";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function editfielddeactivated_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		
		echo "	<script>";
		echo "		function editfieldactivated_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCancelEdit_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldFocus_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		
		
		echo "<script>";
		echo "	function showerror_" . $this->getID() . "(errormessage) {";
		echo "	}";
		echo "</script>";
	}
}

?>