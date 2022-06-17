<?php

include_once "timesheet.class.php";
include_once "timesheetcolumn.class.php";
include_once "timesheettestdata.php";




$timesheet = new Timesheet();

//$timeSelect	   			= new TimeSelectColumn("Tyaaika");
//$timeBasichours			= new TimesheetCalculateColumn();
//$aggreementInput 		= new TimeSheetInputField();



// ************************************************************************************
//
//    Timeselect initialization 
//
// ************************************************************************************
$timesheet->addColumn($timeSelect);



// ************************************************************************************
//
//    Perustunnit column
//
// ************************************************************************************
$projectSelector->setCustomCalculateFunction("countperustunnitfunction");
echo "<script>";
echo "		function countperustunnitfunction(rowid) {";
echo "			var agreementID = " . $aggreementInput->getValueJSFunction("rowid") . ";";
echo "			var starttime = " . $timeSelect->getStarttimeJSFunction("rowid") . ";";
echo "			var endtime = " . $timeSelect->getEndtimeJSFunction("rowid") . ";";
echo "			var totalhours = endtime - starttime;";
echo "			if (agreementID == '0') return '';";
foreach($agreements as $index => $agreement) {
	echo "		if (agreementID == '" . $agreement->agreementID . "') {";
	$salaryrange = $agreement->salaryrange[1];
	echo "			perustunnit = calculatetotaltimeinterval(totalhours, " . $salaryrange->starttime . ", " . $salaryrange->endtime . ");";
	echo "			return perustunnit;";
	echo "		}";
}
echo "		}";
echo "</script>";




echo "<script>";
echo "		function getprojecttasks(projectID) {";
echo "			if (projectID = 1) {";
echo "				return array ('1' => 'Varastomies', '2' => 'keraily');";
echo "			}";
echo "			if (projectID = 2) {";
echo "				return array ('6' => 'Muurari', '2' => 'Sahkaasentaja');";
echo "			}";
echo "			if (projectID = 3) {";
echo "				return array ('1' => 'Toimistoapulainen');";
echo "			}";
echo "		}";
echo "</script>";




// ************************************************************************************
//
//    Ylitya-50% column
//
// ************************************************************************************
$projectSelector->setCustomCalculateFunction("countperustunnitfunction");
echo "<script>";
echo "		function countperustunnitfunction(rowid) {";
echo "			var agreementID = " . $aggreementInput->getValueJSFunction("rowid") . ";";
echo "			var starttime = " . $timeSelect->getStarttimeJSFunction("rowid") . ";";
echo "			var endtime = " . $timeSelect->getEndtimeJSFunction("rowid") . ";";
echo "			var totalhours = endtime - starttime;";
echo "			if (agreementID == '0') return '';";
foreach($agreements as $index => $agreement) {
	echo "		if (agreementID == '" . $agreement->agreementID . "') {";
	$salaryrange = $agreement->salaryrange[1];
	echo "			perustunnit = calculatetotaltimeinterval(totalhours, " . $salaryrange->starttime . ", " . $salaryrange->endtime . ");";
	echo "			return perustunnit;";
	echo "		}";
}
echo "		}";
echo "</script>";





//$timeEveningShift		= new TimeSpanColumn("Iltavuoro","Tyaaika",0,100);
//$timeGraveyardShift		= new TimeSpanColumn("Yavuoro","Tyaaika",0,100);





// ************************************************************************************
//
//    Project selector
//
// ************************************************************************************
//$projectSelector		= new TimeSheetSelector();
$projectSelector->setSelection($projects);
$projectSelector->setCustomOnChangeFunction("projectchanged");
echo "<script>";
echo "		function projectchanged(rowid) {";
echo "			var projectID = " . $projectSelector->getValueJSFunction("rowid") . ";";
echo "			var tasklist = {};";
echo "			var shiftlist = {};";
foreach($projects as $index => $project) {
	echo "		if (projectID == " . $project->projectID . ") {";
	foreach($project->tasks as $index => $taskID) {
		$task = $tasks[$taskID];
		echo "		tasklist[] = '" . $task->name . "';";
	}
	foreach($project->shifts as $index => $shiftID) {
		$shift = $shifts[$shiftID];
		echo "		shiftlist[] = '" . $shift->name . "';";
	}
	echo "		}";	
}
echo "</script>";


// tasklist ja shiftlist sisaltavat keyvaluepair-arrayn, tama pitaa asettaa shift selectorin arvoksi
echo "			" . $taskSelector->updateSelectionJavascriptFunctionCall("rowid", "tasklist") . ";";
echo "			" . $shiftSelector->updateSelectionJavascriptFunctionCall("rowid", "shiftlist") . ";";
echo "		}";
$timesheet->addColumn($projectSelector);


// ************************************************************************************
//
//    Task selector
//
// ************************************************************************************
//$taskSelector = new TimeSheetSelector();
$timesheet->addColumn($taskSelector);

// ************************************************************************************
//
//    Shift selector
//
// ************************************************************************************
//$shiftSelector = new TimeSheetSelector();
$shiftSelector->setCustomOnChangeFunction("shiftchanged");
echo "<script>";
echo "		function shiftchanged(rowid) {";
echo "			var shiftID = " . $shiftSelector->getValueJSFunction("rowid") . ";";
echo "			var agreementID = 0;";
foreach($shifts as $index => $shift) {
	echo "		if (shiftID == '" . $shift->shiftID . "') aggreementID = " . $shift->agreementID . ";";
}
echo "			" . $aggreementInput->updateValueJavascriptFunctionCall("rowid", "aggreementID");
echo "		}";
echo "</script>";
$timesheet->addColumn($shiftSelector);


// ************************************************************************************
//
//    Labor Agreement Inputfield
//
// ************************************************************************************
$aggreementInput->setEditable(false);
$timesheet->addColumn($aggreementInput);


$timesheet->show();




?>