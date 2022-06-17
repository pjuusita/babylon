<?php

class TimesheetsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css', 'testcss.php','mytheme/jquery-ui-test.css');
	}

	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js','timespancolumn.class.js','timeselectcolumn.class.js','changeabledropdowncontentcolumn.class.js','changedropdowncontentcolumn.class.js','timesheetdatecolumn.class.js','hourcalculator.class.js','timesheetemptycolumn.class.js','timesheetsplitcolumn.class.js','timesheetremovecolumn.class.js','timesheetbuttonfunctions.class.js','timesheetremovalvalidations.class.js','timesheetfixedcolumn.class.js','timesheetrowidentifier.class.js','timesheetinputcolumn.class.js','timesheetvalidations.class.js','timesheetauxilaryfunctions.class.js');
	}
	
	
	public function indexAction() {
		//$this->showtimesheetsAction();
		$this->registry->template->show('system/error','unknown');
	}


	
	
	public function showtimesheetsAction() {
	
		$status = 0;
		if (isset($_GET['status'])) $status = $_GET['status'];
	
		$this->registry->status = Table::load('timesheet_status');
		$this->registry->workers = Table::load('hr_workers');
		foreach($this->registry->workers as $index => $worker) {
			$worker->fullname = $worker->firstname . " " . $worker->lastname;
		}
		$this->registry->timesheets = Table::load('timesheet_timesheets');
		$this->registry->template->show('timesheet/timesheets','timesheets');
	}
	
	
}
?>