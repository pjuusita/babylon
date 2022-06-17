<?php

/**
 * 
 * Erityisesti ledger tai journal
 * 
 * 
 * @author pjuusita
 *
 */
class JournalController extends AbstractController {

	
	
	
	public function getCSSFiles() {
		//return array('menu.css','testcss.php','mytheme/jquery-ui.css','prism.css','fileuploader.css','chosentest.css','customhierarchytable.css');
		//return array('menu.css','mytheme/jquery-ui.css','chosentest.css');
		//return array('menu.css','prism.css','chosen.css');
		//return array('menu.css','testcss.php','chosen.css');
		//return array('menu.css','chosentest.css');
		//return array('menu.css','testcss.php');
		return array('babylon.css','menu.css');
		
	}

	
	public function getJSFiles() {
		//return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js','fileuploader.js','timespancolumn.class.js','timeselectcolumn.class.js','changeabledropdowncontentcolumn.class.js','changedropdowncontentcolumn.class.js','timesheetdatecolumn.class.js','timesheetdatepickercolumn.class.js','hourcalculator.class.js','timesheetemptycolumn.class.js','timesheetsplitcolumn.class.js','timesheetremovecolumn.class.js','timesheetbuttonfunctions.class.js','timesheetremovalvalidations.class.js','timesheetfixedcolumn.class.js','timesheetrowidentifier.class.js','timesheetinputcolumn.class.js','timesheetvalidations.class.js','timesheetauxilaryfunctions.class.js');
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}

	
	public function indexAction() {
		//$this->showjournalAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	
	public function showjournalAction () {
		
		//******************************************************************************************************************
		
		$this->registry->receiptsets = Table::load('accounting_receiptsets', "WHERE Deleted='0'");
		
		
		$this->registry->receiptsetID = 1;
		if (isset($_SESSION['journal_receiptsetID'])) $this->registry->receiptsetID = $_SESSION['journal_receiptsetID'];
		
		
		
		
		$this->registry->loadParams();
		
		//$this->registry->journalentries = Table::load('accounting_receiptsets', "WHERE Deleted='0'");
		
		
		/*
		$tables = array();
		$parentKeys = array();
		$childKeys = array();
		$wheres = array();
		
		$tables[0] = "accounting_journalentries"; 
		$parentKeys[0] = "entryID"; 
		$childKeys[0] = ""; 				
		$wheres[0] = "1";
		
		$tables[1] = "accounting_accountentries"; 
		$parentKeys[1] = "entryID"; 
		$childKeys[1] = "journalentryID"; 
		$wheres[1] = "1";
		
		$accountRoot = Table::loadMultiHierarchy($tables,$parentKeys,$childKeys,$wheres);
		
		$accounts	 	= Table::load("accounting_accounts");
		//$suppliers		= Table::load("supply_suppliers");
		$receiptsets 	= Table::load("accounting_receiptsets");
		
		$hierarchyManager = new HierarchyManager();
		
		//$hierarchyManager->addDataToHierarchy($accountRoot,0,"suppliername","supplierID","name",$suppliers);
		//$hierarchyManager->addDataToHierarchy($accountRoot,1,"accountname","accountID","name",$accounts);
		//$hierarchyManager->addDataToHierarchy($accountRoot,1,"accountnumber","accountID","number",$accounts);
		
		$this->registry->accountroot 	= $accountRoot;
		
		//******************************************************************************************************************
		
		if (isset($_GET['selectedaccount'])) {
			$this->registry->selectedaccount=$_GET['selectedaccount'];
		} else {
			$this->registry->selectedaccount=0;
		}
		$this->registry->accounts = Table::load('accounting_accounts');
		
		$accountnumbers = array();
		$accountnames = array();
		foreach($this->registry->accounts as $index => $account) {
			$accountnumbers[$account->accountID] = $account->number;
			$accountnames[$account->accountID] = $account->name;
		}
		$this->registry->accountnumbers = $accountnumbers;
		$this->registry->accountnames = $accountnames;
		
		
		$this->registry->journalentries = Table::load('accounting_journalentries');
		$this->registry->accountentries = Table::load('accounting_accountentries');
		//$this->registry->suppliers = Table::loadKeyValueArray('supply_suppliers', 'SupplierID', 'Name');
		
		foreach($this->registry->journalentries as $index => $journalentry) {
			foreach($this->registry->accountentries as $index => $accountentry) {
				if ($journalentry->entryID == $accountentry->journalentryID) {
					//echo "<br>Add childi";
					$journalentry->addChild($accountentry);
				} else {
					//echo "<br>Not täsmää - " . $journalentry->entryID . " vs. " . $accountentry->journalentryID;
				}	
			}
		}
		*/
		
		$this->registry->template->show('accounting/journal','journal');
	}
	


	private static function addtotable($account, &$selection, $sub) {
		
		if ($account->getChildren() == null) {
			//echo "<br>Nulli";
			return;
		}
		
		foreach($account->getChildren() as $index => $subaccount) {
			if ($subaccount->number == '') {
				//$selection[$subaccount->accountID] = $sub . " " . $subaccount->name;
				//$selection[$subaccount->accountID] = $subaccount;
			} else {
				//$selection[$subaccount->accountID] = $sub . " " . $subaccount->number . " - " . $subaccount->name;
				$selection[$subaccount->accountID] = $subaccount;
			}
			JournalController::addtotable($subaccount,$selection, $sub . "&nbsp;&nbsp;");
		}
		
	}
	
	
	public function shownewentryAction () {
		
		$this->registry->accounts = Table::load('accounting_accounts');
		$selection = array();
		foreach($this->registry->accounts as $index => $account) {
			if ($account->number != '') {
				$selection[$account->accountID] = $account;
			}
			JournalController::addtotable($account,$selection, "&nbsp;&nbsp;");
		}
		//$this->registry->suppliers = Table::loadKeyValueArray('supply_suppliers', 'SupplierID', 'Name');
		$this->registry->receiptsets = Table::loadKeyValueArray('accounting_receiptsets','ReceiptsetID','Name');
		$this->registry->accountselection = Table::load('accounting_accounts');
		
		$journalID = Table::addRow("accounting_journalentries");
		$this->registry->entry = Table::loadRow("accounting_journalentries",$journalID);
		$this->registry->editopen = true;
		
		$accountentries = array();
		$values = array();
		$values['JournalentryID'] = $journalID;
		$rowID = Table::addRow("accounting_accountentries", $values);
		$accountentries[] = Table::loadRow("accounting_accountentries",$rowID);
		$rowID = Table::addRow("accounting_accountentries", $values);
		$accountentries[] = Table::loadRow("accounting_accountentries",$rowID);
		$this->registry->accountentries = Table::load('accounting_accountentries'," WHERE JournalentryID='" . $journalID . "'");
		
		$this->registry->template->show('accounting/journal','newentry');
	}
	
	
	
	public function showentryAction () {

		$journalID = $_GET['id'];
		
		//$this->registry->suppliers = Table::loadKeyValueArray('supply_suppliers', 'SupplierID', 'Name');
		$this->registry->receiptsets = Table::loadKeyValueArray('accounting_receiptsets','ReceiptsetID','Name');
		$this->registry->editopen = false;
		$this->registry->entry = Table::loadRow("accounting_journalentries",$journalID);
		$this->registry->accountentries = Table::load('accounting_accountentries'," WHERE JournalentryID='" . $journalID . "'");
		$this->registry->accountselection = Table::load('accounting_accounts');
		$this->registry->journalentryID = $journalID;
		
		$this->registry->template->show('accounting/journal','newentry');
	}
	
	
	public function updatejournalentryAction() {
		
		/*
		foreach ($_GET as $index => $value) {
			echo "<br>" . $index . "..." . $value;
		}
		*/
		
		$success='';
		$columns = array();
		foreach($_GET as $index => $value) {
			//echo "<br>" . $index . " - " . $value;
			//						$success=$success.$index.'= '.$value.' - ';
			if (($index != 'id') && ($index != 'rt')) {
				
				if ($index == 'Entrydate') {
					//echo "<br>Entrydate - " . $value;
					$day = substr($value, 0, 2);
					$month = substr($value, 3, 2);
					$year = substr($value, 6, 4);
					$datestr = $year . "-" . $month . "-" . $day ;
					//echo "<br>Datemuutettu - " . $datestr;
					$columns[$index] = $datestr;
				} else {
					$columns[$index]=$value;
				}
			} elseif ($index == 'id') {
				$id=$value;
			}
		}
		//		$success=count($_GET);
		$success = Table::updateRow('accounting_journalentries', $columns, $id);
		if ($success === true) {
			echo "[{\"success\":\"true\"}]";
		} else {
			echo "[{\"success\":\"".$success."\"}]";
		}
		
	}
	
	
	
	public function getfreenumberAction() {
		
		global $mysqli;
		
		$receiptsetID = $_GET['receiptsetID'];
		$receiptset = Table::loadRow('accounting_receiptsets',$receiptsetID);
		$sql = "SELECT Receiptnumber FROM accounting_journalentries WHERE ReceiptsetID='" . $receiptsetID . "' ORDER BY Receiptnumber";
		$result = $mysqli->query($sql);
		if (!$result) die('getfreenumberAction failed: ' . $mysqli->connect_error);
		
		$maxnumber = $receiptset->startnumber - 1;
		while($row = $result->fetch_array()) {
			$receiptnumber = $row['Receiptnumber'];
			if ($maxnumber < $receiptnumber) $maxnumber = $receiptnumber;
		}
		$maxnumber = $maxnumber + 1;
		echo "[{\"number\":\"".$maxnumber."\"}]";
	}
	
	
	// Timesheet updateactions
	

	public function updateaccountentrydateAction() {

		$value = $_GET['value'];
		$rowID = $_GET['id'];
		$day = substr($value, 0, 2);
		$month = substr($value, 3, 2);
		$year = substr($value, 6, 4);
		$datestr = $year . "-" . $month . "-" . $day ;
		//$datestr = '01-01-2016';
		
		
		$success = Table::updateRow('accounting_accountentries','Entrydate',$datestr, $rowID);
		returnAjaxResult($success);
	}
	
	
	public function updateaccountselectAction() {
		$value = $_GET['value'];
		$rowID = $_GET['id'];
		$success = Table::updateRow('accounting_accountentries','AccountID',$value, $rowID);
		returnAjaxResult($success);
	}
		
	
	public function updateaccountentrydebetAction() {
		$value = $_GET['value'];
		$rowID = $_GET['id'];
		$success = Table::updateRow('accounting_accountentries','Debet',$value, $rowID);
		returnAjaxResult($success);
	}
	
	
	public function updateaccountentrycreditAction() {
		$value = $_GET['value'];
		$rowID = $_GET['id'];
		$success = Table::updateRow('accounting_accountentries','Credit',$value, $rowID);
		returnAjaxResult($success);	
	}
	
	
	public function insertentryrowAction() {
		
		$rowID = $_GET['journalid'];
		
		$values = array();
		$values['JournalentryID'] = $rowID;
		Table::addRow("accounting_accountentries", $values);
		$insertID = $mysqli->insert_id;
		echo "[{\"insertID\":\"" . $insertID . "\"}]";
		
	}
	
	
	public function removeentryrowAction() {
	
	}
	
	
	
	/*
	public function insertaccountentryAction () {

		$values = array();
		$values['JournalID'] = $_GET['journalid'];
		
		$success = Table::addRow("accounting_accountentries", $values);
		$rowID = $mysqli->insert_id;
		echo "[{\"insertID\":\"".$rowID."\"}]";
	}
	*/
	
	/*
	public function removeaccountentryAction () {
		echo "[{\"success\":\"true\"]";
	}
	*/
	
	
	
	/*
	public function newentryAction () {
			$success='';
			$columns=array();
			
			foreach($_GET as $index => $value) {
				if ($index != 'rt') {
					$columns[$index]=$value;
				}
			}
			//$success = Accountevent::addAccountevent($columns);
			
			if (is_numeric($success)) {
				echo "[{\"success\":\"true\",\"id\":\"".$success."\"}]";
			} else {
				echo "[{\"success\":\"".$success."\"}]";
			}
	}
	*/

	/*
	// tuntematon action, voidaan varmaan poistaa?
	public function tulostatilitapahtumatAction () {
		
		if (isset($_GET['selectedaccount'])) $this->registry->selectedaccount=$_GET['selectedaccount'];
		else $this->registry->selectedaccount=0;
	
		//$this->registry->accountevents = Accountevent::loadEvents();
		$this->registry->accounts = Account::loadAccountArray();
		$this->registry->template->show('accounting/journal','printjournal');
	}
	*/
}
?>