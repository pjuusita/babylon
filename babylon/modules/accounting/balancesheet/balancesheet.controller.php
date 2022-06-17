<?php



class BalanceSheetController extends AbstractController {


	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	

	public function indexAction() {
		//$this->showbalancesheetAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	

	

	public function showbalancesheetAction() {

		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		
		$this->registry->totalprofitaccountID = Settings::getSetting('balancesheet_totalprofitaccountID', 0);
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods','ORDER BY Startdate');
		$previous = null;
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) {
				$this->registry->previousperiod = $previous;
				$this->registry->period = $period;
			}
			$period->fullname = "Tilikausi " . $period->name;
			$previous = $period;
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
		
		if ($selectionID == -1) {
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
		} else {
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
		}
		
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->accounts = Table::load('accounting_accounts');
		
		if ($selectionID == -1) {
			$openingreceipts = Table::load('accounting_receipts', " WHERE ReceiptsetID=8 AND Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Receiptdate", $comments);
			$tempentries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate", $comments);
			$newentries = array();
			foreach($tempentries as $index => $tempentry) {
				if (isset($openingreceipts[$tempentry->receiptID])) {
					//echo "<br>Entry - "  .$tempentry->entryID;
					$newentries[] = $tempentry;	
				}
			}
			$this->registry->entries = $newentries;
			
			//$this->registry->entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
			$this->registry->selectionID = -1;
		} else {
			$this->registry->entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
			
			/*
			$openingreceipts = Table::load('accounting_receipts', " WHERE ReceiptsetID=8 AND Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Receiptdate", $comments);
			$tempentries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate", $comments);
			$newentries = array();
			foreach($tempentries as $index => $tempentry) {
				if (isset($openingreceipts[$tempentry->receiptID])) {
					//echo "<br>Entry - "  .$tempentry->entryID;
				} else {
					$newentries[] = $tempentry;
				}
			}
			$this->registry->entries = $newentries;
			*/
		}
		
		if ($comments) echo "<br>Entrycount - " . count($this->registry->entries);
		foreach($this->registry->entries as $index => $entry) {
			//echo "<br>Entry - " . $entry->entryID . " - ";
		}
		
		$this->registry->accounthierarchy = Table::loadHierarchy('accounting_accounts','parentID','ORDER BY Sortorder');
		
		$this->registry->allaccounts = Table::load('accounting_accounts');
		foreach($this->registry->allaccounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
			$account->startamount = 0;
			$account->selectionamount = 0;
		}
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
		
		$this->registry->periodstartdate = $this->registry->period->startdate;
		if ($comments) echo "<br>Period startdate - " .  $this->registry->period->startdate;
		if ($comments) echo "<br>Current startdate - " .  $startdate;
		
		
		if ($selectionID > -1) {
			$entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
			$receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $enddate . "'");
			
			foreach($entries as $index => $entry) {
				if (!isset($this->registry->allaccounts[$entry->accountID])) {
					echo "<br>ReceiptID - " . $entry->receiptID;
				}
				$account = $this->registry->allaccounts[$entry->accountID];
				if ($entry->entrydate >= $startdate)  {
					$receipt = $receipts[$entry->receiptID];
					if ($receipt->receiptsetID == 8) {
						$account->startamount = $account->startamount + $entry->amount;
					} else {
						$account->selectionamount = $account->selectionamount + $entry->amount;
					}
				} else {
					$account->startamount = $account->startamount + $entry->amount;
				}
			}
		}
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		
		
		$this->registry->template->show('accounting/balancesheet','balancesheet');
		
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
	
	
	public function getEntriesJSONAction() {

		$accountID = $_GET['accountID'];
		$startdate = $_GET['startdate'];
		$enddate = $_GET['enddate'];
		$periodstartdate = $_GET['periodstartdate'];
		
		
		//echo "<br>AccountID - " . $accountID;
		//echo "<br>Startdate - " . $startdate;
		//echo "<br>Enddate - " . $enddate;
				
		$entries = Table::load("accounting_entries","WHERE AccountID=" . $accountID . " AND Entrydate BETWEEN '" . $periodstartdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
			
		$receiptIDs = array();
		foreach($entries as $index => $entry) {
			$receiptIDs[$entry->receiptID] = $entry->receiptID;			
		}
		$receipts = Table::loadWhereInArray("accounting_receipts","ReceiptID", $receiptIDs);
		
		$startamount = 0;
		foreach($entries as $index => $entry) {
			if ($entry->entrydate < $startdate) $startamount = $startamount +  $entry->amount;		
		}
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		
		echo "[";
		$first = true;
		echo " {";
		echo "	  \"entryID\":\"0\",";
		echo "	  \"receiptID\":\"0\",";
		echo "	  \"entrydate\":\"" . $startdate . "\",";
		echo "	  \"amount\":\"" . $startamount . "\",";
		echo "	  \"explanation\":\"Alkusaldo\"";
		
		echo " }";
		foreach($entries as $index => $entry) {
			
			if ($entry->entrydate < $startdate) {
				
			} else {
				echo ",";
				$receipt = $receipts[$entry->receiptID];
				echo " {";
				echo "	  \"entryID\":\"" . $entry->entryID . "\",";
				echo "	  \"receiptID\":\"" . $entry->receiptID . "\",";
				echo "	  \"entrydate\":\"" . $entry->entrydate. "\",";
				echo "	  \"amount\":\"" . $entry->amount . "\",";
				
				if ($receipt->bankstatementID == null) {
					echo "	  \"explanation\":\"0\",";
				} else {
					echo "	  \"bankstatementID\":\"" . $receipt->bankstatementID . "\",";
				}
				
				if ($receipt->invoiceID == null) {
					echo "	  \"invoiceID\":\"0\",";
				} else {
					echo "	  \"invoiceID\":\"" . $receipt->invoiceID . "\",";
				}
				
				if ($receipt->purchaseID == null) {
					echo "	  \"purchaseID\":\"0\",";
				} else {
					echo "	  \"purchaseID\":\"" . $receipt->purchaseID . "\",";
				}
				
				if ($receipt->paycheckID == null) {
					echo "	  \"paycheckID\":\"0\",";
				} else {
					echo "	  \"paycheckID\":\"" . $receipt->paycheckID . "\",";
				}
				
				if (count($this->registry->dimensions) > 0) {
					foreach($this->registry->dimensions as $index => $dimension) {
						$variable = "dimension" . $dimension->dimensionID;
						echo "	  \"" . $variable . "\":\"" . $entry->$variable . "\",";
					}
				}
				echo "	  \"explanation\":\"" . $receipt->explanation . "\"";
				
				echo " }\n";
			}
		}
		echo "]";
		
	}
	
	
	

	public function balancesheetpdfAction() {
	
		$comments = false;
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$companyID = $_SESSION['companyID'];
		$company = Table::loadRow('system_companies', $companyID);
		$this->registry->company = $company;
		
		$this->registry->totalprofitaccountID = Settings::getSetting('balancesheet_totalprofitaccountID', 0);
		
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
		
		if ($selectionID == -1) {
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->startdate;
				
		} else {
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
		}
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->accounts = Table::load('accounting_accounts');
		//$this->registry->entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
		
		/*
		if ($comments) echo "<br>Entrycount - " . count($this->registry->entries);
		foreach($this->registry->entries as $index => $entry) {
			//echo "<br>Entry - " . $entry->entryID . " - ";
		}
		*/
		
		$this->registry->accounthierarchy = Table::loadHierarchy('accounting_accounts','parentID','ORDER BY Sortorder');
		
		$this->registry->allaccounts = Table::load('accounting_accounts');
		foreach($this->registry->allaccounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
			$account->startamount = 0;
			$account->selectionamount = 0;
		}
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
		
		$this->registry->periodstartdate = $this->registry->period->startdate;
		if ($comments) echo "<br>Period startdate - " .  $this->registry->period->startdate;
		if ($comments) echo "<br>Current startdate - " .  $startdate;
		
		if ($selectionID == -1) {
			$receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $enddate . "' AND ReceiptsetID=8");
			$entriestemp = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
			$entries = array();
			foreach($entriestemp as $index => $entry) {
				if (isset($receipts[$entry->receiptID])) $entries[$entry->entryID] = $entry;
			}
		} else {
			$entries = Table::load('accounting_entries', " WHERE Entrydate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $enddate . "' ORDER BY Entrydate");
			$receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $enddate . "'");
		}
		$this->registry->entries = $entries;
		
		foreach($entries as $index => $entry) {
			if (!isset($this->registry->allaccounts[$entry->accountID])) {
				echo "<br>ReceiptID - " . $entry->receiptID;
			}
			$account = $this->registry->allaccounts[$entry->accountID];
			if ($entry->entrydate >= $startdate)  {
		
				$receipt = $receipts[$entry->receiptID];
				//echo "<br>EntryreceiptID - " . $entry->entryID . " - " . $entry->receiptID;
				//echo "<br>ReceiptserieID - " . $receipt->receiptsetID;
				if ($receipt->receiptsetID == 8) {
					$account->startamount = $account->startamount + $entry->amount;
				} else {
					$account->selectionamount = $account->selectionamount + $entry->amount;
				}
		
			} else {
				$account->startamount = $account->startamount + $entry->amount;
			}
		}
		
		
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		$this->registry->template->show('accounting/balancesheet','balancesheetpdf');
	}
	
	
}

?>
