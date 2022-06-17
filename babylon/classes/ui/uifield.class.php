<?php

// taman voisi poistaa abstracktina luokkana ja luoda sensijaan 'ei editoitava'-fieldi 
// tai sitten kaytetaan vaan normi textfieldia, jossa editable on asetettu disabled
abstract class UIField extends UIComponent {
	
	
	protected $parentSectionID;
	protected $editactive = false;
	protected $disabled = false;
	protected $visible = true;
	protected $tabindex = 0;
	protected $enterAction = null;
	protected $customCheckFunction = null;
	
	protected $hideFromView = false;
	protected $hiddenFromEdit = false;
	
	protected $dataformatter = null;
	protected $dataformatterparam = null;
	
	
	public function setFormatter($dataformatter, $dataformatterparam) {
		$this->dataformatter = $dataformatter;
		$this->dataformatterparam = $dataformatterparam;
		$this->disabled = false;
	}
	
	
	public function __construct() {
		parent::__construct();
	}
	

	public function setCustomCheckFunction($functionName) {
		$this->customCheckFunction = $functionName;
	}
	
	
	public function getCustomCheckFunction() {
		return $this->customCheckFunction;
	}
	
	
	public function setEditActive($active) {
		//echo "<br>Set editactive - " . $active;
		$this->editactive = $active;
	}

	public function getEditActive() {
		return $this->editactive;
	}
	
	public function setEnterAction($action) {
		$this->enterAction = $action;
	}
	
	public function getVariable() {
		return "";
	}
	
	public function getOnChange() {
		return null;
	}
	
	// TODO: 22.10.21	Ei muistikuvaa, mitä tällä disabled kentällä haetaan?
	// Disabledilla ilmeisesti haetaan sitä, että kenttää ei voida sectionissa muokata.
	public function setDisabled($disabled) {
		$this->disabled = $disabled;
	}

	// TODO: 8.12.2019 Tätä visibleä käytetään toistaiseksi ainoastaan UITextFieldissä, muissa toteuttamatta
	public function setVisible($boolean) {
		$this->visible = $boolean;
	}
	
	public function getDisabled() {
		return $this->disabled;
	}
	
	
	/**
	 * Hide from Field tarkoittaa sitä, että kenttä on oletusarvoisesti piilossa sectionilla. Sen sisältö kuitenkin 
	 * luodaan sectionille, ja se voidaan näyttää kutsumalla show-kutsua. Tarkoitettu että kenttä näytetään javascriptillä
	 * jossain onchange funktion avulla, tähän tyyliin...
	 * 
	 * var element = '#selectline-".$field->getId()."';";
	 * $(element).show();";
	 * 
	 * @param boolean $boole
	 */
	public function setHideFromView($boole) {
		$this->hideFromView = $boole;
	}
	
	public function isHiddenFromView() {
		return $this->hideFromView;
	}
	
	
	/**
	 * Hidden-kenttä on kokonaan piilossa sekä vieviltä, että editointipuolelta
	 * 
	 * @param unknown $boole
	 */
	public function setHiddenFromEdit($boole) {
		$this->hiddenFromEdit= $boole;
	}
	
	public function isHiddenFromEdit() {
		return $this->hiddenFromEdit;
	}
	
	
	
	public function getStringID() {
		return "editfield-" . $this->parentSectionID . "-" . $this->getID();
	}
	
	public function setParentSection($parentSectionID) {
		$this->parentSectionID = $parentSectionID;
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
	public function setSaveCallback($functionname) {
		$this->savecallback = $functionname;
	}
	

	public function setValueJSFunction() {
		return "setFieldValue_" . $this->getID() . "";
	}
	
	public function getCheckOnSaveJSFunction() {
		return "fieldCheckOnSave_" . $this->getID() . "()";
	}
	
	public function getSaveParamsJSFunction() {
		return "fieldGetSaveParams_" . $this->getID() . "()";
	}
	
	public function getActivateEditJSFunction() {
		return "fieldActivateEdit_" . $this->getID() . "()";
	}
	
	public function getCancelEditJSFunction() {
		return "fieldCancelEdit_" . $this->getID() . "()";
	}
	
	public function getSaveAcceptedJSFunction() {
		return "acceptEditAndClose_" . $this->getID() . "()";
	}
	
	public function getShowErrorJSFunction() {
		return "fieldShowError_" . $this->getID() . "()";
	}
	
	public function getSetFocusJSFunction() {
		return "fieldFocus_" . $this->getID() . "()";
	}
	
	function getEditFieldID() {
	
		$fieldID = $this->getID();
		$ret 	 = "editfield-".$fieldID;
		return  $ret;
	}
	
	function getValueFieldID() {
	
		$fieldID = $this->getID();
		$ret 	 = "fieldvalue-".$fieldID;
		return  $ret;
	
	}
	
	//abstract public function setEditActive($isactive);
	
	abstract public function getTitle();
	
	abstract public function show($data);
	
	public function setTabIndex($tabindex) {
		$this->tabindex = $tabindex;
	}
	
	
	/**
	 * Tata funktiota kaytetaan silloin kun fieldeja luodaan dynaamisesti suoraan tietokannan 
	 * column-tietojen pohjalta.
	 * 
	 * TODO: Select fieldin luonti saattaa olla haastava, se tarvitsee datana taulukon sisallan.
	 * 
	 * @param string $column
	 * @param string $data 		Fieldin tietosisalta 
	 * @return UITextField
	 */
	public static function createUIField($column, $data) {

		//echo "<br>Columntype - " . $column->type;
		
		$uifield = null;
		switch($column->type) {
			case Column::COLUMNTYPE_INTEGER :
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_FLOAT:
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_STRING:
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_DATE:
				return new UIDateField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_DATETIME:
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_TEXT:
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_BOOLEAN:
				return new UIBooleanField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_TABLEREFERENCE:
				//echo "<br>Creating reference select";
				return new UISelectField($column->name, $column->variablename, $column->columnname, $data);
				break;
			case Column::COLUMNTYPE_INTEGERARRAY:
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			case Column::COLUMNTYPE_ORDER:
				return null;	// tämä kenttä ei ole asetettavissa automaattisesti
				break;
			case Column::COLUMNTYPE_KEYVALUELIST:
				return new UITextField($column->name, $column->variablename, $column->columnname);
				break;
			default :
				echo "[{\"success\":\"Tuntematon Column-type " . $column->type . "\"}]";
				exit;
				break;
		}		
	}
	
}


?>