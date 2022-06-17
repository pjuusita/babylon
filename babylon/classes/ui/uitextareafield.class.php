<?php


/**
 * Tämä on kopioitu UITextFieldistä, saattaa sisältää jotain tarpeetonta...
 * 
 * 
 * @author pjuusita
 *
 */
class UITextAreaField extends UIField {
	
	
	private $title;
	private $datavariablename;
	private $urlparametername;
	private $multiline;
	private $maxValue = null;
	private $minValue = null;
	//private $dataformatter = null;
	private $dataformat = null;
	private $onblurcallback = null;
	
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
		$this->multiline = 0;
	}
	
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setOnBlur($functioncall) {
		$this->onblurcallback = $functioncall;
	}
	
	
	public function getVariable() {
		return $this->urlparametername;
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
	
	public function setMultiline($linecount) {
		$this->multiline = $linecount;
	}
	
	// ei toteutettu
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
	
	
	/**
	 * TODO: tällaiseksi tämä pitää ehkä muuttaa.
	 * 
	 * @return string
	 */
	function getCheckfunction() {
		$string .= "		function () {";
		$string .= "			var value = $('#editfield-" . $this->getID() . "').val();";
		if ($this->maxValue != null) {
			$string .= "		if (value.length >" . $this->maxValue . ") return false;";
		}
		if ($this->minValue != null) {
			$string .= "		if (value.length <" . $this->minValue . ") return false;";
		}
		if ($this->notEmpty == true) {
			$string .= "		if (value == '') return false;";
		}
		$string .= "			return true;";
		return $string; 
	}

	
	
	public function formatValue($data) {
	
		if ($data == null) {
			$value = "";
		} else {
			$var = $this->datavariablename;
			if (is_array($data)) {
				$value = $data[$var];
			} else {
				$value = $data->$var;
				if ($this->dataformat == NULL) {
					if ($this->dataformatter == NULL) return $value;
					return $this->dataformatter->getString($value);
				} else {
					if ($this->dataformatter == NULL) return $value;
					$dataformatvar = $this->dataformat;
					$format = $data->$dataformatvar;
					return $this->dataformatter->getString($value,$format);
				}
			}
		}
	}
	
	
	// nullia käytetään silloin kun ollaan insertoimassa, jolloin arvot on tyhjiä (oletusarvot pitää ehkä jotenkin pystyä asettamaan)
	function show($data = null) {
		
		//echo "<br>Datavariablename - " . $this->datavariablename;
		//echo "<br>dataclass - "  . get_class($data);
		
		$var = $this->datavariablename;
		if ($data == null) {
			$value = "";
		} else {
			if (is_array($data)) {
				$value = $data[$var];					
			} else {
				$value = $data->$var;
			}
		}
				
		
		
		
		// huom naissa diveissa ei saa olla indenttia koska ne sekoittaa kayttaliittymaa
		/*
		if ($this->getEditActive() == true) {
			echo "<br>-- editactive true";
		} else {
			echo "<br>-- editactive false";
		}
		*/
		
		echo "<table  cellspacing='1' cellpadding='1' style='width:100%;'>";
		
		$visiblestr = "";
		if ($this->visible == false) {
			$visiblestr = "display:none";
		}
		
		echo "	<tr style='" . $visiblestr . "'>";
		echo "		<td class=field-text style='width:26%;vertical-align:top;'>" . $this->title . "</td>";
		
		if ($this->multiline == 0) {
			echo "		<td colspan=2 style='width:72%;'>";
		} else {
			echo "		<td style='width:72%'>";
		}
		
		
		
		if ($this->editactive == true) {
			
			if ($this->disabled == true) {
				if ($value == '') {
					// TODO: ei asetettu punaisella jos pakollinen kentta
					echo "<div id=fieldvalue-" . $this->getID() . " class= style='height:100%;'><font size=-1 style='font-style:italic;'>Ei asetettu</font></div>";
				} else {
					//echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;'>" . $value . "</div>";
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;'>" . $this->formatValue($data) . "</div>";
				}
				echo "<div id='editdiv-". $this->getID() ."' class=field-value style='height:100%;width:100%;display:none'>";
				//echo "<input disabled class=uitextfield-disabled id=editfield-". $this->getID() ." type='text' style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
				echo "<textarea disabled class=uitextfield-disabled id=editfield-". $this->getID() ." style='width:100%;' type='text' name=".$this->urlparametername." >ff ".$value."</textarea>";
				echo "</div>";
				
			} else {
				if ($value == '') {
					// TODO: ei asetettu punaisella jos pakollinen kentta
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'><font size=-1 style='font-style:italic;'>Ei asetettu</font></div>";
				} else {
					//echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'>" . $value . "</div>";
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'>" . $this->formatValue($data) . "</div>";
				}
				echo "<div id='editdiv-". $this->getID() ."' class=field-value style='height:100%;'>";
				if ($this->tabindex > 0) {
					echo "<input class=uitextfield tabindex=" . $this->tabindex . " id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_". $this->getID() ."()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
				} else {
					echo "<input class=uitextfield  id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_". $this->getID() ."()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
				}
				
				if ($this->enterAction != null) {
					echo "	<script>";
					echo "		$('#editfield-". $this->getID() ."').on('keyup', function (e) {";
					echo "			if (e.keyCode === 13) {";
					echo "				alert('enter pressed');";
					echo "			}";
					echo "		});";
					echo "	</script>";
					
				}
				
				echo "</div>";
			}
			
				
		} else {
			if ($value == '') {
				// TODO: ei asetettu punaisella jos pakollinen kentta
				echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;'><font size=-1 style='font-style:italic;'>Ei asetettu</font></div>";
			} else {
				//echo "<div id=fieldvalue-" . $this->getID() . " class='field-value' style='height:100%;'>" . $value . "</div>";
				if (is_array($value)) {	// COLUMNTYPE_INTEGERARRAY
					echo "<div id=fieldvalue-" . $this->getID() . " class='field-value' style='height:100%;'>" . implode(':', $value) . "</div>";
				} else {
					echo "<div id=fieldvalue-" . $this->getID() . " class='field-value' style='height:100%;'>" . $this->formatValue($data) . "</div>";
				}
			}
			if ($this->disabled == true) {
				echo "<div id='editdiv-". $this->getID() ."' class=field-value style='height:100%;display:none'>";
				echo "<input disabled class=uitextfield-disabled id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_" . $this->getID() . "()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
				echo "</div>";
			} else {
				echo "<div id='editdiv-". $this->getID() ."' class=field-value style='height:100%;display:none'>";
				if (is_array($value)) {	// COLUMNTYPE_INTEGERARRAY
					echo "<input class=uitextfield id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_" . $this->getID() . "()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='" . implode(':', $value) . "'>";
				} else {
					echo "<textarea class=uitextfield id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_" . $this->getID() . "()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;height:100px;' rows=5 type='text' name=".$this->urlparametername.">".$value."</textarea>";
				}
				echo "</div>";
			}
		}
		echo "		</td>";
	
		echo "	</tr>";
		echo "</table>";
		
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
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

		

		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		//echo "			console.log('setfilevalue - '+value);";
		echo "			$('#editfield-" . $this->getID() . "').val(value);";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(value);";
		echo "		}";
		echo "	</script>";
		
		
		
		echo "	<script>";
		echo "		function editfieldChanged_" . $this->getID() . "() {";
		echo "			if (fieldCheckOnSave_" . $this->getID() . "() == true) {";
		echo "				$('#errordiv-"  .$this->getID() . "').hide();";
		echo "			} else {";
		echo "				fieldShowError_" . $this->getID() . "();";
		echo "			}";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldGetSaveParams_" . $this->getID() . "() {";
		echo "			return '" . $this->urlparametername . "=' + $('#editfield-" . $this->getID() . "').val();";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			$('#editdiv-" . $this->getID() . "').show();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function editfielddeactivated_" . $this->getID() . "() {";
		if ($this->onblurcallback != null) echo "" . $this->onblurcallback . ";";
		echo "		}";
		echo "	</script>";
		
		echo "	<script>";
		echo "		function editfieldactivated_" . $this->getID() . "() {";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCancelEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(value);";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
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
		
		
		echo "	<script>";
		echo "		function fieldFocus_" . $this->getID() . "() {";
		echo "			alert('calling showError_" . $this->getID() . "');";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "<script>";
		echo "	function showerror_" . $this->getID() . "(errormessage) {";
		echo "		var textnode = document.createTextNode(errormessage);";
		echo "		textnode.id='errortext-" . $this->getID() . "';";
		echo "		$('#errordiv-" . $this->getID() . "').html('');";
		echo "		$('#errordiv-" . $this->getID() . "').append(textnode);";
		echo "		$('#errordiv-" . $this->getID() . "').show();";
		echo "	}";
		echo "</script>";
	}
}

?>