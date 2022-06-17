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

class UISelectField extends UIField {
	
	private $title;
	private $idvariablename;
	private $valuevariablename;
	private $urlparametername;
	private $selection;
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
	private $mandatory = false;
	private $disabledOptions = null;
	
	private $linkaction;
	private $linkvariable;
	private $multiline;
	
	
	/**
	 * Poistettu, että selectionvariable = null oletusarvo, on pakko asettaa (tämä tuottaa virheitä koodiin).
	 * 
	 * @param string $title
	 * @param string $idvariablename
	 * @param string $urlparametername
	 * @param string $selection
	 * @param string $selectionvariable
	 */
	public function __construct($title, $idvariablename, $urlparametername, $selection, $selectionvariable = null) {
		parent::__construct();
		
		$this->title = $title;
		$this->idvariablename = $idvariablename;
		$this->urlparametername = $urlparametername;
		
		$this->valuevariablename = $selectionvariable;
		$this->selection = $selection;
		
		$this->acceptempty = true;
		$this->multiline = 0;
		
	}
	
	// jos selectionissa kaytetaan jotain muuta id-numeroa kuin default getID-funktiota
	public function setSelection($selectionVariablename, $selection) {
		
	}
	
	
	public function setLink($linkaction, $linkvariable) {
		$this->linkaction = $linkaction;
		$this->linkvariable = $linkvariable;
	}
	
	
	public function setValueCallback($valuecallbackfunction) {
				
	}
	
	

	public function getVariable() {
		return $this->urlparametername;
	}
	
	public function setMandatory($boolean) {
		$this->mandatory = $boolean;
		
	}
	
	
	public function setPredictive($predictiveActive, $predictiveSource = null, $predictiveValue = null) {
		$this->predictiveActive = $predictiveActive;
		$this->predictiveSource = $predictiveSource;
		$this->predictiveValue = $predictiveValue;
	}


	public function setPredictable($predictiveActive, $predictiveSource = null, $predictiveValue = null) {
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
	
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
	}
	
	public function setOnChange($functioncall) {
		$this->onchangecallback = $functioncall;		
	}
	
	public function getOnChange() {
		return $this->onchangecallback;
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
	
	public function setMultiline($linecount) {
		$this->multiline = $linecount;
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
	
	
	function getHideJSFunction() {
		return "hideField" . $this->getID() . "()";
	}
	
	function getShowJSFunction() {
		return "showField" . $this->getID() . "()";
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
	
	
	// TODO: miksi tama asetetaan javascriptilla, tatahan kutsutaan vain php-generoinnin yhteydessa, tuukan kokkailuja. 
	// Kaytassa processmanagerissa, eli kaytassa silloin kun kasitellaan jotain dynaamisesti luotavia taulukoita.
	// tama pitaa korvata funktiolla, joka asettaa valuen silloin kun show(null), eli arvoa ei ole asettu. Tama on kuitenkin
	// hieman vaarallista, koska mahdollisesti joissakintapauksissa 
	function setSelected($selectedID) {
		
		$elementID = "editfield-". $this->getID();
		
		echo "<script>																									";
		echo "																											";
		echo "	var select	= document.getElementById('".$elementID."');												";
		echo "																											";
		echo "	select.value = ".$selectedID.";																			";
		echo "																											";
		echo "																											";
		echo "</script>																									";
		
	}
	
	
	private function createPredictiveload($editfield, $editlist, $call, $id) {
		
		
		echo "<script>";
		echo "	var firsttime" . $id . " = 2;";
		echo "	window.addEventListener('load', function(){";
		echo "				console.log('firsttime');";
		echo "		var inputti = document.getElementById('" . $editfield . "');";
		echo "		inputti.addEventListener('keyup',function(event) {";
		echo "			if (firsttime" . $id . " == 1) {";
		echo "				firsttime" . $id . " = 2;";
		//echo "				console.log('firsttime');";
		echo "			} else {";
		echo "				hinter" . $id . "(event);";
		echo "			}";
		echo "		});";
		echo "		inputti.addEventListener('change',function(event) {";
		echo "			var inputti = document.getElementById('" . $editfield . "');";
		echo "			var selectedvalue = inputti.value;";
		//echo "			console.log('-- '+selectedvalue);";
		echo "			if (selectedvalue == '') {";
		echo "					inputti.setAttribute(\"data\",0);";
		echo "				return;";
		echo "			}";
		//echo "			console.log('change - '+event);";
		echo "			var hugelist = document.getElementById('" . $editlist . "').options;";
		echo "			console.log('-- '+hugelist.length);";
		echo "			for(var x=0;x<(hugelist.length-1);x++) {";
		echo "				if (hugelist[x].value === selectedvalue) {";
		//echo "					console.log('--'+hugelist[x].getAttribute(\"data\")+' -- selected');";
		echo "					console.log('--'+hugelist[x].value+' -- selected');";
		echo "					inputti.setAttribute(\"data\",hugelist[x].getAttribute(\"data\"));";
		echo "				} else {";
		echo "					console.log('--'+hugelist[x].value);";
		echo "				}";
		echo "			}";
		//echo "			hugelist.innerHTML = '';";
		echo "			firsttime = 1;";
		//echo "			var hugelist = this.list;";
		
		echo "		});";
		echo "		window.hinterXHR = new XMLHttpRequest();";
		echo "	});";
		
		echo "	function hinter" . $id . "(event) {";
		//echo "		console.log('eventti');";
		echo "		inputti = event.target;";
		echo "		var hugelist = document.getElementById('" . $editlist . "');";
		echo "		var min_characters = 3;";
		echo "		if (inputti.value.length < min_characters) {";
		echo "			return;";
		echo "		} else {";
		echo "			window.hinterXHR.abort();";
		echo "			window.hinterXHR.onreadystatechange = function() {";
		echo "				if (this.readyState == 4 && this.status == 200) {";
		//echo "					console.log('returni ok - '+this.responseText );";
		echo "					hugelist.innerHTML = '';";
		echo "					JSON.parse(this.responseText, (key,value) => {";
		echo "						var option = document.createElement('option');";
		echo "						option.setAttribute('data', key);";
		echo "						option.value = value;";
		echo "						hugelist.appendChild(option);";
		echo "					});";
		echo "				}";
		echo "			};";
		echo "			window.hinterXHR.open('GET', '" . getUrl($call) . "&prefix='+inputti.value, true);";
		echo "			window.hinterXHR.send();";
		echo "		}";
		echo "	}";
		echo "</script>";
		
	}
	
	
	
	
	function show($data, $widths = null) {
		
		if (is_numeric($this->idvariablename)) {
			$selectedID = $this->idvariablename;
		} else {
			$var = $this->idvariablename;
			if ($data == null) {
				$selectedID = $this->defaultvalue;
			} else {
				if (is_array($data)) {
					$selectedID = $data[$var];
				} else {
					$selectedID = $data->$var;
				}
			}
		}
			
		//if ($selectedID == null) echo "<br>Selectedid null";
		//echo "<br>SelecteiID = " . $selectedID;
		//echo "<br>idvariablename = " . $var;
		
		//if ($data == null) echo "<br>Datanull";
		//$selectedID = $data->$var;
		//echo "<br>SelecteID - " . $selectedID;
		//echo "<br>ValueID - " . $valueID;
		//foreach($this->selection as $index => $value) {
		//	echo "<br>Selection  - " . $index . " - " . $value;
		//}
		
		$setted = true;
		if ($selectedID == null) {
			$value = "<font size=-1 style='font-style:italic;'>Ei asetettu</font>";
		} else {
			if (isset($this->selection[$selectedID]) == false) {
				if ($this->mandatory == true) {
					$value = "<font size=-1 style='font-style:italic;color:red'>Ei asetettu</font>";
				} else {
					$value = "<font size=-1 style='font-style:italic;'>Ei asetettu</font>";
				}
				$setted = false;
			} else {
				$value = $this->selection[$selectedID];
				if (is_numeric($this->valuevariablename)) {
					//echo "<br>is numeric";
					$value = $value[$this->valuevariablename];
				} else {
					//echo "<br>not is numeric";
					if ($this->valuevariablename != null) {
						$valvar = $this->valuevariablename;
						//echo "<br>valvar - "  . $valvar;
						$value = $value->$valvar;
					} else {
						//echo "<br>valuevariablename null";
					}
				}
			}
		}
		
		if ($this->isHiddenFromView() == true) {
			echo "<div  id=selectline-" . $this->getID() . " style='display:none;'>";
			echo "<table id=selectline-" . $this->getID() . " style='width:100%;background-color:pink;'>";
		} else{
			echo "<table id=selectline-" . $this->getID() . " style='width:100%;'>";
		}
		echo "	<tr>";
		
		if ($widths != null) {
			echo "		<td class=field-text style='width:" . $widths[1] . ";'>" . getMultilangString($this->title) . "</td>";
				
			if ($this->multiline == 0) {
				echo "		<td style='width:" . $widths[2] . ";'>";
			} else {
				echo "		<td style='width:74%;'>";
			}
				
		} else {
			echo "		<td class=field-text style='width:26%;'>" . getMultilangString($this->title) . "</td>";
			
			if ($this->multiline == 0) {
				echo "		<td style='width:38%;'>";
			} else {
				echo "		<td style='width:74%;'>";
			}
		}
		
		$editshow = "display:none;";
		$valueshow = "";
		if ($this->editactive == true) {
			$editshow = "";
			$valueshow = "display:none;";
		}
		
		
		echo "<div id=fieldvalue-" . $this->getID() . " class='iu-middle-block field-value' style='height:100%;" . $valueshow . "'>";
		
		if (($this->predictiveActive) && ($this->predictiveSource != null)) {
			if ($this->predictiveValue == null) {
				echo "<font size=-1 style='font-style:italic;'>Ei asetettu</font>";
			} else {
				echo $this->predictiveValue;
			}
		} elseif (($this->linkaction == null) || ($setted == false)) {
			echo $value . "";			
		} else {
			$idvalue = $this->linkvariable;
			echo "<a href='" . getUrl($this->linkaction) . "&id=" . $data->$idvalue . "'>" . $value . "</a>";
		}
		
		echo "</div>";
		
		//echo "<br>this->selection - " . $this->selection;
		//echo "\n<br>count - " . count($this->selection);
		echo "\n<div id='editdiv-". $this->getID() ."' class=iu-middle-block style='height:100%;" . $editshow . "'>";
		//echo "<input id=editfield-". $this->getID() ." type='text' onkeyup='editfieldChanged" . $this->getID() . "()' onfocusin='editfieldactivated(".$this->getID().",". $this->getID() .")' onfocusout='editfielddeactivated(".$this->getID()."," . $this->getID() . ")' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
		
		$onchange = "";
		if ($this->onchangecallback != null) $onchange = ' onchange="' . $this->onchangecallback . "\"";
		
		
		if (($this->predictiveActive) && ($this->predictiveSource != null)) {
			// tätä on testattu worder/concepts/autocomplete, concepts/autocomplete.php
			// TODO: en osaa sanoa onko tämä käytössä? käytössä chosen
			echo "<input  id=editfield-". $this->getID() ." data='" . $selectedID . "' title='jeejee44' list='editfieldlist-". $this->getID() ."' value='" . $this->predictiveValue . "'  class='field-select' style='width:100%;padding-left:5px;'>";
			echo "<datalist id='editfieldlist-". $this->getID() ."'></datalist>";
			$this->createPredictiveload("editfield-". $this->getID(), "editfieldlist-". $this->getID(), $this->predictiveSource, $this->getID());
			
		} else {
			if ($this->disabled == true) {
				echo "<select id=editfield-". $this->getID() ." " . $onchange . " title='" . $this->title . "'  class='field-select uitextfield-disabled' disabled=disabled style='width:100%'>";
			} else {
				echo "<select id=editfield-". $this->getID() ." " . $onchange . " title='" . $this->title . "'  class=field-select style='width:100%'>";
			}
			if ($this->acceptempty == true) {
				if ($selectedID == null) {
					echo "<option value='' selected></option>";
				} else {
					echo "<option value=''></option>";
				}
			} else {
				//if (($selectedID == '') ||($selectedID == null)) echo "<option value=''>bbbb " . $selectedID . "</option>";
			}
			//echo "<option value=''>" . get_class($this->selection) . "</option>";
			
			if ($this->selection == null) {
				//echo "<br>selectioni on nulli";
			} elseif (count($this->selection) == 0) {
				// TODO: ei tietoa mistä tämä virhe tulee, jos tätä ehtoa ei ole feilaa
			} else {
				foreach ($this->selection as $ind => $value) {
					
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
						
						if (($this->acceptempty == true) && ($selectedID == null)) {
							if ($this->valuevariablename != null) {
								if (is_numeric($this->valuevariablename)) {
									$strvalue = $value[$this->valuevariablename];
									echo "<option value=" . $index . ">" . $strvalue . "</option>";
								} else {
									$valvar = $this->valuevariablename;
									$strvalue = $value->$valvar;
									echo "<option value=" . $index . ">" . $strvalue . "</option>";
								}
							} else {
								echo "<option value=" . $index . ">" . $value . "</option>";
							}
							
						} else {
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
						}
							
						//echo "<option value=" . $index . " selected>" . $value . "</option>";
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
								//echo "<br>variablename - " . $this->valuevariablename .  " - " . $valvar . " - " . $strvalue;
								echo "<option value='" . $index ."' " . $disabled . ">" . $strvalue . "</option>";
							}
						} else {
							if (is_array($value)) {
								echo "<option value='" . $index . "' " . $disabled . ">" . $value[$this->idvariablename] . "</option>";
							} else {
								echo "<option value='" . $index . "' " . $disabled . ">" . $value . "</option>";
							}
							
							/*
							if (is_array($value)) {
								echo "<option value='" . $index . "' " . $disabled . ">" . $value[$this->idvariablename] . "</option>";
							} else {
								
								$strvalue = $value[$this->valuevariablename];
								echo "<option value='" . $index . "' " . $disabled . ">" . $strvalue . "</option>";
								//echo "<option value='" . $index . "' " . $disabled . ">" . $this->valuevariablename . "</option>";
							}
							*/
						}
			
						//if (get_class($value) == Row) {
						//	echo "<option value=" . $index . ">" . $row->$selectionvariable . " - " . $value . "</option>";
						//} else {
						//}
					}
				}
			}
			
			echo "</select>";
			
		}
		
		
		if ($this->multiline == 0) {
			echo "</div>";
			echo "		</td>";
			echo "		<td id='fieldmessage-" . $this->getID() . "' style='width:36%;'>";
			echo "			<div class=errordiv id='errordiv-" . $this->getID() . "' style='display:none'>";
			echo "			</div>";
			echo "		</td>";
		}
		echo "	</tr>";
		echo "</table>";
		if ($this->isHiddenFromView() == true) echo "</div>";
		
		/*
		echo "<div class='iu-fielddiv' style='width:26%;margin:0px;float:left;'><div class=iu-middle-block style='height:100%;'>" . $this->title . "</div></div>";
		echo "<div class='iu-fielddiv'  style='width:36%;float:left;'>";
		echo "<div id=fieldvalue-" . $this->getID() . " class=iu-middle-block style='height:100%;'>" . $value . "</div>";
		echo "<div id='editdiv-". $this->getID() ."' class=iu-middle-block style='height:100%;display:none'>";
		echo "<input id=editfield-". $this->getID() ." type='text' onkeyup='editfieldchanged(".$this->getID().",". $this->getID() .")' onfocusin='editfieldactivated(".$this->getID().",". $this->getID() .")' onfocusout='editfielddeactivated(".$this->getID()."," . $this->getID() . ")' class=sectioninputfield style='width:100%;' type='text' name=".$this->urlparametername." value='".$value."'>";
		echo "</div>";
		echo "</div>";
		echo "<div class='iu-fielddiv'  style='width:38%;margin:0;px;float:left;'>";
		echo "<div id=fieldmessage-" . $this->getID() . " class=iu-middle-block style='height:100%;'></div></div>";
		*/		
		
		

		echo "	<script>";
		echo "		function setFieldValue_" . $this->getID() . "(value) {";
		echo "			console.log(' --- setselectfieldvalue - '+value);";
		if ($this->predictiveActive == true) {
			//echo "			console.log('trigger - '+value);";
			echo "			$('#editfield-" . $this->getID() . "').val(value);";
			echo "			$('#editfield-" . $this->getID() . "').trigger('chosen:updated');";
		} else {
			echo "			$('#editfield-" . $this->getID() . "').val(value);";
		}
		/*
		// Tämä ei toimi tällaisenaan, ilmeisesti callbackin pitäisi odottaa kutsun suoritus
		// Tämä hoidetaan setValuella erikseen
		if ($this->onchangecallback != null) {
			echo "		console.log('onchangecallback should be called...');";
			//echo "		" . $this->onchangecallback . ";";
		}
		*/
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCheckOnSave_" . $this->getID() . "() {";
		
		if ($this->getCustomCheckFunction() == null) {
			echo "			var value = $('#editfield-" . $this->getID() . "').val();";
			if ($this->acceptempty == false) echo "		if (value == '') return false;";
			echo "			return true;";
			
		} else {
			echo "		return " . $this->getCustomCheckFunction() . "();";
		}
		
		echo "		}";
		echo "	</script>";

		
		echo "	<script>";
		echo "		function editfieldChanged" . $this->getID() . "() {";
		echo "			if (fieldCheckOnSave_" . $this->getID() . "() == true) {";
		//echo "				alert('fieldonchange - '+fieldCheckOnSave_" . $this->getID() . "());";
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
			//echo "		console.log('jeejee - '+inputti.getAttribute('data'));";
			echo "		console.log('jeejee - " . $this->title . "');";
			echo "		return '" . $this->urlparametername . "=' + vallue;";
		} else { 
			echo "		console.log('jeejee2 - " . $this->title . "');";
			//echo "		console.log('jeejee21 - '+$('#editfield-" . $this->getID() . "').attr(\"title\"));";
			echo "		var item = document.getElementById('editfield-" . $this->getID() . "');";
			echo "		console.log(' --- '+item.title);";
				
			echo "			return '" . $this->urlparametername . "=' + $('#editfield-" . $this->getID() . "').val();";
		}
		//echo "			alert('calling getSaveParams_" . $this->getID() . "');";
		//echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldActivateEdit_" . $this->getID() . "() {";
		
		if ($this->isHiddenFromEdit() == true) {
			echo "			$('#selectline-" . $this->getID() . "').hide();";
		} else {
			echo "			$('#selectline-" . $this->getID() . "').show();";
		}
		
		//echo "			alert('calling activateEdit_" . $this->getID() . "');";
		
		echo "			$('#fieldvalue-" . $this->getID() . "').hide();";
		echo "			$('#editdiv-" . $this->getID() . "').show();";
		if ($this->predictiveActive) {
			if ($this->predictiveSource != null) {
				// tätä on testattu worder/concepts/autocomplete, concepts/autocomplete.php
				
				
				/*
				echo "			console.log('editfield predictiveSource " . $this->predictiveActive . " - " . $this->getID() . "');";
				
				echo "$('#editfield-" . $this->getID() . "').chosen();";
				//echo "$(\".chzn-select-deselect\").chosen({allow_single_deselect:true});";
				echo "$('#editfield-" . $this->getID() . "-choices input').autocomplete({";
				echo "		source: function( request, response ) {";
				echo "			console.log('autocomplete');";
				
				echo "			$.ajax({";
				echo "				url: \"" . $this->predictiveSource . "&prefix=\"+request.term+\"/\",";
				echo "				dataType: \"json\",";
				echo "				beforeSend: function(){\$('#editfield-" . $this->getID() . "-results').empty();},";
				echo "				success: function( data ) {";
				echo "					response( $.map( data, function( item ) {";
				echo "						$('#editfield-" . $this->getID() . "-results').append('<li class=\"active-result\">' + item.name + '</li>');";
				echo "					}));";
				echo "				}";
				echo "			});";
				
				echo "		}";
				echo "	});";
				*/
			} else {
				echo "		console.log('chosen');";
				//echo "		$('#editfield-" . $this->getID() . "').val(237);";
				echo "		$('#editfield-" . $this->getID() . "').chosen();";
				//echo "		$('#editfield-" . $this->getID() . "').trigger('chosen:updated');";
				
				
				//$("#form_field").trigger("chosen:updated");
			}
			
			//echo "		$('.chosen-select').chosen({width: \"95%\"});";
		}
		//echo "			console.log('chosen active');";
		echo "			return false;";
		echo "		}";
		
		if ($this->predictiveActive == true) {
			if ($this->predictiveSource != null) {
				
			} else {
				//echo "		console.log('chosen2');";
				echo "		$('#editfield-" . $this->getID() . "').chosen();";
			}
		}
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldCancelEdit_" . $this->getID() . "() {";
		//echo "			alert('calling cancelEdit_" . $this->getID() . "');";
		if ($this->isHiddenFromView() == true) {
			echo "			$('#selectline-" . $this->getID() . "').hide();";
		} else {
			echo "			$('#selectline-" . $this->getID() . "').show();";
		}
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function getSelectedValue_" . $this->getID() . "(val) {";
		
		if ($this->selection == null) {
			// selectio on null
		} elseif (count($this->selection) == 0) {
			// TODO: ei tietoa mistä tämä virhe tulee, jos tätä ehtoa ei ole feilaa
		} else {
			foreach($this->selection as $index => $value) {
					
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
		}
		echo "			return '<font size=-1><i>Ei asetettu</i></font>';";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function acceptEditAndClose_" . $this->getID() . "() {";
		//echo "			alert('calling acceptEditAndClose_" . $this->getID() . "');";
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		//echo "			alert('value - '+value);";
		echo "			var textval = getSelectedValue_" . $this->getID() . "(value);";
		//echo "			alert('textvalue - '+textval);";
		echo "			$('#fieldvalue-" . $this->getID() . "').html(textval);";
		echo "			$('#fieldvalue-" . $this->getID() . "').show();";
		echo "			$('#editdiv-" . $this->getID() . "').hide();";
		echo "			$('#errordiv-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		
		echo "	<script>";
		echo "		function fieldShowError_" . $this->getID() . "() {";
		//echo "			alert('calling showError_" . $this->getID() . "');";
		
		echo "			var value = $('#editfield-" . $this->getID() . "').val();";
		//echo "			alert('fieldvalue - '+value+' - '+value.length+' - " . $this->maxValue . "');";
		/*
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
		*/
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
		
		echo "	<script>";
		echo "		function showField" . $this->getID() . "() {";
		echo "			$('selectline-" . $this->getID() . "').show();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
		echo "	<script>";
		echo "		function hideField" . $this->getID() . "() {";
		echo "			console.log('hide field - " . $this->getID() . "');";
		echo "			$('selectline-" . $this->getID() . "').hide();";
		echo "			return false;";
		echo "		}";
		echo "	</script>";
		
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
}

?>
