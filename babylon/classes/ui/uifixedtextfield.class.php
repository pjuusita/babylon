<?php


/**
 * 
 * TODO
 *   - notemptya ei testattu
 *   - fixed length ei toteutettu, voi olla etta ei toteutetakkaan
 *   - onlynumbers ei toteutettu
 *   - tämä luokan nimi voitaisiin muuttaa UIStaticFieldiksi (tämä pitäisi myäs sallia selection fieldin arvoja ehkä, mutte ne hoituu UISelectField + editable(false).
 *
 */

class UIFixedTextField extends UIField {
	
	private $title;
	private $value;
	private $urlparam;
	private $urlvalue;
	
	/**
	 * 
	 * @param string $title		Kentän nimi/otsikko
	 * @param string $value		Käyttäliittymässä näkyvä arvo
	 * @param string $urlparam		Tämä on parametrin nimi joka lähtee urlissa
	 * @param string $urlvallue	Tämä on arvo joka lähtee urlissa, tämä voi olla esim. kentän ID-numero. Jos tämä on null, arvona on value
	 */
	public function __construct($title, $value, $urlparam = null, $urlvalue = null) {
		parent::__construct();
		$this->title = $title;
		$this->urlparam = $urlparam;
		if ($urlvalue == null) $this->urlvalue = $value;
		else $this->urlvalue = $urlvalue;
		$this->value = $value;
	}

	
	public function getTitle() {
		return $this->title;
	}
	
	/*
	public function setEditActive($boole) {
		//$this->editactive = $boole;
	}
	*/
	
	
	function setSaveCallback($functionname) {
		$this->savecallback = $functionname;
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
	
	function show($data = null, $tabindex = 0) {
		
		echo "<table style='width:100%'>";
		echo " 	<tr>";
		echo " 		<td  class=field-text style='width:26%;'>";
		echo "".$this->title;
		echo " 		</td>";
		echo " 		<td  class='iu-middle-block field-value' style='width:74%;'>";
		//$var = $this->value;
		//echo "". $data->$var;
		if ($this->urlparam != null) {
			echo "<a href='" . $this->urlparam . "'>" . $this->value . "</a>";
		} else {
			echo "<div id='editfield-" . $this->getID() . "'>" . $this->value . "</div>";
		}
		//echo "			<input type='hidden' id='editfield-" . $this->getID() . "' id='" . $this->urlparam . "' value='" . $this->urlvalue . "'>";
		echo " 		</td>";
		echo "		<td style'width:36%'></td>";
		echo " </tr>";
		echo "</table>";
		

		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		//echo "			alert('UIFixedTextField.setFieldValue');";
		echo "			$('#editfield-".$this->getID()."').html(value);";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function editfieldChanged" . $this->getID() . "() {";
		echo "			return true;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldGetSaveParams_" . $this->getID() . "() {";
		/*
		if ($this->urlparam != null) {
			echo "			return '" . $this->urlparam . "=" . $this->urlvalue . "';";
		} else {
			echo "			return '';";			
		}
		*/
		echo "			return '';";			
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			return true;";
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