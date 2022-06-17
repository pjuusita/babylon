<?php



class AlignmentController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->alignmentAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function alignmentAction() {

		$comments = false;
		updateActionPath("Kohdistus");
		
		$this->registry->suppliers = Table::load("accounting_suppliers");
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		
		$this->registry->bankaccounts = Table::load('accounting_bankaccounts');
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
		if ($bankaccountID == 0) {
			foreach($this->registry->bankaccounts as $index => $bankaccount) {
				$bankaccountID = $bankaccount->bankaccountID;
				if ($comments) echo "<br> setting first.." . $bankaccountID;
				setModuleSessionVar('bankaccountID',$bankaccountID);
				break;
			}
		} else {
			$found = false;
			foreach($this->registry->bankaccounts as $index => $bankaccount) {
				if ($bankaccountID == $bankaccount->bankaccountID) $found = true;
			}
			if ($found == false) {
				if ($comments) echo "<br>Bankaccount not found;";
				foreach($this->registry->bankaccounts as $index => $bankaccount) {
					if ($comments) echo "<br> setting first..";
					$bankaccountID = $bankaccount->bankaccountID;
					setModuleSessionVar('bankaccountID',$bankaccountID);
					break;
				}
			}
		}
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
		$this->registry->bankaccountID = $bankaccountID;
		
		$periodID = getModuleSessionVar('periodID',0);
		$this->registry->periods = Table::load('accounting_periods');
		
		foreach($this->registry->periods as $index => $period) {
			if ($periodID == 0) $periodID = $period->periodID;
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		if ($comments) echo "<br>periodID - " . $periodID;
		$this->registry->periodID = $periodID;
		
		
		$statementID = getModuleSessionVar('statementID',0);
		if ($periodID > 0) {
			$this->registry->statements = Table::load('accounting_bankstatements', " WHERE Startdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $this->registry->period->enddate . "' ORDER BY Startdate");
			foreach($this->registry->statements as $index => $statement) {
				//if ($statementID == 0) {
				//	$statementID = $statement->bankstatementID;
				//}
				if ($statementID == $statement->bankstatementID) {
					$this->registry->statement = $statement;
					$startdate = $statement->startdate;
					$startdate = $statement->startdate;
				}
				$month = substr($statement->startdate, 5, 2);
				$year = substr($statement->startdate, 0, 4);
				$statement->name = $year . "-" . $month;
			}
		}
		if ($comments) echo "<br>statementID - " . $statementID;
		$this->registry->statementID = $statementID;
		
		//echo "<br>StatementID - " . $statementID;
		if ($statementID > 0) {
			
			$this->registry->bankstatement = Table::loadRow('accounting_bankstatements', $statementID);
			$bankstatementrows = Table::load('accounting_bankstatementrows', ' WHERE BankstatementID=' . $statementID . "  AND Status=1 ORDER BY Entrydate, RowID");
			$this->registry->bankstatementrows = $bankstatementrows;
			$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
			$this->registry->accounts = Table::load('accounting_accounts');
			foreach($this->registry->accounts as $index => $account) {
				$account->fullname = $account->number . " " . $account->name;
			}
			
		} else {
			$this->registry->statementID = 0;
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
			$bankstatementrows = Table::load('accounting_bankstatementrows', "WHERE Entrydate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND Status=1 ORDER BY Entrydate, RowID");
			$this->registry->bankstatementrows = $bankstatementrows;
			$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
			$this->registry->accounts = Table::load('accounting_accounts');
			foreach($this->registry->accounts as $index => $account) {
				$account->fullname = $account->number . " " . $account->name;
			}
		}
		
		$targetperiodID = getModuleSessionVar('targetperiodID',0);
		
		foreach($this->registry->periods as $index => $period) {
			if ($targetperiodID == 0) $targetperiodID = $period->periodID;
			if ($period->periodID == $targetperiodID) $this->registry->targetperiod = $period;
		}
		if ($comments) echo "<br>targetperiodID - " . $targetperiodID;
		$this->registry->targetperiodID = $targetperiodID;
		
		$alignmenttypes = array();
		$alignmenttypes[1] = "Ostolaskut";
		$alignmenttypes[2] = "Myyntitulot";
		$alignmenttypes[3] = "Palkkamaksut";
		$alignmenttypes[4] = "Muut maksut";		// Nämä on ehkä saldollisia, eli lyhennetään vain tiettyä poolia
		
		//$alignmenttypes[4] = "Palkkojen sivukulut";		// verohallinto, TyEL-maksut, ehkä näistä luodaan omat ostolaskut
		//$alignmenttypes[5] = "Verohallinto";						// onkohan tämä hyvä olla erikseen...
		//$receipttypes[3] = "Myyntisaldot";		// Asiakaskohtaiset saldot
		//$receipttypes[4] = "Vastikkeet"			// Taloyhtiöitä varten
		//$receipttypes[4] = "Velat"				// Velkojen lyhennys, joko yrityksen lainan lyhennys tai henkilöiden lainoja
		//											// Lainat henkilöille...
		$this->registry->alignmenttypes = $alignmenttypes;
		
		$aligmenttypeID = getModuleSessionVar('aligmenttypeID',1);
		if ($comments) echo "<br>aligmenttypeID - " . $aligmenttypeID;
		$this->registry->aligmenttypeID = $aligmenttypeID;
		
		if ($aligmenttypeID == 1) {
			
			$selectionID = getModuleSessionVar('selectionID',0);
			if ($comments) echo "<br>selectionID - " . $aligmenttypeID;
			
			$selections = Collections::generatePeriodTimescales($this->registry->targetperiod, $selectionID, null, 2);
			$this->registry->selections = $selections;
			if (($selectionID == 0) || ($selectionID == -1)) {
				
				foreach($selections as $index => $value) {
					$selectionID = $value->selectionID;
					break;
				}
				$this->registry->selectionID = $selectionID;
				$startdate = $this->registry->targetperiod->startdate;
				$enddate = $this->registry->targetperiod->enddate;
			
			} else {
				$currentselect = $selections[$selectionID];
				if ($comments) echo "<br>current start - " . $currentselect->startsql;
				if ($comments) echo "<br>current end - " . $currentselect->endsql;
				$startdate = $currentselect->startsql;
				$enddate = $currentselect->endsql;
			}
			$this->registry->selectionID = $selectionID;
			
			
			$this->registry->suppliers = Table::load('accounting_suppliers', ' ORDER BY Name');
			
			//$startdate = $this->registry->targetperiod->startdate;
			//$enddate = $this->registry->targetperiod->enddate;
			if ($comments) echo "<br>startdate - " . $startdate;
			if ($comments) echo "<br>enddate - " . $enddate;
			
			
			$this->registry->invoices = Table::load('accounting_purchases', " WHERE Purchasedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND State<3 ORDER BY Purchasedate", $comments);
			
		} elseif ($aligmenttypeID == 2) {
			
			$selectionID = getModuleSessionVar('selectionID',0);
			if ($comments) echo "<br>selectionID - " . $aligmenttypeID;
				
			$selections = Collections::generatePeriodTimescales($this->registry->targetperiod, $selectionID, null, 2);
			$this->registry->selections = $selections;
			if ($selectionID == 0) {
			
				foreach($selections as $index => $value) {
					$selectionID = $value->selectionID;
					break;
				}
				$this->registry->selectionID = $selectionID;
				$startdate = $this->registry->targetperiod->startdate;
				$enddate = $this->registry->targetperiod->enddate;
					
			} else {
				$currentselect = $selections[$selectionID];
				if ($comments) echo "<br>current start - " . $currentselect->startsql;
				if ($comments) echo "<br>current end - " . $currentselect->endsql;
				$startdate = $currentselect->startsql;
				$enddate = $currentselect->endsql;
			}
			$this->registry->selectionID = $selectionID;
				
				
			$this->registry->companies = Table::load('crm_companies', ' ORDER BY Name');
				
			//$startdate = $this->registry->targetperiod->startdate;
			//$enddate = $this->registry->targetperiod->enddate;
			if ($comments) echo "<br>startdate - " . $startdate;
			if ($comments) echo "<br>enddate - " . $enddate;
				
				
			$this->registry->invoices = Table::load('sales_invoices', " WHERE Invoicedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND State<4 ORDER BY Invoicedate");
				
			
			
		} elseif ($aligmenttypeID == 3) {
			
			$selectionID = getModuleSessionVar('selectionID',0);
			if ($comments) echo "<br>selectionID - " . $aligmenttypeID;
			
			$selections = Collections::generatePeriodTimescales($this->registry->targetperiod, $selectionID, null, 2);
			$this->registry->selections = $selections;
			if ($selectionID == 0) {
					
				foreach($selections as $index => $value) {
					$selectionID = $value->selectionID;
					break;
				}
				$this->registry->selectionID = $selectionID;
				$startdate = $this->registry->targetperiod->startdate;
				$enddate = $this->registry->targetperiod->enddate;
					
			} else {
				$currentselect = $selections[$selectionID];
				if ($comments) echo "<br>current start - " . $currentselect->startsql;
				if ($comments) echo "<br>current end - " . $currentselect->endsql;
				$startdate = $currentselect->startsql;
				$enddate = $currentselect->endsql;
			}
			$this->registry->selectionID = $selectionID;
			
			
			$this->registry->workers = Table::load('hr_workers', ' ORDER BY Lastname, Firstname');
			foreach($this->registry->workers as $workerID => $worker) {
				$worker->fullname = $worker->lastname . " " . $worker->firstname;
			}
			if ($comments) echo "<br>startdate - " . $startdate;
			if ($comments) echo "<br>enddate - " . $enddate;
						
			$this->registry->paychecks = Table::load('payroll_paychecks', " WHERE Paymentdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND State=1 ORDER BY Startdate");
			
			
		} elseif ($aligmenttypeID == 4) {
			
		} else {
			echo "<br>Unknown aligmenttypeID - " . $aligmenttypeID;
			exit;
		}
		
		
		
		$this->registry->template->show('accounting/alignment','alignment');
	}
	
	
	public function deductreceivablesAction() {
		
		$comments = false;
		
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
		
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
		if ($comments) echo "<br>payablesaccountID - " . $bankaccount->payablesaccountID;
		if ($comments) echo "<br>receivablesaccountID - " . $bankaccount->receivablesaccountID;
		
		
		$receivablesaccountID = Settings::getSetting('accounting_recievablesaccountID');
		if ($comments) echo "<br>Receivables saatavat tili - " . $receivablesaccountID;
		//$targetamount = $bankstatementrow->amount;
		//$account = $accounts[$receivablesaccountID];
		$receivablesaccount = Table::loadRow('accounting_accounts', $receivablesaccountID);
		
		if ($comments) echo "<br>Receivablesacount - " . $receivablesaccount->name;
		
		
		$payablescountID = $bankaccount->payablesaccountID;
		
		$bankstatementrowID = $_GET['id'];
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
		
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		//$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = Collections::PAYMENTSTATUS_CONFIRMED;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['InvoiceID'] = 0;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['BankstatementrowID'] = $bankstatementrowID;
		
		/*
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
		*/
		
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
		
		
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		
		/*
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension'.+ $dimension->dimensionID;
				$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
			}
		}*/
		
		$entryID = Table::addRow("accounting_entries", $values, false);

		
		// saamistilin vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $receivablesaccountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		
		/*
		 if (count($dimensions) > 0) {
		 foreach($dimensions as $index => $dimension) {
		 $variable = 'dimension'.+ $dimension->dimensionID;
		 $values['Dimension'. $dimension->dimensionID] = $entry->$variable;
		 }
		 }*/
		
		$entryID = Table::addRow("accounting_entries", $values, false);
		
		
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
			
		// Päivitä bankstatement row tilaa...
		if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	
	
	
	public function deductpayablesAction() {
	
		$comments = true;
	
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
		
		$supplierID = $_GET['supplierID'];
		if ($comments) echo "<br>supplierID  - " . $supplierID;
		
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
		if ($comments) echo "<br>payablesaccountID - " . $bankaccount->payablesaccountID;
		if ($comments) echo "<br>receivablesaccountID - " . $bankaccount->receivablesaccountID;
	
	
		$payablesaccountID = Settings::getSetting('accounting_payablesaccountID');
		if ($comments) echo "<br>Payables tili - " . $payablesaccountID;
		//$targetamount = $bankstatementrow->amount;
		//$account = $accounts[$receivablesaccountID];
		$payablesaccount = Table::loadRow('accounting_accounts', $payablesaccountID);
	
		if ($comments) echo "<br>Payablesaccount - " . $payablesaccount->name;
	
	
		//$payablescountID = $bankaccount->payablesaccountID;
	
		$bankstatementrowID = $_GET['statementrowID'];
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
		
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		if ($bankstatementrow->amount < 0) {
			$values['Debet'] = -1 * $bankstatementrow->amount;
			$values['Credit'] = -1 * $bankstatementrow->amount;
		} else {
			echo "<br>Ostovelkavienti pitäisi olla negatiivinen";
			exit;
		}
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['BankstatementrowID'] = $bankstatementrowID;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['SupplierID'] = $supplierID;
		
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
	
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
	
		// ostovelkatili vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $payablesaccountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$values['SupplierID'] = $supplierID;
		
		$entryID = Table::addRow("accounting_entries", $values, false);
	
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
			
		// Päivitä bankstatement row tilaa...
		//if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	

	private function getBankstatementRowName($bankstatementrow) {
		
		$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
		$startday = substr($bankstatement->startdate, 8);
		$startmonth = substr($bankstatement->startdate, 5, 2);
		$startyear = substr($bankstatement->startdate, 0, 4);
		$endday = substr($bankstatement->startdate, 8);
		$endmonth = substr($bankstatement->startdate, 5, 2);
		$endyear = substr($bankstatement->startdate, 0, 4);
		
		if (($startmonth == $endmonth) && ($startyear == $endyear)) {
			$bankstatementname = $startyear . "/" . $startmonth;
		} else {
			$bankstatementname = $startday . ".". $startmonth . "." . $startyear;
		}
		return $bankstatementname;
	}
	
	public function linkpurchasetostatementrowAction() {
	
		$comments = false;
	
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
	
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
		if ($comments) echo "<br>payablesaccountID - " . $bankaccount->payablesaccountID;
		if ($comments) echo "<br>receivablesaccountID - " . $bankaccount->receivablesaccountID;
	
		
		$purchaseID = $_GET['purchaseID'];
		if ($comments) echo "<br>purchaseID - " . $purchaseID;
		$purchase = Table::loadRow('accounting_purchases', $purchaseID);
	
		//$receivablesaccountID = Settings::getSetting('accounting_recievablesaccountID');
		$payablesaccountID = Settings::getSetting('accounting_payablesaccountID');
		if ($purchase->payableaccountID > 0) {
			$payablesaccountID = $purchase->payableaccountID;
		}
		
		
		if ($comments) echo "<br>Ostovelkatili - " . $payablesaccountID;
		//$targetamount = $bankstatementrow->amount;
		//$account = $accounts[$receivablesaccountID];
		$payableaccount = Table::loadRow('accounting_accounts', $payablesaccountID);
	
		if ($comments) echo "<br>Ostovelkatili - " . $payableaccount->name;
		
		$bankstatementrowID = $_GET['statementrowID'];
		if ($comments) echo "<br>statementrowID - " . $bankstatementrowID;
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		
		// TODO: Pitäisi tsekata, että valittuna oleva bankaccountID täsmää bankstatementRowID:n bankstatementin bankaccountID:hen
		//if ($bankstatementrow->bankstatementID != )
		
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
		
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		//$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = Collections::PAYMENTSTATUS_CONFIRMED;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['PurchaseID'] = $purchaseID;
		$values['InvoiceID'] = 0;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['SupplierID'] = $purchase->supplierID;
		$values['BankstatementrowID'] = $bankstatementrowID;
	
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
	
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
	
		$entryID = Table::addRow("accounting_entries", $values, false);
	
	
		// saamistilin vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $payablesaccountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$values['SupplierID'] = $purchase->supplierID;
		
		// Miten tänne saadaan supplierID asetettua....
		
		$entryID = Table::addRow("accounting_entries", $values, false);
	
	
		// Päivitä tilioterivin käsitellyksi (linked)
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
		
		// Päivitetään ostolaskuun: paymentdate ja status (ja tulevaisuudessa paymentreceiptID...)
		$values = array();
		$values['State'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['Paymentdate'] = $bankstatementrow->entrydate;
		$values['PaymentreceiptID'] = $newreceiptID;
		$values['Paymenttype'] = Collections::PAYMENTTYPE_BANKACCOUNT;
		$success = Table::updateRow('accounting_purchases', $values, $purchaseID, false);
		
		if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	

	
	

	public function insertnewpurchasefrombankstatementAction() {
	
		$comments = true;
		
		$supplierID = $_GET['supplierID'];
		$statementrowID = $_GET['statementrowID'];
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $statementrowID);
		
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		
		// TODO: Bankaccount pitää hakea jostain muusta, koska yrityksellä voi olla useita tilejä...
		
		$amount = -1 * $bankstatementrow->amount;
		$purchasedate = $bankstatementrow->entrydate;
		$duedate = $bankstatementrow->entrydate;
		$paymentmethodID = $_GET['paymentmethodID'];
		
		
		//$amount = str_replace(",",".",$_GET['amount']);
		//$purchasedate = $_GET['purchasedate'];
		/*
		$duedate = $_GET['duedate'];
		if (isset($_GET['duedate'])) {
			$duedate = $_GET['duedate'];
		} else {
			$duedate = $purchasedate;
		}
		*/
		
		if ($comments) {
			echo "<br>Supplier - " . $supplierID;
			echo "<br>Grossamount - " . $amount;
			echo "<br>Purchasedate - " . $purchasedate;
			//echo "<br>Purchasedate - " . dateStrToSql($purchasedate);
			echo "<br>Purchasetype - " . $paymentmethodID;
			echo "<br>duedate - " . $duedate;
			//echo "<br>date - " . dateStrToSql($duedate);
		}
		
		//$purchasedate = dateStrToSql($purchasedate);
		//$duedate = dateStrToSql($duedate);
		echo "<br> -- Purchasedate - " . $purchasedate;
		echo "<br> -- Duedate - " . $duedate;
		
		$paymentmethod = Table::loadRow("accounting_paymentmethods", $paymentmethodID);
			
		$supplier = Table::loadRow("accounting_suppliers", $supplierID);
		if ($supplier->paymenttimemanual == 0) {
			$values = array();
			if ($supplier->paymentmethodID == 0) {
				$values['PaymentmethodID'] = $paymentmethodID;
			} else {
				if ($supplier->paymentmethodID != $paymentmethodID) {
					$values['PaymentmethodID'] = $supplier->paymentmethodID;
				}
			}
				
			if ($paymentmethod->duedateusage == Collections::DUEDATEUSAGE_FROMSUPPLIER) {
				if ($supplier->paymenttime == null) {
					$diff = calculateDateDifference($purchasedate, $duedate);
					echo "<br> -- Diff - " . $diff;
					$values['Paymenttime'] = $diff;
				}
			}
			if (count($values) > 0) {
				//$success = Table::updateRow("accounting_suppliers", $values, $supplierID, $comments);
			}
		} else {
			// Paymenttime on asetettu manuaalisesti, ei muokata
		}
		
		
		$values = array();
		$values['SupplierID'] = $supplierID;
		$values['Grossamount'] = $amount;
		$values['Netamount'] = $amount;
		$values['Purchasedate'] = $purchasedate;
		$values['Duedate'] = $duedate;
		$values['PaymentmethodID'] = $paymentmethodID;
		$values['PayableaccountID'] = $paymentmethod->accountID;
		$values['PayablecostpoolID'] = Settings::getSetting('accounting_payablescostpoolID');
		$values['State'] = Collections::PAYMENTSTATUS_OPEN;
		
		$values['Purchasetype'] = null;		// TODO: voitaneen poistaa
		//$values['Paymenttype'] = Collections::PAYMENTTYPE_BANKACCOUNT;
		$values['Paymenttype'] = null;		// TODO: voitaneen poistaa
		
		$purchaseID = Table::addRow("accounting_purchases", $values);
		
		
		$receiptsetID =  Settings::getSetting('accounting_purchasereceiptsetID', 0);
		$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
		$values = array();
		$values['Receiptdate'] = $purchasedate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $receiptsetID;
		$values['Explanation'] = "Ostolasku " . $purchaseID;		// TODO: teksti resurssiteksteistä?
		$values['PurchaseID'] = $purchaseID;
		$receiptID = Table::addRow("accounting_receipts", $values, false);
			
		$values = array();
		$values['ReceiptID'] = $receiptID;
		Table::updateRow("accounting_purchases", $values, $purchaseID);
		
		
		if (!$comments) redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID,null);
		
		
	}
	
	


	public function linkpayrollpaymenttostatementrowAction() {
	
		
		$comments = false;
	
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
	
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
		if ($comments) echo "<br>payablesaccountID - " . $bankaccount->payablesaccountID;
		if ($comments) echo "<br>receivablesaccountID - " . $bankaccount->receivablesaccountID;
	
	
		$paycheckID = $_GET['paycheckID'];
		if ($comments) echo "<br>paycheckID - " . $paycheckID;
		$paycheck = Table::loadRow('payroll_paychecks', $paycheckID);
	
		$payablesaccountID = Settings::getSetting('payroll_payablesaccountID', 0);
		
		//if ($invoice->receivablesID > 0) {
		//	$payablesaccountID = $purchase->payableaccountID;
		//}
	
	
		if ($comments) echo "<br>payablesaccountID - " . $payablesaccountID;
		//$targetamount = $bankstatementrow->amount;
		//$account = $accounts[$receivablesaccountID];
		$payablesaccount = Table::loadRow('accounting_accounts', $payablesaccountID);
	
		echo "<br>Palkkavelkatili - " . $payablesaccount->name;
		
		$bankstatementrowID = $_GET['statementrowID'];
		if ($comments) echo "<br>statementrowID - " . $bankstatementrowID;
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		// TODO: Pitäisi tsekata, että valittuna oleva bankaccountID täsmää bankstatementRowID:n bankstatementin bankaccountID:hen
		//if ($bankstatementrow->bankstatementID != )
	
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
	
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		//$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = Collections::PAYMENTSTATUS_CONFIRMED;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['PurchaseID'] = 0;
		$values['PaycheckID'] = $paycheckID;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['BankstatementrowID'] = $bankstatementrowID;
	
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
	
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
		if ($comments) echo "<br>Pankkitilivienti created - " . $entryID;
		
	
		// velkatilin vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $payablesaccountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
		if ($comments) echo "<br>velkatilin vienti created - " . $entryID;
		
	
		// Päivitä tilioterivin käsitellyksi (linked)
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
		if ($comments) echo "<br>accounting_bankstatementrows udpated";
		
		// Päivitetään palkkalaskemaan: paiddate, paymentreceiptID ja state
		$values = array();
		$values['State'] = Collections::PAYCHECKSTATE_LINKED;
		$values['Paiddate'] = $bankstatementrow->entrydate;
		$values['PaymentreceiptID'] = $newreceiptID;
		$success = Table::updateRow('payroll_paychecks', $values, $paycheckID, false);
		if ($comments) echo "<br>payroll_paychecks udpated";
		
		if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	

	public function linksalesinvoicetostatementrowAction() {
	
		$comments = true;
	
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
	
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
		if ($comments) echo "<br>payablesaccountID - " . $bankaccount->payablesaccountID;
		if ($comments) echo "<br>receivablesaccountID - " . $bankaccount->receivablesaccountID;
	
	
		$invoiceID = $_GET['invoiceID'];
		if ($comments) echo "<br>invoiceID - " . $invoiceID;
		$purchase = Table::loadRow('sales_invoices', $invoiceID);
	
		$receivablesaccountID = Settings::getSetting('accounting_recievablesaccountID');
		//if ($invoice->receivablesID > 0) {
		//	$payablesaccountID = $purchase->payableaccountID;
		//}
	
	
		if ($comments) echo "<br>Myyntisaamistili - " . $receivablesaccountID;
		//$targetamount = $bankstatementrow->amount;
		//$account = $accounts[$receivablesaccountID];
		$receivablesaccount = Table::loadRow('accounting_accounts', $receivablesaccountID);
	
		echo "<br>Myyntisaamistili - " . $receivablesaccount->name;
		
		$bankstatementrowID = $_GET['statementrowID'];
		if ($comments) echo "<br>statementrowID - " . $bankstatementrowID;
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		// TODO: Pitäisi tsekata, että valittuna oleva bankaccountID täsmää bankstatementRowID:n bankstatementin bankaccountID:hen
		//if ($bankstatementrow->bankstatementID != )
	
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
	
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		//$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = Collections::PAYMENTSTATUS_CONFIRMED;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['PurchaseID'] = 0;
		$values['InvoiceID'] = $invoiceID;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['BankstatementrowID'] = $bankstatementrowID;
	
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
	
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
	
	
		// saamistilin vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $receivablesaccountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
	
	
		// Päivitä tilioterivin käsitellyksi (linked)
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
	
		// Päivitetään ostolaskuun: paymentdate ja status (ja tulevaisuudessa paymentreceiptID...)
		$values = array();
		$values['State'] = Collections::INVOICESTATE_PAID;
		$values['Paymentdate'] = $bankstatementrow->entrydate;
		$values['Paymenttype'] = Collections::PAYMENTTYPE_BANKACCOUNT;
		$success = Table::updateRow('sales_invoices', $values, $invoiceID, false);
		
		if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	
	
	public function linksalesinvoiceAction() {
	
		// Tämä linkitys voi kohdistua myös avoimeen saldoon, pitäisi testata
	
		global $mysqli;
		$comments = true;
	
		$invoiceID = $_GET['invoiceID'];
		$statementrowID = $_GET['statementrowID'];
	
		if ($comments) echo "<br>invoiceID - " . $invoiceID;
		if ($comments) echo "<br>StatementrowID - " . $statementrowID;
	
		exit;
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $statementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankstatement->bankaccountID);
		$invoice = Table::loadRow('sales_invoices', $invoiceID);
		$accounts = Table::load('accounting_accounts');
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
	
	
		if ($bankstatementrow->amount == $invoice->unpaidamount) {
			echo "<br>Avoin saldo ja pankkitilivienti ovat yhtäsuuria, voidaan kohdistaa";
		} else {
			echo "<br>Avoin saldo ja pankkitilivienti ovat erisuuria";
			exit;
		}
	
		// Ainoastaan hyväksytty ja osittainmaksettu voidaan kohdistaa
		if (($invoice->state == 1) || ($invoice->state == 3)) {
			echo "<br>Invoicen tila on " . $invoice->state . " voidaan merkitä maksetuksi";
		} else {
			echo "<br>Invoicen tila on " . $invoice->state . " ei voida merkitä maksetuksi";
			exit;
		}
	
	
	
		$receiptsetID =  Settings::getSetting('accounting_bankstatementreceiptsetID', 0);
		if ($comments) echo "<br>Selected receiptsetID - " . $receiptsetID;
	
		$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
	
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $receiptsetID;
		//$values['Explanation'] = "Tilioterivi " . $statementrowID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = 3;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['InvoiceID'] = 0;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['BankstatementrowID'] = $statementrowID;
		// Dimensiot olisi hyvä laittaa tähän, mistä napataan?
	
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
	
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
		$values = array();
		// Pitäisi ehkä tehdä vasta lopuksi jos viennit onnistuu, koska lisäyksessä aina lisätään receiptID.
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $statementrowID, false);
		echo "<br> Päivitetään pankkitiliriville samantien luotu receiptID:" . $statementrowID . " - " . $newreceiptID;
		echo "<br> ++++ updateRow accounting_bankstatementrows - statementrowID:" . $statementrowID;
			
	
		$receivablesaccountID = Settings::getSetting('accounting_recievablesaccountID');
		if ($comments) echo "<br>Receivables saatavat tili - " . $receivablesaccountID;
		$targetamount = $bankstatementrow->amount;
		$account = $accounts[$receivablesaccountID];
		if ($comments) echo "<br>Receivables saatavat tili - " . $account->number . " - " . $account->name;
	
	
		$entries = Table::load('accounting_entries', ' WHERE ReceiptID=' . $invoice->receiptID);
		foreach($entries as $entryID => $entry) {
			echo "<br> -- entry: " . $entry->entryID . ", accountID: " . $entry->accountID . ", amount: " . $entry->amount;
	
			if ($entry->accountID == $receivablesaccountID && (($entry->linktypeID == null) || ($entry->linktypeID == 0))) {
	
				echo "<br>-- entry on pankkitilivienti ja linkittämätön";
				echo "<br>-- linkittämätön maksun osuus - " . $targetamount;
	
				if ($entry->amount <= $targetamount) {
	
					echo "<br>-- entry amount on pienempi kuin tarvittava, tiliöidään kyseinen vienti maksetuksi --- " . $entry->amount . " vs. " . $targetamount;
	
					// Tehdään myyntisaamisten vähennys vienti
					$values = array();
					$values['ReceiptID'] = $newreceiptID;
					$values['AccountID'] = $receivablesaccountID;
					$values['Amount'] = -1 * $entry->amount;
					$values['Entrydate'] = $bankstatementrow->entrydate;
					$values['VatcodeID'] = 0;
					if (count($dimensions) > 0) {
						foreach($dimensions as $index => $dimension) {
							$variable = 'dimension'.+ $dimension->dimensionID;
							$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
						}
					}
					$values['LinktypeID'] = 1;
					$values['LinktargetID'] = $invoice->invoiceID;
					$entryID = Table::addRow("accounting_entries", $values, false);
					echo "<br>myyntisaamisten vähennysvienti - receiptID:" . $newreceiptID . ", amount: " . (-1 * $entry->amount);
					echo "<br> ++++ updateRow accounting_entries - receiptID:" . $newreceiptID;
	
					if ($comments) echo "<br>New receivablesaccountID entryID created - " . $entryID;
	
	
					// Tehdään pankkitilin lisäys vienti
					if ($comments) echo "<br>Bankaccount tili - " . $bankaccount->accountID;
					//$account = $accounts[$bankaccount->accountID];
					//if ($comments) echo "<br>Receivables saatavat tili - " . $account->number . " - " . $account->name;
	
					$values = array();
					$values['ReceiptID'] = $newreceiptID;
					$values['AccountID'] =  $bankaccount->accountID;
					$values['Amount'] = $entry->amount;
					$values['Entrydate'] = $bankstatementrow->entrydate;
					$values['VatcodeID'] = 0;
					$values['LinktypeID'] = 3;
					$values['LinktargetID'] = $bankstatementrow->rowID;
					if (count($dimensions) > 0) {
						foreach($dimensions as $index => $dimension) {
							$variable = 'dimension'.+ $dimension->dimensionID;
							$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
						}
					}
					$entryID = Table::addRow("accounting_entries", $values, false);
					echo "<br>myyntisaamisten vähennysvienti - receiptID:" . $newreceiptID . ", amount: " . ($entry->amount);
					echo "<br> ++++ updateRow accounting_entries - receiptID:" . $newreceiptID;
		
					// Päivitetään myös ostolaskun vienti niin, että tiedetään että se on kohdistettu...
					$values = array();
					$values['LinktypeID'] = 4;					// saamisvienti maksettu
					$values['LinktargetID'] = $newreceiptID;
					$success = Table::updateRow('accounting_entries', $values, $entry->entryID, false);
					echo "<br> ++++ updateRow accounting_entries - " . $entry->entryID;
					$targetamount = $targetamount -  $entry->amount;
					echo "<br>Updated targetamount - " . $targetamount;
				} else {
					echo "<br>-- entry amount on suurempi kuin tarvittava, tiliöidään kyseinen lasku maksetuksi";
	
				}
	
				if ($targetamount == 0) {
					echo "<br>Koko targetsumma tiliöity";
					break;
				}
	
			} else {
				if ($entry->accountID != $receivablesaccountID) {
					echo "<br>-- entry ei ole pankkitilisaamisvienti";
				} else {
					echo "<br>-- entry on ilmeisesti linkitetty aiemmin";
				}
			}
		}		// ostolaskun entryjen läpikäynti loppuu tähän
			
	
	
	
		if ($targetamount > 0) {
			echo "<br>**** Koko targetsumma ei saatu tiliöityä";
			exit;
		}
			
			
		if ($targetamount < 0) {
			echo "<br>Unpaidamoount ei saa mennä negatiiviseksi - " . $invoice->unpaidamount;
			exit;
		}
	
		if ($targetamount == 0) {
			$values = array();
			echo "<br>Lasku on maksettu kokonaan";
			$values['State'] = 4;	// merkitään maksetuksi
			$values['Unpaidamount'] = 0;
			$values['Paymentdate'] = $bankstatementrow->entrydate;
			$success = Table::updateRow('sales_invoices', $values, $invoice->invoiceID, false);
			echo "<br> ++++ updateRow sales_invoices - " . $invoice->invoiceID;
			if ($comments) echo "<br>invoice state updated - " . $invoiceID;
		}
	
		// Merkitään laskurivi linkitetyksi
		$values = array();
		$values['Status'] = 4;		// Linkitetty
		$success = Table::updateRow('accounting_bankstatementrows', $values, $statementrowID, false);
		if ($comments) echo "<br>accounting_bankstatementrows state updated - " . $statementrowID;
	
		if (!$comments) redirecttotal('accounting/bankstatements/showbankstatement&id=' . $bankstatement->bankstatementID,null);
	}
	
	
	
	public function insertbankstatementreceiptAction() {
	
		$comments = false;
	
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
	
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
		
		$accountID = $_GET['accountID'];
		$account = Table::loadRow('accounting_accounts', $accountID);
		
		if ($comments) echo "<br>Vientitili - " . $accountID;
		if ($comments) echo "<br>Vientitili - " . $account->name;
			
		$bankstatementrowID = $_GET['statementrowID'];
		if ($comments) echo "<br>statementrowID - " . $bankstatementrowID;
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
	
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		//$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = Collections::PAYMENTSTATUS_CONFIRMED;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['PurchaseID'] = 0;
		$values['InvoiceID'] = 0;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['BankstatementrowID'] = $bankstatementrowID;
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
	
		// toisen tilin vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $accountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
		
		// Päivitä tilioterivin käsitellyksi (linked)
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
	
		if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	
	
	
	// tämä on kopioitu täysin edellisestä insertbankstatementreceiptAction, lisätty 
	// ainoastaan supplierID...
	public function insertbankstatementpayablereceiptAction() {
	
		$comments = false;
		
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($comments) echo "<br>bankaccountID - " . $bankaccountID;
	
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankaccountID);
		if ($comments) echo "<br>bankaccount - " . $bankaccount->name;
	
		$supplierID = $_GET['supplierID'];
		$supplier = Table::loadRow('accounting_suppliers', $supplierID);
		
		$accountID = $bankaccount->payablesaccountID;
		$account = Table::loadRow('accounting_accounts', $accountID);
	
		if ($comments) echo "<br>Vientitili - " . $accountID;
		if ($comments) echo "<br>Vientitili - " . $account->name;
			
		$bankstatementrowID = $_GET['statementrowID'];
		if ($comments) echo "<br>statementrowID - " . $bankstatementrowID;
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatementname = $this->getBankstatementRowName($bankstatementrow);
		
		$receiptnumber = $this->getNextReceiptNumber($bankaccount->receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
	
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $bankaccount->receiptsetID;
		//$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['Explanation'] = "Tiliote " . $bankstatementname . ", " . sqlDateToStr($bankstatementrow->entrydate);
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = Collections::PAYMENTSTATUS_CONFIRMED;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['PurchaseID'] = 0;
		$values['InvoiceID'] = 0;
		$values['SupplierID'] = $supplierID;
		$values['Debet'] = $bankstatementrow->amount;
		$values['Credit'] = $bankstatementrow->amount;
		$values['BankstatementrowID'] = $bankstatementrowID;
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
	
		// Pankkitilivienti...
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
		
		// toisen tilin vienti
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $accountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['VatcodeID'] = 0;
		$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
		$values['LinktargetID'] = $bankstatementrow->rowID;
		$entryID = Table::addRow("accounting_entries", $values, false);
	
		// Päivitä tilioterivin käsitellyksi (linked)
		$values = array();
		$values['Status'] = Collections::BANKSTATEMENTSTATE_LINKED;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, false);
	
		if (!$comments) redirecttotal('accounting/alignment/alignment',null);
	}
	
	

	private function getNextReceiptNumber($receiptsetID, $comments = false) {
	
		global $mysqli;
		$comments = false;
	
		$sql = "SELECT * FROM accounting_receipts WHERE ReceiptsetID=" . $receiptsetID . " ORDER BY Receiptnumber DESC LIMIT 1";
		if ($comments) echo "<br> -- " . $sql;
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
	
		if ($row == null) {
			if ($comments) echo "<br> rownull ";
			$receiptset = Table::loadRow("accounting_receiptsets", $receiptsetID);
			$receiptnumber = $receiptset->startnumber;
		} else {
			$receiptnumber = intval($row['Receiptnumber']);
			if ($comments) echo "<br>receiptnumberfound - " . $receiptnumber;
			$receiptnumber++;
		}
		return $receiptnumber;
	}
	
	
}

?>
