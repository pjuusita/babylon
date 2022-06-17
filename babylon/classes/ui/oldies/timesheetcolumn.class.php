<?php

abstract class TimesheetColumn {
	
	abstract public function initalize();
	abstract public function getValue();
	abstract public function getClass();
	abstract public function getName();
	
}

//********************************************************************************************************************************************************
//*** CLASS TIMESHEETINPUTCOLUMN
//*** Creates TimeSpanColumn.
//********************************************************************************************************************************************************

class TimeSheetInputColumn extends TimesheetColumn {

	private $name;
	private $validationFunction;
	private $updateAction;
	private $dataVariable;
	
	public function __construct($name,$dataVariable,$validationFunction,$updateAction) {

		$this->name 	 	 		= $name;
		$this->dataVariable			= $dataVariable;
		$this->validationFunction 	= $validationFunction;
		$this->updateAction			= $updateAction;
	}

	public function initalize() {
	}

	public function getValue() {
	}

	public function getClass() {
		return 'TimeSheetInputColumn';
	}

	public function getName() {
		return $this->name;
	}

	public function getText() {
		return $this->Text;
	}
	
	public function getUpdateAction() {
		return $this->updateAction;
	}
	
	public function getValidationFunction() {
		return $this->validationFunction;
	}

	public function getDataVariable() {
		
		return $this->dataVariable;
	}
}





//********************************************************************************************************************************************************
//*** CLASS TIMESHEETINPUTCOLUMN
//*** Creates TimeSpanColumn.
//********************************************************************************************************************************************************

class TimeSheetDatepickerColumn extends TimesheetColumn {

	private $name;
	private $validationFunction;
	private $updateAction;
	private $dataVariable;

	public function __construct($name,$dataVariable,$validationFunction,$updateAction) {

		$this->name 	 	 		= $name;
		$this->dataVariable			= $dataVariable;
		$this->validationFunction 	= $validationFunction;
		$this->updateAction			= $updateAction;
	}

	public function initalize() {
	}

	public function getValue() {
	}

	public function getClass() {
		return 'TimeSheetDatepickerColumn';
	}

	public function getName() {
		return $this->name;
	}

	public function getText() {
		return $this->Text;
	}

	public function getUpdateAction() {
		return $this->updateAction;
	}

	public function getValidationFunction() {
		return $this->validationFunction;
	}

	public function getDataVariable() {

		return $this->dataVariable;
	}
}

//********************************************************************************************************************************************************
//*** CLASS TIMESHEETREMOVECOLUMN
//*** Creates TimeSpanColumn.
//********************************************************************************************************************************************************

class TimeSheetFixedColumn extends TimesheetColumn {

	private $name;
	private $text;
	
	public function __construct($name,$text) {

		$this->name 	 	 	     = $name;
		$this->text 	 	 	     = $text;
		
	}

	public function initalize() {
	}

	public function getValue() {
	}

	public function getClass() {
		return 'TimeSheetFixedColumn';
	}

	public function getName() {
		return $this->name;
	}
	
	public function getText() {
		return $this->Text;
	}
	
}

//********************************************************************************************************************************************************
//*** CLASS TIMESHEETREMOVECOLUMN
//*** Creates TimesheetRemoveColumn.
//********************************************************************************************************************************************************

class TimeSheetRemoveColumn extends TimesheetColumn {

	private $name;
	private $compareColumn;
	private $restrictionFunction;
	private $removeAction;
	
	public function __construct($name,$compareColumn,$restrictionFunction,$removeAction) {
		
		$this->name 	 	 	     = $name;
		$this->compareColumn 		 = $compareColumn;
		$this->restrictionFunction	 = $restrictionFunction;
		$this->removeAction			 = $removeAction;
	}

	public function initalize() {
	}

	public function getValue() {
	}

	public function getClass() {
		return 'TimeSheetRemoveColumn';
	}

	public function getName() {
		return $this->name;
	}
	
	public function getCompareColumn() {
		return $this->compareColumn;
	}
	
	public function getRestrictionFunction() {
		return $this->restrictionFunction;
	}
	
	public function getRemoveAction() {
		return $this->removeAction;
	}
}


//********************************************************************************************************************************************************
//*** CLASS TIMESHEETSPLITCOLUMN
//*** Creates TimeSpanColumn. Splitname indicates column from where value is copied to new splitted row.
//********************************************************************************************************************************************************

class TimeSheetSplitColumn extends TimesheetColumn {

	private $name;
	private $splitName;
	private	$splitAction;
	
	public function __construct($name,$splitName,$splitAction) {
		
		$this->name 	  	= $name;
		$this->splitName	= $splitName;
		$this->splitAction 	= $splitAction;
	}

	public function initalize() {
	}

	public function getValue() {
	}

	public function getClass() {
		return 'TimeSheetSplitColumn';
	}

	public function getName() {
		return $this->name;
	}
	
	public function getSplitAction() {
		return $this->splitAction;
	}
	
	public function getSplitName() {
		return $this->splitName;
	}
}

//********************************************************************************************************************************************************
//*** CLASS DATECOLUMN
//*** Creates TimesheetDateColumn.
//********************************************************************************************************************************************************

class TimeSheetDateColumn extends TimesheetColumn {
	
	private $name;
	private $fromColumn;
	private $dateFormat;
	private $showDoubles = false;
	
	public function __construct($name,$fromColumn,$dateFormat) {
		
		$this->name 	  = $name;
		$this->fromColumn = $fromColumn;
		$this->dateFormat = $dateFormat;
	}

	public function initalize() {
	
	}
	
	public function getValue() {
		
	}
	
	public function getClass() {
		return 'TimeSheetDateColumn';
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getDateFormat() {
		return $this->dateFormat;
	}
	
	public function getFromColumn() {
		return $this->fromColumn;
	}
	
	public function setShowDoubles($isShown) {
		$this->showDoubles = $isShown;
	}
	
	public function getShowDoubles() {
		return $this->showDoubles;
	}
}

//********************************************************************************************************************************************************
//*** CLASS TIMESPANCOLUMN
//*** Creates TimeSpanColumn.
//********************************************************************************************************************************************************

class TimeSheetTimespanColumn extends TimesheetColumn {
	
	private $name;
	private $updateParameters;
	
	public function __construct($name) {
		
		$this->name = $name;
		
	}
	
	public function initalize()  {
		
	}
	
	public function getValue() {
		
	}

	public function getName() {
		return $this->name;
	}
	
	public function getUpdateParameters() {
		return $this->updateParameters;
	}
	
	public function getClass() {
		return "TimeSpanColumn";
	}
	
}

//********************************************************************************************************************************************************
//*** CLASS TIMESELECTCOLUMN
//*** Creates TimeSelectColumn.
//********************************************************************************************************************************************************

class TimeSheetTimeSelectColumn extends TimesheetColumn {

	private $name;
	private $updateAction;
	private $startHourColumn;
	private $startMinuteColumn;
	private $endHourColumn;
	private $endMinuteColumn;
	
	public function __construct($name,$startHourColumn,$startMinuteColumn,$endHourColumn,$endMinuteColumn,$updateAction) {
		
		$this->name 			= $name;
		$this->updateAction 	= $updateAction;
		
		$this->startHourColumn 	 = $startHourColumn;
		$this->startMinuteColumn = $startMinuteColumn;
		$this->endHourColumn	 = $endHourColumn;
		$this->endMinuteColumn 	 = $endMinuteColumn;
		
	}

	public function initalize()  {

	}

	public function getValue() {

	}

	public function getStartHourColumn() {
		return $this->startHourColumn;
	}
	
	public function getStartMinuteColumn() {
		return $this->startMinuteColumn;
	}
	 
	public function getEndHourColumn() {
		return $this->endHourColumn;
	}
	 
	public function getEndMinuteColumn() {
		return $this->endMinuteColumn;
	}
	
	public function getUpdateAction() {
		return $this->updateAction;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getClass() {
		return "TimeSelectColumn";
	}

}

//********************************************************************************************************************************************************
//*** CLASS CHANGEDROPDOWNCONTENTCOLUMN
//***
//*** Creates ChangeDropdownContentColumn.
//***
//*** $value = datavariable for dropdown value.
//*** $text  = datavariable for dropdown text.
//********************************************************************************************************************************************************

class TimeSheetChangeDropdownContentColumn extends TimesheetColumn {

	private $name;
	private $content;
	private $value;
	private $text;
	private $fromColumn;
	private $updateAction;
	private $validationFunction;
	private $predictive;
	
	
	public function __construct($name,$content,$value,$text,$fromColumn,$updateAction) {

		$this->name 			 = $name;
		$this->content			 = $content;	
		$this->value			 = $value;
		$this->text				 = $text;
		$this->fromColumn		 = $fromColumn;
		$this->updateAction		 = $updateAction;
	}

	public function initalize()  {

	}
	
	
	public function setPredictive($predictive) {
		$this->predictive	= $predictive;
	}
	
	
	public function getClass() {
		return "ChangeDropdownContentColumn";
	}

	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}
	
	public function getPredictive() {
		return $this->predictive;
	}
	
	
	public function getText() {
		return $this->text;
	} 
	
	public function getContent() {
		return $this->content;
	}
	
	public function getFromColumn() {
		return $this->fromColumn;
	}
	
	public function getUpdateAction() {
		return $this->updateAction;
	}
	
	public function setValidationFunction($validationFunction) {
		$this->validationFunction = $validationFunction;
	}
	
	public function getValidationFunction() {
		return $this->validationFunction;
	}
}

//********************************************************************************************************************************************************
//*** CLASS CHANGEABLEDROPDOWNCONTENTCOLUMN
//***
//*** Creates ChangeableDropdownContentColumn.
//***
//*** $filter = datavariable for filtering content.
//*** $value  = datavariable for dropdown value.
//*** $text   = datavariable for dropdown text.
//********************************************************************************************************************************************************

class TimeSheetChangeableDropdownContentColumn extends TimesheetColumn {

	private $name;
	private $value;
	private $filter;
	private $text;
	private $targetColumnName;
	private $fromColumn;
	private $updateAction;
	private $validationFunction = "";
	
	public function __construct($name,$targetColumnName,$content,$filter,$value,$text,$fromColumn,$updateAction) {

		$this->name			 	 = $name;
		$this->filter			 = $filter;
		$this->value			 = $value;
		$this->text				 = $text;
		$this->targetColumnName  = $targetColumnName;
		$this->content			 = $content;
		$this->fromColumn		 = $fromColumn;
		$this->updateAction		 = $updateAction;
		
	}

	public function initalize()  {

	}

	public function getValue() {
		return $this->value;
	}
	
	public function getText() {
		return $this->text;
	}
	
	public function getFilter() {
		return $this->filter;
	}

	public function getClass() {
		return "ChangeableDropdownContentColumn";
	}

	public function getName() {
		return $this->name;
	}

	public function getTargetColumnName() {
		return $this->targetColumnName;
	}
	
	public function getContent() {
		return $this->content;
	}
	
	public function getFromColumn() {
		return $this->fromColumn;
	}
	
	public function getUpdateAction() {
		return $this->updateAction;
	}
	
	public function setValidationFunction($validationFunction) {
		$this->validationFunction = $validationFunction;
	}
	
	public function getValidationFunction() {
		return $this->validationFunction;
	}
	
}



?>