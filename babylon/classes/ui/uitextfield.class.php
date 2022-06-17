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

class UITextField extends UIField {
	
	
	private $title;
	private $datavariablename;
	private $urlparametername;
	private $multiline;
	private $maxValue = null;
	private $minValue = null;
	private $dataformat = null;
	private $dataformatvariable = null;
	private $onblurcallback = null;
	private $notsettedtext = null;
	private $savecallback;
	private	$unit = null;
	// TODO yhdista acceptempty ja not empty, toinen naista on turha
	private $acceptEmpty;
	private $notEmpty = false;
	
	public function __construct($title, $datavariablename, $urlparametername = null) {
		parent::__construct();
		$this->title = $title;
		$this->datavariablename = $datavariablename;
		if ($urlparametername == null) {
			$this->urlparametername = $datavariablename;
		} else {
			$this->urlparametername = $urlparametername;
		}
		$this->acceptEmpty = true;
		$this->multiline = 0;
	}

	public function setNotSettedText($str) {
		$this->notsettedtext = $str;
	}

	public function setUnit($str) {
		$this->unit = $str;
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
	
	
	public function setFormat($dataformat, $dataformatvariable = null) {
		$this->dataformat = $dataformat;
		if ($dataformatvariable == null) {
			if ($dataformat == Column::COLUMNTYPE_FLOAT) $this->dataformatvariable = 2;
		} else {
			$this->dataformatvariable = $dataformatvariable;
		}
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

	
	
	public function formatValue($value, $data) {
	
		
		if ($value == null) {
			$value = "";
		} else {
			
			if ($this->dataformatter != null) {
				$var = $this->dataformatterparam;
				$paramvalue = $data->$var;
				//return "--" . $value . "-" . $var . "-" . $paramvalue;
				return $this->dataformatter->getString($value, $paramvalue);
			}
			
			if ($this->dataformat == Column::COLUMNTYPE_FLOAT) {
				$var = $this->datavariablename;
				$floatval = $data->$var;
				$value = number_format($floatval, 2, ',', '') . "";
				return $value;
			}
		}
		return $value;
	}
	
	
	// nullia käytetään silloin kun ollaan insertoimassa, jolloin arvot on tyhjiä (oletusarvot pitää ehkä jotenkin pystyä asettamaan)
	function show($data = null, $widths = null) {
		
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
		
		//echo "<table  cellspacing='1' cellpadding='1' style='width:100%;'>";
		if ($this->isHiddenFromView() == true) {
			echo "<div  id=textline-" . $this->getID() . " style='display:none;'>hidden";
			echo "<table id=textlinexx-" . $this->getID() . "  cellspacing='1' cellpadding='1'  style='width:100%;background-color:pink;'>";
		} else{
			echo "<div  id=textline-" . $this->getID() . " style='display:block;'>";
			echo "<table id=textlinexxx-" . $this->getID() . " cellspacing='1' cellpadding='1'  style='width:100%;'>";
		}
		$visiblestr = "";
		if ($this->visible == false) {
			$visiblestr = "display:none";
		}
		
		echo "	<tr style='" . $visiblestr . "'>";
		
		if ($widths != null) {
			echo "		<td class=field-text style='width:" . $widths[1] . "'>" . $this->title . "</td>";
			echo "		<td colspan=2 style='width:" . $widths[2] . "'>";
		} else {
			echo "		<td class=field-text style='width:26%;'>" . $this->title . "</td>";
			
			if ($this->multiline == 0) {
				echo "		<td style='width:38%;'>";
			} else {
				echo "		<td colspan=2 style='width:72%'>";
			}
		}
		
		
		
		if ($this->editactive == true) {
			
			if ($this->disabled == true) {
				if ($value == '') {
					// TODO: ei asetettu punaisella jos pakollinen kentta
					//echo "<div id=fieldvalue-" . $this->getID() . " class= style='height:100%;display:none;'><font size=-1 style='font-style:italic;'>2 Ei asetettu</font></div>";
				} else {
					//echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;'>" . $value . "</div>";
					//echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'>" . $this->formatValue($data) . "</div>";
				}
				
				echo "<div id='editdiv-". $this->getID() ."' class=field-value style='height:100%;display:block'>";
				echo "<input disabled class=uitextfield-disabled id=editfield-". $this->getID() ." type='text' style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
				echo "</div>";
				
			} else {
				if ($value == '') {
					// TODO: ei asetettu punaisella jos pakollinen kentta
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'><font size=-1 style='font-style:italic;'>Ei asetettu</font></div>";
				} else {
					//echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'>" . $value . "</div>";
					
					
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;display:none;'>" . $this->formatValue($value, $data) . "</div>";
				}
				echo "<div id='editdiv-". $this->getID() ."' class=field-value style='height:100%;'>";
				if ($this->tabindex > 0) {
					echo "<input class=uitextfield tabindex=" . $this->tabindex . " id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_". $this->getID() ."()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' name=".$this->urlparametername." value='".$value."'>";
				} else {
					echo "<input class=uitextfield  id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_". $this->getID() ."()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' name=".$this->urlparametername." value='".$value."'>";
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
				if ($this->notsettedtext == null) {
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;'><font size=-1 style='font-style:italic;'>Ei asetettu</font></div>";
				} else {
					echo "<div id=fieldvalue-" . $this->getID() . " class=field-value style='height:100%;'>" . $this->notsettedtext . "</div>";
				}
			} else {
				//echo "<div id=fieldvalue-" . $this->getID() . " class='field-value' style='height:100%;'>" . $value . "</div>";
				if (is_array($value)) {	// COLUMNTYPE_INTEGERARRAY
					echo "<div id=fieldvalue-" . $this->getID() . " class='field-value' style='height:100%;'>" . implode(':', $value) . "</div>";
				} else {
					echo "<div id=fieldvalue-" . $this->getID() . " class='field-value' style='height:100%;'>";
					echo $this->formatValue($value,$data);
					if ($this->unit != null) echo " <font size=-1>" . $this->unit . "</font>";
					echo "</div>";
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
					echo "<input class=uitextfield id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged_" . $this->getID() . "()' onfocusin='editfieldactivated_" . $this->getID() . "()' onfocusout='editfielddeactivated_" . $this->getID() . "()' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='" . $value . "'>";
				}
				echo "</div>";
			}
		}
		echo "		</td>";
	
		// multilinen virheilmoitus hoidetaan ylä tai alapuolelle, tämä on tavallista leveämpi teksti
		if ($this->multiline == 0) {
			echo "		<td id='fieldmessage-" . $this->getID() . "' style='width:36%;'>";
			echo "			<div class=errordiv id='errordiv-" . $this->getID() . "' style='display:none'>";
			echo "			</div>";
			echo "		</td>";
		}
		echo "	</tr>";
		echo "</table>";
		if ($this->isHiddenFromView() == true) echo "</div>";
		else echo "</div>";
		
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
		//echo "			console.log('setfieldvalue - '+value);";
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
		echo "			var str = $('#editfield-" . $this->getID() . "').val();";
		echo "			return '" . $this->urlparametername . "=' + str.replaceAll('+','_plus_');";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			console.log('edit activated');";
		
		if ($this->isHiddenFromEdit() == true) {
			echo "			console.log('hide from edit');";
			echo "			$('#textline-" . $this->getID() . "').hide();";
		} else {
			echo "			$('#textline-" . $this->getID() . "').show();";
		}
		
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