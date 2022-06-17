<?php

//******************************************************************************************************
//**
//**
//******************************************************************************************************

function sortByDate($a, $b) {
	
	$adate = intval(str_replace("-","",$a->date));
	$bdate = intval(str_replace("-","",$b->date));
		
	return $adate - $bdate;
}

//******************************************************************************************************
//**
//**
//******************************************************************************************************

class ShiftmanagementController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css', 'testcss.php','mytheme/jquery-ui-test.css');
	}

	public function getJSFiles() { 
		return array('jquery.min.js', 'jquery-ui.js', 'jquery.ui.touch-punch.min.js', 'chosen.jquery.js','prism.js','shiftmanagementprintdialog.class.js','shiftoverview.class.js','shiftmanagementcontroller.class.js', 'shiftmarker.class.js','shifttable.class.js','floatingselection.class.js','floatingselectioninnertable.class.js','draggableelement.class.js');
	} 
	
	
	public function indexAction() {
		//$this->showshiftsAction();
		$this->registry->template->show('system/error','unknown');
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function reserveShiftMarkerRowAction() {
		
		$parameters 	=	array();
		
		$parameters['Starthour'] 	= $_GET['starthour'];
		$parameters['Startminute']	= $_GET['startminute'];
		$parameters['Endhour']		= $_GET['endhour'];
		$parameters['Endminute'] 	= $_GET['endminute'];
		
		$parameters['EmployeeID'] 	= $_GET['employeeid'];
		$parameters['TaskID'] 		= $_GET['taskid'];
		
		$parameters['Date']			= $_GET['datestring'];	
		
		$parameters['Bgcolor']		= $_GET['bgcolor'];
		$parameters['Bordercolor']	= $_GET['bordercolor'];
		
		$parameters['ProjectID']	= $_GET['projectid'];
		
		$tableName					= "shiftmarkers";

		$insertID = Table::addRow($tableName,$parameters);
		echo "[{\"insertID\":\"".$insertID."\"}]";
		
	}
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function updateShiftMarkerRowAction() {
		
		$parameters 	=	array();
		
		$shiftMarkerID	= $_GET['shiftmarkerid'];
		
		$parameters['Starthour'] 	= $_GET['starthour'];
		$parameters['Startminute']	= $_GET['startminute'];
		$parameters['Endhour']		= $_GET['endhour'];
		$parameters['Endminute'] 	= $_GET['endminute'];
		
		$parameters['EmployeeID'] 	= $_GET['employeeid'];
		$parameters['TaskID'] 		= $_GET['taskid'];
		
		$parameters['Date']			= $_GET['datestring'];
		
		$parameters['Bgcolor']		= $_GET['bgcolor'];
		$parameters['Bordercolor']	= $_GET['bordercolor'];
		
		$parameters['ProjectID']	= $_GET['projectid'];
		
		$tableName					= "shiftmarkers";
		$where						= "ShiftmarkerID = '".$shiftMarkerID."'";
		
		Table::updateRow($tableName,$parameters,$shiftMarkerID);
		
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function showshiftsAction() {
		$this->registry->template->show('hr/shiftmanagement','shiftmanagementprototype');
	}
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
		
	public function tukeproto01Action() {
		$this->registry->template->show('hr/shiftmanagement','shiftmanagementprototype');
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function tukeproto02Action() {
		$this->registry->template->show('hr/shiftmanagement','shiftmanagementprototypeversion2');
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function peteproto01Action() {
		$this->registry->template->show('hr/shiftmanagement','peteshiftproto01');
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function peteproto02Action() {
		$this->registry->template->show('hr/shiftmanagement','peteshiftproto02');
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function peteproto03Action() {
		$this->registry->template->show('hr/shiftmanagement','peteshiftproto03');
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function deleteshiftmarkerrowAction() {
		$shiftMarkerID = $_GET['shiftmarkerid'];
		echo "[{\"success\":\"true\"}]";
	}	
	
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function updateshifttablesAction() {
		
		$startDate 					= $_GET['startdate'];
		$days						= intval($_GET['days']);
		
		$dateComponents				= explode("-",$startDate);
		$dateString					= $dateComponents[0]."-".$dateComponents[1]."-".$dateComponents[2];
		
		$startDate					= $dateString;
		
		$date						= new DateTime($dateString);
		
		for($timeWarp=0;$timeWarp<$days;$timeWarp++) $date->modify('+1 days');
		
		$endDate					= $date->format("Y-m-d");
		
		$where						= " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."'";
			
		$this->registry->colors 	= Table::load("system_colors");
		$this->registry->projects	= Table::load("timesheet_projects");
		$this->registry->employees	= Table::loadWithPrimaryKeyIndex("employees","employeeID","");
		$this->registry->shifts		= Table::load("shiftmarkers",$where);
		$this->registry->allshifts  = Table::load("shiftmarkers");
		
		foreach($this->registry->employees as $index => $row) {
			$row->name 	= $row->firstname . " " .  $row->lastname;
		}
		
		$groupBy				= $_GET['groupby'];
		$colorBy				= $_GET['colorby'];
		
		$this->registry->tasks	= Table::load("timesheet_tasks");
		
		$this->registry->groupBy 	= $groupBy;
		$this->registry->colorBy 	= $colorBy;
		$this->registry->startDate	= $startDate;
		$this->registry->days	 	= $days;
		$this->registry->template->show('hr/shiftmanagement','vuorohallintaprotojquery03042016');
	}
	
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function printshifttablesAction() {
	
		$grouping	= $_GET['group'];
			
		if ($grouping=='project') $this->printProjects();
		if ($grouping=='task') $this->printTasks();
		if ($grouping=='employee') $this->printEmployees();
		
		
	}

//******************************************************************************************************
//**
//**
//******************************************************************************************************

	public function printEmployees() {
		
		$startDate  = $_GET['startdate'];
		$endDate  	= $_GET['enddate'];	
		$subject	= $_GET['subject'];
		
		$projects	= Table::loadWithPrimaryKeyIndex("timesheet_projects","projectID","");
		$tasks		= Table::loadWithPrimaryKeyIndex("timesheet_tasks","taskID","");
		$employees	= Table::loadWithPrimaryKeyIndex("employees","employeeID","");
		
		$dateComponents = explode("-",$startDate);
		$time			= mktime(0,0,0,$dateComponents[1],$dateComponents[2],$dateComponents[0]);
		$date 			= date('Y-m-d',$time);
		
		$this->registry->startDate = $startDate;
		$this->registry->endDate   = $endDate;
		
		$where		= "";
		
		if ($subject=="-1") {
			
			$where = " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY Date,EmployeeID";
			$this->registry->subject   = "Kaikki tyantekijat";
			
		}
		
		if ($subject!="-1") {
			
			$where 		= " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."' AND EmployeeID = '".$subject."' ORDER BY Date,EmployeeID";
			$this->registry->subject = $employees[$subject]->firstname." ".$employees[$subject]->lastname;
		}
		
		$allshifts  = Table::load("shiftmarkers",$where);
		
		$dateDifference = floor((strtotime($endDate) - strtotime($startDate))/(60*60*24));
		
		$emptyDates		= array();
		
		for($days=0;$days<$dateDifference;$days++) {
				
			$foundDate = false;
				
			foreach($allshifts as $index => $shift) {
					
				if ($date==$shift->date) $foundDate = true;
		
			}
		
			if (!$foundDate) {
		
				$components 	= explode("-",$date);
				$currentDate    = $components[0].'-'.$components[1].'-'.$components[2];
				//echo "<br>".$currentDate;
				$emptyDates[]   = $currentDate;
			}
				
			$date = date("Y-m-d",strtotime("+1 day",strtotime($date)));
				
		}
		
		
		foreach($allshifts as $index => $shift) {
				
			$emloyeeID = $shift->employeeID;
			$projectID = $shift->projectID;
			$taskID	   = $shift->taskID;
				
			$shift->employeename = $employees[$emloyeeID]->firstname. " " .$employees[$emloyeeID]->lastname;
			$shift->projectname	 = $projects[$projectID]->name;
			$shift->taskname	 = $tasks[$taskID]->name;
				
			if (strlen($shift->starthour)==1) 	$shift->starthour 	= '0'.$shift->starthour;
			if (strlen($shift->startminute)==1) $shift->startminute = '0'.$shift->startminute;
			if (strlen($shift->endhour)==1) 	$shift->endhour 	= '0'.$shift->endhour;
			if (strlen($shift->endminute)==1) 	$shift->endminute 	= '0'.$shift->endminute;
		
			$shift->starttime	 = $shift->starthour . ':' . $shift->startminute;
			$shift->endtime	 	 = $shift->endhour . ':' . $shift->endminute;
			$shift->shiftspan	 = $shift->starttime.'-'.$shift->endtime;
			$shift->shiftlength  = $this->getShiftLength($shift->starttime,$shift->endtime);
			
		}
			
		foreach($emptyDates as $index => $date) {
		
			$emptyRow				= Table::loadRow("shiftmarkers","0");
			$emptyRow->date 		= $emptyDates[$index];
			$emptyRow->shiftspan 	= iconv('UTF-8', 'Windows-1252', "Ei tyavuoroja");
			$allshifts[]			= $emptyRow;
				
		}
		
		usort($allshifts, "sortByDate");
		
		$this->registry->allshifts	= $allshifts;
		$this->registry->projects	= $projects;
		$this->registry->employees  = $employees;
		$this->registry->template->show('hr/shiftmanagement','printshiftsbyemployee');
	}
	
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function printTasks() {
	
		$startDate  	= $_GET['startdate'];
		$endDate  		= $_GET['enddate'];
		$subject		= $_GET['subject'];
		
		$projects		= Table::loadWithPrimaryKeyIndex("timesheet_projects","projectID","");
		$tasks			= Table::loadWithPrimaryKeyIndex("timesheet_tasks","taskID","");
		$employees		= Table::loadWithPrimaryKeyIndex("employees","employeeID","");
		
		$dateComponents = explode("-",$startDate);
		$time			= mktime(0,0,0,$dateComponents[1],$dateComponents[2],$dateComponents[0]);
		$date 			= date('Y-m-d',$time);
		
		$this->registry->startDate = $startDate;
		$this->registry->endDate   = $endDate;
		
		$where		= "";
		
		if ($subject=="-1") {
		
			$where = " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY Date,TaskID";
			$this->registry->subject   = "Kaikki tyatehtavat";
		
		}
		
		if ($subject!="-1") {
		
			$where 		= " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."' AND TaskID = '".$subject."' ORDER BY Date,TaskID";
			$this->registry->subject = $tasks[$subject]->name;
		}

		$allshifts  = Table::load("shiftmarkers",$where);
		
		$dateDifference = floor((strtotime($endDate) - strtotime($startDate))/(60*60*24));
		
		$emptyDates		= array();
		
		for($days=0;$days<$dateDifference;$days++) {
		
			$foundDate = false;
		
			foreach($allshifts as $index => $shift) {
					
				if ($date==$shift->date) $foundDate = true;
		
			}
		
			if (!$foundDate) {
		
				$components 	= explode("-",$date);
				$currentDate    = $components[0].'-'.$components[1].'-'.$components[2];
				$emptyDates[]   = $currentDate;
			}
		
			$date = date("Y-m-d",strtotime("+1 day",strtotime($date)));
		
		}
		
		foreach($allshifts as $index => $shift) {
		
			$emloyeeID = $shift->employeeID;
			$projectID = $shift->projectID;
			$taskID	   = $shift->taskID;
		
			$shift->employeename = $employees[$emloyeeID]->firstname. " " .$employees[$emloyeeID]->lastname;
			$shift->projectname	 = $projects[$projectID]->name;
			$shift->taskname	 = $tasks[$taskID]->name;
		
			if (strlen($shift->starthour)==1) 	$shift->starthour 	= '0'.$shift->starthour;
			if (strlen($shift->startminute)==1) $shift->startminute = '0'.$shift->startminute;
			if (strlen($shift->endhour)==1) 	$shift->endhour 	= '0'.$shift->endhour;
			if (strlen($shift->endminute)==1) 	$shift->endminute 	= '0'.$shift->endminute;
		
			$shift->starttime	 = $shift->starthour . ':' . $shift->startminute;
			$shift->endtime	 	 = $shift->endhour . ':' . $shift->endminute;
			$shift->shiftspan	 = $shift->starttime.'-'.$shift->endtime;
		
		}
			
		foreach($emptyDates as $index => $date) {
		
			$emptyRow				= Table::loadRow("shiftmarkers","0");
			$emptyRow->date 		= $emptyDates[$index];
			$emptyRow->shiftspan 	= iconv('UTF-8', 'Windows-1252', "Ei tyavuoroja");
			$allshifts[]			= $emptyRow;
		
		}
		
		usort($allshifts, "sortByDate");
		
		$this->registry->allshifts	= $allshifts;
		$this->registry->projects	= $projects;
		$this->registry->employees  = $employees;
		$this->registry->template->show('hr/shiftmanagement','printshiftsbytask');
	}
	
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function printProjects() {
	
		$startDate  	= $_GET['startdate'];
		$endDate  		= $_GET['enddate'];
		$subject		= $_GET['subject'];
		
		$projects		= Table::loadWithPrimaryKeyIndex("timesheet_projects","projectID","");
		$tasks			= Table::loadWithPrimaryKeyIndex("timesheet_tasks","taskID","");
		$employees		= Table::loadWithPrimaryKeyIndex("employees","employeeID","");
		
		$dateComponents = explode("-",$startDate);
		$time			= mktime(0,0,0,$dateComponents[1],$dateComponents[2],$dateComponents[0]);
		$date 			= date('Y-m-d',$time);
		
		$this->registry->startDate = $startDate;
		$this->registry->endDate   = $endDate;
		
		$where		= "";
		
		if ($subject=="-1") {
				
			$where = " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY Date,ProjectID";
			$this->registry->subject   = "Kaikki kohteet";
				
		}
		
		if ($subject!="-1") {
				
			$where 		= " WHERE Date BETWEEN '".$startDate."' AND '".$endDate."' AND ProjectID = '".$subject."' ORDER BY Date,ProjectID";
			$this->registry->subject = $projects[$subject]->name;
		}
		
		$allshifts  = Table::load("shiftmarkers",$where);
		
		$dateDifference = floor((strtotime($endDate) - strtotime($startDate))/(60*60*24));
		
		$emptyDates		= array();
		
		for($days=0;$days<$dateDifference;$days++) {
		
			$foundDate = false;
		
			foreach($allshifts as $index => $shift) {
					
				if ($date==$shift->date) $foundDate = true;
		
			}
		
			if (!$foundDate) {
		
				$components 	= explode("-",$date);
				$currentDate    = $components[0].'-'.$components[1].'-'.$components[2];
				//echo "<br>".$currentDate;
				$emptyDates[]   = $currentDate;
			}
		
			$date = date("Y-m-d",strtotime("+1 day",strtotime($date)));
		
		}
		
		foreach($allshifts as $index => $shift) {
		
			$emloyeeID = $shift->employeeID;
			$projectID = $shift->projectID;
			$taskID	   = $shift->taskID;
		
			$shift->employeename = $employees[$emloyeeID]->firstname. " " .$employees[$emloyeeID]->lastname;
			$shift->projectname	 = $projects[$projectID]->name;
			$shift->taskname	 = $tasks[$taskID]->name;
		
			if (strlen($shift->starthour)==1) 	$shift->starthour 	= '0'.$shift->starthour;
			if (strlen($shift->startminute)==1) $shift->startminute = '0'.$shift->startminute;
			if (strlen($shift->endhour)==1) 	$shift->endhour 	= '0'.$shift->endhour;
			if (strlen($shift->endminute)==1) 	$shift->endminute 	= '0'.$shift->endminute;
		
			$shift->starttime	 = $shift->starthour . ':' . $shift->startminute;
			$shift->endtime	 	 = $shift->endhour . ':' . $shift->endminute;
			$shift->shiftspan	 = $shift->starttime.'-'.$shift->endtime;
		
		}
			
		foreach($emptyDates as $index => $date) {
		
			$emptyRow				= Table::loadRow("shiftmarkers","0");
			$emptyRow->date 		= $emptyDates[$index];
			$emptyRow->shiftspan 	= iconv('UTF-8', 'Windows-1252', "Ei tyavuoroja");
			$allshifts[]			= $emptyRow;
		
		}
		
		usort($allshifts, "sortByDate");
		
		$this->registry->allshifts	= $allshifts;
		$this->registry->projects	= $projects;
		$this->registry->employees  = $employees;
		$this->registry->template->show('hr/shiftmanagement','printshiftsbyproject');
	}
		
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function prototyyppi3Action() {

		$startDate	= new DateTime();
		$endDate	= new DateTime();
		
		for($timeWarp=0;$timeWarp<7;$timeWarp++) $endDate->modify('+1 days');
		
		$startStr	= $startDate->format("Y-m-d");
		$endStr		= $endDate->format("Y-m-d");
		
		$where						= " WHERE Date BETWEEN '".$startStr."' AND '".$endStr."'";
		
		$this->registry->colors 	= Table::load("system_colors");
		$this->registry->projects	= Table::load("timesheet_projects");
		$this->registry->employees	= Table::loadWithPrimaryKeyIndex("employees","employeeID","");
		$this->registry->shifts		= Table::load("shiftmarkers",$where);
		$this->registry->allshifts  = Table::load("shiftmarkers");
		
		foreach($this->registry->employees as $index => $row) {
			$row->name 	= $row->firstname . " " .  $row->lastname;
		}
		
		$this->registry->tasks	= Table::load("timesheet_tasks");
		
		$this->registry->groupBy 	= 'project';
		$this->registry->colorBy 	= 'employee';
		$this->registry->startDate	= $startStr;
		$this->registry->days	 	= 7;
		$this->registry->template->show('hr/shiftmanagement','vuorohallintaprotojquery03042016');
	}
	
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function simpleprocesstableAction() {

		$tasks  	= 	Table::load('process_tasks');
		$processes 	=	Table::load('process_processes');
		$users  	=	Table::load('system_users');
		$stages		= 	Table::load('process_stage');
		$types		= 	Table::load('process_types');
		$states 	= 	Table::load('process_states');
		$priorities = 	Table::load('process_priorities');
			
		$this->registry->billingStages = $stages;
		
		foreach($tasks as $index => $task) {
			
			$task->assigned = $users[$task->assignedID]->loginname;
			$task->assigner = $users[$task->assignerID]->loginname;
			$task->process	= $processes[$task->processID]->name;
			$task->type		= $types[$task->typeID]->name;
			$task->stage	= $stages[$task->stageID]->name;
			$task->state	= $states[$task->stateID]->name;
			$task->priority = $priorities[$task->priorityID]->name;
			
		}
		$this->registry->tasks = $tasks;
		$this->registry->template->show('hr/shiftmanagement','vuorohallintaproto3');
	}
	
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************

	public function getShiftLength($startTime,$endTime) {
		
		$startComponents = explode(":",$startTime);
		$endComponents	 = explode(":",$endTime);
		
		$start			 = intval($startComponents[0]) * 60 + intval($startComponents[1]);
		$end			 = intval($endComponents[0]) * 60 + intval($endComponents[1]);
		
		$timeSpan		 = ($end - $start) / 60;
		
		return $timeSpan;
		
		
	}
	
//******************************************************************************************************
//**
//**
//******************************************************************************************************
	
	public function shifthourimageAction() {
		
		$this->registry->hallID = 1;
		$this->registry->shade = 1;
		$this->registry->hall = WarehouseHall::loadHall(1);
		$this->registry->zones = WarehouseZone::loadZonesFromHall(1);
		$this->registry->template->show('hr/shiftmanagement','shifthourimage');
	}
	
	
	
}
	
?>
	