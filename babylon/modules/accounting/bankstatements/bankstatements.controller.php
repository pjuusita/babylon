<?php



class BankstatementsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showbankstatementsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showbankstatementsAction() {

		$comments = false;
		updateActionPath("Tiliotteet");
		
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
		$this->registry->bankaccounts = Table::load('accounting_bankaccounts');
		
		
		$bankaccountID = getModuleSessionVar('bankaccountID',0);
		if ($bankaccountID == 0) {
			foreach($this->registry->bankaccounts as $index => $bankaccount) {
				$bankaccountID = $bankaccount->bankaccountID;
				break;
			}
		}
		$this->registry->bankaccountID = $bankaccountID;
		
		if (isset($_GET['statementID'])) {
			$statementID = $_GET['statementID'];
		} else {
			$statementID = getModuleSessionVar('statementID',0);
		}
		
		$this->registry->statements = Table::load('accounting_bankstatements', " WHERE Startdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $this->registry->period->enddate . "' ORDER BY Startdate");
		foreach($this->registry->statements as $index => $statement) {
			if ($statementID == 0) {
				$statementID = $statement->bankstatementID;
			}
			if ($statementID == $statement->bankstatementID) {
				$this->registry->statement = $statement;
				$startdate = $statement->startdate;
				$startdate = $statement->startdate;
			}
			$month = substr($statement->startdate, 5, 2);
			$year = substr($statement->startdate, 0, 4);
			$statement->name = $year . "-" . $month;
		}
		if ($comments) echo "<br>Startdate -" .$startdate;
		if ($comments) echo "<br>Enddate -" . $enddate;
		
		
		$this->registry->statementID = $statementID;
	
		$this->registry->template->show('accounting/bankstatements','bankstatements');
	}
	
	
	
	
	public function showbankstatementAction() {
	
		$bankstatementID = $_GET['id'];
		$this->registry->bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementID);
		
		// TODO: pitäisikö tuo päivämäärä olla tuossa eritavalla huomioitu, jos päivän pituinen kuitti
		//       niin näytä koko päivämäärä. Jos samalla kuukaudella eka ja vika, niin näytä kuukausi kuten nyt
		updateActionPath("Tiliote " . substr($this->registry->bankstatement->startdate,0,7));
		//updateActionPath("Tiliote " . substr($this->registry->bankstatement->startdate,0,7));
		
		$bankstatementrows = Table::load('accounting_bankstatementrows', ' WHERE BankstatementID=' . $bankstatementID . " ORDER BY Entrydate, RowID");
		
		$startrow = new Row();
		$startrow->rowID = 0;
		$startrow->sourceID = "0";
		
		$startrow->entrydate = $this->registry->bankstatement->startdate;
		$startrow->amount = $this->registry->bankstatement->startamount;
		$startrow->total = $this->registry->bankstatement->startamount;
		$total = $startrow->total;
		
		$this->registry->companies = Table::load('crm_companies');
		$this->registry->clients = Table::load('crm_clients');
		
		$this->registry->suppliers = Table::load("accounting_suppliers");
		$this->registry->workers = Table::load("hr_workers");
		$this->registry->statementrowstatuses = Collections::getBankStatementStates();
		$this->registry->receipts = Table::load('accounting_receipts', ' WHERE BankstatementID=' . $bankstatementID . " ORDER BY Receiptdate");
		
		
		$sources = array();
		$source = new Row();
		$source->rowID = 0;
		$source->sourceID = "0";
		$source->name = "Alkusaldo";
		$sources[0] = $source;

		$source = new Row();
		$source->rowID = 0;
		$source->sourceID = "UD";
		$source->name = "<i>n/a</i>";
		$sources["UD"] = $source;
		
		foreach($this->registry->clients as $index => $person) {
			$person->sourceID = "P" . $person->clientID;
			$person->name = $person->lastname . " " . $person->firstname;
			$sources[$person->sourceID] = $person;
		}
		foreach($this->registry->companies as $index => $company) {
			$company->sourceID = "C" . $company->companyID;
			$sources[$company->sourceID] = $company;
		}
		foreach($this->registry->suppliers as $index => $supplier) {
			$supplier->sourceID = "S" . $supplier->supplierID;
			$sources[$supplier->sourceID] = $supplier;
		}
		foreach($this->registry->workers as $index => $worker) {
			$worker->name = $worker->firstname . " " . $worker->lastname;
			$worker->sourceID = "W" . $worker->workerID;
			$sources[$worker->sourceID] = $worker;
		}
		$this->registry->sources = $sources;
		
		$newrows = array();
		$newrows[] = $startrow;
		foreach($bankstatementrows as $index => $row) {
			$total = $total + $row->amount;
			$row->total = $total;
			if (($row->clientID == 0) && ($row->companyID == 0) && ($row->supplierID == 0) && ($row->workerID == 0)) {
				$row->sourceID = "UD";
			} else {
				if ($row->companyID != 0) $row->sourceID = "C" . $row->companyID;
				if ($row->workerID != 0) $row->sourceID = "W" . $row->workerID;
				if ($row->supplierID != 0) 	$row->sourceID = "S" . $row->supplierID;
				if ($row->clientID != 0) $row->sourceID = "P" . $row->clientID;
			}
			$newrows[] = $row;
		}
		
		$this->registry->bankstatementrows = $newrows;
		$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->template->show('accounting/bankstatements','bankstatement');
	}
	

	
	public function insertbankstatementAction() {
	
		global $mysqli;
		$comments = false;
	
		$startdate = $_GET['startdate'];
		$enddate = $_GET['enddate'];
		$startamount = floatval(str_replace(",",".",$_GET['startamount']));
		
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['Startdate'] = $startdate;
		$values['Enddate'] = $enddate;
		$values['Startamount'] = $startamount;
		$values['BankaccountID'] = $_GET['bankaccountID'];;
		
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
		$statementID = Table::addRow("accounting_bankstatements", $values, $comments);
		if (!$comments) redirecttotal('accounting/bankstatements/showbankstatements',null);
	}
	
	
	public function bankstatementlinkingAction() {
	
		global $mysqli;
		$comments = false;
		
		$bankStatementRowID = $_GET['id'];
		$periodID =  $_GET['periodID'];
		
		$bankStatementRow = Table::loadRow('accounting_bankstatementrows', $bankStatementRowID);
		$bankStatement = Table::loadRow('accounting_bankstatements', $bankStatementRow->bankstatementID);
		$this->registry->period = Table::loadRow('accounting_periods', $periodID);

		$startdate = $this->registry->period->startdate;
		$enddate = $this->registry->period->enddate;
			
		$this->registry->receipts = Table::load('accounting_receipts', " WHERE Receiptdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND BankstatementrowID IS NULL ORDER BY Receiptdate");
		//$this->registry->accounts = Table::load('accounting_accounts');
		
		$this->registry->bankStatement = $bankStatement;
		$this->registry->bankStatementRow = $bankStatementRow;
		
		
		$this->registry->template->show('accounting/bankstatements','bankstatementlinking');
	}
	
	

	public function linksalesinvoiceAction() {
	
		// Tämä linkitys voi kohdistua myös avoimeen saldoon, pitäisi testata
		
		global $mysqli;
		$comments = true;
	
		$invoiceID = $_GET['invoiceID'];
		$statementrowID = $_GET['statementrowID'];
	
		if ($comments) echo "<br>invoiceID - " . $invoiceID;
		if ($comments) echo "<br>StatementrowID - " . $statementrowID;
		
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $statementrowID);
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
		$values['Explanation'] = "Tilioterivi " . $statementrowID;
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
		
					//if ($comments) echo "<br>New bankaccount->accountID entryID created - " . $entryID;
		
					// Receiptiin asetetaan bankstatementrowID ja bankstatementID, päivitetään myös dimensiot viimeisimpään
					/*
					$values = array();
					$values['BankstatementrowID'] = $bankstatementrow->rowID;;
					$values['BankstatementID'] = $bankstatementrow->bankstatemenID;
					if (count($dimensions) > 0) {
						foreach($dimensions as $index => $dimension) {
							$variable = 'dimension'.+ $dimension->dimensionID;
							$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
						}
					}
					$success = Table::updateRow('accounting_receipts', $values, $newreceiptID, false);
					echo "<br> ++++ updateRow accounting_receipts - " . $newreceiptID;
					*/
					
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
		
		
		/*
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] = $recievablescountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['Vatcode'] = 0;
		$entryID = Table::addRow("accounting_entries", $values, $comments);
		if ($comments) echo "<br>New entryID created - " . $entryID;
		
		
		// pankkitili
		if ($comments) echo "<br>Bankaccount tili - " . $bankaccount->accountID;
		$account = $accounts[$bankaccount->accountID];
		if ($comments) echo "<br>Receivables saatavat tili - " . $account->number . " - " . $account->name;
		
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;;
		$values['Amount'] =  $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['Vatcode'] = 0;
		$entryID = Table::addRow("accounting_entries", $values, $comments);
		if ($comments) echo "<br>New entryID created - " . $entryID;
		*/
		
		/*
		$values = array();
		$values['Paymentdate'] = $bankstatementrow->entrydate;
		$values['State'] = 4;
		$success = Table::updateRow('sales_invoices', $values, $invoiceID, $comments);
		if ($comments) echo "<br>invoice state updated - " . $invoiceID;
		*/
		// Pitäisi päivittää myyntisaamisentry laskulla
		
		
		
		//$this->registry->template->show('accounting/bankstatements','bankstatementlinking');
	}
	

	

	public function linkpurchaseAction() {
	
		global $mysqli;
		$comments = true;
	
		$purchaseID = $_GET['purchaseID'];
		$statementrowID = $_GET['statementrowID'];
	
		if ($comments) echo "<br>purchaseID - " . $purchaseID;
		if ($comments) echo "<br>StatementrowID - " . $statementrowID;
	
	
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $statementrowID);
		$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankstatement->bankaccountID);
		$purchase = Table::loadRow('accounting_purchases', $purchaseID);
		$accounts = Table::load('accounting_accounts');
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		
		if ($purchase->state != 1) {
			echo "<br>Purchasen tila on " . $purchase->state . " ei voida merkitä maksetuksi";
			exit;
		}
	
		$receiptsetID =  Settings::getSetting('accounting_bankstatementreceiptsetID', 0);
		
		if ($receiptsetID == 0) {
			echo "<br>Bankstatemenreceiptset ei ole asetuksissa asetettu";
			exit;
		}
		

		$payablescountID = $bankaccount->payablesaccountID;
		if ($comments) echo "<br>Ostovelkojen tili - " . $payablescountID;
		$account = $accounts[$payablescountID];
		if ($comments) echo "<br>Receivables saatavat tili - " . $account->number . " - " . $account->name;
		
		
		$purchaseentries= Table::load('accounting_entries',' WHERE ReceiptID=' . $purchase->receiptID);
		$payablesum = 0;		
		foreach($purchaseentries as $entryID => $purchaseentry) {
			// TODO: pankkitilivienti pitää jakaa dimensioihin sen mukaan kuin ostovelkatili on jaettu dimensioihin

			if ($purchaseentry->accountID == $payablescountID) {
				echo "<br>Ostovelkatili löytyi - entryID:" . $purchaseentry->entryID;
				$payablesum = $payablesum + $purchaseentry->amount;
			}
		}
		echo "<br>Payablesum - " . $payablesum;
		
		if ($payablesum == $bankstatementrow->amount) {
			echo "<br>Ostovelkatili täsmää";
		} else {
			echo "<br>Ostovelkatili ei täsmää";
			exit;
		}
		
		
		
		
		if ($comments) echo "<br>Selected receiptsetID - " . $receiptsetID;
	
		if (($bankstatementrow->receiptID == null) || ($bankstatementrow->receiptID == 0)){
			$receiptnumber = $this->getNextReceiptNumber($receiptsetID, $comments);
			if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
			
			
			$values = array();
			$values['Receiptdate'] = $bankstatementrow->entrydate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Tilioterivi " . $statementrowID;
			$values['ReceiverID'] = 0;
			$values['CostpoolID'] = 0;
			$values['Grossamount'] = $bankstatementrow->amount;
			$values['Netamount'] =  $bankstatementrow->amount;
			$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 3;
			$values['BankstatementID'] = $bankstatementrow->bankstatementID;
			$values['InvoiceID'] = 0;
			$values['PurchaseID'] = $purchaseID;
			$values['BankstatementrowID'] = $statementrowID;
			
			
			if (count($dimensions) > 0) {
				foreach($dimensions as $index => $dimension) {
					$variable = 'dimension'.+ $dimension->dimensionID;
					if (isset($_GET[$variable])) {
						$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
					} else {
						$values['Dimension'. $dimension->dimensionID] = $purchase->$variable;
					}
				}
			}
			
			
			$newreceiptID = Table::addRow("accounting_receipts", $values, $comments);
			if ($comments) echo "<br>New receipt created - " . $newreceiptID;
			
		} else {
			$newreceiptID = $bankstatementrow->receiptID;
		}

		
		foreach($purchaseentries as $entryID => $purchaseentry) {
			// TODO: pankkitilivienti pitää jakaa dimensioihin sen mukaan kuin ostovelkatili on jaettu dimensioihin
		
			if ($purchaseentry->accountID == $payablescountID) {
				echo "<br>Ostovelkatili löytyi - entryID:" . $purchaseentry->entryID;
				$payablesum = $payablesum + $purchaseentry->amount;
				

				$values = array();
				$values['ReceiptID'] = $newreceiptID;
				$values['AccountID'] = $payablescountID;
				$values['Amount'] = -1 * $purchaseentry->amount;
				$values['Entrydate'] = $bankstatementrow->entrydate;
				$values['VatcodeID'] = 0;
				if (count($dimensions) > 0) {
					foreach($dimensions as $index => $dimension) {
						$variable = 'dimension'.+ $dimension->dimensionID;
						$values['Dimension'. $dimension->dimensionID] = $purchaseentry->$variable;
					}
				}
				$entryID = Table::addRow("accounting_entries", $values, false);
				if ($comments) echo "<br>New entryID created - " . $entryID;
				
				
				// pankkitili
				if ($comments) echo "<br>Bankaccount tili - " . $bankaccount->accountID;
				$account = $accounts[$bankaccount->accountID];
				if ($comments) echo "<br>Receivables saatavat tili - " . $account->number . " - " . $account->name;
				
				$values = array();
				$values['ReceiptID'] = $newreceiptID;
				$values['AccountID'] =  $bankaccount->accountID;;
				$values['Amount'] = $purchaseentry->amount;
				$values['Entrydate'] = $bankstatementrow->entrydate;
				$values['VatcodeID'] = 0;
				if (count($dimensions) > 0) {
					foreach($dimensions as $index => $dimension) {
						$variable = 'dimension'.+ $dimension->dimensionID;
						$values['Dimension'. $dimension->dimensionID] = $purchaseentry->$variable;
					}
				}
				$entryID = Table::addRow("accounting_entries", $values, $comments);
				if ($comments) echo "<br>New entryID created - " . $entryID;
			}
		}
		
		
		
		$values = array();
		$values['Paymentdate'] = $bankstatementrow->entrydate;
		$values['State'] = 4;
		$success = Table::updateRow('accounting_purchases', $values, $purchaseID, $comments);
		if ($comments) echo "<br>invoice state updated - " . $purchaseID;
	
		$values = array();
		$values['Status'] = 4;
		$values['ReceiptID'] = $newreceiptID;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $statementrowID, $comments);
		if ($comments) echo "<br>accounting_bankstatementrows state updated - " . $statementrowID;
		if (!$comments) redirecttotal('accounting/bankstatements/showbankstatement&id=' . $bankstatement->bankstatementID,null);
	}
	
	
	
	public function linksalesinvoicetoopenbalanceAction() {
	
		global $mysqli;
		$comments = true;
	
		$clientID = $_GET['clientID'];
		$statementrowID = $_GET['statementrowID'];
		
		echo "<br>Link to open balance - " . $clientID;
		if ($comments) echo "<br>StatementrowID - " . $statementrowID;
		
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $statementrowID);
		$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankstatement->bankaccountID);
		$receiptsetID =  Settings::getSetting('accounting_bankstatementreceiptsetID', 0);
		$accounts = Table::load('accounting_accounts');
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		
		
		if ($receiptsetID == 0) {
			echo "<br>Bankstatemenreceiptset ei ole asetuksissa asetettu";
			exit;
		}
		

		if ($comments) echo "<br>Selected receiptsetID - " . $receiptsetID;
		
		if (($bankstatementrow->receiptID == null) || ($bankstatementrow->receiptID == 0)){
			$receiptnumber = $this->getNextReceiptNumber($receiptsetID, $comments);
			if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
				
				
			$values = array();
			$values['Receiptdate'] = $bankstatementrow->entrydate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Tilioterivi " . $statementrowID;
			$values['ReceiverID'] = 0;
			$values['CostpoolID'] = 0;
			$values['Grossamount'] = $bankstatementrow->amount;
			$values['Netamount'] =  $bankstatementrow->amount;
			$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 3;
			$values['BankstatementID'] = $bankstatementrow->bankstatementID;
			$values['InvoiceID'] = 0;
			$values['PurchaseID'] = 0;
			$values['Debet'] = $bankstatementrow->amount;
			$values['Credit'] = $bankstatementrow->amount;
			$values['BankstatementrowID'] = $statementrowID;
			
			
			/*
			 TODO: Dimensiot päätellään myöhemmin riveistä 
			 
			if (count($dimensions) > 0) {
				foreach($dimensions as $index => $dimension) {
					$variable = 'dimension'.+ $dimension->dimensionID;
					if (isset($_GET[$variable])) {
						$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
					} else {
						$values['Dimension'. $dimension->dimensionID] = $purchase->$variable;
					}
				}
			}
			*/
			$newreceiptID = Table::addRow("accounting_receipts", $values, false);
			echo "<br> ++++ addRow accounting_receipts - newreceiptID:" . $newreceiptID;
				
			// Päivitetään receiptID samantien, jos homma feilaa myöhemmin, niin tämä on ainakin jo lisätty
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$success = Table::updateRow('accounting_bankstatementrows', $values, $statementrowID, false);
			echo "<br> ++++ updateRow accounting_bankstatementrows - statementrowID:" . $statementrowID;
			
			if ($comments) echo "<br>New receipt created - " . $newreceiptID;
				
		} else {
			$newreceiptID = $bankstatementrow->receiptID;
		}
		
		// Haetaan ostolaskut jotka ovat joko ei maksettu tilassa tai osittain maksettu (= <4)
		$invoices = Table::load('sales_invoices', ' WHERE ClientpersonID=' . $clientID . ' AND State<4 ORDER BY Invoicedate');
		$targetamount = $bankstatementrow->amount;
		echo "<br>TargetAmount - " . $targetamount;
		$receivablesaccountID = $bankaccount->receivablesaccountID;
		echo "<br>TargetAccount - " . $receivablesaccountID;
		
		foreach($invoices as $index => $invoice) {
			echo "<br>Käsitellään laskua - " . $invoice->invoiceID . " -- " . $invoice->invoicedate. ", invoice unpaid = " . $invoice->unpaidamount;
			
			$entries = Table::load('accounting_entries', ' WHERE ReceiptID=' . $invoice->receiptID);
			foreach($entries as $entryID => $entry) {
				echo "<br> -- entry: " . $entry->entryID . ", accountID: " . $entry->accountID . ", amount: " . $entry->amount;
				
				if ($entry->accountID == $receivablesaccountID && (($entry->linktypeID == null) || ($entry->linktypeID == 0))) {
				
					echo "<br>-- entry on pankkitilivienti ja linkittämätön";
					echo "<br>-- linkittämätön maksun osuus - " . $targetamount;
						
					if ($entry->amount <= $targetamount) {
							
						echo "<br>-- entry amount on pienempi kuin tarvittava, tiliöidään kyseinen lasku maksetuksi --- " . $entry->amount . " vs. " . $targetamount;
							
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
						
						//if ($comments) echo "<br>New bankaccount->accountID entryID created - " . $entryID;
						
						// Receiptiin asetetaan bankstatementrowID ja bankstatementID, päivitetään myös dimensiot viimeisimpään
						$values = array();
						$values['BankstatementrowID'] = $bankstatementrow->rowID;;
						$values['BankstatementID'] = $bankstatementrow->bankstatemenID;
						if (count($dimensions) > 0) {
							foreach($dimensions as $index => $dimension) {
								$variable = 'dimension'.+ $dimension->dimensionID;
								$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
							}
						}
						$success = Table::updateRow('accounting_receipts', $values, $newreceiptID, false);
						echo "<br> ++++ updateRow accounting_receipts - " . $newreceiptID;
						
						// Päivitetään myös nykyinen vienti niin, että tiedetään että se on kohdistettu...
						$values = array();
						$values['LinktypeID'] = 4;					// saamisvienti maksettu
						$values['LinktargetID'] = $newreceiptID; 
						$success = Table::updateRow('accounting_entries', $values, $entry->entryID, false);
						echo "<br> ++++ updateRow accounting_entries - " . $entry->entryID;
						
						
						// Lasku pitää merkata maksetuksi, jos kaikki pankkitiliviennit on tiliöity...
						$invoice->unpaidamount = $invoice->unpaidamount - $entry->amount;

						$values = array();
						if ($invoice->unpaidamount < 0) {
							echo "<br>Unpaidamoount ei saa mennä negatiiviseksi - " . $invoice->unpaidamount;
							exit;
						}
						if ($invoice->unpaidamount == 0) {
							echo "<br>Lasku on maksettu kokonaan";
							$values['State'] = 4;	// merkitään maksetuksi
						} else {
							$values['State'] = 3;	// merkitään lasku osittain maksetuks
						}
						$values['Paymentdate'] = $bankstatementrow->entrydate;
						$values['Unpaidamount'] = $invoice->unpaidamount;
						$success = Table::updateRow('sales_invoices', $values, $invoice->invoiceID, false);
						echo "<br> ++++ updateRow sales_invoices - " . $invoice->invoiceID;
						
						
						$targetamount = $targetamount -  $entry->amount;
						
						echo "<br>Updated targetamount - " . $targetamount;
						
					} else {
						
						echo "<br>-- Pystytään maksamaan lasku vain osittain, lasku merkataan osittain maksetuksi";
						
						// Pystytään maksamaan lasku vain osittain, lasku merkataan osittain maksetuksi
						// Tehdään myyntisaamisten vähennys vienti
						$values = array();
						$values['ReceiptID'] = $newreceiptID;
						$values['AccountID'] = $receivablesaccountID;
						$values['Amount'] = -1 * $targetamount;
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
						if ($comments) echo "<br>New receivablesaccountID entryID created - " . $entryID;
						echo "<br> ++++ Add accounting_entries - " . $entryID . " - receiptID - " . $newreceiptID;
						
						// Tehdään pankkitilin lisäys vienti
						if ($comments) echo "<br>Bankaccount tili - " . $bankaccount->accountID;
						$account = $accounts[$bankaccount->accountID];
						if ($comments) echo "<br>Receivables saatavat tili - " . $account->number . " - " . $account->name;
							
						$values = array();
						$values['ReceiptID'] = $newreceiptID;
						$values['AccountID'] =  $bankaccount->accountID;
						$values['Amount'] = $targetamount;
						$values['Entrydate'] = $bankstatementrow->entrydate;
						$values['VatcodeID'] = 0;
						$values['LinktypeID'] = 3;		// pankkitilin saamisvienti
						$values['LinktargetID'] = $bankstatementrow->rowID;
						if (count($dimensions) > 0) {
							foreach($dimensions as $index => $dimension) {
								$variable = 'dimension'.+ $dimension->dimensionID;
								$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
							}
						}
						$entryID = Table::addRow("accounting_entries", $values, false);
						echo "<br> ++++ Add accounting_entries - " . $entryID . " - receiptID - " . $newreceiptID;
						
						if ($comments) echo "<br>New bankaccount->accountID entryID created - " . $entryID;
						
						
						// Receiptiin asetetaan bankstatementrowID ja bankstatementID, päivitetään myös dimensiot viimeisimpään
						$values = array();
						$values['BankstatementrowID'] = $bankstatementrow->rowID;;
						$values['BankstatementID'] = $bankstatementrow->bankstatemenID;
						if (count($dimensions) > 0) {
							foreach($dimensions as $index => $dimension) {
								$variable = 'dimension'.+ $dimension->dimensionID;
								$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
							}
						}
						$success = Table::updateRow('accounting_receipts', $values, $newreceiptID, false);
						echo "<br> ++++ Update accounting_receipts - " . $newreceiptID;
						
						// Jaetaan olemassaoleva vienti kahdeksi vienniksi
						$values = array();
						$values['ReceiptID'] = $entry->receiptID;
						$values['AccountID'] =  $entry->accountID;
						$values['Amount'] = $targetamount;
						$values['Entrydate'] = $entry->entrydate;
						$values['VatcodeID'] = $entry->vatcodeID;
						$values['LinktypeID'] = 3;
						$values['LinktargetID'] = $bankstatementrow->rowID;
						if (count($dimensions) > 0) {
							foreach($dimensions as $index => $dimension) {
								$variable = 'dimension'.+ $dimension->dimensionID;
								$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
							}
						}
						$entryID = Table::addRow("accounting_entries", $values, false);
						echo "<br> ++++ Add accounting_entries - receiptID" . $entry->receiptID;
						
						
						
						$values = array();
						$values['Amount'] = $entry->amount - $targetamount;
						$values['Entrydate'] = $entry->entrydate;
						$values['LinktypeID'] = 0;
						$values['LinktargetID'] = 0;
						$success = Table::updateRow("accounting_entries", $values, $entry->entryID);
						echo "<br> ++++ Update accounting_entries - " . $entry->entryID;
						
						
						// päivitetään invoicen unpaidamount
						$invoice->unpaidamount = $invoice->unpaidamount - $targetamount;
						$values = array();
						if ($invoice->unpaidamount < 0) {
							echo "<br>Osittainmaksu Unpaidamoount ei saa mennä negatiiviseksi - " . $invoice->unpaidamount;
							exit;
						}
						if ($invoice->unpaidamount == 0) {
							echo "<br>Osittainmaksu, unpaid nolla maksettu kokonaan, ei saisi tulla tänne";
							exit;
						} else {
							$values['State'] = 3;	// merkitään lasku osittain maksetuks
						}
						$values['Paymentdate'] = $bankstatementrow->entrydate;
						$values['Unpaidamount'] = $invoice->unpaidamount;
						$success = Table::updateRow('sales_invoices', $values, $invoice->invoiceID, false);
						echo "<br> ++++ Update sales_invoices - " . $invoice->invoiceID;
						
						
						$targetamount = 0;
					}
					
					if ($targetamount <= 0) {
						echo "<br>Koko targetsumma tiliöity";
						break;
					}
					
					if ($invoice->unpaidamount < 0) {
						echo "<br>Unpaidamoount ei saa mennä negatiiviseksi - " . $invoice->unpaidamount;
						exit;
					}
				} else {
					if ($entry->accountID != $receivablesaccountID) {
						echo "<br>-- entry ei ole pankkitilisaamisvienti";
					} else {
						echo "<br>-- entry on ilmeisesti linkitetty aiemmin";
					}
				}
			} 
			
			if ($targetamount <= 0) {
				echo "<br>Lopputarkistus - koko targetsumma tiliöity";
				break;
			}
			
		}
		
		
		if ($targetamount > 0) {
			// Maksettu liikaa, lisätään viimeiseen laskuun ylimääräinen maksuvienti...
			// Lisätään uusi tosite, ylimaksu tilille 241, velat asiakkaille, ennakkomaksut
			
			/*
			$values = array();
			$values['Receiptdate'] = $bankstatementrow->entrydate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Tilioterivi " . $statementrowID . ", ylimaksu";
			$values['ReceiverID'] = 0;
			$values['CostpoolID'] = 0;
			$values['Grossamount'] = $bankstatementrow->amount;
			$values['Netamount'] =  $bankstatementrow->amount;
			$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 3;
			$values['BankstatementID'] = $bankstatementrow->bankstatementID;
			$values['InvoiceID'] = 0;
			$values['PurchaseID'] = 0;
			$values['Debet'] = $bankstatementrow->amount;
			$values['Credit'] = $bankstatementrow->amount;
			$values['BankstatementrowID'] = $statementrowID;
			$newreceiptID = Table::addRow("accounting_receipts", $values, false);
			echo "<br> ++++ Ylimaksu, uusi tosite accounting_receipts - newreceiptID:" . $newreceiptID;
				
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$values['AccountID'] =  $bankaccount->accountID;
			$values['Amount'] = $targetamount;
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
			
			
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$values['AccountID'] = 241;
			$values['Amount'] = -1 * $targetamount;
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
			if (count($dimensions) > 0) {
				foreach($dimensions as $index => $dimension) {
					$variable = 'dimension'.+ $dimension->dimensionID;
					$values['Dimension'. $dimension->dimensionID] = $entry->$variable;
				}
			}
			$entryID = Table::addRow("accounting_entries", $values, false);
			if ($comments) echo "<br>New receivablesaccountID entryID created - " . $entryID;
			*/
			
		}
		
		$values = array();
		$values['Status'] = 4;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $statementrowID, false);
		
		if (!$comments) redirecttotal('accounting/bankstatements/showbankstatement&id=' . $bankstatement->bankstatementID,null);
	}
	
	

	private function getNextReceiptNumber($receiptsetID, $comments = false) {
	
		global $mysqli;
	
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
	
	
	

	
	/*
	public function linkreceiptAction() {
	
		global $mysqli;
		$comments = true;
		
		$rowID = $_GET['rowID'];
		$receiptID = $_GET['receiptID'];
		$bankaccountID = $_GEt['bankaccountID'];
		
		echo "<br>RowID - " . $rowID;
		echo "<br>receiptID - " . $receiptID;
		echo "<br>bankaccountID - " . $bankaccountID;
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $rowID);
		$receipt = Table::loadRow('accounting_receipts', $receiptID);
		$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankstatement->bankaccountID);
				
		if ($bankstatementrow->amount < 0) {		// ostovelka

				
			
		} else if ($bankstatementrow->amount > 0) {		// myyntisaaminen
				
				
		} else {
			echo "<br>Bankstatemenrow amount on nolla, ei voida linkittää";
		}
		//$this->registry->template->show('accounting/bankstatements','bankstatementlinking');
	}
	*/
	
	
	public function insertbankstatementrowAction() {
	
		$comments = false;
		$statementID = $_GET['statementID'];
	
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['BankstatementID'] = $statementID;
		$values['Entrydate'] = $_GET['entrydate'];;
		$values['Amount'] = floatval(str_replace(",",".",$_GET['amount']));
		$values['Status'] = 1;
		
		if (isset($_GET['companyID'])) {
			$values['CompanyID'] = $_GET['companyID'];
		}
		if (isset($_GET['supplierID'])) {
			$values['SupplierID'] = $_GET['supplierID'];
		}
		if (isset($_GET['clientID'])) {
			$values['ClientID'] = $_GET['clientID'];
		}
		if (isset($_GET['workerID'])) {
			$values['WorkerID'] = $_GET['workerID'];				
		}
		if (isset($_GET['rowname'])) {
			$values['Rowname'] = $_GET['rowname'];
		}
		if (isset($_GET['reference'])) {
			$values['Reference'] = $_GET['reference'];
		}
		if ($comments) echo "<br>statementID - " . $statementID;
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
		$success = Table::addRow("accounting_bankstatementrows", $values, $comments);
		if (!$comments) redirecttotal("accounting/bankstatements/showbankstatement&id=" . $statementID,null);
	}
	
	
	
	public function getBankStatementStatuses() {
		$values = array();
		$values[1] = "Käsitelty";
		$values[2] = "Odottaa";
		$values[3] = "Käsittelemättä";
		return $values;
	}

	
	public function updatebankstatementrowAction() {
	
		$rowID = $_GET['id'];
		$statementID = $_GET['statementID'];
		
		$values = array();
		$values['BankstatementID'] = $statementID;
		$values['Entrydate'] = $_GET['entrydate'];
		$values['Amount'] = floatval(str_replace(",",".",$_GET['amount']));
		//echo "<br>- amount - " . $values['Amount'];
		if (isset($_GET['companyID'])) {
			$values['CompanyID'] = $_GET['companyID'];
		}

		if (isset($_GET['supplierID'])) {
			$values['SupplierID'] = $_GET['supplierID'];
		}
		
		if (isset($_GET['workerID'])) {
			$values['WorkerID'] = $_GET['workerID'];
		}
		
		if (isset($_GET['clientID'])) {
			$values['ClientID'] = $_GET['clientID'];
		}
		
		if (isset($_GET['rowname'])) {
			$values['Rowname'] = $_GET['rowname'];
		}
		
		if (isset($_GET['reference'])) {
			$values['Reference'] = $_GET['reference'];
		}
		
		$success = Table::updateRow('accounting_bankstatementrows', $values, $rowID, false);
		
		redirecttotal('accounting/bankstatements/showbankstatement&id=' . $statementID,null);
	}
	
	

	public function updatebankstatementAction() {
	
		$statementID = $_GET['id'];
		$startamount = floatval(str_replace(",",".",$_GET['startamount']));
		
		$values = array();
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];;
		$values['Startamount'] = $startamount;
		$success = Table::updateRow('accounting_bankstatements', $values, $statementID, true);
	
		redirecttotal('accounting/bankstatements/showbankstatement&id=' . $statementID,null);
	}
	
	
	
	public function insertreceiptAction() {
	
		$comments = false;
		
		$bankstatementrowID = $_GET['rowID'];
		$bankstatementID =  $_GET['bankstatementID'];
		$accountID =  $_GET['accountID'];
		$amount =  $_GET['amount'];

		$row = Table::loadRow('accounting_receipts', " BankstatementrowID=" . $bankstatementrowID, $comments);
		if ($row != null) {
			echo "<br>Pankkitilirivi on jo linkitettu";
			exit;
		}
		
		$bankstatementrow = Table::loadRow('accounting_bankstatementrows', $bankstatementrowID);
		$bankstatement = Table::loadRow('accounting_bankstatements', $bankstatementrow->bankstatementID);
		$bankaccount = Table::loadRow('accounting_bankaccounts', $bankstatement->bankaccountID);
		
		$receiptsetID =  Settings::getSetting('accounting_bankstatementreceiptsetID', 0);
		if ($comments) echo "<br>Selected receiptsetID - " . $receiptsetID;
		
		$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
		if ($comments) echo "<br>Creating new receipt number - " . $receiptnumber;
		
		$values = array();
		$values['Receiptdate'] = $bankstatementrow->entrydate;
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $receiptsetID;
		$values['Explanation'] = "Tilioterivi " . $bankstatementrowID;
		$values['ReceiverID'] = 0;
		$values['CostpoolID'] = 0;
		$values['Grossamount'] = $bankstatementrow->amount;
		$values['Netamount'] =  $bankstatementrow->amount;
		$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		$values['Paymentstatus'] = 3;
		$values['BankstatementID'] = $bankstatementrow->bankstatementID;
		$values['InvoiceID'] = 0;
		$values['PurchaseID'] = 0;
		$values['BankstatementrowID'] = $bankstatementrowID;
		
		$newreceiptID = Table::addRow("accounting_receipts", $values, $comments);
		if ($comments) echo "<br>New receipt created - " . $newreceiptID;
		
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] = $accountID;
		$values['Amount'] = -1 * $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['Vatcode'] = 0;
		$entryID = Table::addRow("accounting_entries", $values, $comments);
		if ($comments) echo "<br>New entryID created - " . $entryID;
		
		
		// pankkitili
		if ($comments) echo "<br>Bankaccount tili - " . $bankaccount->accountID;
		
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		$values['AccountID'] =  $bankaccount->accountID;
		$values['Amount'] = $bankstatementrow->amount;
		$values['Entrydate'] = $bankstatementrow->entrydate;
		$values['Vatcode'] = 0;
		$entryID = Table::addRow("accounting_entries", $values, $comments);
		if ($comments) echo "<br>New entryID created - " . $entryID;
		
		
		$values = array();
		$values['Status'] = 4;
		$success = Table::updateRow('accounting_bankstatementrows', $values, $bankstatementrowID, $comments);
		if ($comments) echo "<br>accounting_bankstatementrows state updated - " . $bankstatementrowID;
		
		if (!$comments) redirecttotal('accounting/bankstatements/showbankstatement&id=' . $bankstatementID,null);
	}

	/*
	public function insertreceiptAction() {
	
		$comments = true;
	
		global $mysqli;
	
		if ($comments) echo "<br>Receiptdate - " . $_GET['entrydate'];
		$receiptDate = $_GET['entrydate'];
		$receiptsetID = $_GET['receiptsetID'];
		$bankstatementrowID = $_GET['rowID'];
	
		$sql = "SELECT * FROM accounting_receipts WHERE ReceiptsetID=" . $receiptsetID . " ORDER BY Receiptnumber DESC LIMIT 1";
		if ($comments) echo "<br>Sql - " . $sql;
		$result = $mysqli->query($sql);
		$row = $result->fetch_array();
	
		if ($row == null) {
			$period = Table::loadRow("accounting_receiptsets", $receiptsetID);
			$startnumber = $period->startnumber;
		} else {
			$startnumber = intval($row['Receiptnumber']);
			$startnumber++;
		}
		if ($comments) echo "<br>startnumber - "  . $startnumber;
		if ($comments) echo "<br><br>";
	
		$grossAmount = str_replace(",",".",$_GET['amount']);
	
	
		$values = array();
		$values['Receiptdate'] = $receiptDate;
		$values['Paymentdate'] = $receiptDate;
		$values['BankstatementrowID'] = $bankstatementrowID;
		$values['Receiptnumber'] = $startnumber;
		$values['ReceiptsetID'] = $_GET['receiptsetID'];
		//$values['Explanation'] = $_GET['explanation'];
		$values['ReceiverID'] = $_GET['receiverID'];
		$values['CostpoolID'] = $_GET['costpoolID'];
		if ($grossAmount < 0) {
			$values['Grossamount'] = -1 * $grossAmount;
			$values['Netamount'] = -1 * $grossAmount;
			$values['Accounted'] = -1 * $grossAmount;
		} else {
			$values['Grossamount'] = $grossAmount;
			$values['Netamount'] = $grossAmount;
			$values['Accounted'] = $grossAmount;
		}
	
		$receiptID = Table::addRow("accounting_receipts", $values, false);
	
		// Lisäksi pitäisi tehdä entryt, ei tarvinne viedä ostovelkojen kautta, ei ainakaan
		// mikäli receiptdate ja rowdate ovat samat. Jos ovat pitäisi ehkä viedä ostovelkojen
		// kautta
	
		$bankaccountID = $_GET['bankaccountID'];
		if ($comments) echo "<br>BankaccountID - " . $bankaccountID;
		$costpoolID = $_GET['costpoolID'];
		if ($comments) echo "<br>CostpoolID - " . $costpoolID;
	
		$bankaccount = Table::loadRow("accounting_bankaccounts", $bankaccountID);
		$costpool = Table::loadRow("accounting_costpools", $costpoolID);
	
		if ($comments) echo "<br>Costpool accountID - " . $costpoolID;
	
		if (($costpool->accountID == null) || ($costpool->accountID == 0)) {
			echo "<br>Kustannuspaikalta puuttuu tili";
			exit;
		}
	
		// Tehdään pankkitilivienti
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $bankaccount->accountID;
		$values['Entrydate'] = $receiptDate;
		$values['Amount'] = $grossAmount;
		$success = Table::addRow("accounting_entries", $values, $comments);
	
		// Tehdään kulutilivienti
		// TODO: tässä pitäisi ehkä tsekata onko kyse tasetilistä vai kulutilistä, vaihda etumerkki
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $costpool->accountID;
		$values['Entrydate'] = $receiptDate;
		$values['Amount'] = -1 * $grossAmount;
		$success = Table::addRow("accounting_entries", $values, $comments);
	
		$values = array();
		$values['Status'] = 1;
		$success = Table::updateRow("accounting_bankstatementrows", $values, $bankstatementrowID);
	
		// päivitetään bankstatementrow käsitellyksi
		// asetetaan receiptiin bankstatementrowID ja paymentdate
			
		if (!$comments) redirecttotal('accounting/bankstatements/showbankstatements',null);
	}
	*/
	
	
}

?>
