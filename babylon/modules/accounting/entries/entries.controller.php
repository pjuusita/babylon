<?php



class EntriesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showentriesAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showentriesAction() {

		$comments = false;
		
		$oldperiodID = getOldModuleSessionVar('periodID');
		if ($comments) echo "<br>OldperiodID - " . $oldperiodID;
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
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
		
		if (isset($_GET['receiptsetID'])) {
			$receiptsetID = $_GET['receiptsetID'];
		} else {
			//echo "<br>ReceiptSerie pitää olla valittuna";
		}
		
		if (isset($_GET['receiptID'])) {
			$receiptID = $_GET['receiptID'];
		} else {
			$receiptID = 0;
		}
		
		
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		//$selection = Collections::generatePeriodTimescales($this->registry->period, $selectedindex);
		
		
		$this->registry->receipts = Table::load('accounting_receipts', " WHERE ReceiptsetID=" . $receiptsetID . " AND Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Receiptdate");
		foreach($this->registry->receipts as $index => $receipt) {
			if ($receiptID = $receipt->receiptID) {
				$this->registry->receipt = $receipt;
			}
		}
		if ($receiptID != 0) {
			$this->registry->entries = Table::load('accounting_entries', "WHERE receiptID=" . $receiptID . " ORDER BY Entrydate, EntryID");
		} else {
			$this->registry->entries = array();
		}
		
		$sum = 0;
		$lastdate = $startdate;
		foreach($this->registry->entries as $index => $entry) {
				$sum = $sum + $entry->amount;
				$entry->total = $sum;
				if ($entry->entrydate > $lastdate) $lastdate = $entry->entrydate;
		}
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->receiptID = $receiptID;
		
		$this->registry->receiptsetID = $receiptsetID;
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		$this->registry->lastentrydate = $lastdate;
		
		
		
		$this->registry->template->show('accounting/entries','entries');
	}
	
	
	


	public function insertentryAction() {
	
		global $mysqli;
		$comments = false;
	
		$receiptID = $_GET['receiptID'];
		$accountID = $_GET['accountID'];
		$entrydate = $_GET['entrydate'];
		$grossamount = floatval(str_replace(",",".",$_GET['amount']));
	
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $accountID;
		$values['Entrydate'] = $entrydate;
		$values['Amount'] = $grossamount;
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
		$success = Table::addRow("accounting_entries", $values, $comments);
		if (!$comments) redirecttotal('accounting/entries/showentries',null);
	}
	


}

?>
