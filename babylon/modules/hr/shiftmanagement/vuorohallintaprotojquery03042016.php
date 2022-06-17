<?php

//********************************************************************************************************************************************************************
//** INCLUDES
//** Including external files.
//********************************************************************************************************************************************************************

$path = SITE_PATH  . 'modules/utils/colors.php';

include $path;

echo "<style>";
include 'shiftmanagement.css';
echo "</style>";

//********************************************************************************************************************************************************************
//**  VARIABLES
//**  Assigning necessary variables.
//********************************************************************************************************************************************************************

$employees			= $this->registry->employees;
$tasks	   			= $this->registry->tasks;
$projects  			= $this->registry->projects;
$shifts	  			= $this->registry->shifts;
$allshifts			= $this->registry->allshifts;
$colors	   			= $this->registry->colors;
$startDate			= $this->registry->startDate;
$days				= $this->registry->days;

$groupBy			= $this->registry->groupBy;
$colorBy 			= $this->registry->colorBy;

if ($days>7) $daypadding  = $days;
if ($days<=7) $daypadding = 7;

generateColorJSFunctions($colors);

$shiftManagementController 	= new UIShiftManagementController("hr/shiftmanagement/updateshifttables","hr/shiftmanagement/printshifttables",$groupBy,$colorBy,$startDate,$days,$employees,$tasks,$projects);

$shiftOverview				= new UIShiftOverview($startDate,$days,$daypadding,$employees,$tasks,$projects,$allshifts,150,1100);

$timeStep  					= 15;

//********************************************************************************************************************************************************************
//** GROUPING BY PROJECTS
//** Generates components by projects
//********************************************************************************************************************************************************************

if ($groupBy=='project') {
	
	if ($colorBy=='employee') $isPrimary = 'primary';
	if ($colorBy!='employee') $isPrimary = 'secondary';
	
	$employeeSelection = new UIFloatingSelection('Employees','name',$employees,'employee',550,1500,$isPrimary);
	
	if ($colorBy=='task') $isPrimary = 'primary';
	if ($colorBy!='task') $isPrimary = 'secondary';
	
	$taskSelection	  = new UIFloatingSelection('Tasks','name',$tasks,'task',550,1300,$isPrimary);

	foreach($projects as $index => $project) {

		$currentShifts 	   = createDataByProjects($employees,$tasks,$projects,$shifts,$project->projectID);
		$id				   = $project->projectID;
		$colorIndex 	   = $project->color;
		
		$shiftSection	   = new UIShiftTable($project->name,$id,$startDate,$days,$timeStep,"hr/shiftmanagement/reserveShiftMarkerRow","hr/shiftmanagement/updateShiftMarkerRow","hr/shiftmanagement/deleteShiftMarkerRow",$groupBy,$colorBy,$colorIndex);
	
		$shiftSection->setExistingEmployeeData($employees);
		$shiftSection->setExistingTaskData($tasks);
		$shiftSection->setExistingProjectData($projects);
		$shiftSection->setExistingShiftMarkerData($currentShifts);
		$shiftSection->setPrimaryData($project,"projectID",$colorIndex);
		$shiftSection->createExistingShiftMarkers();
	
	}
}

//********************************************************************************************************************************************************************
//**  GROUPING BY EMPLOYEES
//**  Generates components by employees.
//********************************************************************************************************************************************************************

if ($groupBy=='employee') {
	
	if ($colorBy=='task') $isPrimary = 'primary';
	if ($colorBy!='task') $isPrimary = 'secondary';
	
	$taskSelection	   = new UIFloatingSelection('Tasks','name',$tasks,'task',550,1300,$isPrimary);
	
	if ($colorBy=='project') $isPrimary = 'primary';
	if ($colorBy!='project') $isPrimary = 'secondary';
	
	$projectSelection = new UIFloatingSelection('Projects','name',$projects,'project',550,1500,$isPrimary);
		
	foreach($employees as $index => $employee) {
	
		$currentShifts = createDataByEmployees($employees,$tasks,$projects,$shifts,$employee->employeeID);
		$id			   = $employee->employeeID;
		$colorIndex    = $employee->color;
		
		$shiftSection  = new UIShiftTable($employee->name,$id,$startDate,$days,$timeStep,"hr/shiftmanagement/reserveShiftMarkerRow","hr/shiftmanagement/updateShiftMarkerRow","hr/shiftmanagement/deleteShiftMarkerRow",$groupBy,$colorBy,$colorIndex);
	
		$shiftSection->setExistingEmployeeData($employees);
		$shiftSection->setExistingTaskData($tasks);
		$shiftSection->setExistingProjectData($projects);
		$shiftSection->setExistingShiftMarkerData($currentShifts);
		$shiftSection->setPrimaryData($employee,"employeeID",$colorIndex);
		$shiftSection->createExistingShiftMarkers();
				
	}
	
}

//********************************************************************************************************************************************************************
//**  GROUPING BY TASKS
//**  Generating components by tasks.
//********************************************************************************************************************************************************************

if ($groupBy=='task') {
	
	if ($colorBy=='employee') $isPrimary = 'primary';
	if ($colorBy!='employee') $isPrimary = 'secondary';
	
	$employeeSelection = new UIFloatingSelection('Employees','name',$employees,'employee',550,1300,$isPrimary);

	if ($colorBy=='project') $isPrimary = 'primary';
	if ($colorBy!='project') $isPrimary = 'secondary';
	
	$projectSelection = new UIFloatingSelection('Projects','name',$projects,'project',500,1500,$isPrimary);
	
	foreach($tasks as $index => $task) {

		$currentShifts  = createDataByTasks($employees,$tasks,$projects,$shifts,$task->taskID);
		$id				= $task->taskID;
		$colorIndex 	= $task->color;
		
		$shiftSection	= new UIShiftTable($task->name,$id,$startDate,$days,$timeStep,"hr/shiftmanagement/reserveShiftMarkerRow","hr/shiftmanagement/updateShiftMarkerRow","hr/shiftmanagement/deleteShiftMarkerRow",$groupBy,$colorBy,$colorIndex);

		$shiftSection->setExistingEmployeeData($employees);
		$shiftSection->setExistingTaskData($tasks);
		$shiftSection->setExistingProjectData($projects);
		$shiftSection->setExistingShiftMarkerData($currentShifts);
		$shiftSection->setPrimaryData($task,"taskID",$colorIndex);
		$shiftSection->createExistingShiftMarkers();
		
	}
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATEDATABYPROJECTS($EMPLOYEES,$TASKS,$PROJECTS,$SHIFTS,$PROJECTID)
//** Creates data related to projectID.
//********************************************************************************************************************************************************************

function createDataByProjects($employees,$tasks,$projects,$shifts,$projectID) {
	
	$currentShifts = array();
	
	foreach($shifts as $index => $shift) {
	
		if ($shift->projectID == $projectID) $currentShifts[] =  $shift;
	
	}
	
	return $currentShifts;
	
}

//********************************************************************************************************************************************************************
//** FUNCTION CREATEDATABYEMPLOYEES($EMPLOYEES,$TASKS,$PROJECTS,$SHIFTS,$EMPLOYEEID)
//** Creates data related to employeeID.
//********************************************************************************************************************************************************************

function createDataByEmployees($employees,$tasks,$projects,$shifts,$employeeID) {

	$currentShifts = array();

	foreach($shifts as $index => $shift) {

		if ($shift->employeeID == $employeeID) $currentShifts[] =  $shift;

	}

	return $currentShifts;

}

//********************************************************************************************************************************************************************
//** FUNCTION CREATEDATABYTASKS($EMPLOYEES,$TASKS,$PROJECTS,$SHIFTS,$TASKID)
//** Creates data related to taskID.
//********************************************************************************************************************************************************************

function createDataByTasks($employees,$tasks,$projects,$shifts,$taskID) {

	$currentShifts = array();

	foreach($shifts as $index => $shift) {

		if ($shift->taskID == $taskID) $currentShifts[] =  $shift;

	}

	return $currentShifts;

}





?>