<?php

/**
 * UIInsertSection olisi ehkä mahdollista yhdistää UISectioniin, näissä on paljon 
 * yhteistä toiminnallisuutta. Muutos saattaa kuitenkin olla varsin tyäläs.
 * 
 * Jos UISectionia käytetään dialog muokkaus tyyppisesti, niin insert sectionia
 * voitaisiin käyttää kopiointi tyyppisesti.
 * 
 * Insertti tarvitsee ehkä joissakin tapauksissa tarkistuksen että onnistuuko
 * lisäys, esim. unique kenttä ei saa olla sama olemassaolevan kanssa. Tälläin
 * lisäyksen pitää epäonnistua ja kyseinen kenttä jää punareunaiseksi. Sama toiminto
 * ehkä kuitenkin saavutetaan ottamalla json checkki tällaisiin kenttiin.
 * 
 * [15.11.2018] Voidaan varmaankin poistaa, pääosa tämän instansseista on kommentoitu
 * tai ovat vanoissa addxxxx.php tyyppisissä tiedostoissa jotka on korvattu popupdialogilla
 * [17.10.2020] Näitä on instansseja vielä melko paljon olemassa, kaikki pitäisi korvata
 * 
 * @author pjuusita
 *
 */
class UIInsertSection extends UIComponent {
	
	private $title;
	private $editable;
	
	private $updateaction;
	private $insertaction;
	private $insertactiontype;
	private $idvariablename;
	private $actionbackground;
	private $debug = false;
	
	private $data = null;
	private $fields = array();
	private $buttons = array();
	private $openinstart = true;
	
	private $successTarget = '';
	private $successTargetType = '';
	
	private $dialog = false;
	
	private $sectionwidth;
	
	public function __construct($title = '', $width = '600px', $debug = false) {
		parent::__construct();
		$this->fields = array();
		$this->buttons = array();
		$this->title = $title;
		$this->editable = true;
		$this->sectionwidth = $width;
	}
	
	
	public function setDebug($debug) {
		$this->debug = $debug;
	}
	

	public function setDialog($boolean) {
		$this->dialog = $boolean;
	}
	
	
	
	public function disableEdit() {
		$this->editable = false;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	public function setSuccessAction($actionTarget, $type = UIComponent::ACTION_FORWARD) {
		$this->successTarget = $actionTarget;
		$this->successTargetType = $type;
	}
	
	
	public function setInsertAction($action, $type = UIComponent::ACTION_FORWARD) {
		$this->insertaction = $action;
		$this->insertactiontype = $type;
	}
	
	
	
	public function addField($field) {
		$this->fields[] = $field;
		$field->setEditActive(true);
	}

	
	public function editable($editable) {
		$this->editable = $editable;
	}
	
	
	public function getShowJSFunction() {
		return "showsection" . $this->getID() . "()";
	}

	
	public function getHideJSFunction() {
		return "hidesection" . $this->getID() . "()";
	}
	
	
	public function addButton($text, $link) {
		$this->buttons[$text] = $link;	
	}
	
	public function setOpen($open) {
		$this->openinstart = $open;
	}
	
	
	private function generateSectionHeader() {
		
		if ($this->openinstart == true) {
			echo "<div class=section-header-closed id=sectionheaderclose-".$this->getID()." onclick=\"javascript:showsection" . $this->getID() . "()\" style='display:none;'>";
		} else {
			echo "<div class=section-header-closed id=sectionheaderclose-".$this->getID()." onclick=\"javascript:showsection" . $this->getID() . "()\">";
		}
		echo "		<table style='width:100%;'>";
		echo "			<tr>";
		echo "				<td class=section-title style='width:450px;'>".$this->title."<td>";
		echo "				<td style='text-align:right;'>";
		echo "					<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-cog' ></i></button>";
		echo "					<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-chevron-down'></i></button>";
		echo "				<td>";
		echo "		</table>";
		echo "</div>";
		
		if ($this->openinstart == true) {
			echo "	<div  id=sectionheaderopen-".$this->getID()." class=section-header-open onclick=\"javascript:hidesection" . $this->getID() . "()\"  style='display:block;'>";
		} else {
			echo "	<div  id=sectionheaderopen-".$this->getID()." class=section-header-open onclick=\"javascript:hidesection" . $this->getID() . "()\" style='display:none;'>";
		}
		
		echo "		<table style='width:100%;'>";
		echo "			<tr>";
		echo "				<td class=section-title  style='width:450px;'>".$this->title."<td>";
		echo "				<td style='text-align:right;'>";
		echo "				<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-cog' ></i></button>";
		echo "				<button class=section-button-header style='widht:22px;height:22px;'><i class='fa fa-chevron-up'></i></button>";
		echo "				<td>";
		echo "		</table>";
		echo "</div>";
		
		echo "<script>";
		echo "		function hidesection" . $this->getID() . "() {";
		echo "			$('#sectionheaderclose-" . $this->getID() . "').show();";
		echo "			$('#sectionheaderopen-" . $this->getID() . "').hide();";
		echo "			$('#sectioncontent-".$this->getID()."').hide();";
		echo "		};";
		echo "</script>";
		
		echo "<script>";
		echo "		function showsection" . $this->getID() . "() {";
		echo "			$('#sectionheaderclose-" . $this->getID() . "').hide();";
		echo "			$('#sectionheaderopen-" . $this->getID() . "').show();";
		echo "			$('#sectioncontent-".$this->getID()."').show();";
		echo "		};";
		echo "</script>";
		
		if ($this->openinstart == true) {
			echo "<div id=sectioncontent-".$this->getID()." class='section-content' style='display:block;'>";
		} else {
			echo "<div id=sectioncontent-".$this->getID()." class='section-content' style='display:none;'>";
		}
	}
	
	


	private function generateContent() {
		
		
		echo "	<div>";
		
		echo "	<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "		<tr>";
		echo "			<td>";
		
		// successmessage
		echo "				<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "					<tr>";
		echo "						<td style='width:62%;'>";
		echo "							<div class=messagediv id='sectionsuccessdiv-" . $this->getID() . "' style='display:none'></div>";
		echo "						</td>";
		echo "						<td style='width:36%;'></td>";
		echo "					</tr>";
		echo "				</table>";
		
		// errormessage
		echo "				<table cellpadding='0' cellpadding='0'  style='width:100%;'>";
		echo "					<tr>";
		echo "						<td style='width:62%;'>";
		echo "							<div class=errordiv id='sectionerrordiv-" . $this->getID() . "' style='display:none'></div>";
		echo "						</td>";
		echo "						<td style='width:36%;'></td>";
		echo "					</tr>";
		echo "				</table>";
		
		foreach($this->fields as $index => $field) {
			$field->show($this->data);
		}
		echo "			</td>";
		echo "		</tr>";
		echo "</table>";
	
		
		echo "	<table style='width:100%;'>";
		echo "		<tr>";
		echo "			<td class=contentleft></td>";
		echo "			<td class=contentcell style=''>";
		echo "				<table class=contentinsidetable style='width:100%;text-align:right'>";
		echo "					<tr id=sectionfooter-".$this->getID().">";
		echo "						<td style='display:inline; float:right;'>";
		if ($this->dialog == true) {
			echo "							<button  class=section-button style='width:90px;' onclick='cancelButtonPressed_" . $this->getID() . "()'>Peruuta</button>";
		}
		echo "							<button  class=section-button style='width:90px;' onclick='addButtonPressed_" . $this->getID() . "()'>Lisää</button>";
		echo "						</td>";
		echo "					</tr>";
		echo "				</table>";
		echo "			</td>";
		echo "		</tr>";
		echo "	</table>";
		echo "</div>";
		
		
		echo "<script>";
		echo "		function cancelButtonPressed_" . $this->getID() . "() {";
		echo "  		$('#sectiondialog-" . $this->getID() . "').dialog('close');";
		echo "		};";
		echo "	</script>";
		
		
		echo "<script>";
		echo "		function addButtonPressed_" . $this->getID() . "() {";
		echo "			var accept = true;";
		foreach($this->fields as $index => $field) {
			echo " if (" . $field->getCheckOnSaveJSFunction() . " == false) accept = false;";
		}
		echo "			if (accept == false) {";
		foreach($this->fields as $index => $field) {
			echo " 			accept = " . $field->getShowErrorJSFunction() . ";";
		}
		echo "			} else {";
		$variable = $this->idvariablename;
		echo "				var parameters= '';";
		echo "				var temp = '';";
		foreach($this->fields as $index => $field) {
			echo "			temp =  " . $field->getSaveParamsJSFunction() . ";";
			//echo "			console.log('funkkari - " . $field->getSaveParamsJSFunction()  . "');";
			//echo "			temp = encodespecialcharacters(temp);";
			//echo "			console.log('funkkari - '+temp);";
			echo "			parameters = parameters + '&' + temp;";
		}
		echo "				var urli = '" . getUrl($this->insertaction) . "';";
		
		if ($this->insertactiontype == UIComponent::ACTION_FORWARD) {
			
			if ($this->debug == true) {
				echo "				alert('DEGUB: '+urli+parameters);";
				echo "				window.location = urli + parameters + '&debug=1';";
			} else {
				echo "				window.location = urli + parameters;";
			}
		} else if ($this->insertactiontype == UIComponent::ACTION_JAVASCRIPT) {
			
			echo "				console.log('inseraction call javascript: " . $this->insertaction . "');";
			echo "				console.log('params: ' + parameters);";
			echo "				var params =  [];";
			foreach($this->fields as $index => $field) {
				
				echo "			temp = $('#editfield-" . $field->getID() . "').val();";
				//echo "			temp = encodespecialcharacters(temp);";
				//echo "			console.log('jee - '+temp);";
				echo "			params[" . $index . "] = temp;";
			}
			echo "  			$('#sectiondialog-" . $this->getID() . "').dialog('close');";
			echo "				" . $this->insertaction . ".apply(this, params);";	
			
			//echo "				window.location = urli + parameters + '&debug=1';";
			
		} else {
			echo "				$.getJSON(urli,parameters,function(reply) { ";
			echo "  				if (reply[0].success == 'true') {";
			//echo "						alert('insert success');";
			
			echo "						var id = reply[0].id;";
			//echo "						alert('id- '+id);";
			
			//echo "						window.location = '" . getUrl($this->forwardURL) . "&id='+id;";
			
			if ($this->successTargetType == UIComponent::ACTION_FORWARD) {
				echo "						addSuccessMessage('Lisätty onnistuneesti');";
				//echo "						loadurl('" . getUrl($this->forwardURL) . "&id='+id);";
			} elseif ($this->successTargetType == UIComponent::ACTION_JAVASCRIPT) {
				echo "						addSuccessMessage('Lisätty onnistuneesti');";
				echo "  					$('#sectiondialog-" . $this->getID() . "').dialog('close');";
				echo "						" . $this->successTarget . ";";				
			}
			
			
			/*
			 echo "						var textnode = document.createTextNode('Lisätty onnistuneesti');";
			 echo "						textnode.id='sectionerrortext-" . $this->getID() . "';";
			 echo "						$('#sectionsuccessdiv-" . $this->getID() . "').html('');";
			 echo "						$('#sectionsuccessdiv-" . $this->getID() . "').append(textnode);";
			 echo "						$('#sectionsuccessdiv-" . $this->getID() . "').show();";
			 echo "						setTimeout(function() { $('#sectionsuccessdiv-" . $this->getID() . "').slideUp(500); },1000);";
			 echo "						$('#sectiondefaultbuttons-".$this->getID()."').show();";
			 echo "						$('#sectionsavebuttons-".$this->getID()."').hide();";
			 foreach($this->fields as $index => $field) {
			 echo "					" . $field->getSaveAcceptedJSFunction() . ";";
			 }
			 if ($this->forwardURL == '') {
			 echo "						alert('ForwardURL puuttuu');";
			 } else {
			 echo "						window.location = '".getUrl($this->forwardURL)."';";
			 }
			 */
			echo "  				} else {";
			echo "						alert('insert failed - '+success.message);";
			/*
			 echo "						var textnode = document.createTextNode(reply[0].success);";
			 echo "						textnode.id='sectionerrortext-" . $this->getID() . "';";
			 echo "						$('#sectionerrordiv-" . $this->getID() . "').html('');";
			 echo "						$('#sectionerrordiv-" . $this->getID() . "').append(textnode);";
			 echo "						$('#sectionerrordiv-" . $this->getID() . "').show();";
			 echo "						setTimeout(function() { $('#sectionerrordiv-" . $this->getID() . "').slideUp(500); },1000);";
			 */
			echo "  				}";
			echo "				});";
		}
		
		echo "			}";
		echo "		};";
		echo "	</script>";
	}
	
	
	private function generateFooter() {
	}
	
	
	// frameworkin pitää hoitaa sectionin sulkeminen, koska editointi jossain muualla voi olla kesken, pitää kutsua itempagen showsection funktiota
	public function show() {
		
		if ($this->dialog) {
			echo "<div id='sectiondialog-" . $this->getID() . "'   title=\"" . $this->title . "\"  style='overflow:visible;width:" . $this->sectionwidth  .";display:none;'>";
			//$this->generateSectionHeader();
			$this->generateContent();
			//$this->generateFooter();
			echo "</div>";
			
			echo "<script>";
			echo "	$(function() {";
			echo "		$('#sectiondialog-" . $this->getID() . "').dialog({ modal:true, autoOpen: false, width: \"" . $this->sectionwidth . "\" });";
			echo "	});";
			echo "</script>";
			
			
			
		} else {
			echo "<div style='width:" . $this->sectionwidth  .";'>";
			$this->generateSectionHeader();
			$this->generateContent();
			$this->generateFooter();
			echo "</div>";
		}
		return false;
	}
	
}

?>