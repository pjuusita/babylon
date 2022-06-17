<?php


//*******************************************************************************************************************************************
//*** TUNTILISTARIVIEN MUOKKAUS
//***
//*******************************************************************************************************************************************

include_once "timesheet.class.php";
include_once "timesheetcolumn.class.php";
include_once "timesheetbutton.class.php";

$projects 	 			 = $this->registry->projects;
$tasks    	  			 = $this->registry->projecttasks;
$shifts   	  			 = $this->registry->projectshifts;
$loadedrows	  			 = $this->registry->loadedrows;

$isDisabled				= false;
$calculationAlgorithm	= "calculateHours";
$databasePrimaryKeyName	= "timesheetrowID";

$dateUpdateAction		= "hr/timesheets/updatetimesheetdate";
$timeUpdateAction		= "hr/timesheets/updatetimesheettime";
$timespanUpdateAction	= "hr/timesheets/updatetimespan";

$projectUpdateAction	= "hr/timesheets/updateproject";
$projectValidation		= "selectedValueNotZero";

$taskUpdateAction		= "hr/timesheets/updatetask";
$taskValidation			= "selectedValueNotZero";

$shiftUpdateAction		= "hr/timesheets/updateshift";
$shiftValidation		= "selectedValueNotZero";

$splitAction			= "hr/timesheets/inserttimesheetrow";
$removeAction			= "hr/timesheets/removetimesheetrow";

$freeInputAction		= "hr/timesheets/updatefreeinput";
$freeInputValidation	= "isValidNumber";

$timesheet 	  			= new TimeSheet($loadedrows,$isDisabled,$calculationAlgorithm,$databasePrimaryKeyName);

$dateColumn				= new TimeSheetDateColumn("Paivamaara","rowdate","dn.d.m");
$dateColumn->setShowDoubles(false);

$timeSelect	   			= new TimeSheetTimeSelectColumn("Tyaaika","starthour","startminute","endhour","endminute",$timeUpdateAction);
$timeMorningShift		= new TimeSheetTimeSpanColumn("Perus<br>tunnit");
$timeEveningShift		= new TimeSheetTimeSpanColumn("Iltatya");
$timeGraveyardShift		= new TimeSheetTimeSpanColumn("Yatya");
$timeSaturdayShift		= new TimeSheetTimeSpanColumn("Lauantaitya");
$timeSundayShift		= new TimeSheetTimeSpanColumn("Sunnuntaitya");

$selectProject			= new TimeSheetChangeDropdownContentColumn("Projekti",$projects,"projectID","name","projectID",$projectUpdateAction);
$selectProject->setValidationFunction($projectValidation);

$selectTask				= new TimeSheetChangeableDropdownContentColumn("Tyatehtava","Projekti",$tasks,"projectID","taskID","taskname","taskID",$taskUpdateAction);
$selectTask->setValidationFunction($taskValidation);

$selectShift			= new TimeSheetChangeableDropdownContentColumn("Vuoro","Projekti",$shifts,"projectID","shiftID","shiftname","shiftID",$shiftUpdateAction);
$selectShift->setValidationFunction($shiftValidation);

$splitColumn			= new TimeSheetSplitColumn("Split","Paivamaara",$splitAction);
$removeColumn			= new TimeSheetRemoveColumn("Remove","Paivamaara","PreserveSingleDates",$removeAction);
$freeInputColumn		= new TimeSheetInputColumn("Vapaasyatta","",$freeInputValidation,$freeInputAction);

$checkOutButton			= new TimeSheetButton("Check out","checkout","CheckOutTimeSheet");
$sendButton				= new TimeSheetButton("Send","send","SendTimeSheet");

$timesheet->addColumn($dateColumn);
$timesheet->addColumn($timeSelect);
$timesheet->addColumn($timeMorningShift);
$timesheet->addColumn($timeEveningShift);
$timesheet->addColumn($timeGraveyardShift);
$timesheet->addColumn($timeSaturdayShift);
$timesheet->addColumn($timeSundayShift);
$timesheet->addColumn($selectProject);
$timesheet->addColumn($selectTask);
$timesheet->addColumn($selectShift);
$timesheet->addColumn($splitColumn);
$timesheet->addColumn($removeColumn);
$timesheet->addColumn($freeInputColumn);

$timesheet->addButton($checkOutButton);
$timesheet->addButton($sendButton);

$timesheet->setShowWeeklyTotal(true);
$timesheet->setShowSheetTotal(true);

$timesheet->show();


?>