<?php


/**
 *  Tämä on vanhemman mallin tuntilistoista peräisin. En nyt oikein muista
 *  miten nämä aiemmin toimivat tai oli tarkoitus toimia.
 * 
 * 
 *
 */

class TimesheetsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css', 'testcss.php','mytheme/jquery-ui-test.css');
	}

	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js','timespancolumn.class.js','timeselectcolumn.class.js','changeabledropdowncontentcolumn.class.js','changedropdowncontentcolumn.class.js','timesheetdatecolumn.class.js','hourcalculator.class.js','timesheetemptycolumn.class.js','timesheetsplitcolumn.class.js','timesheetremovecolumn.class.js','timesheetbuttonfunctions.class.js','timesheetremovalvalidations.class.js','timesheetfixedcolumn.class.js','timesheetrowidentifier.class.js','timesheetinputcolumn.class.js','timesheetvalidations.class.js','timesheetauxilaryfunctions.class.js');
	}

	
	public function indexAction() {
		//$this->testprototypesAction();
		$this->registry->template->show('system/error','unknown');
	}

//*************************************************************************************************************************************************************************
//*** ACTION : CHOOSETIMESHEET()
//*** Creates a table with paginator containing timesheet views.
//*************************************************************************************************************************************************************************

	public function choosetimesheetAction() {
			
		$valueFrom					   = array();
		
		$valueFrom[0]				   = "Lastname";
		$valueFrom[1]				   = "Firstname";
		
		$this->registry->employees	   = Table::loadKeyValuePairsMultipleValues('employees','EmployeeID',$valueFrom," ");
		
		$employeenames 				   = $this->registry->employees;
		$timesheets					   = Table::load('timesheet');
			
		foreach($timesheets as $index => $timesheet) {
			$timesheet->employeename = $employeenames[$timesheet->employeeID];
		}
		
		$this->registry->timesheets = $timesheets;
		$this->registry->template->show('hr/timesheets','choosetimesheet');
	}	
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : CHOOSETIMESHEETWITHLIMIT()
//*** Creates a table with paginator containing timesheet views according paginator action.
//*************************************************************************************************************************************************************************
	
	public function choosetimesheetwithlimitAction() {
			
		$valueFrom					   = array();
	
		$valueFrom[0]				   = "Lastname";
		$valueFrom[1]				   = "Firstname";
	
		$this->registry->employees	   = Table::loadKeyValuePairsMultipleValues("employees","EmployeeID",$valueFrom," ");
	
		$employeenames 				   = $this->registry->employees;
		
		$limit 						   = $_GET['limit'];
		$offset 					   = $_GET['offset'];
		$sort						   = "Startdate";
		$direction					   = "ASC";

		$timesheets 				   = Table::loadRowsByLimitAndOffset("timesheet",$limit,$offset,$sort,$direction);
			
		foreach($timesheets as $index => $timesheet) {
			$timesheet->employeename = $employeenames[$timesheet->employeeID];
		}
	
		$this->registry->timesheets = $timesheets;
		$this->registry->template->show('hr/timesheets','choosetimesheet');
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : SHOWTIMESHEET()
//*** Creates TimeSheet-component with chosen timesheet view.
//*************************************************************************************************************************************************************************
		
	public function showtimesheetAction() {
		
		$timesheets = Table::load('timesheet');
		$rowID		   = $_GET['id'];
		
		$startDate	= $timesheets[$rowID]->startdate;
		$endDate	= $timesheets[$rowID]->enddate;
		$employeeID = $timesheets[$rowID]->employeeID;
				
		$where		= " WHERE EmployeeID=".$employeeID." AND Rowdate >= \"".$startDate."\" AND Rowdate <= \"".$endDate."\" ORDER BY Rowdate";
		
		$this->registry->loadedrows    = Table::load("timesheetrows",$where);
		
		$this->registry->projects 	   = Table::load('timesheet_projects');
		
		$this->registry->projecttasks  = Table::load('timesheet_projecttasks');
		$this->registry->tasks		   = Table::load('timesheet_tasks');
		
		$this->registry->projectshifts = Table::load('timesheet_projectshifts');
		$this->registry->shifts		   = Table::load('timesheet_shifts');
		
		foreach($this->registry->projecttasks as $index => $row) {
			$task 	=  $this->registry->tasks[$row->taskID];
			$row->taskname = $task->name;
		}
		
		foreach($this->registry->projectshifts as $index => $row) {
			$shift 	=  $this->registry->shifts[$row->shiftID];
			$row->shiftname = $shift->name;
		}
		$this->registry->template->show('hr/timesheets','showtimesheet');
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : TIMESHEETTEST()
//*** Testing, testing, testing, testing.
//*************************************************************************************************************************************************************************
	
	public function timesheettestAction() {
				
		$valueFrom					   = array();
		
		$valueFrom[0]				   = "Lastname";
		$valueFrom[1]				   = "Firstname";
		
		$this->registry->employees	   = Table::loadKeyValuePairsMultipleValues('employees','EmployeeID',$valueFrom," ");
		
		$employeenames 				   = $this->registry->employees;
		$timesheets					   = Table::load('timesheet');
		 
		foreach($timesheets as $index => $timesheet) {
			$timesheet->employeename = $employeenames[$timesheet->employeeID];
		}
		
		$this->registry->timesheets = $timesheets;
		
		$this->registry->projects 	   = Table::load('timesheet_projects');
		$this->registry->loadedrows    = Table::load('timesheetrows',' ORDER BY Rowdate');
		
		$this->registry->projecttasks  = Table::load('timesheet_projecttasks');
		$this->registry->tasks		   = Table::load('timesheet_tasks');
		
		$this->registry->projectshifts = Table::load('timesheet_projectshifts');
		$this->registry->shifts		   = Table::load('timesheet_shifts');
		
		foreach($this->registry->projecttasks as $index => $row) {
			$task 	=  $this->registry->tasks[$row->taskID];
			$row->taskname = $task->name;
		}
		
		foreach($this->registry->projectshifts as $index => $row) {
			$shift 	=  $this->registry->shifts[$row->shiftID];
			$row->shiftname = $shift->name;
		}
		$this->registry->template->show('hr/timesheets','timesheettest');
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : SHOWINSERTTIMESHEET()
//*** Shows timesheet creation.
//*************************************************************************************************************************************************************************
	
	public function showinserttimesheetAction() {
		
		$valueFrom					   = array();
		$valueFrom[0]				   = "Lastname";
		$valueFrom[1]				   = "Firstname";
		$this->registry->employees	   = Table::loadKeyValuePairsMultipleValues('employees','EmployeeID',$valueFrom," ");
		$employeenames 				   = $this->registry->employees;
		$this->registry->template->show('hr/timesheets','inserttimesheet');
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : INSERTTIMESHEETROW()
//*** Inserts new timesheetrow.
//*************************************************************************************************************************************************************************
		
	public function inserttimesheetrowAction() {
		
		$tablename = 'timesheetrows';
		
		if (isset($_GET['copyvalue'])) $date = $_GET['copyvalue'];
		
		$values = array();
		
		$values['Rowdate']		 = $date;
		$values['Starthour'] 	 = 0;
		$values['Startminute']	 = 0;
		$values['Endhour'] 		 = 0;
		$values['Endminute'] 	 = 0;
		$values['ProjectID']   	 = 0;
		$values['TaskID'] 		 = 0;
		$values['ShiftID'] 		 = 0;
		$values['Timevalues'] 	 = null;
		
		$insertID = Table::addRow($tablename,$values);
		echo "[{\"insertID\":\"".$insertID."\"}]";
	}

//*************************************************************************************************************************************************************************
//*** ACTION : REMOVETIMESHEETROW()
//*** Remove new timesheetrow.
//*************************************************************************************************************************************************************************	
	
	public function removetimesheetrowAction() {
		
		$tablename = "timesheetrows";
		if (isset($_GET['id'])) $removeID = $_GET['id'];
		$where = "TimesheetrowID='".$removeID."'";
		$success  = Table::deleteRow($tablename,$where);
		echo "[{\"success\":\"true\"}]";
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : UPDATETIMESHEETTIME()
//*** Updates timesheetrow time.
//*************************************************************************************************************************************************************************
	 
	public function updatetimesheettimeAction() {
		
		$tablename	 			 = 'timesheetrows';
		$values				 	 = array();
		$rowID					 = $_GET['id'];
		$values['Starthour'] 	 = $_GET['starthour'];
		$values['Startminute'] 	 = $_GET['startminute'];
		$values['Endhour'] 		 = $_GET['endhour'];
		$values['Endminute'] 	 = $_GET['endminute'];
		
		$where = "TimesheetrowID = ".$rowID;
		Table::updateRow($tablename,$values,$rowID);
	
		echo "[{\"success\":\"true\"}]";
		
	}
	
//*************************************************************************************************************************************************************************
//*** ACTION : UPDATEPROJECT()
//*** Updates timesheetrow project.
//*************************************************************************************************************************************************************************
		
	public function updateprojectAction() {
		
		$tablename 			 = 'timesheetrows';
		$values 			 = array();
		$values['ProjectID'] = $_GET['value'];
		$rowID	   			 = $_GET['id'];
		$where = "TimesheetrowID = ".$rowID;
		Table::updateRow($tablename,$values,$rowID);
		echo "[{\"success\":\"true\"}]";
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : UPDATETASK()
//*** Updates timesheetrow task.
//*************************************************************************************************************************************************************************
	
	public function updatetaskAction() {
	
		$tablename 			 = 'timesheetrows';
		$values 			 = array();
		$values['TaskID'] 	 = $_GET['value'];
		$rowID	   			 = $_GET['id'];
		$where = "TimesheetrowID = ".$rowID;
		Table::updateRow($tablename,$values,$rowID);
		echo "[{\"success\":\"true\"}]";
	}
	
	
//*************************************************************************************************************************************************************************
//*** ACTION : UPDATESHIFT()
//*** Updates timesheetrow shift.
//*************************************************************************************************************************************************************************
	
	public function updateshiftAction() {
	
		$tablename 			 = 'timesheetrows';
		$values 			 = array();
		$values['ShiftID'] 	 = $_GET['value'];
		$rowID	   			 = $_GET['id'];
		$where = "TimesheetrowID = ".$rowID;
		Table::updateRow($tablename,$values,$rowID);
		echo "[{\"success\":\"true\"}]";
	}

//*************************************************************************************************************************************************************************
//*** ACTION : UPDATEFREEINPUT()
//*** Updates timesheetrow freeinput.
//*************************************************************************************************************************************************************************
		
	public function updatefreeinputAction() {
		
		echo "[{\"success\":\"true\"}]";
		
	}
	
//*************************************************************************************************************************************************************************
//*** ACTION : CREATETIMESHEETROWS()
//*** Creates new timesheet view and rows if necessary.
//*************************************************************************************************************************************************************************
	
	public function createtimesheetrowsAction() {
		
		$startDate 		= $_GET['startdate'];
		$endDate		= $_GET['enddate'];
		$employeeID		= $_GET['employeeID'];
	
		$startDate		= explode(".",$startDate);
		$startDate		= $startDate[2].'-'.$startDate[1].'-'.$startDate[0];
		
		$endDate		= explode(".",$endDate);
		$endDate		= $endDate[2].'-'.$endDate[1].'-'.$endDate[0];
		
		$startIndex		= intval(str_replace("-","",$startDate));
		$endIndex		= intval(str_replace("-","",$endDate));
		
		$startDate 		= new DateTime($startDate);
		$endDate 		= new DateTime($endDate);
		
		// Lisataan nakyma (tuntilista)
		
		$tablename 						= "timesheet";
		
		$timesheetValues = array();
		
		$timesheetValues['EmployeeID'] 	= $employeeID;
		$timesheetValues['Startdate'] 	= $startDate->format('Y-m-d');
		$timesheetValues['Enddate'] 	= $endDate->format('Y-m-d');
		
		if ($startIndex>$endIndex)		echo "[{\"success\":\"false\"}]";
		if (($endIndex-$startIndex)>31) echo "[{\"success\":\"false\"}]";
		
		Table::addRow($tablename,$timesheetValues);
		
		// Luodaan tarvittaessa uusia riveja.
		
		$tablename		= 'timesheetrows';
		
		$keycolumn		= "Rowdate";
		$valuecolumn	= "TimesheetrowID";
		$where			= "EmployeeID = '".$employeeID."'";

		$existingDates 	= Table::loadKeyValueArray($tablename, $keycolumn, $valuecolumn , $where);
		
		$currentDate	= $startDate;
		
		for($dateIndex = $startIndex;$dateIndex<=$endIndex;$dateIndex++) {

			$insertDate 			= $currentDate->format('Y-m-d');
			
			if (!isset($existingDates[$insertDate])) { 
				
				$values = array();
		
				$values['Rowdate']		 = $insertDate;
				$values['Starthour'] 	 = 0;
				$values['Startminute']	 = 0;
				$values['Endhour'] 		 = 0;
				$values['Endminute'] 	 = 0;
				$values['ProjectID']   	 = 0;
				$values['TaskID'] 		 = 0;
				$values['ShiftID'] 		 = 0;
				$values['Timevalues'] 	 = null;
				$values['EmployeeID']	 = $employeeID;
			
				Table::addRow($tablename,$values);
				
			}
			
			$currentDate->modify('+1 day');
			
		}
			
		echo "[{\"success\":\"true\"}]";
		
	}
		
	public function testprototypesAction() {
		
		//$this->registry->menu = Menu::loadMenu($_SESSION['usergroupID']);
		$this->registry->template->show('hr/timesheets/','vuorohallintaprototype2');
		
	}
	
}
?>