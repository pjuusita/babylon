<?php




class UISection extends UIAbstractSection {

	private $data;
	private $dialog;
	private $customConcentFunction;
	private $customSetFunction;
	private $onopenfunction;
	
	private $leftWidth;
	private $centerWidth;
	private $rightWidth;
	
	private $errormessages;
	
	
	public function __construct($title = '', $width = '600px') {
		parent::__construct($title,$width);
		$this->items = array();
		$this->buttons = array();
		$this->customConcentFunction = null;
		$this->customSetFunction = null;
		$this->errormessages = null;
		
		$this->leftWidth = "26%";
		$this->centerWidth = "38%";
		$this->rightWidth = "0%";
	}
		
	
	public function setDialog($boolean) {
		$this->dialog = $boolean;
	}
	
	
	public function addErrorMessage($message) {
		if ($this->errormessages == null) $this->errormessages = array();
		$this->errormessages[] = $message;
	}
	

	public function setCustomContent($customContentFunction) {
		$this->customConcentFunction = $customContentFunction;
	}
	
	
	public function setOnOpenFunction($onopenfunction) {
		$this->onopenfunction = $onopenfunction;		
	}
	
	
	public function setCustomSetFunction($customSetFunction) {
		$this->customSetFunction = $customSetFunction;
	}

	
	public function addField($field) {
		if (($this->mode == UIComponent::MODE_EDIT) || ($this->mode == UIComponent::MODE_INSERT)) $field->setEditActive(true);		
		$this->items[] = $field;
	}
		

	public function setData($data) {
		$this->data = $data;
	}
	
	
	public function setWidths($leftWidth, $centerWidth, $rightWidth) {
		$this->leftWidth = $leftWidth;
		$this->centerWidth = $centerWidth;
		$this->rightWidth = $rightWidth;
	}
	
	
	protected function generateContent() {
	
		if ($this->errormessages != null) {
			echo "<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
			echo "	<tr>";
			echo "		<td style='width:100%;'>";
			if (count($this->errormessages) > 1) {
				echo "			<div class=errormessagediv style='text-align:left;padding:15px;' id='sectionerrordiv-" . $this->getID() . "'>";
			} else {
				echo "			<div class=errormessagediv id='sectionerrordiv-" . $this->getID() . "'>";
			}
			$counter = 0;
			foreach($this->errormessages as $index => $message) {
				if ($counter > 0) echo "<br><br>";
				echo "" . $message;
				$counter++;
			}			
			echo "			</div>";
			echo "		</td>";
			echo "	</tr>";
			echo "</table>";
		}
		
		
		
		if ($this->data == null) {
			//echo "<br>Thisdata null2";
			//echo "				console.log('data - null');";
			echo "<input id='sectionidvalue-".$this->getID()."' type='hidden' value=''>";
		} else {
			//echo "<br>Thisdata nulll";
			$variable = $this->updatevariable;
			if ($variable != null) {
				echo "<input id='sectionidvalue-".$this->getID()."' type='hidden' value='" . $this->data->$variable . "'>";
			}
		}
		
		
		
		echo "	<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "		<tr>";
		echo "			<td>";
		//echo "				<div style='width:100%;position:relative;margin:0px;'>";
		//echo "					<div class='sectionleftspace' style='width:20%;background-color:white;float:left;margin:0px;'></div>";
		//echo "					<div class='sectionleftspace' style='width:20%;background-color:white;float:left;margin:0px;'></div>";
		//echo "					<section class='sectioncontent' style='margin-left:10%;'>";
	
		// successmessage
	
		echo "<table cellpadding='0' cellpadding='0' style='width:100%;'>";
		echo "	<tr>";
		echo "		<td style='width:62%;'>";
		echo "			<div class=successdiv id='sectionsuccessdiv-" . $this->getID() . "' style='display:none'></div>";
		echo "		</td>";
		echo "		<td style='width:36%;'></td>";
		echo "	</tr>";
		echo "</table>";
	
		// errormessage
		echo "<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "	<tr>";
		echo "		<td style='width:62%;'>";
		echo "			<div class=errordiv id='sectionerrordiv-" . $this->getID() . "' style='display:none'></div>";
		echo "		</td>";
		echo "		<td style='width:36%;'></td>";
		echo "	</tr>";
		echo "</table>";

		echo "<form id=sectionform-" . $this->getID() . ">";
		$tabindex = 1;
		$widths = array();
		$widths[1] = $this->leftWidth;
		$widths[2] = $this->centerWidth;
		$widths[3] = $this->rightWidth;
		
		foreach($this->items as $index => $field) {
			// Tässä tulee jotain ongelmia, kun sisällä on table, ilmeisesti UITablessa on 
			// innertable ja ehkä myös UITableSecionissa. Tämä feilaa databasemanagerissa
			//echo "<br>item class - " . get_class($field);
			//$field->setTabIndex($tabindex);
			//$field->setEnterAction('someaction');
			$field->show($this->data, $widths);
			$tabindex++;
		}
		echo "			</form>";
		
		echo "			</td>";
		echo "		</tr>";
		echo "</table>";
	
		
		// tämä funktio voisi olla myäs static
		echo "<script>";
		echo "		function addSuccessMessage_" . $this->getID() . "(message) {";
		echo "			var textnode = document.createTextNode(message);";
		echo "			textnode.id='sectionerrortext-" . $this->getID() . "';";
		echo "			$('#sectionsuccessdiv-" . $this->getID() . "').html('');";
		echo "			$('#sectionsuccessdiv-" . $this->getID() . "').append(textnode);";
		echo "			$('#sectionsuccessdiv-" . $this->getID() . "').show();";
		echo "			setTimeout(function() { $('#sectionsuccessdiv-" . $this->getID() . "').slideUp(500); },1000);";
		echo "		}";
		echo "</script>";
			
		
		//echo "<br>Iseditable - " . $this->isEditable();
		//echo "<br>Mode  - " . $this->mode;
		
		if ($this->isEditable() == true) {
			
			//echo "<br>Editable";
			echo "	<table style='width:100%;border-collapse:collapse;margin-top:3px;'>";
			echo "		<tr>";
			echo "			<td class=contentcell style=''>";
			echo "				<table class=contentinsidetable style='width:100%;text-align:right;border-collapse:collapse;'>";
			echo "					<tr id=sectionfooter-".$this->getID().">";
	
			echo "						<td style='display:inline; float:left;'>";
			

			if ($this->deleteaction != null) {
				echo "	<div style='float:left'>";
				echo "								<button  class=section-button id='deletebutton".$this->getID()."' style='vertical-align:left;background-color:pink;'>Poista</button>";
				echo "	</div>";
				
				echo "						<script>";
				echo "							$('#deletebutton".$this->getID()."').click(function() {";
				//echo "								console.log('leftbuttonclick - ');";
				if ($this->deleteactiontype == UIComponent::ACTION_FORWARD) {
				
					echo "				var url = '" . getUrl($this->deleteaction) . "&id=' + $('#sectionidvalue-".$this->getID()."').val();";
					//echo "				console.log('section to loadpage - '+url);";
					echo "				alert('temp 03');";
					echo "				window.location = url;";
					//echo "				loadpage('" . $this->deleteaction  ."','" . $this->actiontitle . ");";
				}
				echo "							});";
				echo "						</script>";
			}
			echo "						</td>";
	
			echo "						<td style='padding-right:5px;align:right;'>";
	
			if (count($this->items) > 0) {
				if (($this->mode == UIComponent::MODE_EDIT) || ($this->mode == UIComponent::MODE_INSERT)) {
					echo "							<div id=sectiondefaultbuttons-".$this->getID()." style='display:none'>";
					if (count($this->buttons) > 0) {
						foreach($this->buttons as  $index => $button) {
							$button->show();
							echo " ";
						}
					}
					echo "								<button  class=section-button  onclick='editButtonPressed".$this->getID()."()'>Muokkaa</button>";
					echo "							</div>";
				
					echo "							<div id=sectionsavebuttons-".$this->getID()." style='width:250px;float:right;'>";
					
					if ($this->dialog == true) {
						echo "								<button  class=section-button onclick='closeDialog".$this->getID()."()'>Sulje</button>";
					} else {
						echo "								<button  class=section-button onclick='editCancelPressed".$this->getID()."()'>Peruuta</button>";
					}
					echo "								<button tabindex=" . $tabindex . " class=section-button onclick='saveButtonPressed".$this->getID()."()'>Tallenna</button>";
					echo "							</div>";
				} elseif ($this->mode == UIComponent::MODE_NOEDIT) {
					echo "							<div id=sectiondefaultbuttons-".$this->getID()." style=''>";
					if (count($this->buttons) > 0) {
						foreach($this->buttons as  $index => $button) {
							$button->show();
							echo " ";
						}
					}
					echo "							</div>";
				} else {
					echo "							<div id=sectiondefaultbuttons-".$this->getID().">";
					if (count($this->buttons) > 0) {
						foreach($this->buttons as  $index => $button) {
							$button->show();
							echo " ";
						}
					}
					echo "								<button  class=section-button  onclick='editButtonPressed".$this->getID()."()'>Muokkaa</button>";
					echo "							</div>";
				
					echo "							<div id=sectionsavebuttons-".$this->getID()." style='display:none;width:250px;float:right;'>";
					echo "								<button  class=section-button onclick='editCancelPressed".$this->getID()."()'>Peruuta</button>";
					echo "								<button  class=section-button onclick='saveButtonPressed".$this->getID()."()'>Tallenna</button>";
					echo "							</div>";
				}
			} else {
				
				if (count($this->buttons) > 0) {
					foreach($this->buttons as  $index => $button) {
						$button->show();
						echo " ";
					}
				} else {
					echo "								<button  class=section-button onclick='closeDialog".$this->getID()."()'>Sulje</button>";
				}
			}
			
			echo "						</td>";
	
			// editoinnin jälkeen buttoneita ei varmaankaan ole vielä olemassa, muokkaa, tallenna ja peruuta buttonit ovat oletuksena olemasssa
			/*
			 if (count($this->buttons)>0) {
			 foreach($this->buttons as $text => $button) {
			 echo "					<td style='padding-right:5px;'>";
			 echo "						<div id=custombuttons-".$this->getID().">";
			 echo "							<button class=section-button onclick='sectionButtonClicked_" . $button->getID() . "()'>" . $button->title . "</button>";
			 echo "						</div>";
			 echo "					</td>";
	
			 echo "	<script>";
			 echo "		function sectionButtonClicked_" . $button->getID() . "() {";
			 echo "			alert('custom button toteutus kesken');";
			 //echo "			window.location = '" . getUrl($link) . "';";
			 echo "		}";
			 echo "	</script>";
			 }
			 }
			 */
			echo "					</tr>";
			echo "				</table>";
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
			
			
			echo "<script>";
			echo "		function closeDialog".$this->getID()."() {";
			echo "  		$('#sectiondialog-" . $this->getID() . "').dialog('close');";
			echo "		};";
			echo "	</script>";
			
			
			echo "<script>";
			echo "		function editButtonPressed".$this->getID()."() {";
			//echo "			console.log('editbuttonpressed - getID(): " . $this->getID() . "');";
			foreach($this->items as $index => $field) {
				echo "" . $field->getActivateEditJSFunction() . ";";
			}
			echo "			$('#sectiondefaultbuttons-".$this->getID()."').hide();";
			echo "			$('#sectionsavebuttons-".$this->getID()."').show();";
			echo "		};";
			echo "	</script>";
	
			
			echo "<script>";
			echo "		function editCancelPressed" . $this->getID() . "() {";
			//echo "			console.log('editCancelPressed - getID(): " . $this->getID() . "');";
			foreach($this->items as $index => $field) {
				echo "" . $field->getCancelEditJSFunction() . ";";
			}
			echo "			$('#sectiondefaultbuttons-".$this->getID()."').show();";
			echo "			$('#sectionsavebuttons-".$this->getID()."').hide();";
			echo "		};";
			echo "	</script>";
	
			/*
			echo "<script>";
			echo "		function setSectionIDValue" . $this->getID() . "(value) {";
			echo "			return $('#sectionidvalue-".$this->getID()."').val(value);";
			echo "		};";
			echo "	</script>";
			
			
			echo "<script>";
			echo "		function getSectionIDValue" . $this->getID() . "() {";
			echo "			return $('#sectionidvalue-".$this->getID()."').val();";
			echo "		};";
			echo "	</script>";
			*/
			
			echo "<script>";
			echo "		function contentLoaded() {";
			echo "			var messagecount = getSuccessMessageCount();";
			//echo "			console.log('contentLoaded - '+messagecount);";
			echo "			if (messagecount > 0) {";
			//echo "				alert('section content loaded - '+messagecount);";
			echo "				while(getSuccessMessageCount() > 0) {";
			echo "					var message = consumeMessage();";
			//echo "					console.log('message - '+message);";
			echo "					addSuccessMessage_" . $this->getID() . "(message);";
			echo "				}";
			echo "			} else {";
			//echo "				alert('no messages')";
			echo "			};";
			echo "		};";
			echo "	</script>";
	
	
			// ei muistikuvaa mihin tätä tarvitaan, voidaan ehkä poistaa
			/*
			 echo "<div id=testdialog title='Debugmessage' style='display:none;width:800px'>";
			 echo "<p id=testdialogcontent>Dialogin esimerkkisisalta.</p>";
			 echo "</div>";
	
			 echo "<script>";
			 echo "	$(function() {";
			 echo "		$('#testdialog').dialog({ autoOpen: false, width: \"800px\" });";
			 echo "	});";
			 echo "</script>";
			 */
	
			
	
			echo "<script>";
			echo "		function saveButtonPressed" . $this->getID() . "() {";
			//echo "			console.log('saveButtonPressed - section_ID: " . $this->getID() . "');";
			echo "			console.log('action - " . $this->updateactiontype . "');";
			echo "			console.log('updateaction - " . $this->updateaction . "');";
				
			if ($this->updateactiontype == UIComponent::ACTION_JAVASCRIPT) {
				//echo "			console.log('updateaction javascript');";
				echo "  		$('#sectiondialog-" . $this->getID() . "').dialog('close');";
				echo "			" . $this->updateaction . "(" . $this->getID() . ");";
				
			} else {
				echo "			var accept = true;";
				foreach($this->items as $index => $field) {
					echo " if (" . $field->getCheckOnSaveJSFunction() . " == false) {";
					echo "		console.log('accepted value false');";
					echo "		$('#" . $field->getEditFieldID() . "').addClass('uitextfield-invalid');";
					echo "		accept = false;";
					echo "  } else {";
					echo "		console.log('accepted value true');";
					echo "		$('#" . $field->getEditFieldID() . "').removeClass('uitextfield-invalid');";
					echo "  }";
				}
				
				echo "			if (accept == false) {";
				echo "				console.log('save not accepted');";
				/*
				echo "				alert('save not accepted');";
				foreach($this->items as $index => $field) {
					echo " 			accept = " . $field->getShowErrorJSFunction() . ";";
				}
				*/
				echo "			} else {";
				echo "				var parameters= '&id=' + $('#sectionidvalue-".$this->getID()."').val();";
				echo "				var temp = '';";
				foreach($this->items as $index => $field) {
					echo "			temp =  " . $field->getSaveParamsJSFunction() . ";";
					//echo "			temp = encodespecialcharacters(temp);";
					//echo "			console.log('" . $field->getSaveParamsJSFunction() . "');";
					//echo "			console.log('temp - '+temp);";
					
					echo "			var tempvalue = $('#editfield-" . $field->getID() . "').val();";
					//echo "			console.log('tempval - '+tempvalue);";
					echo "			parameters = parameters + '&' + temp;";
				}
				echo "				var urli = '" . getUrl($this->updateaction) . "';";
				//echo "				console.log('updateaction - " . $this->updateaction  . "');";
				if ($this->updateactiontype == UIComponent::ACTION_FORWARD) {
					echo "			console.log('savebuttonpressed FORWARD - " . $this->getID() . "'+urli + parameters);";
					//echo "			alert('action forward');";
					//echo "			return;";
					echo "			window.location = urli + parameters;";
				
				
				} else if ($this->updateactiontype == UIComponent::ACTION_NEWWINDOW) {
				
					//echo "			alert('new window');";
					//echo "			return;";
					echo "				var win = window.open(urli + parameters, '_blank');";
					echo "				win.focus();";
					echo "  			$('#sectiondialog-" . $this->getID() . "').dialog('close');";
						
				} else if ($this->updateactiontype == UIComponent::ACTION_JSON) {
					/*
					 echo "			console.log('savebuttonpressed - " . $this->getID() . "'+urli + parameters);";
					 echo "				$.getJSON(urli,parameters,function(reply) { ";
					 echo "  				if ((reply[0].success == '1') || (reply[0].success == 'true')) {";
					 echo "						addSuccessMessage_" . $this->getID() . "('Tallennettu onnistuneesti');";
					 echo "						$('#sectiondefaultbuttons-".$this->getID()."').show();";
					 echo "						$('#sectionsavebuttons-".$this->getID()."').hide();";
					 foreach($this->items as $index => $field) {
					 echo "					" . $field->getSaveAcceptedJSFunction() . ";";
					 }
					 echo "  				} else {";
					 echo "  					alert('Tallennus epaonnistui: '+reply[0].success);";
					 echo "						var textnode = document.createTextNode(reply[0].success);";
					 echo "						textnode.id='sectionerrortext-" . $this->getID() . "';";
					 echo "						$('#sectionerrordiv-" . $this->getID() . "').html('');";
					 echo "						$('#sectionerrordiv-" . $this->getID() . "').append(textnode);";
					 echo "						$('#sectionerrordiv-" . $this->getID() . "').show();";
					 echo "  				}";
					 echo "				});";
					 echo "			}";
					 */
				} else	{
					//echo "			console.log('savebuttonpressed 2- unknown actiontype - " . $this->updateactiontype . "');";
				}
				echo "			}";
			}
			
			
			
			echo "		};";
			echo "	</script>";
	
	
		} else {
			
			echo "	<table style='width:100%'>";
			echo "		<tr>";
			echo "			<td class=contentleft></td>";
			echo "			<td class=contentcell>";
			echo "				<table class=contentinsidetable style='width:100%;text-align:right'>";
			echo "					<tr id=sectionfooter-".$this->getID().">";
			echo "						<td style='padding-right:5px;'>";
			echo "						</td>";
			echo "						<td style='padding-right:5px;'>";
			echo " 						</td>";
			echo "						<td style='padding-right:5px;text-align:right;'>";
			if (count($this->buttons) > 0) {
				foreach($this->buttons as  $index => $button) {
					$button->show();
				}
			}
			
			//if ($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) $this->generateButtons();
			//if ($this->buttonVerticalAlign == UIComponent::VALIGN_TOP) $this->generateButtons();
			echo "						</td>";
			echo "					</tr>";
			echo "				</table>";
			echo "			</td>";
			echo "		</tr>";
			echo "	</table>";
		}
		
		if (($this->buttonVerticalAlign == UIComponent::VALIGN_BOTTOM) || ($this->buttonVerticalAlign == 0)) {
			$this->generateButtons();
		}
				
		
		
		if ($this->customSetFunction == null) {
			echo "	<script>";
			echo "		function setValue_" . $this->getID(). "(fieldname, value) {";
			//echo "			console.log('----------------------------------------------------------');";
			echo "			console.log('UI-Section setvalue - " . $this->getID() . " - " . get_class($this) . " - '+fieldname+' - '+value);";
			//echo "			console.log('call setvalue update - " . $this->updatevariable . "');";
			
			if ($this->updatevariable != "") {
				echo "		if (fieldname == '" . $this->updatevariable . "') {";
				echo "			$('#sectionidvalue-".$this->getID()."').val(value);";
				//echo "			console.log(' updatevariable found - " . $this->updatevariable . " - '+value+' - '+fieldname);";
				echo "		}";
			}
				
			foreach($this->items as $index => $field) {
					
				echo "			console.log('variables - " . $field->getVariable() . ", up: " . $this->updatevariable . "');";
					
				if ($field instanceof UIButtonColumn) {
					//echo "			console.log(' ----- buttoncolumn');";
			
				} else {
					//echo "			console.log(' comparing- " . $field->getVariable() . " vs '+fieldname);";
			
					echo "			console.log(' cc - " . $field->getVariable() . " - '+fieldname);";
			
					echo "		if (fieldname == '" . $field->getVariable() . "') {";
					echo "			console.log(' ----- fieldclass - " . get_class($field) . "');";
					echo "			console.log(' normal value found - " . $field->getVariable() . "-" . get_class($field)  . " - " . $field->setValueJSFunction() . " - '+value);";
					echo "			console.log(' ----- " . $field->setValueJSFunction() . "');";
					echo "			" . $field->setValueJSFunction() . "(value);";
					
					echo "		}";
				}
			}
			echo "		}";
			echo "	</script>";
		} else {
			callFunc($this->customSetFunction);
		}
		
		echo "	<script>";
		echo "		function getValue_" . $this->getID(). "(fieldname) {";
		echo "			console.log('call getvalue - " . $this->getID() . " - " . get_class($this) . " - '+fieldname+' - ');";
		foreach($this->items as $index => $field) {
			echo "			if (fieldname == '" . $field->getVariable() . "') {";
			echo "				console.log('fieldname found - '+fieldname);";
			echo "				var val = $('#editfield-" . $field->getID() . "').val();";
			echo "				return val;";
			//echo "				" . $field->setValueJSFunction() . "(value);";
			echo "			}";
		}
		echo "		}";
		echo "	</script>";
		
		//echo "</div>";
	}
	
	
	public function show() {

		/*
		if ($this->customConcentFunction != null) {
			echo "<div style='width:" . $this->sectionwidth  .";'>";
			$this->generateSectionHeader();
			callFunc($this->customConcentFunction);
			$this->generateFooter();
			echo "</div>";
			return false;
		}
		*/
		if ($this->dialog) {
			
			if ($this->customConcentFunction != null) {
				echo "<div id='sectiondialog-" . $this->getID() . "'   title=\"" . $this->title . "\"  style='overflow:visible;width:" . $this->sectionwidth  .";display:none;'>";
				//$this->generateSectionHeader();
				callFunc($this->customConcentFunction);
				//$this->generateFooter();
				echo "</div>";
			} else {
				echo "<div id='sectiondialog-" . $this->getID() . "'   title=\"" . $this->title . "\"  style='overflow:visible;width:" . $this->sectionwidth  .";display:none;'>";
				$this->generateContent();
				echo "</div>";
			}
				
			echo "<script>";
			echo "	$(function() {";
			if ($this->onopenfunction != null) {
				$openfunctionstring = $this->onopenfunction . "();";
			} else {
				$openfunctionstring = "";
			}
			echo "		$('#sectiondialog-" . $this->getID() . "').dialog({ open: function(event,ui) { " . $openfunctionstring . " } , modal:true, autoOpen: false, width: \"" . $this->sectionwidth . "\"});";
			echo "	});";
			echo "</script>";
			
			/*
			echo "<script>";
			echo "	function dialogopen" . $this->getID() . "(event,ui) {";
			echo "		console.log('dialog opened');";
			echo "	});";
			echo "</script>";
			*/
				
		} else {
			
			if ($this->customConcentFunction != null) {
				echo "<div style='width:" . $this->sectionwidth  .";'>";
				$this->generateSectionHeader();
				callFunc($this->customConcentFunction);
				$this->generateFooter();
				echo "</div>";
				
			} else {
				echo "<div style='width:" . $this->sectionwidth  .";'>";
				$this->generateSectionHeader();
				$this->generateContent();
				$this->generateFooter();
				echo "</div>";
			}
		}
		return false;
	}
}

?>