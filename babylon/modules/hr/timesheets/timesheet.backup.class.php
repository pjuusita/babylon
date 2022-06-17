<?php


class Timesheet {
	
	private $columns = null;
	
	public function __construct() {
	
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
							
			}
		}
	}
	
//********************************************************************************************************************************************************
//***  PRIVATE FUNCTION CREATETIMESPANCOLUMN($COLUMN)
//***  Creates TimeSpanColumn-object from column.
//********************************************************************************************************************************************************

	private function createTimeSpanColumn($column) {
		
	$name 				= $column->getName();
	$selectColumnName 	= $column->getSelectColumnName();
	$start			  	= $column->getStart();
	$end			  	= $column->getEnd();
	
		echo "<script>																																	";
		echo "																																			";
		echo "	var column = new TimeSpanColumn('".$name."','".$selectColumnName."',".$start.",".$end.");												";
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
		
		echo "<script>																																	";
		echo "																																			";
		echo "	var column = new TimeSelectColumn('".$name."');																							";
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
			
		foreach($content as $index => $row) {
		
			$datavariables = $row->getDataVariables();
		
			echo "		var data 	= [];																												";
		
			foreach($datavariables as $varname => $varvalue) {
					
			echo "	data['".$varname."'] = '".$varvalue."'; 																							";
			
			}
		
			echo "	content.push(new ChangeContentRow(data));																							";	
		}
		
		echo " return content;																															";
		echo "																																			";
		echo "	}																																		";
		echo "</script>																																	";
		
		//********************************************************************************************************************************************************
		//*** MINIMAIN
		//*** Creates Javascript-object from column and pushes it to prototypeColumns.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo " 	var content = [];																														";
		echo "	content = createChangeContentRows();																									";
		echo "	var column = new ChangeDropdownContentColumn('".$name."','".$value."','".$text."',content);																		";
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
			
		foreach($content as $index => $row) {
		
			$datavariables = $row->getDataVariables();
		
		echo "		var data 	= [];																													";
				
			foreach($datavariables as $varname => $varvalue) {
			
		echo "	data['".$varname."'] = '".$varvalue."'; 																										";
		
			}
		
		echo "	content.push(new ContentRow(data));																										";
			
		}
		
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
		echo "	var column = new ChangeableDropdownContentColumn('".$name."','".$target."',content,'".$filter."','".$value."','".$text."');				";
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
//***
//***
//***
//***	
//***
//********************************************************************************************************************************************************

	public function initalizeGlobalVariables() {
	
		echo "<script>																																	";
		echo "																																			";
		echo "	var prototypeColumns = [];																												";
		echo "																																			";
		echo "</script>																																	";
	
	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION CREATEPROTOTYPES()
//*** Creates Javascripts for prototypes.
//********************************************************************************************************************************************************

	public function createPrototypes() {
	
	//********************************************************************************************************************************************************
	//*** CLASS TIMESPANCOLUMN(NAME,SELECTCOLUMNNAME,START,END)
	//*** Implements TimeSpanColumn-class
	//********************************************************************************************************************************************************
		
	/*echo "<script>																																	";
	echo "																																			";
	echo "	function TimeSpanColumn(name,selectColumnName,start,end) {																				";
	echo "																																			";
	echo "		this.name 			  = name;																										";
	echo "		this.selectColumnName = selectColumnName;																							";
	echo "		this.selectColumn	  = null;																										";
	echo "		this.start			  = start;																										";
	echo "		this.end			  = end;																										";
	echo "		this.domElement		  = null;																										";
	echo "																																			";
	echo " 	this.getName  = function() { 																											";
	echo "		return this.name																													";
	echo "	} ;																																		";
	echo "																																			";
	echo "	this.getValue = function() { return 'new value';};																						";
	echo "																																			";
	echo "	this.finalize = function(columns) {																										";
	echo "																																			";
	echo "		var selectColumnName = this.selectColumnName;																						";
	echo "																																			";
	echo "			for(var column in columns) {																									";
	echo "																																			";
	echo "				if (columns[column].getName()==selectColumnName) {																			";
	echo "					this.selectColumn = columns[column];																					";
	echo "				}																															";
	echo "			}																																";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.createEventListeners = function() {																								";
	echo "																																			";
	echo "		var selectColumn  = this.selectColumn;																								";
	echo "		var sourceElement = selectColumn.getDomElement();																					";
	echo "																																			";
	echo "		var targetElement = this.getDomElement();																							";
	echo "		var target		  = this;																											";																																			
	echo "																																			";
	echo "		sourceElement.addEventListener('change',function(event) { targetElement.value = selectColumn.getValue(); },true);					";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.createDomElement = function() {																									";
	echo "																																			";
	echo "		this.domElement = createTimeSpanElement(this);	 																					";
	echo "		return this.domElement;																												";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getStart = function() {																											";
	echo "		return this.start;																													";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getEnd = function() {																												";
	echo "		return this.end;																													";
	echo "	};																																		";
	echo "																																			";
	echo "																																			";
	echo "	this.getDomElement = function() {																										";
	echo "		return this.domElement;																												";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getClass = function() {																											";
	echo "		return 'TimeSpanColumn';																											";
	echo "	};																																		";
	echo "																																			";
	echo "	}																																		";
	echo "</script>																																	";*/
	
	//********************************************************************************************************************************************************
	//*** CLASS TIMESELECTCOLUMN(NAME)
	//*** Implements TimeSelectColumn-class
	//********************************************************************************************************************************************************

	/*echo "<script>																																";
	echo "																																			";
	echo "	function TimeSelectColumn(name) {																										";
	echo "																																			";
	echo "		this.name 		= name;																												";
	echo "		this.domElement = null;																												";
	echo "																																			";
	echo "																																			";
	echo "	this.createDomElement = function() {																									";
	echo "																																			";
	echo "		this.domElement = createTimeSelectElement(this);																					";
	echo "		return this.domElement;																												";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getName = function() {																												";
	echo "		return this.name;																													";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getValue = function() {																											";
	echo "																																			";
	echo "		var starthours 	 = this.domElement.childNodes[0].childNodes[0].childNodes[0].childNodes[0].value;									";
	echo "		var startminutes = this.domElement.childNodes[0].childNodes[0].childNodes[1].childNodes[0].value;									";
	echo "		var endhours     = this.domElement.childNodes[0].childNodes[0].childNodes[2].childNodes[0].value;									";
	echo "		var endminutes   = this.domElement.childNodes[0].childNodes[0].childNodes[3].childNodes[0].value;									";
	echo "																																			";
	echo "		var value = starthours + ':' + startminutes + '->' + endhours + ':' + endminutes;													";
	echo "																																			";
	echo "		return value;																														";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.finalize = function(columns) {																										";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.createEventListeners = function() {																								";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getDomElement = function() {																										";
	echo "		return this.domElement;																												";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getClass = function() {																											";
	echo "		return 'TimeSelectColumn';																											";
	echo "	};																																		";
	echo "	}																																		";
	echo "</script>																																	";*/

//********************************************************************************************************************************************************
//*** CLASS CHANGEDROPDOWNCONTENTCOLUMN(NAME,CONTENT)
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
	
	/*echo "<script>																																	";
	echo "																																			";
	echo "	function ChangeDropdownContentColumn(name,value,text,content) {																			";
	echo "																																			";
	echo "		this.name 			   = name;																										";
	echo "		this.content 		   = content;																									";
	echo "		this.value			   = value;																										";
	echo "		this.text			   = text;																										";
	echo "																																			";
	echo "	this.createDomElement = function() {																									";
	echo "																																			";
	echo "		this.domElement = createChangeDropdownContentElement(this);																			";
	echo "		return this.domElement;																												";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getName = function() {																												";
	echo "		return this.name;																													";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getValue = function() {																											";
	echo "		return 0;																															";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getTargetColumns = function() {																											";
	echo "		return this.targetColumns;																															";
	echo "	};																																		";
	echo "																																			";
	echo "	this.finalize = function(columns) {																										";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.createEventListeners = function() {																								";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getDomElement = function() {																										";
	echo "		return this.domElement;																												";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getClass = function() {																											";
	echo "		return 'ChangeDropdownContentColumn';																								";
	echo "	};																																		";
	echo "																																			";
	echo "	}																																		";
	echo "</script>																																	";
	
//********************************************************************************************************************************************************
//*** CLASS CHANGEABLEDROPDOWNCONTENTCOLUMN(NAME)
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function ChangeableDropdownContentColumn(name,targetColumnName,content,filter,value,text) {												";
	echo "																																			";
	echo "		this.name 	 			= name;																										";
	echo "		this.targetColumnName 	= targetColumnName;																							";
	echo "		this.targetColumn		= null;																										";
	echo "		this.content 			= content;																									";
	echo "		this.filter				= filter;																									";
	echo "		this.value				= value;																									";
	echo "		this.text				= text;																										";
	echo "																																			";
	echo "	this.createDomElement = function() {																									";
	echo "																																			";
	echo "		this.domElement = createChangeableDropdownContentElement(this);																		";
	echo "		return this.domElement;																												";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getName = function() {																												";
	echo "		return this.name;																													";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getValue = function() {																											";
	echo "		return 0;																															";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getContent = function() {																											";
	echo "		return this.content;																												";
	echo "	};																																		";
	echo "																																			";
	echo "	this.finalize = function(columns) {																										";
	echo "																																			";
	echo "		var targetColumnName = this.targetColumnName;																						";
	echo "																																			";
	echo "		for(var column in columns) {																										";
	echo "																																			";
	echo "			if (columns[column].getName()==targetColumnName) {																				";
	echo "																																			";
	echo "				this.targetColumn = columns[column];																						";
	echo "			}																																";
	echo "		}																																	";
	echo "																																			";
	echo "	};																																		";
	echo "																																			";
	echo "	this.createEventListeners = function() {																								";
	echo "																																			";
	echo "			var targetColumn 	= this.targetColumn;																						";
	echo "			var targetElement	= targetColumn.getDomElement();																				";
	echo "			var source		 	= this;																										";
	echo "			targetElement.addEventListener('change',function(event) {																		";
	echo "																																			";
	echo "				var confirmID = this.value;																									";
	echo "				source.createNewValues(confirmID);																							";
	echo "																																			";
	echo "			},true);																														";
	echo "	};																																		";
	echo "																																			";
	echo "	this.createNewValues = function(confirmID) {																							";
	echo "																																			";
	echo "		var content = this.content;																											";
	echo "		var select  = this.getDomElement();																									";
	echo "		var filter  = this.filter;																											";
	echo "		var value   = this.value;																											";
	echo "		var text  	= this.text;																											";
	echo "		var item	= null;																													";
	echo "																																			";
	echo "		while(select.firstChild) {	select.removeChild(select.firstChild); }																";
	echo "																																			";
	echo "		item 		= document.createElement('option');																						";
	echo "		item.text	= 'Ei valittu';																											";
	echo "		item.value	= 0;																													";
	echo "		select.add(item);																													";
	echo "																																			";
	echo "			for(var index in content) {																										";
	echo "																																			";
	echo "				var contentItem = content[index];																							";
	echo "				if (confirmID==contentItem.data[filter]) {																					";
	echo "																																			";
	echo "					item   = document.createElement('option');																				";
	echo "					item.text  = contentItem.data[text];																					";
	echo "					item.value = contentItem.data[value];																					";
	echo "					select.add(item);																										";
	echo "																																			";
	echo "				}																															";
	echo "			}																																";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getDomElement = function() {																										";
	echo "		return this.domElement;																												";
	echo "	};																																		";
	echo "																																			";
	echo "	this.getClass = function() {																											";
	echo "		return 'ChangeableDropdownContentColumn';																							";
	echo "	};																																		";
	echo "																																			";
	echo "	}																																		";
	echo "</script>																																	";*/
	
/*//********************************************************************************************************************************************************
//*** FUNCTION CREATETIMESPANELEMENT(COLUMN) 
//*** Creates DOM-element for TimeSpanColumn-class
//********************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function createTimeSpanElement(column) {																								";
	echo "																																			";
	echo "		var input 	= document.createElement('input');																						";
	echo "		input.size  = 8;																													";
	echo "																																			";
	echo "		return input;																														"; 
	echo "	 }																																		";
	echo "																																			";
	echo "</script>																																	";
*/	
//********************************************************************************************************************************************************
//*** FUNCTION CREATETIMESELECTELEMENT()
//*** Creates DOM-element for TimeSelectColumn-class.
//********************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function createTimeSelectElement(column) {																								";
	echo "																																			";
	echo "		var selectTable		  = document.createElement('table'); 																			";
	echo "		var row				  = selectTable.insertRow();																					";
	echo "		var cell		      = null;																										";
	echo "																																			";
	echo "		var dropdownStartHours   = document.createElement('select');																		";
	echo "		var dropdownStartMinutes = document.createElement('select');																		";
	echo "		var dropdownEndHours 	 = document.createElement('select');																		";
	echo "		var dropdownEndMinutes   = document.createElement('select');																		";
	echo "																																			";
	echo "		createOptionHours(dropdownStartHours);																								";
	echo "		createOptionMinutes(dropdownStartMinutes);																							";
	echo "		createOptionHours(dropdownEndHours);																								";
	echo "		createOptionMinutes(dropdownEndMinutes);																							";
	echo "																																			";
	echo "		cell = row.insertCell();																											";
	echo "		cell.appendChild(dropdownStartHours);																								";
	echo "																																			";
	echo "		cell = row.insertCell();																											";
	echo "		cell.appendChild(dropdownStartMinutes);																								";
	echo "																																			";
	echo "		cell = row.insertCell();																											";
	echo "		cell.appendChild(dropdownEndHours);																									";
	echo "																																			";
	echo "		cell = row.insertCell();																											";
	echo "		cell.appendChild(dropdownEndMinutes);																								";
	echo "																																			";
	echo "		return selectTable;																													";
	echo "																																			";
	echo "	 }																																		";
	echo "																																			";
	echo "</script>																																	";
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function createOptionHours(hourSelect) {																								";
	echo "																																			";
	echo "		var hourOption;																														";
	echo "		var hourString;																														";
	echo "		var stringLen;																														";
	echo "																																			";
	echo "		for(hour=0;hour<24;hour++) {																										";
	echo "																																			";
	echo "			hourString 		= hour.toString();																								";
	echo "			stringLen		= hourString.length;																							";
	echo "																																			";
	echo "			if (stringLen==1) hourString = '0' + hourString;																				";
	echo "																																			";
	echo "			hourOption 		= document.createElement('option');																				";
	echo "			hourOption.text = hourString;																									";
	echo "			hourOption.value = hour;																										";
	echo "																																			";
	echo "			hourSelect.add(hourOption);																										";
	echo "																																			";
	echo "		}																																	";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function createOptionMinutes(minuteSelect) {																							";
	echo "																																			";
	echo "		var minuteOption;																													";
	echo "		var minuteString;																													";
	echo "		var stringLen;																														";
	echo "																																			";
	echo "		for(minute=0;minute<60;minute++) {																									";
	echo "																																			";
	echo "			minuteString 		= minute.toString();																						";
	echo "			stringLen			= minuteString.length;																						";
	echo "																																			";
	echo "			if (stringLen==1) minuteString = '0' + minuteString;																			";
	echo "																																			";
	echo "			minuteOption 	  	= document.createElement('option');																			";
	echo "			minuteOption.text  	= minuteString;																								";
	echo "			minuteOption.value 	= minuteString;																								";
	echo "																																			";
	echo "			minuteSelect.add(minuteOption);																									";
	echo "																																			";
	echo "		}																																	";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";

//********************************************************************************************************************************************************
//*** FUNCTION CREATECHANGEDROPDOWNCONTENTELEMENT(CHANGEDROPDOWN)
//*** Creates DOM-element for ChangeDropdownContentColumn-class.
//********************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function createChangeDropdownContentElement(changeDropdown) {																			";
	echo "																																			";
	echo "		var content = changeDropdown.content;																								";
	echo "		var select  = document.createElement('select');																						";
	echo "		var item	= null;																													";
	echo "		var value	= changeDropdown.value;																									";
	echo "		var text	= changeDropdown.text;																									";
	echo "																																			";
	echo "		item 		= document.createElement('option');																						";
	echo "		item.text	= 'Ei valittu';																											";
	echo "		item.value	= 0;																													";
	echo "		select.add(item);																													";
	echo "																																			";
	echo "		for(var index in content) {																											";
	echo "																																			";
	echo "			var contentItem = content[index];																								";
	echo "																																			";
	echo "			item = document.createElement('option');																						";
	echo "			item.text = contentItem.data[text];																								";
	echo "			item.value = contentItem.data[value];																							";
	echo "																																			";
	echo "			select.add(item);																												";
	echo "																																			";
	echo "		}																																	";
	echo "																																			";
	echo "		return select;																														";
	echo "																																			";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
//********************************************************************************************************************************************************
//*** FUNCTION CREATECHANGEABLEDROPDOWNCONTENTELEMENT(CHANGEDROPDOWN)
//*** Creates DOM-element for ChangeDropdownContentColumn-class.
//********************************************************************************************************************************************************
	
	echo "<script>																																	";
	echo "																																			";
	echo "	function createChangeableDropdownContentElement(changeDropdown) {																		";
	echo "																																			";
	echo "		var select  = document.createElement('select');																						";
	echo "		var item	= document.createElement('option');																						";
	echo "																																			";
	echo "		item.text 	= 'Ei valittu';																											";
	echo "		item.value 	= 0;																													";
	echo "																																			";
	echo "		select.add(item);																													";
	echo "		select.id 	= changeDropdown.getName();																								";
	echo "																																			";
	echo "		return select;																														";
	echo "																																			";
	echo "	}																																		";
	echo "																																			";
	echo "</script>																																	";
	
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
		//*** FUNCTION CREATEROW()
		//*** Creates a new row.
		//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	function createRow() {																													";
		echo "																																			";
		echo "		var sheet		 = document.getElementById('sheet');																				";
		echo "		var row 		 = sheet.insertRow();																								";
		echo "		var	clones 		 = clonePrototypeColumns();																							";
		echo "																																			";
		echo "		for(var column in clones) {																											";
		echo "																																			";
		echo "			var element = clones[column].createDomElement();																				";
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
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
	
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
		echo "			if (cloned.getClass()=='TimeSpanColumn') clone = new TimeSpanColumn(cloned.name,cloned.selectColumnName,cloned.start,cloned.end);																				";
		echo "			if (cloned.getClass()=='TimeSelectColumn') clone = new TimeSelectColumn(cloned.name); 																															";
		echo "			if (cloned.getClass()=='ChangeDropdownContentColumn') clone = new ChangeDropdownContentColumn(cloned.name,cloned.value,cloned.text,cloned.content);																";
		echo "			if (cloned.getClass()=='ChangeableDropdownContentColumn') clone = new ChangeableDropdownContentColumn(cloned.name,cloned.targetColumnName,cloned.content,cloned.filter,cloned.value,cloned.text);				";
		echo "																																			";	
		echo "			clones.push(clone);																												";
		echo "		}																																	";
		echo "																																			";
		echo "		clones = finalizeClones(clones);																									";
		echo "																																			";
		echo "		return clones;																														";
		echo "	}																																		";
		echo "																																			";
		echo "</script>																																	";
		
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
//*** GLOBAL STARTER
//*** GLOBAL STARTER
//********************************************************************************************************************************************************
		
		echo "<script>																																	";
		echo "																																			";
		echo "	createTableHeader();																													";
		echo "																																			";
		echo "	createRow();																															";
		echo "	createRow();																															";
		echo "	createRow();																															";
		echo "	createRow();																															";
		echo "	createRow();																															";
		echo "																																			";
		echo "</script>																																	";

	}
	
//********************************************************************************************************************************************************
//*** PUBLIC FUNCTION SHOW()
//*** Creates sheet.
//********************************************************************************************************************************************************
		
	public function show() {

		echo "<table id='sheet'>";
		echo "</table>";
			
		$columns = $this->columns;

		$this->initalizeGlobalVariables();
		
		$this->createPrototypes();
			
		$this->createColumns($columns);
		
		$this->main();
		
	}	
}




?>