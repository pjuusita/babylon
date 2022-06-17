<?php



class AccountbalancesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showaccountbalancesAction() {
		
		updateActionPath('accounting_title_accountbalance');
		
		
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
		
		if ($comments) {
			echo "<br>Fullname - "  .$period->fullname;
		}
		
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
			$receiptsetID = getModuleSessionVar('receiptsetID');
			$selectionID = getModuleSessionVar('selectionID');
			$startdate = getModuleSessionVar('periodstartdate');
			$enddate = getModuleSessionVar('periodenddate');
		}
		
		$selection = Collections::generatePeriodTimescales($period, $selectionID);
		if ($comments) echo "<br>selection - " . count($selection);
		
		$this->registry->selection = $selection;
		

		$this->registry->receiptsetID = $receiptsetID;
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		
		$selectedaccountID = getModuleSessionVar('selectedaccountID',0);
		if (isset($_GET['accountID'])) {
			$selectedaccountID = $_GET['accountID'];
			//echo "<br>Accountti - " . $selectedaccountID;
			setModuleSessionVar('selectedaccountID',$selectedaccountID);
		}
		
		
		if ($selectedaccountID == 0) {
			echo "<br>Näytetään kaikki saldont...";
		}
		
		if ($comments) {
			echo "<br>Startdate - "  .$startdate;
			echo "<br>Enddate - "  .$enddate;
		}
		$this->registry->receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Receiptdate");
		
		//$this->registry->receipts = Table::load('accounting_receipts');
		$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		
		
		$this->registry->allaccounts = Table::load('accounting_accounts');
		
		$this->registry->allentries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
		$sums = array();
		$currentaccount = null;
		$selectedaccounts = array();
		$selectedentries = array();
		foreach($this->registry->allentries as $index => $entry) {
			
			//echo "<br>Receipt - " . $entry->receiptID;
			//echo "<br>AccountID - " . $entry->accountID;
				
			$receipt = $this->registry->receipts[$entry->receiptID];
			
			$entry->receiptnumber = $receipt->receiptnumber;
			$entry->costpoolID = $receipt->costpoolID;
			
			//if ($selectedaccountID == 0) {
				//$selectedaccountID = $entry->accountID;
				//$currentaccount = $this->registry->allaccounts[$entry->accountID];
			//}
			$testaccount =  $this->registry->allaccounts[$entry->accountID];
			//echo "<br>Entry - " . $entry->entryID . " - " . $entry->accountID . ", amount: " . $entry->amount . " - " . $testaccount->name;
				
			
			if ($selectedaccountID == $entry->accountID) {
				$selectedaccountID = $entry->accountID;
				$currentaccount = $this->registry->allaccounts[$entry->accountID];
			}
			$selectedaccountID = 0;
				
			
			if ($entry->accountID == $selectedaccountID) {
				//echo "<br>Selectedentry - " . $entry->accountID . " - " . $entry->accountID . " - " . $selectedaccountID;
				$selectedentries[] = $entry;
			}
			
			if (isset($sums[$entry->accountID])) {
				$sums[$entry->accountID] = $sums[$entry->accountID] + $entry->amount;
				$entry->balance = $sums[$entry->accountID];
				//echo "<br>entry -> accountID - " . $entry->accountID;
				$selectedaccounts[$entry->accountID] = $this->registry->allaccounts[$entry->accountID];
			} else {
				$entry->balance = $entry->amount;
				$sums[$entry->accountID] = $entry->amount;
				$selectedaccounts[$entry->accountID] = $this->registry->allaccounts[$entry->accountID];
			}
		}
		//echo "<br>accountcount - " . count($selectedaccounts);
		$this->registry->accounts = $selectedaccounts;
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		$this->registry->currentaccount = $currentaccount;
		//if ($currentaccount != null) $this->registry->currentaccountID = $currentaccount->accountID;
		
		$this->registry->entries = $selectedentries;
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
		$this->registry->template->show('accounting/accountbalances','accountbalances');
	}
	
	
	
	public function showsummaryAction() {
	
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
	
		$selection = $this->generatePeriodTimescales($period, $selectionID);
		if ($comments) echo "<br>selection - " . count($selection);
		
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
		$muuttilit = array();
		
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
	
			$typefound = false;
			if ($account->accounttypeID == 1) {
				$account->total = $account->total + $entry->amount;
				$omaisuustilit[$account->accountID] = $account;
				$typefound = true;				
			}
				
			if ($account->accounttypeID == 2) {
				$account->total = $account->total + $entry->amount;
				$velkatilit[$account->accountID] = $account;
				$typefound = true;				
			}
				
			if ($account->accounttypeID == 3) {
				$account->total = $account->total + $entry->amount;
				$tulotilit[$account->accountID] = $account;
				$typefound = true;				
			}
				
			if ($account->accounttypeID == 4) {
				$account->total = $account->total + $entry->amount;
				$menotilit[$account->accountID] = $account;
				$typefound = true;				
			}
			
			if ($typefound == false) {
				$account->total = $account->total + $entry->amount;
				$muuttilit[$account->accountID] = $account;
			}
		}
	
		$this->registry->tulotilit = $tulotilit;
		$this->registry->menotilit = $menotilit;
		$this->registry->omaisuustilit = $omaisuustilit;
		$this->registry->velkatilit = $velkatilit;
		$this->registry->muuttilit = $muuttilit;
		
		
		//echo "<br>accountcount - " . count($selectedaccounts);
		$this->registry->accounts = $selectedaccounts;
		$this->registry->currentaccount = $currentaccount;
		//if ($currentaccount != null) $this->registry->currentaccountID = $currentaccount->accountID;
	
		$this->registry->template->show('accounting/accountbalances','summary');
	}
	
	
	

	public function showaccountbalanceAction() {
		
		$comments = false;
		updateActionPath("Saldot");
		
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
			
			if ($periodID == 0) {
				echo "<br>Pediodia ei aiemmin asetettu";
				exit;
			}
			
			if ($comments) echo "<br>Period not changed";
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			if ($comments) echo "<br>SelectionID get - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			$enddate = getModuleSessionVar('periodenddate');
		}
		
		
		if ($comments) echo "<br>selectedperiod - " . $this->registry->periodID;
		$selection = $this->generatePeriodTimescales($this->registry->period, $selectionID);
		if ($comments) echo "<br>selection - " . count($selection);
		
		$this->registry->selection = $selection;
		
		if (($selectionID == 0) || ($selectionID == -1)) {
		
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
		
		$selectedaccountID = getModuleSessionVar('selectedaccountID', 0);
		if ($comments) echo "<br>selectedaccount - " . $selectedaccountID;
			
		
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		$this->registry->selectedaccountID = $selectedaccountID;
		
		if ($comments) echo "<br>Selectedaccount - " . $selectedaccountID;
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		/*
		if ($selectedaccountID == 0) {
			foreach($this->registry->accounts as $index => $account) {
				$selectedaccountID = $account->accountID;
				break;
			}
		}
		*/
		echo "<br>selectedaccountID - " . $selectedaccountID;
			
		if ($selectedaccountID == 0) {
			echo "<br>Näytetään kaikki saldot aaa";
				
			$accounts = Table::load('accounting_accounts');
			$entries = Table::load('accounting_entries', "WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate, EntryID");
			$receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "'", $comments);
				
			$sums = array();
			$currentaccount = null;
			$selectedaccounts = array();
			$selectedentries = array();
			$tulotilit = array();
			$menotilit = array();
			$velkatilit = array();
			$omaisuustilit = array();
			$muuttilit = array();
			
			$balance  = 0;
			

			foreach($accounts as $index => $account) {
				$account->total = 0;
			}
				
			
			foreach($entries as $index => $entry) {
				//echo "<br>account - " . $entry->accountID . " - " . $entry->amount;
				
				$account = $accounts[$entry->accountID];
				$account->total = $account->total + $entry->amount;
			}
			
			foreach($accounts as $index => $account) {
				//echo "<br>" . $account->accountID . " - " . $account->accountnumber . " - "  .$account->name . " -- " . $account->total;
			}

			foreach($accounts as $index => $account) {
				$typefound = false;
				//echo "<br>account - " . $account->accounttypeID;
					
				if ($account->accounttypeID == 1) {
					if ($account->total != 0) {
						$omaisuustilit[$account->accountID] = $account;
					}
					$typefound = true;
				}
				
				if ($account->accounttypeID == 2) {
					if ($account->total != 0) {
						$velkatilit[$account->accountID] = $account;
					}
					$typefound = true;
				}
				
				if ($account->accounttypeID == 3) {
					if ($account->total != 0) {
						$tulotilit[$account->accountID] = $account;
					}
					$typefound = true;
				}
				
				if ($account->accounttypeID == 4) {
					if ($account->total != 0) {
						$menotilit[$account->accountID] = $account;
					}
					$typefound = true;
				}
					
				if ($typefound == false) {
					if ($account->total != 0) {
						$muuttilit[$account->accountID] = $account;
					}
				}
				$account->fullname = $account->number . " " . $account->name;
					
			}
			
			/*
			foreach($this->registry->accounts as $index => $account) {
				$account->fullname = $account->number . " " . $account->name;
			}
			*/

			echo "<br>Accountsss.... - " . count($accounts);
			$this->registry->accounts = $accounts;
			$this->registry->tulotilit = $tulotilit;
			$this->registry->menotilit = $menotilit;
			$this->registry->omaisuustilit = $omaisuustilit;
			$this->registry->velkatilit = $velkatilit;
			$this->registry->muuttilit = $muuttilit;
			echo "<br>Accountsss.... - " . count($this->registry->accounts);
				
			$this->registry->template->show('accounting/accountbalances','summary');
			exit;
			
			
		} else {
			
			if ($comments) echo "<br>SelectedAccount aa - " . $selectedaccountID;
			$this->registry->account = Table::loadRow('accounting_accounts', "WHERE AccountID=" . $selectedaccountID);
			if ($comments) echo "<br>SelectedAccount cc - " . $selectedaccountID;
			$this->registry->entries = Table::load('accounting_entries', "WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND AccountID=" . $selectedaccountID . " ORDER BY Entrydate, EntryID");
			
			// TODO: Tämä lataa ylimääräisiä rivejä, mutta en jaksa nyt filtteröidä
			$this->registry->receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "'", $comments);
			if ($comments) echo "<br>Entrycount - " . count($this->registry->entries);
			

			$sums = array();
			$currentaccount = null;
			$selectedaccounts = array();
			$selectedentries = array();
			$tulotilit = array();
			$menotilit = array();
			$velkatilit = array();
			$omaisuustilit = array();
			
			$balance  = 0;
			foreach($this->registry->entries as $index => $entry) {
			
				if (!isset($this->registry->receipts[$entry->receiptID])) {
					echo "<br>Unknown receipt - " . $entry->receiptID . " (entryID:" . $entry->entryID . ")";
				}
				$receipt = $this->registry->receipts[$entry->receiptID];
					
				$entry->explanation = $receipt->explanation;
				$entry->receiptnumber = $receipt->receiptnumber;
			
					
				if ($entry->accountID == 14) {
					if ($comments) echo "<br>14 amount - " . $entry->entryID . " - " . $entry->amount;
					if ($comments) echo "<br>accounttypeID - " . $account->accounttypeID;
				}
			
				if ($account->accounttypeID == 3) {
					$account->total = $account->total + $entry->amount;
				}
			
				if ($account->accounttypeID == 4) {
					$account->total = $account->total + $entry->amount;
				}
			
				if ($account->accounttypeID == 1) {
					$account->total = $account->total + $entry->amount;
				}
			
				if ($account->accounttypeID == 2) {
					$account->total = $account->total + $entry->amount;
				}
					
				$balance = $balance + $entry->amount;
				$entry->balance = $balance;
			}
			$this->registry->currentaccount = $currentaccount;
			
		}
		
				
		
		
		$this->registry->template->show('accounting/accountbalances','accountbalance');
	}
	

	
	
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
