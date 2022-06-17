<?php

class UIEditableRow {
	
	private $columns;
	private $row;
	private $rowID;
	private $updateaction;
	
	public function __construct($fieldID,$columns,$row,$updateaction) {

		$this->getID()		= $fieldID;
		$this->columns  	= $columns;
		$this->row			= $row;
		$this->rowID		= $row->getID();
		$this->updateaction = $updateaction;
		
	}

//***********************************************************************************************************
//*** FUNCTION CREATEROWCOMPONENT()
//*** Initalizes component creation.
//***********************************************************************************************************
		
	private function createRowComponent() {
			
		$this->createFixedRow();
		$this->createEditableRow();
		
	}
	
//***********************************************************************************************************
//*** FUNCTION CREATEFIXEDROW()
//*** Creates fields for fixedrow.
//***********************************************************************************************************
	
	private function createFixedRow() {
		
		$rowID		= $this->rowID;
		$fieldID	= $this->getID();
		$columns 	= $this->columns;
		
		$this->createMessageRow();
	
		echo "<tr id='fixed_row_".$fieldID."_".$rowID."'>";
		
			foreach($columns as $index => $column) {	
					$this->createFixedField($column);
			}
			
			$this->createEditButton();

			echo "</tr>";
	}
	
//***********************************************************************************************************
//*** FUNCTION CREATEMESSAGEROW()
//*** Creates messagerow for fixedrow.
//***********************************************************************************************************
	
	private function createMessageRow() {
		
		$rowID		= $this->rowID;
		$fieldID	= $this->getID();
		$columns 	= $this->columns;
		
		echo "<tr id='message_row_".$fieldID."_".$rowID."' style='display:none'>";
		echo "	<td colspan=0>";
		echo "		<div id='message_div_".$fieldID."_".$rowID."' style='display:none'>";
		echo "		</div>";
		echo "	</td>";
		echo "</tr>";
	}
	
//***********************************************************************************************************
//*** FUNCTION CREATEFIXEDFIELD($column)
//*** Creates fixed field according given $column.
//***********************************************************************************************************
	
	private function createFixedField($column) {
		
		$class = get_class($column);
		
		switch($class) {
		
			case 'UISelectColumn':
				$this->createFixedSelectField($column);
				break;

			case 'UIRadioColumn' :
				$this->createFixedRadioField($column);
				break;
						
			case 'UIMultiSelectColumn' :
				$this->createFixedMultiSelectField($column);
				break;	
				
			case 'UISortColumn' :
				if ($column->type==10) $this->createFixedIntegerField($column);
				if ($column->type==11) $this->createFixedFloatField($column);
				if ($column->type==12) $this->createFixedStringField($column);
				if ($column->type==15) $this->createFixedTextField($column);
				if ($column->type==16) $this->createFixedBooleanField($column);
				break;
				
			default :
				$this->createFixedTextField($column);
				break;
					
		}
	}
	
//***********************************************************************************************************
//*** FUNCTION CREATEEDITBUTTON()
//*** Creates edit-button for fixed row.
//***********************************************************************************************************
	
	private function createEditButton() {
		
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
	
		$row_to_show = "editable_row_".$fieldID."_".$rowID;
		$row_to_hide = "fixed_row_".$fieldID."_".$rowID;
		
		echo "<td>";
		echo "<span class='buttonstyle' onclick=showAndHideRow('".$row_to_show."','".$row_to_hide."')>Muokkaa</span>";
		echo "</td>";
		
	}
	

//***********************************************************************************************************
// FUNCTION CREATE FIXED FIELDS($column)
// Creates fixed fields.
//***********************************************************************************************************
	
	private function createFixedTextField($column) {
		$this->createDefaultFixedField($column);	
	}

	private function createFixedStringField($column) {
		$this->createDefaultFixedField($column);
	}
	
	private function createFixedIntegerField($column) {
		$this->createDefaultFixedField($column);
	}
	
	private function createFixedFloatField($column) {
		$this->createDefaultFixedField($column);
	}
	
	private function createFixedRadioField($column) {
		$this->createFixedSelectField($column);
	}

	private function createFixedBooleanField($column) {
	
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$value 			= $row->$datavariable;
		$datatype 		= $column->type;
		$cellname   	= "fixed_cell_".$fieldID."_".$rowID;
		
		echo "<td name='".$cellname."'>";
			if ($value==0) echo "Ei";
			if ($value==1) echo "Kylla";
			if (($value!=0) && ($value!=1)) echo "ei maaritely";
		echo "</td>";
		
	}
	
	private function createFixedSelectField($column) {
		
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$value 			= $row->$datavariable;
		$output			= $column->data[$value];
		$datatype 		= $column->type;
		$cellname   	= "fixed_cell_".$fieldID."_".$rowID;
		
		echo "<td name='".$cellname."'>";
			echo "".$output;
		echo "</td>";
		
	}
	
	private function createFixedMultiSelectField($column) {
	
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$value 			= $row->$datavariable;
		$datatype 		= $column->type;
		$cellname   	= "fixed_cell_".$fieldID."_".$rowID;
		$items 			= explode(":",$value);

		$output = "";
		
		foreach($items as $index => $item) {
			$output.=$column->data[$item].",";
			echo "<td id='hidden' name='".$cellname."' style='display:none'></td>";
		}
	
		echo "<td name='".$cellname."'>";
		
		$output = trim($output,",");
		echo "".$output;
		
		echo "</td>";
	
	}
	
	private function createDefaultFixedField($column) {
		
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$value 			= $row->$datavariable;
		$datatype 		= $column->type;
		$cellname   	= "fixed_cell_".$fieldID."_".$rowID;
		
		echo "<td name='".$cellname."'>";
		echo "".$value;
		echo "</td>";
		
	}
	
//***********************************************************************************************************
// FUNCTION CREATEEDITABLEROW()
// Creates fields for editable row.
//***********************************************************************************************************
	
	private function createEditableRow() {
	
		$rowID		  = $this->rowID;
		$fieldID	  = $this->getID();
		$columns	  = $this->columns;
		$updateaction = getUrl($this->updateaction);
		
	
		echo "<tr id='editable_row_".$fieldID."_".$rowID."' name='".$updateaction."' style='display:none'>";
	
		foreach($columns as $index => $column) {
				
			echo "<td style='vertical-align:top'>";
			$this->createEditableField($column);
			echo "</td>";
	
		}
	
		$this->createSaveCancelButtons();
	
		echo "</tr>";
	
	}
	
//***********************************************************************************************************
// FUNCTION CREATEEDITABLEFIELD($column)
// Creates editable field according given $column.
//***********************************************************************************************************
	
	private function createEditableField($column) {
	
		$class = get_class($column);
	
		switch($class) {
	
			case 'UISelectColumn':
				$this->createEditableSelectField($column);
				break;
					
			case 'UIMultiSelectColumn':
				$this->createEditableMultiSelectField($column);
				break;
						
			case 'UIRadioColumn' :
				$this->createEditableRadioField($column);
				break;
						
			case 'UISortColumn' :
				if ($column->type==10) $this->createEditableIntegerField($column);
				if ($column->type==11) $this->createEditableFloatField($column);
				if ($column->type==12) $this->createEditableStringField($column);
				if ($column->type==15) $this->createEditableTextField($column);
				if ($column->type==16) $this->createEditableBooleanField($column);
				break;
				
			default :
				$this->createEditableTextField($column);
				break;
		}
	}
	
//***********************************************************************************************************
// FUNCTION CREATE EDITABLE FIELDS($column)
// Creates fields that can be edited.
//
// Cell name holds following parameters; type of row, rowID, fieldID to identify elements belonging to certain 
// field and row.
// Cell ID holds following paramaters; datavariable, datatype, rowID, item name, primary/secondary.
// Item name is required to handle checkboxes since checkbox element doesn't have value pair. 
// Primary/Secondary is used to tell apart which element is primary element on multielement selections. 
//***********************************************************************************************************
			
	private function createEditableRadioField($column) {
		$this->createEditableSelectField($column);
	}
	
	private function createEditableTextField($column) {
	
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$value 			= $row->$datavariable;
		$length			= strlen($value);
		$datatype 		= $column->type;
		$cellname   	= "editable_cell_".$fieldID."_".$rowID;
		$cellID			= $datavariable."_".$datatype."_".$rowID."_null_primary";
		
		echo "<input name='".$cellname."' id='".$cellID."' class='uitextfield' type=text size=".$length." value='".$value."'></input>";
		
	}

	private function createEditableStringField($column) {
	
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$value 			= $row->$datavariable;
		$length			= strlen($value);
		$datatype 		= $column->type;
		$cellname   	= "editable_cell_".$fieldID."_".$rowID;
		$cellID			= $datavariable."_".$datatype."_".$rowID."_null_primary";
			
		echo "<input name='".$cellname."' id='".$cellID."' class='uitextfield' type=text size=".$length." value='".$value."'></input>";
	}
	
	private function createEditableIntegerField($column) {
	
		$row	 		 = $this->row;
		$rowID			 = $this->rowID;
		$fieldID		 = $this->getID();
		$datavariable 	 = $column->datavariable;
		$value 			 = $row->$datavariable;
		$length			 = strlen($value);
		$datatype 		 = $column->type;
		$cellname   	 = "editable_cell_".$fieldID."_".$rowID;
		$cellID			 = $datavariable."_".$datatype."_".$rowID."_null_primary";
		$row_to_validate = "editable_row_".$fieldID."_".$rowID;
		
		
		//echo "<input name='".$cellname."' id='".$cellID."' class='uitextfield' type=text size=".$length." value='".$value."' onkeyup='validateIntegerElementOnKeyPressed(\"".$cellID."\")'></input>";
		echo "<input name='".$cellname."' id='".$cellID."' class='uitextfield' type=text size=".$length." value='".$value."' onkeyup=validateRow('".$row_to_validate."'></input>";
	}
	
	private function createEditableFloatField($column) {
	
		$row	 		 = $this->row;
		$rowID			 = $this->rowID;
		$fieldID		 = $this->getID();
		$datavariable 	 = $column->datavariable;
		$value 			 = $row->$datavariable;
		$length			 = strlen($value);	
		$datatype 		 = $column->type;
		$cellname   	 = "editable_cell_".$fieldID."_".$rowID;
		$cellID			 = $datavariable."_".$datatype."_".$rowID."_null_primary";
		$row_to_validate = "editable_row_".$fieldID."_".$rowID;
		
		//echo "<input name='".$cellname."' id='".$cellID."' class='uitextfield' type=text size=".$length." value='".$value."' onkeyup='validateFloatElementOnKeyPressed(\"".$cellID."\")'></input>";
		echo "<input name='".$cellname."' id='".$cellID."' class='uitextfield' type=text size=".$length." value='".$value."' onkeyup=validateRow('".$row_to_validate."')></input>";
		
	}
	
	private function createEditableBooleanField($column) {
		
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$default_value	= $row->$datavariable;
		$datatype 		= $column->type;
		$cellname   	= "editable_cell_".$fieldID."_".$rowID;
		$cellID			= $datavariable."_".$datatype."_".$rowID."_null_primary";
		
		echo "<select name='".$cellname."' id='".$cellID."'>";
		
			echo "<option selected='selected' value='0'>Ei</option>";
			echo "<option value='1'>Kylla</option>";
		
		echo "</select>";
	
	}
	
	private function createEditableSelectField($column) {
		
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$default_value	= $row->$datavariable;
		$items 			= $column->data;
		$datatype 		= $column->type;
		$cellname   	= "editable_cell_".$fieldID."_".$rowID;
		$cellID			= $datavariable."_".$datatype."_".$rowID."_null_primary";
		
		echo "<select name='".$cellname."' id='".$cellID."'>";
			foreach($items as $index => $item) {
				if ($item==$default_value) echo "<option selected='selected' value='".$index."'>".$item."</option>";
				if ($item!=$default_value) echo "<option value='".$index."'>".$item."</option>";
			}
		echo "</select>";
	}
	
	private function createEditableMultiSelectField($column) {
	
		$row	 		= $this->row;
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
		$datavariable 	= $column->datavariable;
		$values			= explode(":",$row->$datavariable);
		$items 			= $column->data;
		$datatype 		= $column->type;
		$cellname   	= "editable_cell_".$fieldID."_".$rowID;
		$cellgroup		= $datavariable."_".$datatype."_".$rowID;
		$primary_item   = true;
		
		foreach($items as $index => $item) {

			if ($primary_item==true) {
				$cellID		  = $datavariable."_".$datatype."_".$rowID."_".$item."_primary";
				$primary_item = false;
			}  else {
				$cellID		  = $datavariable."_".$datatype."_".$rowID."_".$item."_secondary";
			}
			
			if ($this->isMultiSelected($index,$values)) {
				echo "<input name='".$cellname."' id='".$cellID."' type='checkbox' class='".$cellgroup."' value='".$index."' checked>".$item."</input><br>";
			} else {
				echo "<input name='".$cellname."' id='".$cellID."' type='checkbox' class='".$cellgroup."' value='".$index."'>".$item."</input><br>";	
			}	
		}
	}

//***********************************************************************************************************
//*** FUNCTION CREATESAVECANCELBUTTONS()
//*** Creates save- and cancel-buttons for editable row.
//***********************************************************************************************************
	
	private function createSaveCancelButtons() {
	
		$rowID			= $this->rowID;
		$fieldID		= $this->getID();
	
		$row_to_show = "fixed_row_".$fieldID."_".$rowID;
		$row_to_hide = "editable_row_".$fieldID."_".$rowID;
	
		echo "<td style='vertical-align:top'>";
		echo "<span id='save_button_".$fieldID."_".$rowID."' class='buttonstyle' onclick=saveAndHideRow('".$row_to_show."','".$row_to_hide."')>Tallenna</span>";
		echo "<span class='buttonstyle' onclick=showAndHideRow('".$row_to_show."','".$row_to_hide."')>Peruuta</span>";
		echo "</td>";
	}	
	
//***********************************************************************************************************
// FUNCTION ISMULTISELECTED()
// Returns true if item is selected on multiselect.
//***********************************************************************************************************
		
	private function isMultiSelected($item_index,$values) {
		
		foreach($values as $value_index => $value) {
			if ($item_index==$value) return true;
		}
		
		return false;
	}
	
	public static function createScripts() {
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEROWANDHIDEROW(save,show)
		//*** Saves and hides row and shows fixed row given as paramaters.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function saveAndHideRow(show,save) {																	";
		echo "																											";
		echo "		if (validateRow(save)) {;																			";
		echo "																											";
		echo "			saveRowData(save,parseSaveParameters(save));													";
		echo "			showAndHideRow(show,save);																		";
		echo "			updateFixedRow(show,save);																		";
		echo "		}																									";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";

		//***************************************************************************************************************
		//*** FUNCTION SHOWANDHIDEROW(show,hide)
		//*** Shows and hides rows given as paramaters.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function showAndHideRow(show,hide) {																	";
		echo "																											";
		echo "		$('#'+show).show();																					";
		echo "		$('#'+hide).hide();																					";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEROW(row)
		//*** Saves row.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function validateRow(row) {																				";
		echo "																											";
		echo "		var elements  	 = getEditableRowElements(row);														";
		echo "		var length 		 = elements.length;																	";
		echo "		var success		 = true;																			";
		echo "																											";
		echo "		for(var n=0;n<length;n++) {																			";
		echo "																											";
		echo "			var integer  = 10;																				";
		echo "			var float	 = 11;																				";
		echo "																											";
		echo "			var element  = elements[n];																		";
		echo "			var datatype = getElementDataType(element);														";
		echo "																											";
		echo "			if (datatype==integer) if (!validateIntegerElement(element)) success=false;						";
		echo "			if (datatype==float) if (!validateFloatElement(element)) success=false;							";
		echo "		}																									";
		echo "																											";
		echo "		saveButtonActive(row,success);																		";
		echo "																											";
		echo " 		return success;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";


		//***************************************************************************************************************
		//*** FUNCTION PARSESAVEPARAMETES(elements)
		//*** Parses url-parameters from elements.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function parseSaveParameters(save) {																	";
		echo "																											";
		echo "		var elements 	 = getEditableRowElements(save);													";
		echo "		var length  	 = elements.length;																	";
		echo "		var rowID		 = getElementRowID(elements[0]);													";
		echo "		var param_string = '&id='+rowID;																	";
		echo "																											";
		echo "		for(var n=0;n<length;n++) {																			";
		echo "																											";
		echo "			var element = elements[n];																		";
		echo "			var name    = getElementDataVariable(element);													";
		echo "			var value   = element.value;																	";
		echo "			var primary = isPrimaryElement(element);														";
		echo "																											";
		echo "			if ((element.nodeName=='INPUT') && (element.type=='checkbox') && (primary)) {					";
		echo "				param_string += '&'+name +'='+getMultiSelectIDValues(element);								";
		echo "			} else {																						";
		echo "				if (primary) param_string += '&'+name+'='+element.value;									";
		echo "			}																								";
		echo "		}																									";
		echo "																											";
		echo "		return param_string;																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEROW(row)
		//*** Saves row.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function updateFixedRow(fixed,editable) {																";
		echo "																											";
		echo "		var editable_elements  	 = getEditableRowElements(editable);										";
		echo "		var fixed_elements		 = getFixedRowElements(fixed);												";
		echo "		var length 		 		 = editable_elements.length;												";
		echo "																											";
		echo "		for(var n=0;n<length;n++) {																			";
		echo "																											";
		echo "			var datatype 	= getElementDataType(editable_elements[n]);										";
		echo "			var value	 	= getValue(editable_elements[n],datatype);										";
		echo "			fixed_elements[n].innerHTML = value;															";
		echo "		}																									";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETVALUE(element,datatype)
		//*** Returns correct value for given datatype and columntype
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getValue(editable,datatype) {																	";
		echo "																											";
		echo "		var return_value = null;																			";
		echo "																											";
		echo "		if (editable.nodeName=='SELECT') {																	";
		echo "																											";
		echo "			var index 	 = editable.selectedIndex;															";
		echo "			return_value = editable[index].innerHTML;														";
		echo "		}																									";
		echo "																											";
		echo "		if (editable.nodeName!='SELECT') {																	";
		echo "			return_value = editable.value;																	";
		echo "		}																									";
		echo "																											";
		echo "		if ((editable.nodeName=='INPUT') && (editable.type=='checkbox') && (editable.id!='hidden'))	{		";	
		echo "			return_value = getMultiSelectNameValues(editable);												";
		echo "		}																									";
		echo "																											";
		echo "		if (datatype==16) return_value = booleanToString(editable.value);									";															
		echo "																											";
		echo "		return return_value;																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETMULTISELECTNAMEVALUES(element)
		//*** Returns values of multiselect element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getMultiSelectNameValues(multiselect) {														";
		echo "																											";
		echo "		var datavariable = getElementDataVariable(multiselect);												";
		echo "		var datatype	 = getElementDataType(multiselect);													";
		echo "		var rowID 		 = getElementRowID(multiselect);													";
		echo "		var node_names	 = datavariable + '_' + datatype + '_' + rowID;										";
		echo "		var group_values = document.getElementsByClassName(node_names);										";																									
		echo "		var length 		 = group_values.length;																";
		echo "		var return_value = '';																				";
		echo "																											";
		echo "		for(var n=0;n<length;n++) {																			";
		echo "																											";
		echo "			if (group_values[n].checked) {																	";
		echo "				var text_value = getCheckboxTextValue(group_values[n]);										";
		echo "				return_value = return_value + text_value + ',' + '&nbsp';									";
		echo "			}																								";
		echo "		}																									";
		echo "																											";
		echo "		return_value = return_value.substring(0, return_value.length -6);									";
		echo "																											";
		echo "		return return_value;																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETMULTISELECTIDVALUES(element)
		//*** Returns values of multiselect element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getMultiSelectIDValues(multiselect) {															";
		echo "																											";
		echo "		var datavariable = getElementDataVariable(multiselect);												";
		echo "		var datatype	 = getElementDataType(multiselect);													";
		echo "		var rowID 		 = getElementRowID(multiselect);													";
		echo "		var node_names	 = datavariable + '_' + datatype + '_' + rowID;										";
		echo "		var group_values = document.getElementsByClassName(node_names);										";
		echo "		var length 		 = group_values.length;																";
		echo "		var return_value = '';																				";
		echo "																											";
		echo "		for(var n=0;n<length;n++) {																			";
		echo "																											";
		echo "			if (group_values[n].checked) {																	";
		echo "				return_value += group_values[n].value + ':';												";
		echo "			}																								";
		echo "		}																									";
		echo "		return_value = return_value.substring(0, return_value.length - 1);									";
		echo "																											";
		echo "		return return_value;																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION BOOLEANASSTRING(boolean)
		//*** Returns string value of boolean marked by integer values 0 and 1.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function booleanToString(value) {																		";
		echo "																											";
		echo "		if (value==0) return 'Ei';																			";
		echo "		if (value==1) return 'Kylla';																		";
		echo "																											";
		echo "		return 'Ei maaritelty';																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETELEMENTTYPE(element)
		//*** Returns elements type.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getElementDataType(element) {																	";
		echo "																											";
		echo "		var elementID = element.id;																			";
		echo "		var IDs 	  = elementID.split('_');																";
		echo "		var type 	  = IDs[1];																				";
		echo "																											";
		echo "		return type;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETELEMENTDATAVARIABLE(element)
		//*** Returns elements type.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getElementDataVariable(element) {																";
		echo "																											";
		echo "		var elementID    = element.id;																		";
		echo "		var IDs 	     = elementID.split('_');															";
		echo "		var datavariable = IDs[0];																			";
		echo "																											";
		echo "		return datavariable;																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETROWID(element)
		//*** Returns elements rowID
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getElementRowID(element) {																		";
		echo "																											";
		echo "		var elementID    = element.id;																		";
		echo "		var IDs 	     = elementID.split('_');															";
		echo "		var rowID 	     = IDs[2];																			";
		echo "																											";
		echo "		return rowID;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";

		//***************************************************************************************************************
		//*** FUNCTION GETINPUTTEXTVALUE(element)
		//*** Returns elements text value hidden in id-field
		//***************************************************************************************************************
	
		echo "<script>																									";
		echo "																											";
		echo "	function getCheckboxTextValue(element) {																";
		echo "																											";
		echo "		var elementID    = element.id;																		";
		echo "		var IDs 	     = elementID.split('_');															";
		echo "		var text_value 	 = IDs[3];																			";
		echo "																											";
		echo "		return text_value;																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION ISPRIMARYELEMENT(element)
		//*** Returns true if element is primary element on its' element group. Standalones are always primary.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function isPrimaryElement(element) {																	";
		echo "																											";
		echo "		var elementID    = element.id;																		";
		echo "		var IDs 	     = elementID.split('_');															";
		echo "		var value 	 = IDs[4];																				";
		echo "																											";
		echo "		if (value=='primary') return true;																	";
		echo "																											";
		echo "		return false;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETEDITABLEROWELEMENTS(row)
		//*** Returns cell elements contained by editable row.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getEditableRowElements(row) {																	";
		echo "																											";																							
		echo "		var IDs 		  = row.split('_');																	";
		echo "		var fieldID 	  = IDs[2];																			";
		echo "		var rowID 		  = IDs[3];																			";
		echo "		var element_names = 'editable_cell_'+fieldID+'_'+rowID;  											";
		echo "																											";
		echo "		var elements	  = document.getElementsByName(element_names);										";
		echo "																											";
		echo "		return elements;																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETFIXEDROWELEMENTS(row)
		//*** Returns cell elements contained by fixed row.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getFixedRowElements(row) {																		";
		echo "																											";
		echo "		var IDs 		  = row.split('_');																	";
		echo "		var fieldID 	  = IDs[2];																			";
		echo "		var rowID 		  = IDs[3];																			";
		echo "		var element_names = 'fixed_cell_'+fieldID+'_'+rowID;  												";
		echo "																											";
		echo "		var elements  	 = document.getElementsByName(element_names);										";
		echo "																											";
		echo "		return elements;																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETMESSAGEROWELEMENT(row)
		//*** Returns rows messagerow element. 
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getMessageRowElement(row) {																	";
		echo "																											";
		echo "		var fieldID 	 	  = getFieldID(row);															";
		echo "		var rowID 		 	  = getRowID(row);																";
		echo "		var message_row_name  = 'message_'+fieldID+'_'+rowID;  												";
		echo "																											";
		echo "		var message_row = document.getElementsByName(message_row_name);										";
		echo "																											";
		echo "		return message_row;																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETMESSAGEDELEMENT(row)
		//*** Returns rows messagerow element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getMessageRowElement(row) {																	";
		echo "																											";
		echo "		var fieldID 	 	  = getFieldID(row);															";
		echo "		var rowID 		 	  = getRowID(row);																";
		echo "		var message_row_name  = 'message_'+fieldID+'_'+rowID;  												";
		echo "																											";
		echo "		var message_row = document.getElementsByName(message_row_name);										";
		echo "																											";
		echo "		return message_row;																					";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETMESSAGEROWNAME(row)
		//*** Returns rows messagerow element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getMessageRowName(row) {																		";
		echo "																											";
		echo "		var fieldID 	   	  = getFieldID(row);															";
		echo "		var rowID 		 	  = getRowID(row);																";
		echo "		var message_row_name  = 'message_row_'+fieldID+'_'+rowID;  											";
		echo "																											";
		echo "		return message_row_name;																			";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETMESSAGEDIVNAME(row)
		//*** Returns rows messagerow element.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getMessageDivName(row) {																		";
		echo "																											";
		echo "		var fieldID 	   	  = getFieldID(row);															";
		echo "		var rowID 		 	  = getRowID(row);																";
		echo "		var message_row_name  = 'message_div_'+fieldID+'_'+rowID;  											";
		echo "																											";
		echo "		return message_row_name;																			";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETUPDATEACTION(row)
		//*** Returns cell elements contained by fixed row.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getUpdateAction(row) {																			";
		echo "																											";
		echo "		var element 	 = document.getElementById(row);													";
		echo "		var updateaction = element.getAttribute('name');													";
		echo "																											";
		echo "		return updateaction;																				";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		
		//***************************************************************************************************************
		//*** FUNCTION GETROWID(row)
		//*** Returns rows id.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getRowID(row) {																				";
		echo "																											";
		echo "		var IDs   = row.split('_');																			";
		echo "		var rowID = IDs[3];																					";
		echo "																											";
		echo "		return rowID;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION GETFIELDID(row)
		//*** Returns rows fieldID.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function getFieldID(row) {																				";
		echo "																											";
		echo "		var IDs 	= row.split('_');																		";
		echo "		var fieldID	= IDs[2];																				";
		echo "																											";
		echo "		return fieldID;																						";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION VALIDATEFLOATELEMENTONKEYUP(elementID)
		//*** Validates float.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function validateFloatElementOnKeyPressed(elementID) {													";
		echo "																											";
		echo "		var element = document.getElementById(elementID);													";
		echo "		validateFloatElement(element);																		";
		echo "																											";
		echo "	}																										";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION VALIDATEINTEGERELEMENTONKEYUP(elementID)
		//*** Validates float.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function validateIntegerElementOnKeyPressed(elementID) {												";
		echo "																											";
		echo "		var element = document.getElementById(elementID);													";
		echo "		validateIntegerElement(element);																	";
		echo "																											";
		echo "	}																										";
		echo "</script>																									";
		
		
		//***************************************************************************************************************
		//*** FUNCTION VALIDATEFLOAT(value)
		//*** Validates float.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function validateFloatElement(element) {																";
		echo "																											";
		echo "		var value = element.value;																			";
		echo "		var valid = isFinite(value);																		";
		echo "		var element_id 	 = '#' + element.id;																";
		echo "																											";
		echo "		if (!valid) {																						";
		echo "																											";
		echo "			$(element_id).addClass('errordiv');																";
		echo "			return false;																					";
		echo "																											";
		echo "		} else {																							";
		echo "			$(element_id).removeClass('errordiv');															";
		echo "			return true;																					";
		echo "		}																									";
		echo "																											";
		echo "	}																										";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION VALIDATEINTEGER(value)
		//*** Validates integer.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function validateIntegerElement(element) {																";
		echo "																											";
		echo "		var value 			= element.value;																";
		echo "		var integer_reg_exp = /^\s*(\+|-)?\d+\s*$/;															";
		echo "		var is_integer 		= String(value).search(integer_reg_exp);										";
		echo "		var element_id 	 = '#' + element.id;																";
		echo "																											";
		echo "		if (is_integer == -1) {																				";
		echo "																											";
		echo "			$(element_id).addClass('errordiv');																";
		echo "			return false;																					";
		echo "																											";
		echo "		} else {																							";
		echo "																											";
		echo "			$(element_id).removeClass('errordiv');															";
		echo "			return true;																					";
		echo "		}																									";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";

		//***************************************************************************************************************
		//*** FUNCTION SAVEROWDATA(params)
		//*** Saves row data.
		//***************************************************************************************************************

		echo "<script>																									";
		echo "																											";
		echo "	function saveRowData(row,parameters) {																	";
		echo "																											";
		echo "		var url 	   = getUpdateAction (row);																";
		echo "		var slidespeed = 500;																				";
		echo "		var delay	   = 1000;																				";
		//echo "		alert(url+parameters);																									";
		echo "		$.getJSON(url,parameters,function(reply) {															";
		echo "																											";
		echo "				if (reply[0].success == true) {																";
		echo "					showSuccess('Tallennus onnistui',row,slidespeed,delay);									";
		echo "				}																							";
		echo "																											";
		echo "				if (reply[0].success != true) {																";
		echo "					showError('Tallennus epaonnistui',row,slidespeed,delay);								";
		echo "				}																							";
		echo "		});																									";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEBUTTONACTIVE(row,is_active)
		//*** Changes class of save button according to validation state (true,false).
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function saveButtonActive(row,is_active) {																";
		echo "																											";
		echo "		var rowID	   = getRowID(row);																		";
		echo "		var fieldID	   = getFieldID(row);																	";
		echo "		var button	   = '#save_button_' + fieldID + '_' + rowID;											";
		echo "																											";
		echo "		if (is_active) $(button).removeClass('errordiv');													";
		echo "		if (!is_active) $(button).addClass('errordiv');														";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEROWDATA(params)
		//*** Saves row data.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function showSuccess(success,row,slidespeed,delay) {													";
		echo "																											";
		echo "		var messagerow = '#'+getMessageRowName(row);														";
		echo "		var messagediv = '#'+getMessageDivName(row);														";
		echo "																											";
		echo "		$(messagerow).show();																				";
		echo "		$(messagediv).show();																				";
		echo "		$(messagediv).html(success);																		";
		echo "		$(messagediv).addClass('successdiv');															";
		echo "																											";
		echo "		setTimeout(function() { $(messagediv).slideUp('slow');},delay);										";
		echo "		setTimeout(function() { $(messagerow).hide();},delay + slidespeed);									";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
		//***************************************************************************************************************
		//*** FUNCTION SAVEROWDATA(params)
		//*** Saves row data.
		//***************************************************************************************************************
		
		echo "<script>																									";
		echo "																											";
		echo "	function showError(error,row,slidespeed,delay) {														";
		echo "																											";
		echo "		var messagerow = '#'+getMessageRowName(row);														";
		echo "		var messagediv = '#'+getMessageDivName(row);														";
		echo "																											";
		echo "		$(messagerow).show();																				";
		echo "		$(messagediv).show();																				";
		echo "		$(messagediv).html(error);																			";
		echo "		$(messagediv).addClass('errordiv');																	";
		echo "																											";
		echo "		setTimeout(function() { $(messagediv).slideUp('slow');},delay);										";
		echo "		setTimeout(function() { $(messagerow).hide();},delay + slidespeed);									";
		echo "																											";
		echo "	}																										";
		echo "																											";
		echo "</script>																									";
		
	}
	
	public function show() {
		
		$this->createRowComponent();
		
	}
}
?>