<?php


class TimeSheet {
	
	private $columns 				= null;
	private $buttons				= null;
	private $showWeeklyTotal 		= false;
	private $showSheetTotal			= false;
	private $databasePrimaryKeyName	= null;
	private $predictive				= true;
	
	private $loadedrows;
	private $calculationAlgorithm;
	
	public function __construct($loadedrows,$isDisabled,$calculationAlgorithm,$databasePrimaryKeyName) {
	
		$this->loadedrows 				= $loadedrows;
		$this->isDisabled 				= $isDisabled;
		$this->calculationAlgorithm 	= $calculationAlgorithm;
		$this->databasePrimaryKeyName	= $databasePrimaryKeyName;

	}
	
	public function setPredictive($value) {
		$this->predictive = $value;
	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION ADDCOLUMN($COLUMN)
//*** Adds column to Timesheet
//********************************************************************************************************************************************************

	public function addColumn($column) {
				
		if ($this->columns==null) $this->columns = array(); 
		
		$this->columns[] = $column;
		
	}

//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION ADDBUTTON($BUTTON)
//*** Adds button to Timesheet
//********************************************************************************************************************************************************
	
	public function addButton($button) {
	
		if ($this->buttons==null) $this->buttons = array();
	
		$this->buttons[] = $button;
	
	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION SHOWWEEKLYTOTAL($ISHOWN)
//*** Sets if weekly total row is shown on sheet.
//********************************************************************************************************************************************************
	
	public function setShowWeeklyTotal($isShown) {
	
		$this->showWeeklyTotal = $isShown;
		
	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION SHOWSHEETTOTAL($ISHOWN)
//*** Sets if sheet total row is shown on sheet.
//********************************************************************************************************************************************************
	
	public function setShowSheetTotal($isShown) {
	
		$this->showSheetTotal = $isShown;
	
	}

	
//********************************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATECOLUMS($COLUMNS)
//*** Creates prototypes from columns.
//********************************************************************************************************************************************************
		
	private function createColumns($columns) {
		
		foreach($columns as $index => $column) {
			
			$type = $column->getClass();
				
			switch($type) {
				
				case 'TimeSpanColumn' : 
					$this->createTimeSpanColumn($column);
					break;
				
				case 'TimeSelectColumn' : 
					$this->createTimeSelectColumn($column);
					 break;
							
				case 'ChangeDropdownContentColumn' :
					$this->CreateChangeDropdownContentColumn($column);
					break;			

				case 'ChangeableDropdownContentColumn' :
					$this->CreateChangeableDropdownContentColumn($column);
					break;
					
				case 'TimeSheetDateColumn' :
					$this->CreateTimeSheetDateColumn($column);
					break;

				case 'TimeSheetSplitColumn' : 
					$this->CreateTimeSheetSplitColumn($column);
					break;	
					
				case 'TimeSheetRemoveColumn' :
					$this->CreateTimeSheetRemoveColumn($column);
					break;
					
				case 'TimeSheetFixedColumn' :
					$this->CreateTimeSheetFixedColumn($column);
					break;
					
				case 'TimeSheetInputColumn' :
					$this->CreateTimeSheetInputColumn($column);
					break;
					
				case 'TimeSheetDatepickerColumn' :
					$this->CreateTimeSheetDatepickerColumn($column);
					break;
					
			}
		}
	}

//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATEBUTTONS()
//***  Creates buttons.
//********************************************************************************************************************************************************
	
	private function createButtons() {
			
		$buttons = $this->buttons;
	
		if ($buttons==null) return;
		
		foreach($buttons as $index => $button) {
			$this->createButton($button);
		}
	
	}
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATEBUTTON($BUTTON)
//***  Creates button
//********************************************************************************************************************************************************

	private function createButton($button) {
		
		$text 	 = $button->getText();
		$value	 = $button->getValue();
		$func	 = $button->getFunction();
			
		echo "<script>																						";
		echo "																								";
		echo "																								";
		echo "	var row = document.getElementById('buttonrow');												";
		echo "	var domButton 	= document.createElement('button');											";
		echo "	domButton.value = '".$value."';																";
		echo "	domButton.innerHTML = '".$text."';															";
		echo "																								";
		echo "	domButton.onclick = function(event) {														";
		echo "		window['".$func."'](event);																";
		echo "	};																							";
		echo "																								";
		echo "	var cell = row.insertCell();																";
		echo "	cell.appendChild(domButton);																";
		echo "																								";
		echo "	buttons.push(domButton);																	";
		echo "																								";
		echo "</script>																						";
		
	}
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESHEETFIXEDCOLUMN($COLUMN)
//***  Creates FixedColumn-object from column.
//********************************************************************************************************************************************************
	
	private function CreateTimeSheetFixedColumn($column) {
	
		$name = $column->getName();
		$text = $column->getText();
	
		echo "<script>																																	";
		echo "																																			";
		echo "	var column = new TimeSheetFixedColumn('".$name."','".$text."');																			";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
	
	}
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESHEETSPLITCOLUMN($COLUMN)
//***  Creates Splitcolumn-object from column.
//********************************************************************************************************************************************************
	
	private function CreateTimeSheetSplitColumn($column) {
		
		$name 			= $column->getName();
		$splitAction 	= getURL($column->getSplitAction());
		$splitName		= $column->getSplitName();
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var column = new TimeSheetSplitColumn('".$name."','".$splitName."','".$splitAction."');													";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
		
	}
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESHEETSPLITCOLUMN($COLUMN)
//***  Creates Splitcolumn-object from column.
//********************************************************************************************************************************************************
	
	private function CreateTimeSheetRemoveColumn($column) {
	
		$name 		   		 = $column->getName();
		$compareColumn 		 = $column->getCompareColumn();
		$restrictionFunction = $column->getRestrictionFunction();
		$removeAction		 = getURL($column->getRemoveAction());
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var column = new TimeSheetRemoveColumn('".$name."','".$compareColumn."','".$restrictionFunction."','".$removeAction."');				";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
	
	
	}	
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESPANCOLUMN($COLUMN)
//***  Creates TimeSpanColumn-object from column.
//********************************************************************************************************************************************************

	private function createTimeSpanColumn($column) {
		
	$name 				= $column->getName();

	//********************************************************************************************************************************************************
	//*** FUNCTION CREATECHANGECONTENTROWS()
	//*** Creates content from $column->content.
	//********************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";																	
	echo "																																			";
	echo "	var column = new TimeSpanColumn('".$name."','normal');																					";
	echo "	prototypeColumns.push(column);																											";
	echo "																																			";
	echo "</script>																																	";
		
	} 
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESHEETINPUTCOLUMN($COLUMN)
//***  Creates TimeSpanColumn-object from column.
//********************************************************************************************************************************************************
	
	private function createTimeSheetInputColumn($column) {
	
		$name 				= $column->getName();
		$dataVariable		= $column->getDataVariable();
		$updateAction		= getURL($column->getUpdateAction());
		$validationFunction = $column->getValidationFunction();	
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATECHANGECONTENTROWS()
		//*** Creates content from $column->content.
		//********************************************************************************************************************************************************
	
		echo "<script>																																	";
		echo "																																			";
		echo "																																			";
		echo "	var column = new TimeSheetInputColumn('".$name."','".$dataVariable."','".$validationFunction."','".$updateAction."');					";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
	
	}
	
	

	//********************************************************************************************************************************************************
	//***  PRIVATE FUNCTION CREATETIMESHEETINPUTCOLUMN($COLUMN)
	//***  Creates TimeSpanColumn-object from column.
	//********************************************************************************************************************************************************
	
	private function createTimeSheetDatepickerColumn($column) {
	
		$name 				= $column->getName();
		$dataVariable		= $column->getDataVariable();
		$updateAction		= getURL($column->getUpdateAction());
		$validationFunction = $column->getValidationFunction();
	
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATECHANGECONTENTROWS()
		//*** Creates content from $column->content.
		//********************************************************************************************************************************************************
	
		echo "<script>																																	";
		echo "																																			";
		echo "																																			";
		echo "	var column = new TimeSheetDatepickerColumn('".$name."','".$dataVariable."','".$validationFunction."','".$updateAction."');					";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
	
	}
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESELECTCOLUMN($COLUMN) 
//***  Creates TimeSelectColumn-object from column.
//********************************************************************************************************************************************************
	 
	private function createTimeSelectColumn($column) {
	
		$name 				= $column->getName();
		$updateAction		= getURL($column->getUpdateAction());
		$startHourColumn	= $column->getStartHourColumn();
		$startMinuteColumn	= $column->getStartMinuteColumn();
		$endHourColumn		= $column->getEndHourColumn();
		$endMinuteColumn	= $column->getEndMinuteColumn();
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var column = new TimeSelectColumn('".$name."','".$startHourColumn."','".$startMinuteColumn."','".$endHourColumn."','".$endMinuteColumn."','".$updateAction."');		";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
	
	}
	
	
//********************************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATECHANGEDROPDOWNCONTENTCOLUMN($COLUMN)
//*** Creates HTML- and Javascript-representations of column.
//********************************************************************************************************************************************************
	
	private function CreateChangeDropdownContentColumn($column) {

		$name 				= $column->getName();
		$content			= $column->getContent();
		$value				= $column->getValue();
		$text				= $column->getText();
		$fromColumn			= $column->getFromColumn();
		$validationFunction = $column->getValidationFunction();
		$updateAction		= getURL($column->getUpdateAction());
		$predictive			= getURL($column->getPredictive());
		
		//********************************************************************************************************************************************************
		//*** FUNCTION ChangeContentRow(rowdata)
		//*** Creates Javascript Object, which encapsulates rowdata.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function ChangeContentRow(rowdata) {																									";
		echo "																																			";
		echo "		this.data = [];																														";
		echo "																																			";
		echo "		for(var index in rowdata) { 																										";
		echo "			this.data[index] = rowdata[index];																								";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATECHANGECONTENTROWS()
		//*** Creates content from $column->content.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createChangeContentRows() {																									";
		echo "																																			";
		echo "	var content = [];																														";
		echo "	var id;																																	";
		
		foreach($content as $index => $row) {
		
			$datavariables = $row->getDataVariables();
		
			echo "		var data 	= [];																												";
		
			foreach($datavariables as $varname => $varvalue) {

			// ID as index in array.
			if ($varname==$value) {
				
			echo "	id = '".$varvalue."';																																";	
				
			}	
				
			echo "	data['".$varname."'] = '".$varvalue."'; 																							";
			
			}
		
			echo "	content[id] =  new ChangeContentRow(data);																							";	
		}
		
		//echo "	debugContent(content);																													";
		echo " return content;																															";
		echo "																																			";
		echo "	}																																		";
		echo "</script>																																	";

		//********************************************************************************************************************************************************
		//*** FUNCTION DEBUGCONTENT(CONTENT)
		//*** Prints content to console.log.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function debugContent(content) {																																		";
		echo "																																			";
		echo "		for(var index in content)	 {																										";
		echo "																																			";
		//echo "			console.log(index + ':' + content[index]);																						";
		echo "																																			";
		echo "			item = content[index];																											";
		echo "																																			";
		echo "			for(var varname in item.data) {																									";
		echo "																																			";
		//echo "				console.log(varname + ':' + item.data[varname]);																			";
		echo "			}																																";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** MINIMAIN
		//*** Creates Javascript-object from column and pushes it to prototypeColumns.
		//********************************************************************************************************************************************************
		 
		echo "<script>																																	";
		echo "																																			";
		echo " 	var content = [];																														";
		echo "	content = createChangeContentRows();																									";
		echo "	var column = new ChangeDropdownContentColumn('".$name."','".$value."','".$text."',content,'".$fromColumn."','".$validationFunction."','".$updateAction."','" . $predictive . "');																		";
		echo "																																			";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";

		
		
	}
	
//********************************************************************************************************************************************************
//*** PRIVATE FUNCTION CREATECHANGEABLEDROPDOWNCONTENTCOLUMN($COLUMN)
//*** Creates HTML- and Javascript-representation from column.
//********************************************************************************************************************************************************
	
	private function CreateChangeableDropdownContentColumn($column) {
	
		$name 				= $column->getName();
		$target				= $column->getTargetColumnName();
		$content			= $column->getContent();
		$filter				= $column->getFilter();
		$value				= $column->getValue();
		$text				= $column->getText();
		$fromColumn			= $column->getFromColumn();
		$validationFunction = $column->getValidationFunction();
		$updateAction		= getURL($column->getUpdateAction());
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CONTENTROW(DATA)
		//*** Creates Javascript Object, which encapsulates rowdata.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function ContentRow(rowdata) {																											";
		echo "																																			";
		echo "		this.data = [];																														";
		echo "																																			";
		echo "		for(var index in rowdata) { 																										";
		echo "			this.data[index] = rowdata[index];																								";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATECONTENTROWS
		//*** Creates Javascript-objects from column->content.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createContentRows() {																											";
		echo "																																			";
		echo "	var content = [];																														";	
		echo "	var id;																																	";
		echo "																																			";
		
		foreach($content as $index => $row) {
		
			$datavariables = $row->getDataVariables();
		
		echo "		var data 	= [];																													";
				
		foreach($datavariables as $varname => $varvalue) {
			
		echo "	data['".$varname."'] = '".$varvalue."'; 																								";
	
		}

		echo "	content.push(new ContentRow(data));																										";
		
		}
		
		//echo "	debugContent(content);																													";
		echo "																																			";
		echo " return content;																															";
		echo "																																			";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";

		//********************************************************************************************************************************************************
		//*** FUNCTION DEBUGCONTENT(CONTENT)
		//*** Prints content to console.log.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function debugContent(content) {																																		";
		echo "																																			";
		echo "		for(var index in content)	 {																										";
		echo "																																			";
		//echo "			console.log(index + ':' + content[index]);																						";
		echo "																																			";
		echo "			item = content[index];																											";
		echo "																																			";
		echo "			for(var varname in item.data) {																									";
		echo "																																			";
		//echo "				console.log(varname + ':' + item.data[varname]);																			";
		echo "			}																																";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** MINIMAIN
		//*** Creates ChangeableDropdownContentColumn-object for Javascript.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var content = createContentRows() ;																										";	
		echo "																																			";
		echo "	var column = new ChangeableDropdownContentColumn('".$name."','".$target."',content,'".$filter."','".$value."','".$text."','".$fromColumn."','".$validationFunction."','".$updateAction."');				";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";

	}
		
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESHEETDATECOLUMN($COLUMN)
//***  Creates TimeSheetDateColumn-object from column.
//********************************************************************************************************************************************************
	
	private function CreateTimeSheetDateColumn($column) {
		
		$name 			= $column->getName();
		$fromColumn 	= $column->getFromColumn();
		$dateFormat		= $column->getDateFormat();
		$showDoubles	= $column->getShowDoubles();
		
		if ($showDoubles) $showDoubles = 1;
		if (!$showDoubles) $showDoubles = 0;
		
		//********************************************************************************************************************************************************
		//*** MINIMAIN
		//*** Creates TimeSheetDataColumn-object for Javascript.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "																																			";
		echo "	var column = new TimeSheetDateColumn('".$name."','".$fromColumn."','".$dateFormat."','".$showDoubles."');								";
		echo "	prototypeColumns.push(column);																											";
		echo "																																			";
		echo "</script>																																	";
		
	}
	
//********************************************************************************************************************************************************
//***
//***
//***
//***
//***
//***	
//*** GLOBALS GLOBALS GLOBALS GLOBALS GLOBALS GLOBALS GLOBALS
//***
//***
//*** GLOBAL ARRAYS
//***
//***	prototypeColumns : Column prototypes where from rows are initially cloned before setting values. 
//***	dataRows		 : Contains data objects.
//***	buttons			 : Contains domElement buttons.
//***
//********************************************************************************************************************************************************

	public function initalizeGlobalVariables() {
	
		$calculationAlgorithm	 = $this->calculationAlgorithm;
		$databasePrimaryKeyName  = $this->databasePrimaryKeyName;
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var prototypeColumns = [];																												";
		echo "	var dataRows  		 = [];																												";
		echo "	var buttons			 = [];																												";
		echo "																																			";
		echo "	var databasePrimaryKeyName 	= '".$databasePrimaryKeyName."';																			";
		echo "																																			";
		echo "	var calculationAlgorithm 	= '".$calculationAlgorithm."';																				";
		echo "																																			";
		echo "	var showWeeklyTot = '".$this->showWeeklyTotal."';																						";
		echo "	var showSheetTot  = '".$this->showSheetTotal."';																						";
		echo "																																			";
		echo "																																			";
		echo "</script>																																	";
	
		//********************************************************************************************************************************************************
		//*** OBJECT ROWIDENTIFIER(TIMESHEETROWID)
		//*** Contains rowID for database.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function RowIdentifier(timesheetrowID) {																								";
		echo "																																			";
		echo "		var data = [];																														";
		echo "		data['timesheetrowID'] = timesheetrowID;																							";
		echo "		this.data = data;																													";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION CREATEPROTOTYPES()
//*** Creates Javascripts for prototypes.
//********************************************************************************************************************************************************

	public function createPrototypes() {
		// In JS-files now.
	}
		
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION MAIN()
//*** MAIN MAIN MAIN MAIN MAIN MAIN MAIN MAIN MAIN MAIN MAIN
//********************************************************************************************************************************************************
	
	public function main() {
	
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATETABLEHEADER()
		//*** Creates table-header.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createTableHeader() {																											";
		echo "																																			";
		echo "		var sheet		 = document.getElementById('sheet');																				";
		echo "																																			";
		echo "		for(var column in prototypeColumns) {																								";
		echo "																																			";
		echo "			var th 		 = document.createElement('th');																					";
		echo "			th.innerHTML = prototypeColumns[column].getName();																				";
		echo "			sheet.appendChild(th);																											";
		echo "																																			";
		echo "		}																																	";
		echo "																																			";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATELOADEDROW()
		//*** Creates a new row.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createLoadedRow(rowValues,index) {																								";
		echo "																																			";
		echo "		var sheet		 = document.getElementById('sheet');																				";
		echo "		var row 		 = sheet.insertRow();																								";
		echo "		var	clones 		 = clonePrototypeColumns();																							";
		echo "		var isSunday	 = false;																											";
		echo "																																			";
		echo "		var timesheetRowIdentifier = new TimeSheetRowIdentifier(rowValues[databasePrimaryKeyName],'normal');								";
		echo "		clones.push(timesheetRowIdentifier);																								";
		//echo "		debugContent(rowValues);																											";
		//echo " 		alert(timesheetRowIdentifier.timesheetRowId + ':' + timesheetRowIdentifier.rowType);												";
		echo "		clones = finalizeClones(clones);																									";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			if (clones[column].getClass()!='TimeSheetRowIdentifier') {																		";
		echo "																																			";
		echo "				var element = clones[column].createDomElement();																			";
		//echo "				console.log('getclass - '+element);";
		echo "				var cell	= row.insertCell();																								";
		echo "				cell.appendChild(element);";
		//echo "				console.log('predictive - '																								";
		//if ($this->predictive == true) echo "				if (element instanceof HTMLSelectElement) $(element).chosen();";
		echo "																																			";
		echo "			}																																";
		echo "		}																																	";
		echo "																																			";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			var cloneClass = clones[column].getClass();																						";
		echo "			var fromColumn;																													";
		echo "			var targetColumn;																												";
		echo "																																			";
		echo "																																			";
		echo "			switch(cloneClass){ 																											";
		echo "																																			";
		echo "																																			";

//---------------------------------------------------------------------------------------------------------------		
//		Creation order sensitive. TargetColumn with set value must exists before changeablecolumn can be created.
//---------------------------------------------------------------------------------------------------------------

		echo "				case 'ChangeableDropdownContentColumn' :																					";
		echo "																																			";
		echo "					var confirmID = rowValues[clones[column].filter];																		";
		echo "																																			";
		echo "					fromColumn = clones[column].getFromColumn();																			";
		echo "																																			";
		echo "					clones[column].createNewValues(confirmID);																				";
		echo "					clones[column].setValue(rowValues[fromColumn]);																			";
		echo "																																			";
		echo "					break;																													";
		echo "																																			";
		echo "				case 'ChangeDropdownContentColumn' :																						";
		echo "																																			";
		echo "					fromColumn = clones[column].getFromColumn();																			";
		echo "					clones[column].setValue(rowValues[fromColumn]);																			";
		//echo "					$(clones[column]).chosen();";
		echo "					break;																													";
		echo "																																			";
		echo "				case 'TimeSelectColumn' :																									";
		echo "																																			";
		echo "					var startHourColumn 	 = clones[column].getStartHourColumn();															";
		echo "					var startMinuteColumn 	 = clones[column].getStartMinuteColumn();														";
		echo "					var endHourColumn 		 = clones[column].getEndHourColumn();															";
		echo "					var endMinuteColumn 	 = clones[column].getEndMinuteColumn();															";																						
		echo "																																			";
		echo "					var timeArray = [];																										";
		echo "																																			";
		echo "					timeArray[startHourColumn] 	 = rowValues[startHourColumn]; 																";
		echo "					timeArray[startMinuteColumn] = rowValues[startMinuteColumn]; 															";
		echo "					timeArray[endHourColumn]	 = rowValues[endHourColumn]; 																";
		echo "					timeArray[endMinuteColumn] 	 = rowValues[endMinuteColumn]; 																";
		echo "																																			";
		echo "					clones[column].setValue(timeArray);																						";
		//echo "					$(clones[column]).chosen();";
		echo "																																			";
		echo "					break;																													";
		echo "																																			";
		echo "				case 'TimeSheetDateColumn' :																								";
		echo "																																			";
		echo "					fromColumn = clones[column].getFromColumn();																			";
		echo "					var dateString = rowValues[fromColumn];																					";
		echo "					var dateFormat = clones[column].dateFormat;																				";
		echo "																																			";
		echo "					clones[column].setValue(dateString,dateFormat);																			";
		echo "																																			";
		echo "					break;																													";
		echo "																																			";
		echo "				case 'TimeSheetSplitColumn' :																								";
		echo "					clones[column].setIndex(row.rowIndex);																				 	";
		echo "					break;																													";
		echo "																																			";
		echo "				case 'TimeSheetRemoveColumn' :																								";
		echo "					clones[column].setIndex(row.rowIndex);																				 	";
		echo "					break;																													";
		echo "																																			";
		echo "				case 'TimeSheetFixedColumn' :																								";
		echo "																																		 	";
		echo "					break;																													";
		echo "																																		 	";
		echo "				case 'TimeSheetEmptyColumn' :																								";
		echo "																																		 	";
		echo "					break;																													";
		echo "																																		 	";
		echo "				case 'TimeSheetRowIdentifier' :																							 	";
		echo "																																		 	";
		echo "					break;																												 	";
		echo "																																		 	";
		echo "				case 'TimeSheetDatepickerColumn' :																							 	";
		echo "																																		 	";
		echo "					var dataVariable = clones[column].getDataVariable();																 	";
		echo "					var datestr = rowValues[dataVariable];";
		echo "					var year = datestr.substring(0,4);";
		echo "					var monthstr = datestr.substring(5,7);";
		echo "					var day = datestr.substring(8);";
		echo "					var newdate = day+'.'+monthstr+'.'+year;";
		echo "					clones[column].setValue(newdate);																		";
		echo "					break;																												 	";
		echo "																																		 	";
		echo "				case 'TimeSheetInputColumn' :																								";
		echo "																																		 	";
		echo "					var dataVariable = clones[column].getDataVariable();																 	";
		echo "					if (dataVariable!='') {																							 		";
		echo "						clones[column].setValue(rowValues[dataVariable]);																		";
		echo "					}																													 	";
		echo "					break;																													";
		echo "																																		 	";
		echo "			}																																";
		echo "		}																																	";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			clones[column].createEventListeners();																							";
		echo "		}																																	";
		echo "																																			";
		echo "		dataRows.push(clones);																												";
		echo "																																			";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATEWEEKLYTOTALS()
		//*** Creates weekly totals.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createWeeklyTotals() {																											";
		echo "																																			";
		echo "		for(var rowIndex in dataRows) {																										";
		echo "																																			";
		echo "			var dataRow = dataRows[rowIndex];																								";
		echo "			var rowCount = dataRows.length;																									";
		echo "																																			";
		echo "			for(var dataIndex in dataRow) {																									";
		echo "																																			";
		echo "				var currentObject = dataRow[dataIndex];																						";
		echo "																																			";
		echo "				if ((currentObject.getClass()=='TimeSheetDateColumn') && (rowIndex<rowCount)){												";
		echo "																																			";
		echo "					var nextIndex = parseInt(rowIndex) + 1;																					";
		echo "					var nextRow = dataRows[nextIndex];																						";
		echo "					var nextObject = nextRow[dataIndex];																					";
		echo "																																			";
		echo "					if (nextObject.getClass()=='TimeSheetDateColumn') {																		";
		echo "																																			";
		echo "						if ((nextObject.date.getDayNumber()!=6) && (currentObject.date.getDayNumber()==6)) createSumRow('weekly',nextIndex);";
		echo "																																			";
		echo "					}																														";
		echo "				}																															";
		echo "			}																																";
		echo "		}																																	";
		echo "																																			";
		// Creating last row if last datarow is sunday.
		echo "																																			";
		echo "		var rowCount = dataRows.length;																										";
		echo "		var lastIndex	= parseInt(rowCount)-1;																								";
		echo "																																			";
		echo "		dataRow = dataRows[lastIndex];																										";
		echo "																																			";
		echo "		for(var dataIndex in dataRow) {																										";
		echo "																																			";
		echo "			var currentObject = dataRow[dataIndex];																							";
		echo "																																			";
		echo "			if (currentObject.getClass()=='TimeSheetDateColumn') {																			";
		echo "																																			";
		echo "				if (currentObject.date.getDayNumber()==6) {																					";
		echo "					createSumRow('weekly',lastIndex+1);																						";
		echo "				}																															";
		echo "			}																																";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION HIDEDOUBLEDATES()
		//*** Hides double dates.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function hideDoubleDates() {																											";
		echo "																																			";
		echo "		var rowCount = dataRows.length;																										";
		echo "		var previousObject = null;																											";
		echo "																																			";
		echo "		for(var rowIndex in dataRows) {																										";
		echo "																																			";
		echo "			dataRow = dataRows[rowIndex];																									";
		echo "																																			";
		echo "			for(var dataIndex in dataRow) {																									";
		echo "																																			";
		echo "				var dataObject = dataRow[dataIndex]; 																						";
		echo "																																			";
		echo "				if (dataObject.getClass()=='TimeSheetDateColumn'){																			";
		echo "																																			";
		echo "					if (previousObject!=null) {																								";
		echo "																																			";
		echo "						if (dataObject.date.getDayNumber() == previousObject.date.getDayNumber()) {											";
		//echo "							console.log('Found matching dates, showDoubles value ' + dataObject.showDoubles);								";
		echo "							if (dataObject.showDoubles=='0') {																				";
		echo " 							 	var date = dataObject.getSelectedObject();																	";
		echo "						 	 	dataObject.setValue(date.dateString,'hidden');																";
		echo "							}																												";
		echo "						}																													";
		echo "					}																														";
		echo "																																			";
		echo "					previousObject = dataObject;																							";
		echo "																																			";
		echo "				}																															";
		echo "			}																																";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATESPLITROW(index)
		//*** Creates splits row on index.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createSplitRow(index,splitID,splitObject) {																					";
		echo "																																			";
		echo "		index			 = parseInt(index,10) + 1;																							";
		echo "		var sheet		 = document.getElementById('sheet');																				";
		echo "		var row 		 = sheet.insertRow(index);																							";
		echo "		var	clones 		 = clonePrototypeColumns();																							";
		echo "		var date		 = null;																											";
		echo "																																			";
		echo "		var timesheetRowIdentifier = new TimeSheetRowIdentifier(splitID,'normal');															";
		echo "		clones.push(timesheetRowIdentifier);																								";
		echo "																																			";
		echo "		clones = finalizeClones(clones);																									";
		echo "																																			";
		echo "			var dataRow = dataRows[index-1];																								";
		echo "																																			";
		echo "			for(var column in clones) {																										";
		echo "																																			";
		echo "				if (clones[column].getName()==splitObject.getName()) {																		";
		echo "																																			";
		echo "					var valueObject = splitObject.getCopy();																				";
		//echo "					alert(valueObject + ' ' + clones[column].getClass());																	";
		echo "																																			";
		echo "					clones[column].setCopy(valueObject);																					";
		echo "				}																															";
		echo "			}																																";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			if (clones[column].getClass()!='TimeSheetRowIdentifier') {																		";
		echo "				var element = clones[column].createDomElement();																			";
		echo "				var cell	= row.insertCell();																								";
		echo "				cell.appendChild(element);																									";
		echo "			}																																";
		echo "		}																																	";		
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			if (clones[column].getClass()!='TimeSheetSplitColumn') clones[column].createEventListeners();									";
		echo "																																			";
		echo "			if ((clones[column].getClass()=='TimeSheetDateColumn') && (clones[column].showDoubles=='0')) {									";
		echo "																																			";
		echo "						 var date = clones[column].getSelectedObject();																		";
		echo "						 clones[column].setValue(date.dateString,'hidden');																	";
		echo "																																			";
		echo "			}																																";
		echo "		}																																	";
		echo "																																			";
		echo "																																			";
		echo "		dataRows.splice(row.rowIndex,0,clones);																								";
		echo "																																			";
		echo "		updateIndexes();																													";
		echo "																																			";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";

		//********************************************************************************************************************************************************
		//*** FUNCTION REMOVEROW(FROMINDEX)
		//*** Creates removes row from Sheet and dataRows if removal restrictions apply. 
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function removeRow(fromIndex,restriction) {																								";
		echo "																																			";
		echo "		var validRemoval = false;																											";
		echo "		var sheet		 = document.getElementById('sheet');																				";
		echo "		validRemoval 	 = window[restriction](fromIndex); 																					";
		echo "																																			";
		echo "		if (validRemoval) {																													";
		echo "																																			";
		echo "			dataRows.splice(fromIndex,1);																									";
		echo "			sheet.deleteRow(fromIndex);																										";
		echo "			updateIndexes();																												";
		echo "																																			";
		echo "		}																																	";
		echo "																																			";
		echo "		return validRemoval;																												";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION UPDATEINDEXES()
		//*** Updates dataRow indexes if split and removal columns exists.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function updateIndexes()	{																											";
		echo "																																			";
		echo "		for(var rowIndex in dataRows) {																										";
		echo "																																			";
		echo "			dataRow = dataRows[rowIndex];																									";
		echo "																																			";
		echo "			for(var column in dataRow) {																									";
		echo "																																			";
		echo "				if(dataRow[column].getClass()=='TimeSheetSplitColumn') {																	";
		echo "						dataRow[column].setIndex(rowIndex);																					";
		echo "						dataRow[column].createEventListeners();																				";
		echo "				}																															";
		echo "																																			";
		echo "				if(dataRow[column].getClass()=='TimeSheetRemoveColumn') {																	";
		echo "						dataRow[column].setIndex(rowIndex);																					";
		echo "						dataRow[column].createEventListeners();																				";
		echo "				}																															";
		echo "																																			";		
		echo "			}																																";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CLONEPROTOTYPECOLUMNS()
		//*** Clones prototypecolumns for new row.
		//********************************************************************************************************************************************************
	 
		echo "<script>																																	";
		echo "																																			";
		echo "	function clonePrototypeColumns() {																										";
		echo "																																			";
		echo "		var clones = [];																													";
		echo "		var cloned = null;																													";
		echo "																																			";
		echo "		for(var column in prototypeColumns) {																								";
		echo "																																			";
		echo "			cloned = prototypeColumns[column];																								";
		echo "																																			";
		echo "			if (cloned.getClass()=='TimeSheetInputColumn') clone = new TimeSheetInputColumn(cloned.name,cloned.dataVariable,cloned.validationFunction,cloned.updateAction);									";
		echo "			if (cloned.getClass()=='TimeSheetDatepickerColumn') clone = new TimeSheetDatepickerColumn(cloned.name,cloned.dataVariable,cloned.validationFunction,cloned.updateAction);									";
		echo "			if (cloned.getClass()=='TimeSpanColumn') clone = new TimeSpanColumn(cloned.name,cloned.type);									";
		echo "			if (cloned.getClass()=='TimeSheetSplitColumn') clone = new TimeSheetSplitColumn(cloned.name,cloned.splitName,cloned.splitAction);				";
		echo "			if (cloned.getClass()=='TimeSheetRemoveColumn') clone = new TimeSheetRemoveColumn(cloned.name,cloned.compareColumnName,cloned.restriction,cloned.removeAction);			";
		echo "			if (cloned.getClass()=='TimeSelectColumn') clone = new TimeSelectColumn(cloned.name,cloned.startHourColumn,cloned.startMinuteColumn,cloned.endHourColumn,cloned.endMinuteColumn,cloned.updateAction); 																															";
		echo "			if (cloned.getClass()=='ChangeDropdownContentColumn') clone = new ChangeDropdownContentColumn(cloned.name,cloned.value,cloned.text,cloned.content,cloned.fromColumn,cloned.validationFunction,cloned.updateAction);																";
		echo "			if (cloned.getClass()=='ChangeableDropdownContentColumn') clone = new ChangeableDropdownContentColumn(cloned.name,cloned.targetColumnName,cloned.content,cloned.filter,cloned.value,cloned.text,cloned.fromColumn,cloned.validationFunction,cloned.updateAction);				";
		echo "			if (cloned.getClass()=='TimeSheetDateColumn') clone = new TimeSheetDateColumn(cloned.name,cloned.fromColumn,cloned.dateFormat,cloned.showDoubles);										";
		echo "																																			";	
		echo "			clones.push(clone);																												";
		echo "		}																																	";
		echo "																																			";
		echo "																																			";
		echo "																																			";
		echo "		return clones;																														";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
	
		//********************************************************************************************************************************************************
		//*** FUNCTION FINALIZESCLONES(CLONES)
		//*** Finalizes clones.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	 	function finalizeClones(clones) {																									";
		echo "																																			";
		echo "			for(var column in clones) {																										";
		echo "				clones[column].finalize(clones);																							";
		echo "			}																																";
		echo "																																			";
		echo "		return clones;																														";
		echo "																																			";
		echo "		}																																	";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CREATESUMROW()
		//*** Creates a new row.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createSumRow(sumType,rowIndex) {																								";
		echo "																																			";
		echo "		var sheet		 = document.getElementById('sheet');																				";
		echo "		var row 		 = sheet.insertRow(rowIndex);																						";
		echo "		var	clones 		 = cloneSumPrototypeColumns(sumType);																				";
		echo "																																			";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			var element = clones[column].createDomElement();																				";
		echo "																																			";
		echo "			if (clones[column].getClass()=='TimeSpanColumn') clones[column].setDisabled(true);												";
		/*
		echo "			if (clones[column].getClass()=='ChangeDropdownContentColumn') {";
		//echo "				 console.log('create chosen');												";
		echo "				 clones[column].chosen();												";
		echo "			}";
		echo "			if (clones[column].getClass()=='ChangeableDropdownContentColumn') {";
		//echo "				 console.log('create chosen2');												";
		echo "				 clones[column].chosen();												";
		echo "			}";
		*/
		echo "																																			";
		echo "			var cell	= row.insertCell();																									";
		echo "			cell.appendChild(element);																										";
		echo "																																			";
		echo "		}																																	";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			clones[column].createEventListeners();																							";
		echo "																																			";
		echo "		}																																	";
		echo "																																			";
		echo "		var timesheetRowIdentifier = new TimeSheetRowIdentifier(rowIndex,sumType);															";
		echo "		clones.push(timesheetRowIdentifier);																								";
		//echo "		dataRows.push(clones);																											";
		echo "		dataRows.splice(row.rowIndex,0,clones);																								";
		echo "		updateIndexes();																													";
		echo "																																			";
		//echo " 		alert(timesheetRowIdentifier.timesheetRowId + ':' + timesheetRowIdentifier.rowType);																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION CLONESUMPROTOTYPECOLUMNS(SUMTYPE)
		//*** Clones sumprototypecolumns by type.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function cloneSumPrototypeColumns(sumType) {																							";
		echo "																																			";
		echo "		var clones = [];																													";
		echo "		var cloned = null;																													";
		echo "																																			";
		echo "		for(var column in prototypeColumns) {																								";
		echo "																																			";
		echo "			cloned = prototypeColumns[column];																								";
		echo "																																			";
		echo "			if (cloned.getClass()=='TimeSpanColumn') { clone = new TimeSpanColumn(cloned.name,sumType); }									";
		echo "			if (cloned.getClass()=='TimeSheetDatepickerColumn') clone = new TimeSheetEmptyColumn(cloned.name);									";
		echo "			if (cloned.getClass()=='TimeSheetInputColumn') { clone = new TimeSpanColumn(cloned.name,sumType);}								";
		echo "			if (cloned.getClass()=='TimeSheetSplitColumn') clone = new TimeSheetEmptyColumn(cloned.name);									";
		echo "			if (cloned.getClass()=='ChangeDropdownContentColumn') clone = new TimeSheetEmptyColumn(cloned.name);							";
		echo "			if (cloned.getClass()=='ChangeableDropdownContentColumn') cclone = new TimeSheetEmptyColumn(cloned.name);						";
		echo "			if (cloned.getClass()=='TimeSheetDateColumn') clone = new TimeSheetEmptyColumn(cloned.name);									";
		echo "																																			";
		echo "			if (cloned.getClass()=='TimeSelectColumn') {																					";
		echo "																																			";
		echo "				if (sumType=='weekly') {														 											";
		echo "					clone = new TimeSheetFixedColumn('Viikko','<b>Viikko yhteensä.</b>');													";
		echo "				}																															";
		echo "																																			";
		echo "				if (sumType=='sheettotal') {																								";
		echo "					clone = new TimeSheetFixedColumn('Lista','<b>Lista yhteensä</b>');														";
		echo "				}																															";
		echo "																																			";
		echo "			}																																";
		echo "																																			";
		echo "			clones.push(clone);																												";
		echo "		}																																	";
		echo "																																			";
		echo "		clones = finalizeSumClones(clones);																									";
		echo "																																			";
		echo "		return clones;																														";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION FINALIZESUMCLONES(CLONES)
		//*** Finalizes sum clones.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	 function finalizeSumClones(clones) {																									";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "			clones[column].finalize(clones);																								";
		echo "		}																																	";
		echo "																																			";
		echo "		return clones;																														";
		echo "																																			";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** FUNCTION DISABLETIMESSHEET()
		//*** Disables TimeSheet
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function TimeSheetDisabled(isDisabled) {																								";
		echo "																																			";
		echo "		for(var dataIndex in dataRows) {																									";
		echo "																																			";
		echo "			dataRow = dataRows[dataIndex];																									";
		echo "																																			";
		echo "			for(var column in dataRow) {																									";
		echo "																																			";
		echo "				dataRow[column].setDisabled(isDisabled);																					";
		echo "																																			";
		echo "			}																																";
		echo "		}																																	";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
		
		
		
//********************************************************************************************************************************************************
//*** GLOBAL STARTER
//*** GLOBAL STARTER
//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	createTableHeader();																													";
		echo "																																			";
		
		$loadedrows = $this->loadedrows;
		
		foreach($loadedrows as $index => $row) {
			echo " var rowvalues = [];																													";	
			$datavariables = $row->getDatavariables();
			foreach($datavariables as $name => $value) {
				echo "	rowvalues['".$name."'] = '".$value."';																							";
			}
			//echo "	debugContent(rowvalues);																										";
			echo "	createLoadedRow(rowvalues,".$index.");																								";
		}
		
		echo "	if (showWeeklyTot==1) createWeeklyTotals();																								";
		echo "	if (showSheetTot==1)  createSumRow('sheettotal');																						";
		echo "																																			";
		echo "	hideDoubleDates();																														";
		echo "																																			";
		echo "																																			";
		echo "																																			";
		echo "</script>																																	";

	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION SHOW()
//*** Creates sheet.
//********************************************************************************************************************************************************
		
	public function show() {

		echo "<table id='sheet'></table>";		
		echo "<table id='buttontable' style='float:right'><tr id='buttonrow'></tr></table>";
	
		$columns = $this->columns;

		$this->initalizeGlobalVariables();
		
		$this->createPrototypes();
			
		$this->createColumns($columns);
		
		$this->main();
		
		$this->createButtons();

		if ($this->isDisabled) 	{
			echo "<script>TimeSheetDisabled(true);</script>";																												
		}
	}	
}




?>