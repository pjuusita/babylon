<?php



class IncomestatementController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		$this->showincomestatementAction();
		//$this->registry->template->show('system/error','unknown');
	}
	
	public function showincomestatementAction() {
	
		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
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
		
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		
		if ($selectionID == 0) {
		
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
		
		} else {
			$currentselect = $selection[$selectionID];
			if ($comments) echo "<br>current start - " . $currentselect->startsql;
			if ($comments) echo "<br>current end - " . $currentselect->endsql;
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
			if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
			$this->registry->selectionID = $selectionID;
		}
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
		
		
		
		$this->registry->accounthierarchy = Table::loadHierarchy('accounting_accounts','parentID',' ORDER BY Number');
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
		

		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		//echo "<br>Dimensioncount - " . count($this->registry->dimensions);
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		
		
		
		
		$this->registry->template->show('accounting/incomestatement','incomestatement');
		
	}
	
	
	
	
		
	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showincomestatement_from_accountbalancesAction() {
	
		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;	
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;

		
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
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
		
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		
		if ($selectionID == 0) {

			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
				
		} else {
			$currentselect = $selection[$selectionID];
			if ($comments) echo "<br>current start - " . $currentselect->startsql;
			if ($comments) echo "<br>current end - " . $currentselect->endsql;
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
			if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
			$this->registry->selectionID = $selectionID;				
		}
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->allaccounts = Table::load('accounting_accounts');
		$this->registry->allentries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
		$sums = array();
		$currentaccount = null;
		$selectedaccounts = array();
		$selectedentries = array();
		$tulotilit = array();
		$menotilit = array();
		$velkatilit = array();
		$omaisuustilit = array();
		
		foreach($this->registry->allentries as $index => $entry) {
			
			
			if (!isset($this->registry->allaccounts[$entry->accountID])) {
				if ($comments) echo "<br>Account not found - " . $entry->accountID;
			} else {
				$account = $this->registry->allaccounts[$entry->accountID];
			}
			
			if ($entry->accountID == 14) {
				if ($comments) echo "<br>14 amount - " . $entry->entryID . " - " . $entry->amount;
				if ($comments) echo "<br>accounttypeID - " . $account->accounttypeID;
			}
				
			if ($account->accounttypeID == 3) {
				$account->total = $account->total + $entry->amount;
				$tulotilit[$account->accountID] = $account;
			}
			
			if ($account->accounttypeID == 4) {
				$account->total = $account->total + $entry->amount;
				$menotilit[$account->accountID] = $account;
			}
			
			if ($account->accounttypeID == 1) {
				$account->total = $account->total + $entry->amount;
				$omaisuustilit[$account->accountID] = $account;
			}
			
			if ($account->accounttypeID == 2) {
				$account->total = $account->total + $entry->amount;
				$velkatilit[$account->accountID] = $account;
			}
		}
		
		$this->registry->tulotilit = $tulotilit;
		$this->registry->menotilit = $menotilit;
		$this->registry->omaisuustilit = $omaisuustilit;
		$this->registry->velkatilit = $velkatilit;
		
		
		//echo "<br>accountcount - " . count($selectedaccounts);
		$this->registry->accounts = $selectedaccounts;
		$this->registry->currentaccount = $currentaccount;
		//if ($currentaccount != null) $this->registry->currentaccountID = $currentaccount->accountID;
		
		$this->registry->template->show('accounting/incomestatement','incomestatement');
	}
	
	

	public function incomestatementpdfAction() {
	
		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;	
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;

		$companyID = $_SESSION['companyID'];
		$company = Table::loadRow('system_companies', $companyID);
		$this->registry->company = $company;
		
		$this->registry->period = Table::loadRow('accounting_periods', $periodID);

		$startdate = $this->registry->period->startdate;
		$enddate = $this->registry->period->enddate;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		//$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
		$this->registry->accounthierarchy = Table::loadHierarchy('accounting_accounts','parentID',' ORDER BY Number');
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		$selectionID = 0;
		if ($oldperiodID != $periodID) {		// tilikautta on vaihdettu
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
		
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		
		if ($selectionID == 0) {

			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
				
		} else {
			$currentselect = $selection[$selectionID];
			if ($comments) echo "<br>current start - " . $currentselect->startsql;
			if ($comments) echo "<br>current end - " . $currentselect->endsql;
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
			if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
			$this->registry->selectionID = $selectionID;				
		}
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->allaccounts = Table::load('accounting_accounts', "ORDER BY Number");
		$this->registry->allentries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
		$sums = array();
		$currentaccount = null;
		$selectedaccounts = array();
		$selectedentries = array();
		$tulotilit = array();
		$menotilit = array();
		$velkatilit = array();
		$omaisuustilit = array();
		
		foreach($this->registry->allentries as $index => $entry) {
			
			$foundsome = false;
			if (!isset($this->registry->allaccounts[$entry->accountID])) {
				if ($comments) echo "<br>Account not found - " . $entry->accountID;
			} else {
				$account = $this->registry->allaccounts[$entry->accountID];
			}
			
			if ($entry->accountID == 14) {
				if ($comments) echo "<br>14 amount - " . $entry->entryID . " - " . $entry->amount;
				if ($comments) echo "<br>accounttypeID - " . $account->accounttypeID;
				$foundsome = true;
			}
				
			if ($account->accounttypeID == 3) {
				$account->total = $account->total + $entry->amount;
				$tulotilit[$account->accountID] = $account;
				$foundsome = true;
			}
			
			if ($account->accounttypeID == 4) {
				$account->total = $account->total + $entry->amount;
				$menotilit[$account->accountID] = $account;
				$foundsome = true;
			}
			
			if ($account->accounttypeID == 1) {
				$account->total = $account->total + $entry->amount;
				$omaisuustilit[$account->accountID] = $account;
				$foundsome = true;
			}
			
			
			
			if ($account->accounttypeID == 2) {
				$account->total = $account->total + $entry->amount;
				$velkatilit[$account->accountID] = $account;
				$foundsome = true;
			}
			
			//if ($foundsome == false) {
			//	echo "<br>Accounttypemissing - " . $account->accountID;
			//}
		}
		
		$this->registry->tulotilit = $tulotilit;
		$this->registry->menotilit = $menotilit;
		$this->registry->omaisuustilit = $omaisuustilit;
		$this->registry->velkatilit = $velkatilit;
		
		
		//echo "<br>accountcount - " . count($selectedaccounts);
		//$this->registry->accounts = $selectedaccounts;
		$this->registry->accounts = $this->registry->allaccounts;
		
		$this->registry->currentaccount = $currentaccount;
		//if ($currentaccount != null) $this->registry->currentaccountID = $currentaccount->accountID;
		
		//$this->registry->template->show('accounting/incomestatement','incomestatement');
		
		//echo "Jee";
		$this->registry->template->show('accounting/incomestatement','incomestatementpdf');
	}
	
	
	private function generateYearTimescales($periods, &$selectedindex, $currentdate = null) {
	
		$comments = false;
		$selection = array();
		$selectionindex = 0;
	
		if ($currentdate == null) {
			$currentdate = date("Y-m-d");
		}
		if ($comments) echo "<br>Current - " . $currentdate;
		//$selectedindex = 0;
	
		foreach($periods as $index => $period) {
			if ($comments) echo "<br>" . $period->startdate . " - " . $period->enddate;
				
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
	
	
				if ($comments) echo "<br>" . $year . "/" . $month . " --- " . sqlDateToStr($startdate) . " - " . sqlDateToStr($enddate);
				$selectionindex++;
				$row->rowID = $selectionindex;
				$row = new Row();
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
					$selection[$selectionindex] = $row;
				}
			}
		}
		return $selection;
	}
	
	
	
	/*
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
		* /
		
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
	*/
	
}

?>
