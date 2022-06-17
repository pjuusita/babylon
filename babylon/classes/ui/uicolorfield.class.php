<?php

/**
 * Tämä on kipioitu suoraan SelectFieldistä, ideana on että tämä sisältää stringlistan jossa
 * stringit ovat värikoodeja.
 *
 * Tämä tuntuu toimivan perustapauksissa, mutta on täysi kopio UISelectColumnista, joten
 * tarvinnee siistimistä.
 * 
 */
class UIColorField extends UIField {
	
	private $title;
	private $idvariablename;
	private $valuevariablename;
	private $urlparametername;
	private $colors;
	private $defaultvalue = 0;
	
	private $savecallback;
	private $onchangecallback = null;
	private $acceptempty;
	//private $editactive;
	private $notEmpty;
	
	private $predictiveActive = false;	// tama tarttee chosen.css:n tai vastaavan
	private $predictiveSource = null;	// testausta autocomplete.php
	private $predictiveValue = null;	// testausta autocomplete.php
	
	private $bounddata;
	private $disabledOptions = null;
	
	private $linkaction;
	private $linkvariable;
	private $multiline;
	private $colorvariable;
	
	
	/**
	 * Poistettu, että selectionvariable = null oletusarvo, on pakko asettaa (tämä tuottaa virheitä koodiin).
	 * 
	 * @param string $title
	 * @param string $idvariablename
	 * @param string $urlparametername
	 * @param string $selection
	 * @param string $selectionvariable
	 */
	public function __construct($title, $idvariablename, $urlparametername, $colors) {
		parent::__construct();
		
		$this->title = $title;
		$this->idvariablename = $idvariablename;
		$this->urlparametername = $urlparametername;
		$this->colors = $colors;
		$this->acceptempty = true;
	}
	
	
	public function setLink($linkaction, $linkvariable) {
		$this->linkaction = $linkaction;
		$this->linkvariable = $linkvariable;
	}
	
	

	public function getVariable() {
		return $this->urlparametername;
	}
	
	
	public function setPredictive($predictiveActive, $predictiveSource = null, $predictiveValue = null) {
		$this->predictiveActive = $predictiveActive;
		$this->predictiveSource = $predictiveSource;
		$this->predictiveValue = $predictiveValue;
	}
	
	
	/**
	 * Pudotusvalikossa taulukon sisältämät indeksit ovat harmaannettu (tai poistettu kokonaan)
	 * 
	 * @param string $array
	 */
	public function setDisabledOptions($array) {
		$this->disabledOptions = $array;
	}
	
	
	public function setOnChange($functioncall) {
		$this->onchangecallback = $functioncall;		
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function acceptEmpty($accept) {
		$this->acceptempty = $accept;		
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
	 * 
	 * Tama ei kuitenkaan ole se arvo, jonka datarow->value ei ole asetettu johtaa, tama on aina ei asetettu arvo.
	 * 
	 * @param string $defaultValue
	 */
	function setValue($defaultValue) {
		$this->defaultvalue = $defaultValue;	
	}
	
	
	
	function show($data, $widths = null) {
		
		if ($data != null) {
			$selectedID = $this->idvariablename;
			
			if (isset($this->colors[$selectedID]) == false) {
			
				echo "<br>no color setted - " . $selectedID;
				$value = "<font size=-1 style='font-style:italic;'>Ei asetettu</font>";
				$colorcode = null;
					
			} else {
					
				$value = $this->colors[$selectedID];
					
				$colorname = "xxx";
			
			}
			echo "<br>namevar - " . $this->valuevariable;
			echo "<br>colorname - " . $colorname;
			
				
		} else {
			$colorname = "aaa";
			$colorcode = "FFFFFF";
			$selectedID = 0;
		}
		
	
		
		echo "<table style='width:100%;'>";
		
		if ($widths != null) {
			echo "		<td class=field-text style='width:" . $widths[1] . ";'>" . getMultilangString($this->title) . "</td>";
			echo "		<td style='width:" . $widths[2] . ";'>";
		
		} else {
			echo "		<td class=field-text style='width:26%;'>" . getMultilangString($this->title) . "</td>";
			echo "		<td style='width:38%;'>";
		}
		/*
		echo "	<tr>";
		echo "		<td class=field-text style='width:26%;'>" . getMultilangString($this->title) . "</td>";
		echo "		<td style='width:38%;'>";
		
		$editshow = "display:none;";
		$valueshow = "";
		if ($this->editactive == true) {
			$editshow = "";
			$valueshow = "display:none;";
		}
		*/
		$editshow = "display:none;";
		$valueshow = "";
		if ($this->editactive == true) {
			$editshow = "";
			$valueshow = "display:none;";
		}
		echo "<div id=fieldvalue-" . $this->getID() . " class='iu-middle-block field-value' style='height:100%;" . $valueshow . "'>";
		if ($colorcode == null) {
			echo "";
		} else {
			echo "<div style='background-color:#" . $colorcode . "'></div>";
		}
		echo "</div>";
		
		//echo "<br>this->selection - " . $this->selection;
		//echo "\n<br>count - " . count($this->selection);
		echo "\n<div id='editdiv-". $this->getID() ."' class=iu-middle-block style='height:100%;" . $editshow . ";background-color:pink;'>";
		//echo "<input id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged" . $this->getID() . "()' onfocusin='editfieldactivated(".$this->getID().",". $this->getID() .")' onfocusout='editfielddeactivated(".$this->getID()."," . $this->getID() . ")' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
		
		
		
		
		
		
		if ($this->disabled == true) {
			echo "<select id=editfield-". $this->getID() ." class='field-select uitextfield-disabled' disabled=disabled style='width:100%'>";
		} else {
			echo "<select id=editfield-". $this->getID() ." onchange='colorchanged_". $this->getID() ."(this.value)' class=field-select style='width:100%'>";
		}
			
		
		if ($this->acceptempty == true) {
			if ($selectedID == 0) {
				echo "<option value='0' style='background-color:#FFFFFF' selected></option>";
			} else {
				echo "<option value='0' style='background-color:#FFFFFF'></option>";
			}
		} else {
			if ($selectedID == 0) echo "<option value='0'></option>";
		}
			
		if ($this->colors == null) {
			//echo "<br>selectioni on nulli";
		} elseif (count($this->colors) == 0) {
			// TODO: ei tietoa mistä tämä virhe tulee, jos tätä ehtoa ei ole feilaa
		} else {
			$colorindex = 0;
			foreach ($this->colors as $colorID => $color) {
					
				
				//echo "<option value='" . $index . "' " . $disabled . " style='background-color:#" . $value->normal . "'>" . $value->name . "</option>";
				echo "<option value='" . $colorID . "' style='background-color:#" . $color->normal . "'></option>";
				$colorindex++;
				
				/*
				if (is_numeric($this->idvariablename)) {
					if (is_array($value)) {
						$index =  $value[$this->idvariablename];
					} else {
						$index =  $ind;
					}
				} else {
					$index = $ind;
				}
					
				if ($selectedID == $index) {
						
					if ($this->valuevariablename != null) {
						if (is_numeric($this->valuevariablename)) {
							$strvalue = $value[$this->valuevariablename];
							echo "<option value=" . $index . " selected>" . $strvalue . "</option>";
						} else {
							$valvar = $this->valuevariablename;
							$strvalue = $value->$valvar;
							echo "<option value=" . $index . " selected>" . $strvalue . "</option>";
						}
					} else {
						echo "<option value=" . $index . " selected>" . $value . "</option>";
					}
				} else {
					$disabled = "";
					if ($this->disabledOptions != null) {
						if (isset($this->disabledOptions[$index])) $disabled = "disabled";
					}
					if ($this->valuevariablename != null) {
						if (is_numeric($this->valuevariablename)) {
							$strvalue = $value[$this->valuevariablename];
							echo "<option value='" . $index . "' selected>" . $strvalue . "</option>";
						} else {
							$valvar = $this->valuevariablename;
							$strvalue = $value->$valvar;
							echo "<option value='" . $index ."' " . $disabled . ">" . $strvalue . "</option>";
						}
					} else {
						if (is_array($value)) {
							echo "<option value='" . $index . "' " . $disabled . ">" . $value[$this->idvariablename] . "</option>";
						} else {
							echo "<option value='" . $index . "' " . $disabled . ">" . $value . "</option>";
						}
					}
				}
				*/
			}
		}
		echo "</select>";
		
		echo "</td>";
		echo "		<td id='fieldmessage-" . $this->getID() . "' style='width:36%;'>";
		echo "			<div class=errordiv id='errordiv-" . $this->getID() . "' style='display:none'>";
		echo "			</div>";
		echo "		</td>";
		
		echo "	</tr>";
		echo "</table>";
		
		echo "<script>";
		//$counter = 0;
		echo "	var colors_" . $this->getID() . " = {";
			echo " '0':'#FFFFFF'";
		foreach ($this->colors as $colorID => $color) {
			//if ($counter > 0) echo ",";
			echo ",'" . $color->colorID . "':'#" . $color->normal ."'";
			//$counter++;
		}
		echo "	}";
		echo "</script>";
		
		
		/*
		echo "<script>";
		echo "	$('#editfield-". $this->getID() ."').on('change', function() {";
		echo "		alert( 'colorchange - '+this.value );";
		echo "	});";
		echo "</script>";
		*/

		echo "<script>";
		echo "	function colorchanged_". $this->getID() ."(colorID) {";
		echo "		console.log('colorchange - '+colorID);";
		echo "		$('#editfield-". $this->getID() ."').css('background-color',colors_" . $this->getID() . "[colorID]);";
		if ($this->onchangecallback != null) echo "" . $this->onchangecallback . "();";
		echo "	}";
		echo "</script>";
		
		
		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		echo "			console.log('xxxxxxxxxxxxxxx setcolorvalue - '+value);";
		echo "			$('#editfield-" . $this->getID() . "').val(value);";
		echo "			$('#editfield-". $this->getID() ."').css('background-color',colors_" . $this->getID() . "[value]);";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		if ($this->acceptempty == false) echo "		if (value == 0) return false;";
		echo "			return true;";
		echo "		}";
		echo "	</script>";

		
		echo "	<script>";
		echo "		function editfieldChanged" . $this->getID() . "() {";
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
		
		if (($this->predictiveActive) && ($this->predictiveSource != null)) {
			echo "		var inputti = document.getElementById('editfield-" . $this->getID() . "');";
			echo "		var vallue = inputti.getAttribute('data');";
			echo "		return '" . $this->urlparametername . "=' + vallue;";
		} else { 
			echo "			return '" . $this->urlparametername . "=' + $('#editfield-" . $this->getID() . "').val();";
		}
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			$('#editdiv-" . $this->getID() . "').show();";
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
		echo "		function getSelectedValue_" . $this->getID() . "(val) {";
		if ($this->colors == null) {
		} elseif (count($this->colors) == 0) {
			
		} else {
			/*
			foreach($this->colors as $index => $value) {
					
				if ($this->valuevariablename != null) {
					$valvar = $this->valuevariablename;
					$strvalue = $value->$valvar;
					echo " if (val == " . $index . ") return '" . $strvalue . "';";
				} else {
					if (is_array($value)) {
						echo " if (val == " . $index . ") return '" . $value[$this->idvariablename] . "';";
					} else {
						$var = $this->valuevariablename;
						if ($var == '') {
							echo " if (val == " . $index . ") return 'tuntematon';";
						} else {
							echo " if (val == " . $index . ") return '" . $value->$var . "';";
						}
					}
				}
					
			}
			*/
		}
		echo "			return '<font size=-1><i>Ei asetettu</i></font>';";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		echo "			var textval = getSelectedValue_" . $this->getID() . "(value);";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(textval);";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
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
