<?php



class VatreportController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showvatreportAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showvatreportAction() {

		$comments = false;
	
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$periods = Table::load('accounting_periods');
		$selectedperiod = null;
		foreach($periods as $index => $period) {
			$period->fullname = "Tilikausi " . $period->name;
			if ($comments)echo "<br>" . $period->periodID . " - " . $period->startdate . " - " . $period->enddate;
			if ($period->periodID == $periodID) {
				$this->registry->periodID = $period->periodID;
				$this->registry->period = $period;
				$selectedperiod = $period;
			}
		}
		$this->registry->periods = $periods;
		
		if ($comments)echo "<br>Selected - " . $this->registry->periodID;
		
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
			$receiptsetID = 0;
			setModuleSessionVar('receiptsetID',$receiptsetID);
			$selectionID = 0;
			setModuleSessionVar('selectionID',$selectionID);
			$startdate = $this->registry->period->startdate;
			setModuleSessionVar('periodstartdate',$startdate);
			$enddate = $this->registry->period->enddate;
			setModuleSessionVar('periodenddate',$enddate);
		} else {
			if ($comments) echo "<br>Period not changed";
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			$enddate = getModuleSessionVar('periodenddate');
		}
		
		if ($comments)echo "<br>Selected222 - " . $this->registry->period->periodID;
		if ($comments)echo "<br>Selected222 - " . $this->registry->period->startdate;
		
		
		$selection = $this->generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		if ($selectionID == 0) {
			if ($comments) echo "<br>selection id nolla - " . $selectionID;
			foreach($selection as $index => $value) {
				$selectionID = $index;
				if ($comments) echo "<br>First - " . $selectionID;
				break;
			}
		}
		$currentselect = $selection[$selectionID];
		if ($comments) echo "<br>current start - " . $currentselect->startsql;
		if ($comments) echo "<br>current end - " . $currentselect->endsql;
		$startdate = $currentselect->startsql;
		$enddate = $currentselect->endsql;
		if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		$this->registry->selectionID = $selectionID;
		
		
		$entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $currentselect->startsql . "' AND '" . $currentselect->endsql . "'");
		$vatcodes = Table::load('accounting_vatreportcodes');
		
		$vatsums = array();
		foreach($vatcodes as $index => $vatcode) {
			$vatsums[$vatcode->vatcodeID] = 0;
			//echo "<br>vatcode - " . $vatcode->vatcodeID;
		}
		foreach($entries as $index => $entry) {
			if ($entry->vatcodeID > 0) {
				
				if ($entry->vatcodeID == 2) {
					echo "<br>Entry - " . $entry->entryID . " - " .  $entry->receiptID . " - " . $entry->amount. " - " . $entry->vatcodeID; // . " (receipdID:" . $entry->receiptID. ")";
				} else {
					//echo "<br>Entry - " . $entry->entryID . " - " .  $entry->receiptID . " - " . $entry->amount. " - " . $entry->vatcodeID; // . " (receipdID:" . $entry->receiptID. ")";
				}
			}
			
			
			//echo "<br>Entry - " . $entry->entryID . " - " .  $entry->receiptID . " - " . $entry->amount. " - " . $entry->vatcodeID; // . " (receipdID:" . $entry->receiptID. ")";
					
			if ($entry->vatcodeID != 0) {
				if (isset($vatsums[$entry->vatcodeID])) {
					$vatsums[$entry->vatcodeID] = $vatsums[$entry->vatcodeID] + $entry->amount;
				} else {
					$vatsums[$entry->vatcodeID] = $entry->amount;
				}
			}
		}
		foreach($vatsums as $index => $sum) {
			if ($sum < 0) $vatsums[$index] = -1 * $sum;
		}
		
		$this->registry->vatsums = $vatsums;
				
		$this->registry->template->show('accounting/vatreport','vatreport');
	}
	
	

// kopioitu receiptsistä
	private function generatePeriodTimescales($period, &$selectedindex, $currentdate = null) {
	
		$comments = false;
		$selection = array();
		$selectionindex = 0;
	
		if ($currentdate == null) {
			$currentdate = date("Y-m-d");
		}
		if ($comments) echo "<br>Current - " . $currentdate;
		//$selectedindex = 0;
	
		if ($comments) echo "<br>" . $period->startdate . " - " . $period->enddate;
	
		/*
			if ($comments) echo "<br>Tilikausi " . $period->name . " --- " . sqlDateToStr($period->startdate) . " - " . sqlDateToStr($period->enddate);
			$row = new Row();
			$year = substr($period->startdate, 0, 4);
	
			$selectionindex++;
			$row->rowID = $selectionindex;
			$row->year = $year;
			$row->name = "Tilikausi " . $period->name;
			$row->startsql = $period->startdate;
			$row->endsql = $period->enddate;
			$row->startdate = sqlDateToStr($period->startdate);
			$row->enddate = sqlDateToStr($period->enddate);
			$selection[$selectionindex] = $row;
			*/
	
		$counter = 0;
		$startdate = $period->startdate;
		$quarterstart = $period->startdate;
		$quartercounter = 0;
		$quaretertemp = 0;
	
		while ($startdate  < $period->enddate) {
	
			//echo "<br>Creating - " . $startdate . " - "  . $period->enddate;
			$month = substr($startdate, 5, 2);
			$year = substr($startdate, 0, 4);
			$enddate = date("Y-m-t", strtotime($startdate));
	
	
			if ($comments) echo "<br>aa " . $year . "/" . $month . " --- " . sqlDateToStr($startdate) . " - " . sqlDateToStr($enddate);
			$selectionindex++;
			$row = new Row();
			$row->rowID = $selectionindex;
			$row->year = $year;
			$row->name = $year . "/" . $month;
			$row->startsql = $startdate;
			$row->endsql = $enddate;
			$row->startdate = sqlDateToStr($startdate);
			$row->enddate = sqlDateToStr($enddate);
			$selection[$selectionindex] = $row;
	
			if (($currentdate >= $startdate) && ($currentdate <= $enddate)) {
				if ($comments) echo "<br>*********** current";
				//$selectedindex = $selectionindex;
			}
				
			$quaretertemp++;
			if ($quaretertemp == 3) {
				$quaretertemp = 0;
				$quartercounter++;
				if ($comments) echo "<br>" . $year . "/Q" . $quartercounter . " --- " . sqlDateToStr($quarterstart) . " - " . sqlDateToStr($enddate);
				$quarterstart = $startdate;
	
				$row = new Row();
				$selectionindex++;
				$row->rowID = $selectionindex;
				$row->year = $year;
				$row->name = $year . "/Q" . $quartercounter;
				$row->startsql = $quarterstart;
				$row->endsql = $enddate;
				$row->startdate = sqlDateToStr($quarterstart);
				$row->enddate = sqlDateToStr($enddate);
				//$selection[$selectionindex] = $row;
			}
				
			$monthnumber = intval($month);
			$monthnumber++;
			if ($monthnumber > 12) {
				$monthnumber = 1;
				$year = intval($year);
				$year++;
			}
			if ($monthnumber < 10) $monthstr = "0" . $monthnumber;
			else $monthstr = $monthnumber;
			$startdate = $year . "-" . $monthstr . "-01";
				
			$counter++;
			if ($counter > 100) break;
	
		}
		return $selection;
	}
	
}

?>
