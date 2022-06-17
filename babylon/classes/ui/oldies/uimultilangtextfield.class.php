<?php

class UIMultiLangTextField extends UIField {
	
	private $title;
	private $urlParameter;
	private $dataVariable;
	
	private $maxValue = null;
	private $minValue = null;
	private $savecallback;
	private $databasetable;
	private $languages;
	
	// TODO yhdista acceptempty ja not empty, toinen naista on turha
	private $acceptEmpty;
	private $notEmpty = false;
	
	//public function __construct($title, $returnparameter, $languages,$languageString) {
	public function __construct($title, $dataVariable, $urlParameter, $languages) {
		
		parent::__construct();
		
		$this->title = $title;
		$this->urlParameter = $urlParameter;
		$this->dataVariable = $dataVariable;
		$this->languages = $languages;
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
	
	
	public function createShowDiv($languages,$items, $visible) {
		
		$display = 'display:none';
		if ($visible) $display = 'display:block';
		
		echo "<div id='show_div_" . $this->getID() . "' style='" . $display . "'>";
		echo "	<table style='width:100%'>";
		echo "		<tr>";
		echo "			<td style='width:26%;vertical-align:top;padding-top:7px;'>";
		echo "				" . $this->title;
		echo "			</td>";
		echo "			<td style='width:50%'>";
							$this->createMultiLangFixedFieldTable($languages,$items);
		echo "			</td>";
		echo "			<td>";
		echo "			</td>";	
		echo "		</tr>";
		echo "  	<tr>";
		echo "		</tr>";
		echo "</table>";
		echo "</div>";
	}

	
	public function createEditDiv($languages, $items, $visible) {
		
		$title = $this->title;
		$fieldID = $this->getID();
		
		$display = 'display:none';
		if ($visible) $display = 'display:block';
		
		echo "<div id='edit_div_".$fieldID."' style='" . $display . "'>														";
		echo "	<table style='width:100%'>																				";
		echo "		<tr>																								";
		echo "			<td style='width:26%;vertical-align:top;padding-top:7px;'>";
		echo "				".$title;
		echo "			</td>																							";
		echo "			<td style='width:50%'>																			";
		  					$this->createMultiLangEditFieldTable($languages,$items);
		echo "			</td>																							";	
		echo "			<td>																							";
		echo "			</td>																							";
		echo "		</tr>																								";
		echo "  	<tr>																								";
		echo "		</tr>																								";
		echo "	</table>																								";
		echo "</div>																									";
	}
	
	
	public function createMultiLangFixedFieldTable($languages,$items) {
	
		echo "<table style='width:100%'>";
		foreach($languages as $index => $language) {
			echo "<tr>";
			echo "	<td style='width:12%'>".$language->languagecode.": </td>";
			
			if (isset($items[$language->languageID])) {
				echo "	<td style='width:75%' id=language_value_".$this->getID()."_" . $language->languageID . ">".$items[$language->languageID]."</td>";
			} else {
				echo "	<td style='width:75%' id=language_value_".$this->getID()."_" . $language->languageID . ">Ei asetettu</td>";
			}
			echo "	<td></td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	
	public function createMultiLangEditFieldTable($languages,$items) {
		echo "<table>";
		foreach($languages as $index => $language) {
			
			$value = "";
			if (isset($items[$language->languageID])) $value = $items[$language->languageID];
			
			echo "<tr>";
			echo "	<td style='width:12%' id='language_code_".$this->getID()."'>".$language->languagecode ."</td>";
			echo "	<td style='width:75%'>";
			echo "		<input style='width:100%' id='language_edit_".$this->getID()."_" . $language->languageID . "' type=text class='uitextfield' value='" . $value . "'>";
			echo "	</td>";
			echo "  <td></td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	
	
	/**
	 * TODO: naista osa voitaisiin ehka periaatteessa korvata globaaleilla funktioilla, joilla field-id olisi parametrina. Tama aiheuttaisi sen, etta
	 * taalla pitaisi olla jokin staattinen muuttuja joka kertoo onko kyseiset funktiot inludetettu jo aiemmin. Antaa nyt toistaiseksi kuitenkin olla
	 * nain. Tama saattaa joissakin tapauksissa saastaa koodin pituutta tiedonsiirrossa. Ainakin editable-tablen tapauksessa vastaavat pitaa korvata
	 * globaaleille, en tieda onko nain jo tehty.
	 * 
	 * @param string $languages
	 */
	private function createScripts($languages) {
		
		
		echo "<script>";
		echo "	function fieldGetSaveParams_".$this->getID()."() {";
		echo "		var params = '" . $this->urlParameter . "=';";		
		foreach($languages as $index => $language) {
			echo "	params = params + '[" . $language->languageID . "]' + $('#language_edit_".$this->getID()."_" . $language->languageID . "').val();";
		}
		echo "		return params;";
		echo "	}";
		echo "</script>";
		
		
		
		//***************************************************************************************************************
		//*** FUNCTION FIELDACTIVAEDIT()
		//*** Implements what happens when Edit-button is pressed on UISection
		//***************************************************************************************************************
		
		$fieldID		= $this->getID();
		
		echo "<script>																									";
		echo "																											";
		echo "	function fieldActivateEdit_".$fieldID."() {																";
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(false);																	";
		echo " 		showEditDiv_".$fieldID."(true);																		";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION CHECKONSAVE_()
		//*** Validate data when Save-button is pressed.
		//***************************************************************************************************************
		
		$fieldID		= $this->getID();
		
		echo "<script>																									";
		echo "																											";
		echo "	function fieldCheckOnSave_".$fieldID."() {																";
		echo "		return true;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	
		//***************************************************************************************************************
		//*** FUNCTION ACCEPTEDITANDCLOSE_()
		//*** Hides edit-div and shows show-div.
		//***************************************************************************************************************
		
		$fieldID		= $this->getID();
	
		echo "<script>																									";
		echo "	function acceptEditAndClose_".$fieldID."() {															";
		foreach($languages as $index => $language) {
			echo "	var newValue = $('#language_edit_".$this->getID()."_" . $language->languageID . "').val();";
			echo "	$('#language_value_".$this->getID()."_" . $language->languageID . "').html(newValue);";
		}
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(true);																		";
		echo " 		showEditDiv_".$fieldID."(false);																	";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
			
		//***************************************************************************************************************
		//*** FUNCTION FIELDCANCELEDIT_()
		//*** Cancels edit when Cancel-button is pressed.
		//***************************************************************************************************************
		
		$fieldID		= $this->getID();
		
		echo "<script>																									";
		echo "																											";
		echo "	function fieldCancelEdit_".$fieldID."() {																";
		echo "																											";
		echo " 		showShowDiv_".$fieldID."(true);																		";
		echo " 		showEditDiv_".$fieldID."(false);																	";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";

		//***************************************************************************************************************
		//*** FUNCTION FIELDFOCUS_()
		//*** What happens when field is focused.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function fieldFocus_".$fieldID."() {																	";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION FIELDSHOWERROR_()
		//*** Shows error.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function fieldShowError_".$fieldID."() {																";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";

		//***************************************************************************************************************
		//*** FUNCTION SHOWSHOWDIV_(bool)
		//*** Shows or hides Show-div.
		//***************************************************************************************************************
		
		$fieldID = $this->getID();
		
		echo "<script>																									";
		echo "																											";
		echo "	function showShowDiv_".$fieldID."(show) {																";
		echo "																											";
		echo "		var show_div_id = '#show_div_".$fieldID."';															";
		echo "																											";
		echo "		if (show) $(show_div_id).show();																	";
		echo "		if (!show) $(show_div_id).hide();																	";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
			
		//***************************************************************************************************************
		//*** FUNCTION SHOWEEDITDIV_(bool)
		//*** Shows or hides Edit-div.
		//***************************************************************************************************************
		
		$fieldID = $this->getID();
		
		echo "<script>																									";
		echo "																											";
		echo "	function showEditDiv_".$fieldID."(show) {																";
		echo "																											";
		echo "		var edit_div_id = '#edit_div_".$fieldID."';															";
		echo "																											";
		echo "		if (show) $(edit_div_id).show();																	";
		echo "		if (!show) $(edit_div_id).hide();																	";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
	}
	
	
	public function show($data = null) {
		
		$this->createScripts($this->languages);
		
		if ($data == null) {
			$items = array();
		} else {
			$dataVariable = $this->dataVariable;
			$items = parseMultilangArray($data->$dataVariable);
		}
		
		if ($this->editactive == true) {
			$this->createShowDiv($this->languages, $items, false);
			$this->createEditDiv($this->languages, $items, true);
		} else {
			$this->createShowDiv($this->languages, $items, true);
			$this->createEditDiv($this->languages, $items, false);
		}
	}
}

?>