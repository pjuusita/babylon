<?php



class ReceiptsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showreceiptsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showreceiptsAction() {
	
		$comments = false;
		updateActionPath("Tositteet");
		
	
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
			$receiptsetID = getModuleSessionVar('receiptsetID',0);
			$selectionID = getModuleSessionVar('selectionID');
			if ($comments) echo "<br>SelectionID - " . $selectionID;
			$startdate = getModuleSessionVar('periodstartdate');
			$enddate = getModuleSessionVar('periodenddate');
		}
		
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		if ($selectionID == 0) {
			if ($comments) echo "<br>selection id nolla - " . $selectionID;
			foreach($selection as $index => $value) {
				//$selectionID = $index;
				if ($comments) echo "<br>First - " . $selectionID;
				break;
			}
		}
		if (($selectionID == 0) || ($selectionID == -1)) {
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
			if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		} else {
			$currentselect = $selection[$selectionID];
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
			if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		}
		$this->registry->selectionID = $selectionID;
		
		
		
		
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		//$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		
		if ($comments) echo "<br>ReceiptsetID - " . $receiptsetID;
		
		if ($receiptsetID == 0) {
			$this->registry->receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Receiptdate");
		} else {
			$this->registry->receipts = Table::load('accounting_receipts', " WHERE ReceiptsetID=" . $receiptsetID . " AND Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Receiptdate");
		}
		
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->receiptsetID = $receiptsetID;
		$this->registry->selectedindex = $selectionID;
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$lastdate = $startdate;
		if ($this->registry->receipts != null) {
			foreach($this->registry->receipts as $index => $receipt) {
				if ($receipt->receiptdate > $lastdate) $lastdate = $receipt->receiptdate;
				
				//if ($receipt->grossamount == $receipt->accounted) $receipt->status = 1;
				//else if ($receipt->accounted == 0) $receipt->status = 3;
				//else $receipt->status = 3;
			}
		}
		
		
		/*
		if ($this->registry->receipt->supplierID > 0) {
			$this->registry->supplier = $this->registry->receipt->supplierID;
			//$this->registry->supplier = Table::load("accounting_supliers", $this->registry->receipt->suplierID);
		}
		*/
		

		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		
		
		
		$this->registry->receiptlastdate = $lastdate;
		$this->registry->template->show('accounting/receipts','receipts');
	}
	
	
	
	public function showopeningreceiptAction() {
	
		$comments = false;
		updateActionPath("Avaustosite");
		
		$periodID = AccountingModule::getBookkeepingPeriod();
		if ($comments) echo "<br>PeriodID - " . $periodID;
		$this->registry->periodID = $periodID;
		
		$this->registry->periodID = $periodID;
		$period = Table::loadRow('accounting_periods', $periodID, $comments);
		
		$receipt = Table::loadRow("accounting_receipts","WHERE ReceiptsetID=8 AND Receiptdate='" . $period->startdate . "'", $comments);
		
		if ($receipt == null) {
			echo "<br>Avaustosite puuttuu";
			$this->registry->entries = array();
		} else {
			if ($comments) echo "<br>ReceiptID - " . $receipt->receiptID;
			$receiptID = $receipt->receiptID;
			$this->registry->entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $receipt->receiptID);
		}
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->accounthierarchy = Table::loadHierarchy('accounting_accounts','parentID');
		$this->registry->accounttypes = Table::load('accounting_accounttypes');
		
	
		$this->registry->template->show('accounting/receipts','openingreceipt');
	
	}
	
	
	
	
	public function showreceiptAction() {
	
		$receiptID = $_GET['id'];
		updateActionPath("Tosite " . $receiptID);
		
		$this->registry->costpooltypes = Collections::getCostpoolTypes();
		$this->registry->periods = Table::load('accounting_periods');
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		$this->registry->receipt = Table::loadRow('accounting_receipts', $receiptID);
		$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		$this->registry->vats = Table::load('system_vats');
		$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
		foreach($this->registry->vatcodes as $index => $code) {
			if ($code->name != "") {
				$code->name = $code->vatcode . " - " . $code->name;
			}
		}
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		updateActionPath("Tosite " . $this->registry->receipt->receiptID);
		
		
		$this->registry->entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $receiptID);
		$debet = 0;
		$credit = 0;
		foreach($this->registry->entries as $index => $entry) {
			if ($entry->amount < 0) {
				$entry->debet = 0;
				$entry->credit = -1 * $entry->amount;
				$credit = $credit + $entry->credit;
			} else {
				$entry->debet = $entry->amount;
				$debet = $debet + $entry->debet;
				$entry->credit = 0;
			}
		}
		if ($this->registry->receipt->debet == 0) {
			if ($debet > 0) echo "<br>Receipt debet is null";
			
			// TODO: Pitäisikö päivittää?
			
		}
		if ($this->registry->receipt->credit == 0) {
			if ($credit > 0) echo "<br>Receipt credit is null";
		}
		//$this->registry->receipt->debet = $debet;
		//$this->registry->receipt->credit = $credit;
		
		if ($this->registry->receipt->debet != $debet) echo "<br>Receipt debet - differs";
		if ($this->registry->receipt->credit != $credit) echo "<br>Receipt credit - differs";
		
		
		/*
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		
		if (count($this->registry->dimensions) > 0) {
			$dimensionvalues = Table::load('system_dimensionvalues');
			$sorteddimensionvalues = array();
			foreach($dimensionvalues as $index => $dimensionvalue) {
				if (!isset($sorteddimensionvalues[$dimensionvalue->dimensionID])) $sorteddimensionvalues[$dimensionvalue->dimensionID] = array();
				$sorteddimensionvalues[$dimensionvalue->dimensionID][$dimensionvalue->dimensionvalueID] = $dimensionvalue;
			}
			$this->registry->dimensionvalues = $sorteddimensionvalues;
		} else {
			$this->registry->dimensionvalues = array();
		}
		*/
		
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		
		$this->registry->receipttypes = Collections::getReceiptTypes();
		
		$this->registry->clients = Table::load('crm_companies','ORDER BY Name');
		$this->registry->workers = Table::load('hr_workers','ORDER BY Lastname, Firstname');
		foreach($this->registry->workers as $index => $worker) {
			$worker->fullname = $worker->firstname . " " . $worker->lastname;
		}
		$this->registry->suppliers = Table::load('accounting_suppliers','ORDER BY Name');
		
		
		$this->registry->template->show('accounting/receipts','receipt');
	}
	

	
	
	// TODO: mistähän tätä kutsutaan? tarvitaanko supplierID asetusta?
	public function insertreceiptAction() {
	
		//echo "<br>Receiptdate - " . $_GET['receiptdate'];
		global $mysqli;
		
		$receiptsetID = $_GET['receiptsetID'];
		
		$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
		//echo "<br>receiptnumber - "  . $receiptnumber;
		//echo "<br><br>";

		$grossAmount = str_replace(",",".",$_GET['grossamount']);
				
		$values = array();
		$values['Receiptdate'] = $_GET['receiptdate'];
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $_GET['receiptsetID'];
		$values['Explanation'] = $_GET['explanation'];
		$values['Debet'] = 0;
		$values['Credit'] = 0;
		
		//$values['ReceiverID'] = $_GET['receiverID'];
		//$values['CostpoolID'] = $_GET['costpoolID'];
		//$values['Grossamount'] = $grossAmount;
		//$values['Netamount'] = $grossAmount;
		$receiptID = Table::addRow("accounting_receipts", $values, false);
		
		redirecttotal('accounting/receipts/showreceipt&id=' . $receiptID,null);
	}
	
	
	

	public function insertentryAction() {
	
		global $mysqli;
		$comments = false;
		
		$accountID = $_GET['accountID'];
		$receiptID = $_GET['receiptID'];
		
		$vatcodeID = 0;
		if (isset($_GET['vatcodeID'])) {
			$vatcodeID = $_GET['vatcodeID'];
		}
		$debet = getFloatParam('debet');
		$credit = getFloatParam('credit');
		
		if (($debet != 0) && ($credit != 0)) {
			echo "<br>Entryssä ei voi olla molemmat erisuuria kuin nolla";
			exit;
		}
		
		//$amount = floatval(str_replace(",",".",$_GET['amount']));
		
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		
		
	
		$receipt = Table::loadRow('accounting_receipts', $receiptID, $comments);
		$account = Table::loadRow('accounting_accounts', $accountID, $comments);
		
		if (($account->accounttypeID == null) || ($account->accounttypeID == 0)) {
			echo "<br>Tilin tyyppi puuttuu - " . $account->number . " - " . $account->name;
			exit;
		} else {
			$accounttype = Table::loadRow('accounting_accounttypes', $account->accounttypeID,$comments);
			if ($comments) echo "<br>Accountti creditvalue  - " . $accounttype->credit . " - " .  $accounttype->name;
			$receiptaccoutcredit = $accounttype->credit;
			//echo "<br>Accounttype - " . $accounttype->name;
		}
		
		if ($comments) echo "<br>";
		
		
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $accountID;
		$values['Entrydate'] = $receipt->receiptdate;
		$values['VatcodeID'] = $vatcodeID;
		
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension'.+ $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		
		
		
		if ($receiptaccoutcredit == 0) {
			if ($comments) echo "<br>-- receiptaccountcredit - 0";
			if ($comments) echo "<br>Debet = " . $debet;
			if ($comments) echo "<br>Credit = " . $credit;
			
			if ($debet > 0) {
				$values['Amount'] = $debet;
			} else {
				$values['Amount'] = -1 * $credit;
			}
		} else {
			if ($comments) echo "<br>-- receiptaccountcredit - 1";
			if ($comments) echo "<br>Debet = " . $debet;
			if ($comments) echo "<br>Credit = " . $credit;
				
			if ($debet > 0) {
				$values['Amount'] = $debet;
			} else {
				$values['Amount'] = -1 * $credit;
			}
		}
	
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
		$success = Table::addRow("accounting_entries", $values, $comments);
		
		
		//$this->updateReceiptAccounted($receiptID, $comments);
		$this->updateReceiptDebetAndCredit($receiptID, $comments);
		
		
		if (!$comments) redirecttotal('accounting/receipts/showreceipt&id=' . $receiptID ,null);
	}
	

	
	/*
	public function insertpaymententryAction() {
	
		global $mysqli;
		$comments = false;
	
		$receiptID = $_GET['receiptID'];
		$accountID = $_GET['accountID'];
		$vatID = $_GET['vatID'];
		$paymentmethodID = $_GET['paymentmethodID'];
		$grossamount = floatval(str_replace(",",".",$_GET['amount']));
	
		$receipt = Table::loadRow('accounting_receipts', $receiptID,$comments);
		$account = Table::loadRow('accounting_accounts', $accountID,$comments);
	
		if (($account->accounttypeID == null) || ($account->accounttypeID == 0)) {
			echo "<br>Tilin tyyppi puuttuu - " . $account->number . " - " . $account->name;
			exit;
		} else {
			$accounttype = Table::loadRow('accounting_accounttypes', $account->accounttypeID,$comments);
			if ($comments) echo "<br>Accountti creditvalue  - " . $accounttype->credit . " - " .  $accounttype->name;
			$receiptaccoutcredit = $accounttype->credit;
			//echo "<br>Accounttype - " . $accounttype->name;
		}
	
		if ($comments) echo "<br>";
		$paymentmethods = Table::loadRow('accounting_paymentmethods',$paymentmethodID,$comments);
		if ($comments) echo "<br>";
		$vat = Table::loadRow('system_vats', $vatID,$comments);
	
		if ($vat->percent > 0) {
			if ($comments) echo "<br>Tehdään alv vienti myös, alviprosentti yli nollan";
				
			$netamount = $grossamount / (1+$val->percent);
			$vatamount = $grossamount - $netamount;
				
			if ($comments) echo "<br>netamount - " . $netamount;
			if ($comments) echo "<br>grossamount - " . $grossamount;
			if ($comments) echo "<br>vatamount - " . $vatamount;
		} else {
				
			$netamount = $grossamount;
		}
	
		$paymentaccountID = $paymentmethods->accountID;
		$paymentaccount = Table::loadRow('accounting_accounts', $paymentaccountID,$comments);
	
		if (($paymentaccount->accounttypeID == null) || ($paymentaccount->accounttypeID == 0)) {
			echo "<br>Maksutilin tyyppi puuttuu - " . $paymentaccount->number . " - " . $paymentaccount->name;
			exit;
		} else {
			$paymentaccounttype = Table::loadRow('accounting_accounttypes', $paymentaccount->accounttypeID,$comments);
			if ($comments) echo "<br>Maksutilin creditvalue  - " . $paymentaccounttype->credit . " - " .  $paymentaccounttype->name;
			//echo "<br>Accounttype - " . $accounttype->name;
				
			if ($comments) echo "<br>Tehdään maksutilivienti";
			$values = array();
			$values['ReceiptID'] = $receiptID;
			$values['AccountID'] = $paymentaccountID;
			$values['Entrydate'] = $receipt->receiptdate;
			if ($paymentaccounttype->credit == 0) {
				$values['Amount'] = $grossamount;
			} else {
				$values['Amount'] = -1 * $grossamount;
			}
			if ($comments) echo "<br>";
			if ($comments) var_dump($values);
			$success = Table::addRow("accounting_entries", $values, $comments);
		}
	
	
	
		// tehdään kulutilivienti
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $accountID;
		$values['Entrydate'] = $receipt->receiptdate;
		if ($receiptaccoutcredit == 0) {
			$values['Amount'] = $netamount;
		} else {
			$values['Amount'] = -1 * $netamount;
		}
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
		$success = Table::addRow("accounting_entries", $values, $comments);
	
		if ($vat->percent > 0) {
			// tehdään alv vienti
				
			// Päivitetään vielä accounted ja net value sarake receiptiin...
				
		} else {
			// Päivitetään vielä accounted sarake receiptiin...
				
			// Pitäisi oikeastaan tässävaiheessa hakea kaikki entryt ja laskea niiden summa... negatiiviset yhteen ja positiiviset yhteen...
			$this->updateReceiptAccounted($receiptID, $comments);
			$this->updateReceiptDebetAndCredit($receiptID, $comments);
		
		}
	
		if (!$comments) redirecttotal('accounting/receipts/showreceipt&id=' . $receiptID ,null);
	}
	*/
	
	
	
	// Ei oikein muistikuvaa mitä tämä tekee?
	// Vastaus: Tämä laskee tilin debet ja credit erotuksen, eli tämä on erisuuri kuin nolla, mikäli 
	//          viennit ovat puutteellisia. En usko, että tätä tarvitsee mihinkään tauluilla tämä voidaan laskea debet ja credit kentistä
	//			jotka pitää ehkä receiptiin lisätä (tai korvataan netamount ja grossamount kentät näillä
	/*
	private function updateReceiptAccounted($receiptID, $comments = false) {
		
		$entries= Table::load('accounting_entries', "WHERE receiptID=" . $receiptID);
		$values = array();
		$creditsum = 0;
		$debetsum = 0;
		if ($comments) echo "<br><br>Calculated accounted value....";
		foreach ($entries as $index => $row) {
			if ($row->amount < 0) {
				$creditsum = $creditsum + -1 * $row->amount;
				if ($comments) echo "<br>--- credit: " . $row->amount . " = " . $creditsum;
			} else {
				$debetsum = $debetsum + $row->amount;
				if ($comments) echo "<br>--- debet: " . $row->amount . " = " . $debetsum;
			}
		}
		if ($creditsum == $debetsum) {
			$values['Accounted'] = $debetsum;
		} else {
			$values['Accounted'] = $debetsum - $creditsum;
		}
		if ($comments) echo "<br>--- accounted: " . $values['Accounted'];
		$success = Table::updateRow('accounting_receipts', $values, $receiptID, false);
	}
	*/
	
	
	private function updateReceiptDebetAndCredit($receiptID, $comments = false) {
		
		$entries= Table::load('accounting_entries', "WHERE receiptID=" . $receiptID, $comments);
		$values = array();
		$creditsum = 0;
		$debetsum = 0;
		if ($comments) echo "<br><br>Calculated accounted value....";
		foreach ($entries as $index => $row) {
			if ($row->amount < 0) {
				$creditsum = $creditsum + -1 * $row->amount;
				if ($comments) echo "<br>--- credit: " . $row->amount . " = " . $creditsum;
			} else {
				$debetsum = $debetsum + $row->amount;
				if ($comments) echo "<br>--- debet: " . $row->amount . " = " . $debetsum;
			}
		}
		/*
		if ($creditsum == $debetsum) {
			$values['Accounted'] = $debetsum;
		} else {
			$values['Accounted'] = $debetsum - $creditsum;
		}
		*/
		$values = array();
		$values['Debet'] = $debetsum;
		$values['Credit'] = $creditsum;
		$success = Table::updateRow('accounting_receipts', $values, $receiptID, $comments);
	}
	
	
	

	public function updatereceiptAction() {
	
		$receiptID = $_GET['id'];
		$values = array();
		
		
		
		$comments = false;
		
		if ($comments) echo "<br>updatereceipt - " . $receiptID;
		$receipt = Table::loadRow('accounting_receipts', $receiptID, false);
		
		if ($receipt->receiptdate != $_GET['receiptdate']) {
			echo "<br>Receiptin päivämäärää on muutettu";
			$values = array();
			$values['Entrydate'] = $_GET['receiptdate'];;
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" . $receiptID);
		}
		
		
		
		$values = array();
		$values['ReceiptsetID'] = $_GET['receiptsetID'];
		$values['Receiptnumber'] = $_GET['receiptnumber'];
		$values['Explanation'] = $_GET['explanation'];
		$values['Receiptdate'] = $_GET['receiptdate'];
		
		// Nollataan vanha arvo
		if ($receipt->receipttype != $_GET['receipttype']) {
			//if ($receipt->receipttype == Collections::RECEIPTTYPE_NONE) $values['xxxx'] = 0;		
			// Nolataan vanha arvo
			if ($receipt->receipttype == Collections::RECEIPTTYPE_PAYABLE) $values['supplierID'] = 0;		
			if ($receipt->receipttype == Collections::RECEIPTTYPE_RECEIVABLE) $values['clientID'] = 0;
			if ($receipt->receipttype == Collections::RECEIPTTYPE_PAYROLL) $values['workerID'] = 0;		
			//if ($registry->receipt->receipttype == Collections::RECEIPTTYPE_OTHER) $values['xxxxx'] = 0;

			// Nollataan uusi arvo
			if ($_GET['receipttype'] == Collections::RECEIPTTYPE_PAYABLE) $values['supplierID'] = 0;
			if ($_GET['receipttype'] == Collections::RECEIPTTYPE_RECEIVABLE) $values['clientID'] = 0;
			if ($_GET['receipttype'] == Collections::RECEIPTTYPE_PAYROLL) $values['workerID'] = 0;
		}
		$values['Receipttype'] = $_GET['receipttype'];

		if (isset($_GET['supplierID'])) $values['SupplierID'] = $_GET['supplierID'];
		if (isset($_GET['clientID'])) $values['ClientID'] = $_GET['clientID'];
		if (isset($_GET['workerID'])) $values['WorkerID'] = $_GET['workerID'];
		
		if (isset($_GET['debet'])) $values['Debet'] = $_GET['debet'];
		if (isset($_GET['credit'])) $values['Credit'] = $_GET['credit'];
		
		//$values['CostpoolID'] = $_GET['costpoolID'];
		//$values['ReceiverID'] = $_GET['receiverID'];
		//$values['Grossamount'] = str_replace(",",".",$_GET['grossamount']);
		//$values['Netamount'] = str_replace(",",".",$_GET['netamount']);
		//$values['Paymentstatus'] = $_GET['paymentstatus'];
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$variable = "dimension" . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$columname = "Dimension" . $dimension->dimensionID;
		
					if (isset($_GET[$variable])) {
						if ($receipt->$variable != $_GET[$variable]) {
							if ($comments) echo "<br>Dimensio muuttunut, päivitetään kaikkiin riveihin ja entryihin";
							$this->updateReceiptDimension($receiptID, $dimension, $_GET[$variable]);
							$values[$columname] = $_GET[$variable];
							if ($comments) echo "<br>" . $columname . " - " . $_GET[$variable];
						}
					} else {
						echo "<br>Dimensiota ei tullut parametrina";
					}
		
				}
			}
		}
		$success = Table::updateRow('accounting_receipts', $values, $receiptID, true);
		
		
		
		if (!$comments) redirecttotal('accounting/receipts/showreceipt&id=' . $receiptID,null);
	}
	
	
	private function updateInvoiceDate($invoice, $invoicedate) {
	
		// Päivitetään
		$values = array();
		$values['Invoicedate'] = $invoicedate;
		$success = Table::updateRowsWhere("sales_invoicerows", $values, " WHERE InvoiceID=" . $invoice->invoiceID);
	
		if ($invoice->receiptID > 0) {
				
			$values = array();
			$values['Receiptdate'] = $invoicedate;
			$success = Table::updateRow("accounting_receipts", $values, $invoice->receiptID);
				
			$values = array();
			$values['Entrydate'] = $invoicedate;
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" .  $invoice->receiptID);
		}
	}
	
	

	private function updateReceiptDimension($receiptID, $dimension, $dimensionvalueID) {
	
		$variable = 'dimension' . $dimension->dimensionID;
		$columname = 'Dimension' . $dimension->dimensionID;
		$values = array();
		$values[$columname] = $dimensionvalueID;
		
		if ($receiptID > 0) {
	
			$values = array();
			$values[$columname] = $dimensionvalueID;
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" .  $receiptID);
		}
	}
	
	
	public function copyreceiptAction() {
	
		global $mysqli;
		
		$receiptID = $_GET['receiptID'];
		$newreceiptdate = $_GET['newreceiptdate'];
		
		$receipt = Table::loadRow('accounting_receipts', $receiptID);
		$receiptnumber = $this->getNextReceiptNumber($receipt->receiptsetID);
		
		$values = array();
		$values['Receiptdate'] = $newreceiptdate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $receipt->receiptsetID;
		$values['Explanation'] = $_GET['explanation'];
		//$values['ReceiverID'] = $receipt->receiverID;
		//$values['CostpoolID'] = $receipt->costpoolID;
		$values['Debet'] = $receipt->debet;
		$values['Netamount'] = $receipt->netamount;
		$values['Accounted'] = $receipt->accounted;
		$values['Paymentstatus'] = 0;
		$values['SupplierID'] = $receipt->supplierID;
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		
		$this->registry->entries = Table::load('accounting_entries', "WHERE receiptID=" . $receiptID);
		foreach($this->registry->entries as $index => $entry) {
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$values['AccountID'] = $entry->accountID;
			$values['Entrydate'] = $newreceiptdate;
			$values['Amount'] = $entry->amount;
			$values['VatcodeID'] = $entry->vatcodeID;
			$success = Table::addRow("accounting_entries", $values, $comments);
		}
		redirecttotal('accounting/receipts/showreceipt&id=' . $newreceiptID,null);
	}
	
	
	
	private function getNextReceiptNumber($receiptsetID) {
		
		global $mysqli;
		
		$sql = "SELECT * FROM accounting_receipts WHERE ReceiptsetID=" . $receiptsetID . " ORDER BY Receiptnumber DESC LIMIT 1";
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
		
		if ($row == null) {
			$receiptset = Table::loadRow("accounting_receiptsets", $receiptsetID);
			$receiptnumber = $receiptset->startnumber;
		} else {
			$receiptnumber = intval($row['Receiptnumber']);
			$receiptnumber++;
		}
		return $receiptnumber;
	}
	
	

	public function updateentryAction() {
	
		$comments = true;
		
		$receiptID = $_GET['receiptID'];
		$entryID = $_GET['id'];
		$values = array();
		if (isset($_GET['entrydate'])) {
			$values['Entrydate'] = $_GET['entrydate'];
		}
		$values['AccountID'] = $_GET['accountID'];
		$values['VatcodeID'] = $_GET['vatcodeID'];
		$values['C'] = $_GET['vatcodeID'];
		
		$costpooltype = $_GET['costpooltype'];
		$values['Costpooltype'] = $costpooltype;
		$values['TargetID'] = $_GET['targetID'];
		$targetID = $_GET['targetID'];
		
		$values['AssetID'] = 0;
		$values['WorkerID'] = 0;
		$values['ClientID'] = 0;
		$values['SupplierID'] = 0;
		$values['LiabilityID'] = 0;
		
		if ($costpooltype == Collections::COSTPOOLTYPE_ASSET) {
			$values['AssetID'] = $targetID;
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_WORKER) {
			$values['WorkerID'] = $targetID;
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
			$values['ClientID'] = $targetID;
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
			$values['SupplierID'] = $targetID;
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
			$values['LiabilityID'] = $targetID;
		}
		
		
		$debet = str_replace(",",".",$_GET['debet']);
		$credit = str_replace(",",".",$_GET['credit']);
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		
		$amount = 0;
		if ($debet > $credit) $amount = $debet;
		else $amount = -1 * $credit;
		
		$values['Amount'] = $amount;
		
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension'.+ $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $invoice->$variable;
				}
			}
		}
		
		
		$success = Table::updateRow('accounting_entries', $values, $entryID, $comments);
		//$this->updateReceiptAccounted($receiptID, $comments);
		$this->updateReceiptDebetAndCredit($receiptID, $comments);
		
		
		if (!$comments) redirecttotal('accounting/receipts/showreceipt&id=' . $receiptID,null);
	}
	
	
	
	public function removeentryAction() {
	
		$comments = false;
		$receiptID = $_GET['receiptID'];
		$entryID = $_GET['id'];
		
		echo "<br>receiptID - " . $receiptID;
		echo "<br>EntryID - " . $entryID;
		
		//$values = array();
		//$values['Name'] = $_GET['name'];
		//$values['Percent'] = $_GET['percent'];
		//$success = Table::addRow("system_vats", $values, true);
		$success = Table::deleteRow("accounting_entries", $entryID, $comments);
		//$success = Table::deleteRow($wordtable,$id);
		//$this->updateReceiptAccounted($receiptID);
		$this->updateReceiptDebetAndCredit($receiptID, $comments);
		
		if (!$comments) redirecttotal('accounting/receipts/showreceipt&id=' . $receiptID,null);
	}
	
	
	// TODO: Tämän kanssa pitäisi olla varovainen, pitäisi tarkistaa / päivittää myös
	//       liitännäiset laskut, palkkalaskelmat, tilioterivit jne.
	// TODO: Ainakin tänne pitäisi lisätä kohdistetun pankkitiliotteen poistaminen
	public function removereceiptAction() {
	
		$receiptID = $_GET['id'];
		
		echo "<br>receiptID - " . $receiptID;
		$receipt = Table::loadRow('accounting_receipts',$receiptID);
		
		if ($receipt->bankstatementrowID > 0) {
			$values = array();
			$values['Status'] = Collections::BANKSTATEMENTSTATE_1;
			$values['ReceiptID'] = 0;
			$success = Table::updateRow('accounting_bankstatementrows', $values, $receipt->bankstatementrowID, false);
		} 
		
		if ($receipt->invoiceID > 0) {
			$values = array();
			$values['State'] = Collections::INVOICESTATE_ACCEPTED;
			$values['ReceiptID'] = 0;
			$success = Table::updateRow('sales_invoices', $values, $receipt->invoiceID, false);
		}
		
		if ($receipt->purchaseID > 0) {
			$values = array();
			$values['State'] = Collections::PURCHASESTATE_ACCEPTED;
			$values['Paymentdate'] = '0000-00-00';
			$values['PaymentreceiptID'] = 0;
			//$values['ReceiptID'] = 0;
			$success = Table::updateRow('accounting_purchases', $values, $receipt->purchaseID, false);
		}
		$success = Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $receiptID, true);
		$success = Table::deleteRow("accounting_receipts", $receiptID, true);
		redirecttotal('accounting/receipts/showreceipts',null);
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
}

?>
