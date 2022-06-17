<?php


/**
 * Purchases Ledger - Ostoreskontra - Ostolaskut / Ostot / Ostolaskujen seuranta, onkohan tama sama kuin Payables?
 *
 * Erapaivat merkittyna, muuten merkitaan maksetuiksi ja maksupaiva. Muuten samat valikkoratkaisut
 * kuin sales osion taulussa.
 *
 * Samalla pitaa pystya syattamaan liitetiedosto.
 *
 * Jonkinlainen pallukkaratkaisu mika nayttaa laskun tilan. Liittyen ostolaskukiertoon. Ostolaskukierto
 * on kaytassa vasta laajemmassa versiossa.
 *
 *
 */
class PurchasesController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php','fileuploader.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','fileuploader.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showpurchasesAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showpurchasesAction() {
	
		$comments = true;
		
		updateActionPath("Ostolaskut");
		$this->registry->suppliers = Table::load('accounting_suppliers', ' ORDER BY Name');
		//$this->registry->bankaccounts = Table::load('accounting_bankaccounts', ' ORDER BY Name');
		//$this->registry->persons = Table::load('hr_workers', ' ORDER BY Lastname,Firstname');
		//$this->registry->paymentcards = Table::load('accounting_paymentcards');
		//$this->registry->purchasetypes = Collections::getPurchaseTypes();
		//$this->registry->paybacktypes = Collections::getPaybackTypes();
		$this->registry->costpools = Table::load('accounting_costpools');
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		$this->registry->duedateselection = Collections::getDueDateUsageSelection();
		$this->registry->duedateselectionshort = Collections::getDueDateUsageSelectionShort();
		
		
		
		//foreach($this->registry->purchasetypes as $index => $value) {
		//	echo "<br>" . $index . " - " . $value;
		//}
		/*
		foreach($this->registry->paymentcards as $index => $card) {
			$person = $this->registry->persons[$card->workerID];
			if ($card->bankaccountID == 0) {
				$card->name = $card->number . " - " . $person->lastname . " " . $person->firstname . " (oma kortti)";
			} else {
				$card->name = $card->number . " - " . $person->lastname . " " . $person->firstname . " (firman kortti)";
			}
		}
		*/
		
		$periodID = getSessionVar('periodID',AccountingModule::getBookkeepingPeriod());
		//echo "<br>periodID - " . $periodID;
		//$oldperiodID = getOldModuleSessionVar('periodID');
		
		//if ($periodID != $oldperiodID) {
		//	echo "<br>Tilikautta muutettu";
		//}
		
		//if ($comments) echo "<br>OldperiodID - " . $oldperiodID;	
		//$periodID = AccountingModule::getBookkeepingPeriod();
		//if ($comments) echo "<br>PeriodID - " . $periodID;
		
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		if (count($this->registry->periods) == 0) {
			$errors = array();
			$errors[] = "Yhtään tilikautta ei ole luotu. Luo uusi tilikausi kohdasta <a style='color:black;' href='" .getUrl('accounting/bookkeepingsettings/showsettings') . "'>Hallinta / Kirjanpitoasetukset / Tilikaudet</a>."; 
			$this->registry->errors = $errors;
			$this->registry->template->show('system/error','errorpage');
			return;				
		}
				
		
		
		$selectionID = getSessionVar('selectionID',0);
		//if ($comments) echo "<br>selectionID - " . $selectionID;
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		$this->registry->selectionID = $selectionID;
		
		if ($selectionID == 0) {
			foreach($selection as $index => $sel) {
				$selectionID = $index;
				break;
			}			
		}
		$selectedmonth = $selection[$selectionID];
		
		$startdate = $selectedmonth->startsql;
		$enddate = $selectedmonth->endsql;
		//if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;

		
		$this->registry->invoices = Table::load('accounting_purchases', " WHERE Purchasedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Purchasedate");
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->lastdate = null;
		$purchaselist = array();
		$receiptlist = array();
		foreach($this->registry->invoices as $index => $invoice) {
			$invoice->alvamount = $invoice->grossamount - $invoice->netamount;
			if ($this->registry->lastdate == null) $this->registry->lastdate = $invoice->purchasedate;
			if ($this->registry->lastdate < $invoice->purchasedate) $this->registry->lastdate = $invoice->purchasedate;
			$purchaselist[$invoice->purchaseID] = $invoice->purchaseID;
			
			if ($invoice->receiptID > 0) {
				$receiptlist[$invoice->receiptID] = $invoice->receiptID;
			}
			
				
			// Tilan määrittäminen tähän...
			if ($invoice->state == 0) {
				$invoice->statestr = "X----";
			} 
			if ($invoice->state == 1) {
				$invoice->statestr = "XX---";
			}
			if ($invoice->state == 2) {
				$invoice->statestr = "XXX--";
			}
			if ($invoice->state == 3) {
				$invoice->statestr = "XXXX-";
			}
			if ($invoice->state == 4) {
				$invoice->statestr = "XXXXX";
			}
				
			
		}
		
		$receipts = Table::loadWhereInArray("accounting_receipts","ReceiptID", $receiptlist);
		foreach($receipts as $index => $receipt) {
			//echo "<br>receipt - " . $index . " - " . $receipt->receiptID;
			$purchase = $this->registry->invoices[$receipt->purchaseID];
			$purchase->receiptnumber = $receipt->receiptnumber;
		}		
		foreach($this->registry->invoices as $index => $invoice) {
			if ($invoice->receiptID > 0) {
				if (isset($receipts[$invoice->receiptID])) {
					$receipt = $receipts[$invoice->receiptID];
					if ($receipt->files != "") {
						$resultarray = array();
						$filearray = explode(",",$receipt->files);
						$counter = 1;
						foreach($filearray as $index => $filename) {
							$ext = $this->getExtension($filename);
							//echo "<br>Filename - " . $filename . " - " . $this->getExtension($filename); 
							if (isset($resultarray[$ext])) {
								$resultarray[$ext . "_" . $counter] = $filename . "&purchaseID=" . $invoice->purchaseID;
							} else {
								$resultarray[$ext] = $filename . "&purchaseID=" . $invoice->purchaseID;
							}
							$counter = 0;
						} 
						$invoice->file = $resultarray;
							//$invoice->link = "<a target='_blank' href='" .getUrl("accounting/purchases/upload") ."&id=" . $invoice->purchaseID . "&file=" . $receipt->files . "'>PDF</a>";
					} else {
						//$invoice->link = $invoice->receiptID;
						$invoice->link = "";
					}
				} else {
					echo "<br>Receipt not found 2 - " . $invoice->receiptID . ", invoiceID:" . $invoice->purchaseID;	
				}
			}
			//$invoice->file = $invoice->receiptID;
			
			//$invoice->link = $invoice->receiptID;
			//echo "<br>inv - " . $invoice->purchaseID . " - " . $invoice->receiptID;
		}
		if ($this->registry->lastdate == null) {
			$this->registry->lastdate = $this->registry->startdate;
			
			//$this->registry->lastdate = date('Y-m-d');
		}
		
		//echo "<br>ssi - " . $_SESSION['accounting/purchases/showpurchases_periodID'];
		$this->registry->template->show('accounting/purchases','purchases');
	}
	
	
	private function getExtension($filename) {
		$pos = strpos($filename, ".");
		//echo "<br>Pos - " . $pos;
		$str = substr($filename, $pos+1);
		//echo "<br>substr - " . $str;
		return $str;
	}
	

	
	// palauttaa listan kyseiselle periodille kuuluvista kuukausista
	/*
	private function generatePeriodTimescales($period, &$selectedindex, $currentdate = null) {
	
		$comments = false;
		$selection = array();
		$selectionindex = 0;
		$months = Collections::getMonths();
		
		if ($currentdate == null) {
			$currentdate = date("Y-m-d");
		}
		if ($comments) echo "<br>Current - " . $currentdate;
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
			$row->selectionID = $selectionindex;
			$row->year = $year;
			$row->name = $months[$month];
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
	

	// TODO: Siirretty ehkä ledger-moduliin
	public function showledgerAction() {
	
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
	
		$supplierID = getOldModuleSessionVar('supplierID',0);
		if ($comments) echo "<br>setted supplier - " . $supplierID;
		if (isset($_GET['supplierID'])) {
			if ($supplierID != $_GET['supplierID']) {
				$supplierID = $_GET['supplierID'];
				setModuleSessionVar('supplierID',$supplierID);
				$startdate = $this->registry->period->startdate;
				setModuleSessionVar('periodstartdate',$startdate);
				$enddate = $this->registry->period->enddate;
				setModuleSessionVar('periodenddate',$enddate);
				$selectionID = 0;
				setModuleSessionVar('selectionID',$selectionID);
			}
		}
		if ($comments) echo "<br>curren supplier - " . $supplierID;
		// TODO: Tämä lienee pitäisi tulla asetuksista ehkä...
		$receiptsetID = 1;
		$this->registry->supplierID = $supplierID;
	
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		if ($selectionID == 0) {
			if ($comments) echo "<br>selection id nolla - " . $selectionID;
			/*
				foreach($selection as $index => $value) {
				$selectionID = $index;
				if ($comments) echo "<br>First - " . $selectionID;
				break;
				}
				*/
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;
				
		} else {
			$currentselect = $selection[$selectionID];
			if ($comments) echo "<br>current start - " . $currentselect->startsql;
			if ($comments) echo "<br>current end - " . $currentselect->endsql;
			$startdate = $currentselect->startsql;
			$enddate = $currentselect->endsql;
		}
	
	
		if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		$this->registry->selectionID = $selectionID;
	
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		$this->registry->suppliers = Table::load('accounting_suppliers', ' ORDER BY Name');
		$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
		$this->registry->vats = Table::load('system_vats');
		$this->registry->costpools = Table::load('accounting_costpools');
		$this->registry->purchasestates = Collections::getPurchaseStates();
	
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
	
	
	
		if ($supplierID != 0) {
			$this->registry->invoices = Table::load('accounting_purchases', " WHERE Purchasedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND SupplierID=" . $supplierID . " ORDER BY Purchasedate");
		} else {
			$this->registry->invoices = Table::load('accounting_purchases', " WHERE Purchasedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Purchasedate");
		}
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
	
		$this->registry->lastdate = null;
		foreach($this->registry->invoices as $index => $invoice) {
				
			$invoice->alvamount = $invoice->grossamount - $invoice->netamount;
			if ($this->registry->lastdate == null) $this->registry->lastdate = $invoice->purchasedate;
			if ($this->registry->lastdate < $invoice->purchasedate) $this->registry->lastdate = $invoice->purchasedate;
		}
		if ($this->registry->lastdate == null) $this->registry->lastdate = date('Y-m-d');
	
		$this->registry->template->show('accounting/purchases','ledger');
	}
	
	
	
	
	public function uploadAction() {
		
		$purchaseID = $_GET['id'];
		$systemID = $_SESSION['systemID'];
		
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
		$receipt = Table::loadRow('accounting_receipts',$purchase->receiptID);
		$year = substr($receipt->receiptdate, 0,4);
		$path = SAVEROOT . "bookkeeping-" . $systemID . "/" . $year . "/receipts/";
		
		//echo "<br>Path - " . $path;
		//echo SAVEROOT;
		require_once(__DIR__ . '/../../utils/fileuploader.php');
		$allowedExtensions = array('jpg','pdf');
		
		$sizeLimit = 10 * 1024 * 1024;
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		
		if ($receipt->files == null) {
			$name = $receipt->receiptdate . "_" . $receipt->receiptnumber . "_01";
		} else {
			$filearray = explode(",",$receipt->files);
			$count = count($filearray)+1;
			if ($count < 10) {
				$name = $receipt->receiptdate . "_" . $receipt->receiptnumber . "_0" . $count;
			} else {
				$name = $receipt->receiptdate . "_" . $receipt->receiptnumber . "_" . $count;
			}
		}
		
		$filestr = "";
		if ($receipt->files == null) {
			$filestr = $name . "." . $uploader->getExtension();
		} else {
			$filestr = $receipt->files . "," . $name . "." . $uploader->getExtension();
		}
		
		$values = array();
		$values['Files'] = $filestr;
		Table::updateRow("accounting_receipts", $values, $purchase->receiptID);
		
		//$randi =  mt_rand(10000000,99999999);
	
		$result = $uploader->handleUpload($path, $name, false);
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	
	public function removeattachmentAction() {
		
		echo "<br>Remove atachment";
		$purchaseID = $_GET['purchaseID'];
		$file = $_GET['file'];
		$systemID = $_SESSION['systemID'];
		
		if ($file == "") {
			echo "<br>Empty file";
			die();
		}
		
		echo "<br>Purchase - " . $purchaseID;
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
		echo "<br>Receipt - " . $purchase->receiptID;
		$receipt = Table::loadRow('accounting_receipts',$purchase->receiptID);
		$year = substr($receipt->receiptdate, 0,4);
		
		echo "<br>Files - " . $receipt->files;
		$files = explode(',', $receipt->files);
		$matchfile = null;
		$filestr = "";
		$first = true;
		foreach($files as $index => $value) {
			if ($value == $file) {
				echo "<br>Yeas match - " . $value;
				$matchfile = $value;
			} else {
				echo "<br>No match - " . $value;
				if ($first = true) {
					 $first = false;
					$filestr = $filestr . $value;
				} else {
					$filestr = $filestr . "," . $value;
				}
			}		
		}
		
		$path = SAVEROOT . "bookkeeping-" . $systemID . "/" . $year . "/receipts/";
		$filename = $path . "/" . $matchfile;
		
		if(file_exists($filename)){
			echo "<br>File: " . $filename;
			$success = unlink($filename);
			if ($success) {
				echo "<br>File unlink success";
				$values = array();
				$values['Files'] = $filestr;
				Table::updateRow("accounting_receipts", $values, $purchase->receiptID);
				redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
				
			} else {
				echo "<br>File unlink failed";
				die();
			}
		} else {
			//echo "<br>File not foud failed - " . $filename;
			$values = array();
			$values['Files'] = $filestr;
			Table::updateRow("accounting_receipts", $values, $purchase->receiptID);
			redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		}
	}
		
	public function showpurchaseAction() {
	
		$comments = false;
		
		$purchaseID = $_GET['id'];
		
		$this->registry->purchasetypes = Collections::getPurchaseTypes();
		$this->registry->paymentstatuses = Collections::getPaymentStatuses();
		$this->registry->purchasestates = Collections::getPurchaseStates();
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		$this->registry->vatreportcodes = Table::load('accounting_vatreportcodes');
		
		$this->registry->costpooltypes = Collections::getCostpoolTypes();
		$this->registry->suppliers = Table::load('accounting_suppliers');			// tätä tarvitaan vasta muokkauksessa, voidaan ladata vasta sitten
		$this->registry->purchase = Table::loadRow('accounting_purchases',$purchaseID);
		$this->registry->purchaserows = Table::load('accounting_purchaserows',"WHERE PurchaseID=" . $purchaseID);
		
		$this->registry->costpools = Table::load('accounting_costpools', "ORDER BY Name");
		$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		// Tähän pitäisi asettaa ehkä mieluummin ostolaskunumero
		updateActionPath("Ostolasku " . $this->registry->purchase->purchaseID);
		$this->registry->dimensions = Table::load('system_dimensions','WHERE Usedinsales=1');
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
		
		
		$this->registry->vats = Table::load('system_vats');
		$this->registry->vatcodes = Table::load('accounting_vatreportcodes');
		foreach($this->registry->vatcodes as $index => $vatcode) {
			$vatcode->fullname = $vatcode->vatcodeID . " " . $vatcode->name;
		}
		//$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		
		//$this->registry->payments = Table::load('accounting_payments',"WHERE Paymentsource=" . Collections::PAYMENTSOURCE_PURCHASES . " AND PaymentsourceID=" . $purchaseID);
		
		
		foreach($this->registry->purchaserows as $index => $row) {
			$row->alvamount = $row->grossamount - $row->netamount;
		}
		
		/*
		if ($this->registry->purchase->state == 0) {
				
			$this->registry->entries = $this->createEntriesFromPurchase($this->registry->purchase, $this->registry->purchaserows, $this->registry->suppliers[$this->registry->purchase->supplierID], $this->registry->costpools, $this->registry->accounts, $this->registry->vats);
			foreach($this->registry->entries as $index => $entry) {
				if ($entry->amount < 0) {
					$entry->debet = 0;
					$entry->credit = -1 * $entry->amount;
				} else {
					$entry->debet = $entry->amount;
					$entry->credit = 0;
				}
			}
			echo "<br>Entries - " . count($this->registry->entries);
		} else {
			
		}
		*/
		
		if ($comments) {
			echo "<br>ReceiptID - " . $this->registry->purchase->receiptID;
		}
		$this->registry->receipt = Table::loadRow("accounting_receipts","WHERE ReceiptID=" . $this->registry->purchase->receiptID, $comments);
			
		if ($this->registry->receipt == null) {
			echo "<br>Receipts not found 22, virhetila. State != 0, receipts pitäisi olla";
			$receiptsetID =  Settings::getSetting('accounting_purchasereceiptsetID', 0);
			$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
			$values = array();
			$values['Receiptdate'] = $this->registry->purchase->purchasedate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Ostolasku " . $this->registry->purchase->purchaseID;		// TODO: teksti resurssiteksteistä?
			$values['PurchaseID'] = $this->registry->purchase->purchaseID;
			$values['SupplierID'] = $this->registry->purchase->supplierID;
			$receiptID = Table::addRow("accounting_receipts", $values, false);
				
			$values = array();
			$values['ReceiptID'] = $receiptID;
			Table::updateRow("accounting_purchases", $values, $this->registry->purchase->purchaseID);
			echo "<br>Receipt created - " . $receiptID;
			
		} else {

			if ($comments) echo "<br>Receipt calcu - " . $this->registry->receipt->receiptID;
				
			$this->registry->purchase->receiptnumber = $this->registry->receipt->receiptnumber;
			$this->registry->purchase->files = $this->registry->receipt->files;
			$this->registry->entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $this->registry->receipt->receiptID, $comments);
			
			if ($comments) echo "<br>entries count - " . count($this->registry->entries);
				
			if (count($this->registry->entries) == 0) {
				if ($this->registry->purchase->state == 0) {
				
					$this->registry->entries = $this->createEntriesFromPurchase($this->registry->purchase, $this->registry->purchaserows, $this->registry->suppliers[$this->registry->purchase->supplierID], $this->registry->costpools, $this->registry->accounts, $this->registry->vats);
					foreach($this->registry->entries as $index => $entry) {
						if ($entry->amount < 0) {
							$entry->debet = 0;
							$entry->credit = -1 * $entry->amount;
						} else {
							$entry->debet = $entry->amount;
							$entry->credit = 0;
						}
					}
					//echo "<br>Entries - " . count($this->registry->entries);
				} else {
					echo "<br>Missing entries...";
				}
			} else {
				foreach($this->registry->entries as $index => $entry) {
					if ($entry->amount < 0) {
						$entry->debet = 0;
						$entry->credit = -1 * $entry->amount;
					} else {
						$entry->debet = $entry->amount;
						$entry->credit = 0;
					}
				}
			}
		}
		//}
		
		
		if (!$comments) $this->registry->template->show('accounting/purchases','purchase');
	}
	

	public function getcostpoolvatJSONAction() {
		
		if (isset($_GET['costpoolID'])) {
			$costpoolID = $_GET['costpoolID'];
			$costpool = Table::loadRow("accounting_costpools", $costpoolID);
				
			echo " {";
			echo "	  \"costpoolID\":\"" . $costpool->costpoolID . "\",";
			echo "	  \"vatID\":\"" . $costpool->vatID. "\"";
			echo " }\n";
			return;
		}
		echo "<br>No costpool";		
	}
	
	
	
	
	public function getcostpoolexpendituresJSONAction() {
	
		if (isset($_GET['costpoolID'])) {
			$costpoolID = $_GET['costpoolID'];
			$costpool = Table::loadRow('accounting_costpools', $costpoolID);
			//echo "<br>Costpooltype - " . $costpool->costpooltype;
			
			$items = array();
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_ASSET) {
				$assets = Table::load('accounting_assets');
				
				// Pitäisi filtteröidä pois ne itemit, joilla on childeja, koska parentit ovat jaotteluun...

				$parents = array();
				foreach($assets as $index => $asset) {
					$parents[$asset->parentID] = $asset->parentID;
				}
				$items = array();
				foreach($assets as $index => $asset) {
					if (!isset($parents[$asset->assetID])) $items[$asset->assetID] = $asset;
				}
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_WORKER) {
				$items = Table::load('hr_workers');
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
				$items = Table::load('crm_clients');
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
				$items = Table::load('accounting_suppliers', "ORDER BY Name");
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
				$items = Table::load('accounting_liabilities');
			}
			
			echo "{";
			echo "	  \"vatID\":\"" . $costpool->vatID. "\",";
			echo "	  \"costpooltype\":\"" . $costpool->costpooltype . "\",";
			echo "	  \"costpoolitems\":";
			echo "[";
			$first = true;
			foreach($items as $index => $item) {
				if ($first == true) $first = false; else echo ",";
				echo " {";
				
				if ($costpool->costpooltype == Collections::COSTPOOLTYPE_ASSET) {
					echo "	  \"itemID\":\"" . $item->assetID . "\",";
					echo "	  \"name\":\"" . $item->name. "\"";
				} 
				if ($costpool->costpooltype == Collections::COSTPOOLTYPE_WORKER) {
					echo "	  \"itemID\":\"" . $item->workerID . "\",";
					echo "	  \"name\":\"" . $item->lastname . " " . $item->firstname . "\"";
				}
				if ($costpool->costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
					echo "	  \"itemID\":\"" . $item->clientID . "\",";
					echo "	  \"name\":\"" . $item->lastname . " " . $item->firstname . "\"";
				}
				if ($costpool->costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
					echo "	  \"itemID\":\"" . $item->supplierID . "\",";
					echo "	  \"name\":\"" . $item->name . "\"";
				}
				if ($costpool->costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
					echo "	  \"itemID\":\"" . $item->liabilityID . "\",";
					echo "	  \"name\":\"" . $item->name . "\"";
				}
				
				//echo "	  \"accountID\":\"" . $costpoolaccount->accountID . "\",";
				
				echo " }\n";
			}
			echo "]";
			echo "}";
	
			return;
		}
	
		/*
			echo "[";
			$first = true;
			foreach($words as $index => $word) {
			if ($first == true) $first = false; else echo ",";
			//echo "<br>" . $concept->conceptID . " - " . $concept->name . " - " . $concept->frequency;
	
			echo " {";
			echo "	  \"wordID\":\"" . $word->wordID . "\",";
			echo "	  \"name\":\"" . $word->lemma . "\",";
			echo "	  \"gloss\":\"" . $word->lemma . "\",";
			if ($word->wordclassID == 0) {
			echo "	  \"wordclassID\":\"0\",";
			echo "	  \"wordclass\":\"No class\",";
			} else {
			$wordclass = $wordclasses[$word->wordclassID];
			echo "	  \"wordclassID\":\"" . $word->wordclassID . "\",";
			echo "	  \"wordclass\":\"" . $wordclass->name . "\",";
			}
			echo "	  \"frequency\":\"0\"";
			echo " }\n";
			}
			echo "]";
			*/
	
		echo "<br>No costpool";
	}
	
	
	

	public function getcostpooltypeitemsJSONAction() {
	
		$costpooltype = $_GET['costpooltype'];
		
		$items = array();
		if ($costpooltype == Collections::COSTPOOLTYPE_ASSET) {
			$assets = Table::load('accounting_assets');
			// Pitäisi filtteröidä pois ne itemit, joilla on childeja, koska parentit ovat jaotteluun...

			$parents = array();
			foreach($assets as $index => $asset) {
				$parents[$asset->parentID] = $asset->parentID;
			}
			$items = array();
			foreach($assets as $index => $asset) {
				if (!isset($parents[$asset->assetID])) $items[$asset->assetID] = $asset;
			}
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_WORKER) {
			$items = Table::load('hr_workers');
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
			$items = Table::load('crm_clients');
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
			$items = Table::load('accounting_suppliers', "ORDER BY Name");
		}
		if ($costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
			$items = Table::load('accounting_liabilities');
		}
		
		echo "{";
		//echo "	  \"vatID\":\"" . $costpool->vatID. "\",";
		echo "	  \"costpooltype\":\"" . $costpooltype . "\",";
		echo "	  \"costpoolitems\":";
		echo "[";
		$first = true;
		foreach($items as $index => $item) {
			if ($first == true) $first = false; else echo ",";
			echo " {";
			if ($costpooltype == Collections::COSTPOOLTYPE_ASSET) {
				echo "	  \"itemID\":\"" . $item->assetID . "\",";
				echo "	  \"name\":\"" . $item->name. "\"";
			}
			if ($costpooltype == Collections::COSTPOOLTYPE_WORKER) {
				echo "	  \"itemID\":\"" . $item->workerID . "\",";
				echo "	  \"name\":\"" . $item->lastname . " " . $item->firstname . "\"";
			}
			if ($costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
				echo "	  \"itemID\":\"" . $item->clientID . "\",";
				echo "	  \"name\":\"" . $item->lastname . " " . $item->firstname . "\"";
			}
			if ($costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
				echo "	  \"itemID\":\"" . $item->supplierID . "\",";
				echo "	  \"name\":\"" . $item->name . "\"";
			}
			if ($costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
				echo "	  \"itemID\":\"" . $item->liabilityID . "\",";
				echo "	  \"name\":\"" . $item->name . "\"";
			}
			echo " }\n";
		}
		echo "]";
		echo "}";

		return;
	}
	
	
	
	
	public function downloadAction() {
		
		$comments = false;
		$systemID = $_SESSION['systemID'];
			
		if (isset($_GET['purchaseID'])) {
			$purchaseID = $_GET['purchaseID'];
			$file = null;
			if (isset($_GET['id'])) {
				$file = $_GET['id'];
			}
		} else {
			$purchaseID = $_GET['id'];
			$file = $_GET['file'];
		}
		
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
		$receipt = Table::loadRow('accounting_receipts',$purchase->receiptID);
		$year = substr($receipt->receiptdate, 0,4);
		$path = SAVEROOT . "bookkeeping-" . $systemID . "/" . $year . "/receipts/";
		//$path = SAVEROOT . "bookkeeping-" . $systemID . "/receipts/";
		if ($comments) {
			echo "<br>path - " . $path;
			echo "<br>file - " . $receipt->files;
			$extension = $this->getExtension($receipt->files);
			echo "<br>Extension - " . $extension;
			
		} else {
			if ($file ==  null) {
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename=" . $file);
				readfile($path . $receipt->files);
			} else {
				$extension = $this->getExtension($receipt->files);
				echo "<br>Extension - " . $extension;
				if ($extension == "jpg") {
					header("Content-type:application/jpg");
					header("Content-Disposition:inline;filename=" . $file);
					readfile($path . $file);
				} else {
					header("Content-type:application/pdf");
					header("Content-Disposition:inline;filename=" . $file);
					readfile($path . $file);
				}
			}
		}
	}
	
	
	

	public function insertpurchaseAction() {
	
		$comments = false;
		
		$supplierID = $_GET['supplierID'];
		$amount = str_replace(",",".",$_GET['amount']);
		$purchasedate = $_GET['purchasedate'];
		$paymentmethodID = $_GET['purchasetype'];
		$duedate = $_GET['duedate'];
		if (isset($_GET['duedate'])) {
			$duedate = $_GET['duedate'];
		} else {
			$duedate = $purchasedate;
		}
		
		if ($comments) {
			echo "<br>Supplier - " . $supplierID;
			echo "<br>Grossamount - " . $amount;
			echo "<br>Purchasedate - " . $purchasedate;
			echo "<br>date - " . dateStrToSql($purchasedate);
			echo "<br>Purchasetype - " . $paymentmethodID;
			echo "<br>duedate - " . $duedate;
			echo "<br>date - " . dateStrToSql($duedate);
		}
		
		$purchasedate = dateStrToSql($purchasedate);
		$duedate = dateStrToSql($duedate);
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
				$success = Table::updateRow("accounting_suppliers", $values, $supplierID, $comments);
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
		$values['SupplierID'] = $supplierID;
		$receiptID = Table::addRow("accounting_receipts", $values, false);
			
		$values = array();
		$values['ReceiptID'] = $receiptID;
		Table::updateRow("accounting_purchases", $values, $purchaseID);
		
		
		if (!$comments) redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID,null);
	}
	
	
	
	private function insertPayment() {
		
	}
	
	
	// Ei ehkä käytetä enää
	public function isOwnCard($cardID) {
		$card = Table::loadRow('accounting_paymentcards',$cardID);
		if ($card->bankaccountID == 0) return true;
		return false;
	}
	

	// TODO: EU yhteisöoston alv-vientien generointi puuttuu, epäselvää miten toteutetaan...
	public function insertpurchaserowAction() {
	
		$comments = true;
	
		$purchaseID = getIntParam('purchaseID');
		$vatID = getIntParam('vatID');
		$costpoolID = getIntParam('costpoolID');
		$costpooltype = getIntParam('costpooltype');
		$targetID = getIntParam('targetID');
		$grossamount = getFloatParam('grossamount');
		$netamount = getFloatParam('netamount');
		$vatamount = getFloatParam('vatamount');
		
		$purchase = Table::loadRow('accounting_purchases', $purchaseID);
		$dimensions = array();
		
		$costpool = Table::loadRow('accounting_costpools',$costpoolID);
		if ($comments) echo "<br>costpoolID - " . $costpoolID;
		
		if ($costpool->expenseaccountID == 0) {
			if ($comments) echo "<br>Costpool - " . $costpool->name . " - kirjanpitotilikiinnitys puuttuu tilikartasta";
			exit;
		}
		
		if ($comments) echo "<br>expenseaccountID - " . $costpool->expenseaccountID;
		$account = Table::loadRow('accounting_accounts',$costpool->expenseaccountID);
		if ($comments) echo "<br>expenseaccount - " . $account->name;
		if ($comments) echo "<br><br>";
		
		$paymentmethod = Table::loadRow('accounting_paymentmethods',$purchase->paymentmethodID);
		if ($comments) echo "<br>paymentmethodID - " . $purchase->paymentmethodID;
		if ($comments) echo "<br>paymentmethod - " . $paymentmethod->name;
		if ($comments) echo "<br>paymentmethod accountID - " . $paymentmethod->accountID;
		
		$vat = Table::loadRow('system_vats',$vatID);
		
		/*
		$vat = null;
		$vatentryID = 0;
		if ($vatID > 0) {
			$vat = Table::loadRow('system_vats',$vatID);
			$this->checkVat($vat, $grossamount, $netamount);
			if ($vat->percent > 0) {
				// TODO: Tässä pitää varmaan tsekata onko maksu vai suoriteperusteinen
				$vataccountID = Settings::getSetting('accounting_vatrecievablesaccountID');
				if ($comments) echo "<br>alv tilin lisäys - " . $vataccountID;
				$vatentryID = $this->createEntry($purchase->receiptID, $vataccountID, $purchase->purchasedate, $vatamount, 7, $dimensions, $purchase);
				$vatpercent = $vat->percent;
				echo "<br>vatpercent - " . $vatpercent;
			}
		}
		
		// Ostovienti - tilinumero napataan valitusta kustannuspaikkasta
		$purchaseentryID = $this->createEntry($purchase->receiptID, $costpool->expenseaccountID,  $purchase->purchasedate, $netamount, 0, $dimensions, $purchase, $costpoolID, $comments);
		
		// Ostovelkavienti - tili haetaan ostotavasta.
		$payablesentryID = $this->createEntry($purchase->receiptID, $paymentmethod->accountID, $purchase->purchasedate, -1 * $grossamount, 0, $dimensions, $purchase, 0, $comments);
		*/
		
		//$purchaserowID = $this->insertPurchaseRow($purchase, $costpool, $targetID, $vat, $netamount, $vatamount, $grossamount, $purchaseentryID, $payablesentryID, $vatentryID, $dimensions);
		$purchaserowID = $this->insertPurchaseRow($purchase, $costpool, $targetID, $vat, $netamount, $vatamount, $grossamount, 0, 0, 0, $dimensions);
				
		$purhcaserows = Table::load('accounting_purchaserows'," WHERE PurchaseID=" . $purchaseID);
		$this->updatepurchasesums($purchaseID, $purhcaserows, $comments);
		
		// updatereceiptiä ei pitäisi tarvita, koska statessa ei ole lainkaan entryjä...
		// $this->updatepurchasereceipt($purchase->receiptID, $comments);
		
		if (!$comments) redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
	}
	
	
	

	// TODO: poisto saisi olla mahdollinen ainoastaan state = 0
	public function removepurchaserowAction() {
	
		$purchaseID = $_GET['purchaseID'];
		$rowID = $_GET['id'];
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
	
		if ($purchase->state != 0) {
			echo "<br>Ainoastaan avoimena olevalta laskulta voidaan poistaa rivejä";
			exit;
		}
	
		$purchaserow = Table::loadRow('accounting_purchaserows',$rowID);
	
		//if ($purchaserow->purchaseentryID > 0) Table::deleteRow('accounting_entries',$purchaserow->purchaseentryID);
		//if ($purchaserow->payablesentryID > 0) Table::deleteRow('accounting_entries',$purchaserow->payablesentryID);
		//if ($purchaserow->vatentryID > 0) Table::deleteRow('accounting_entries',$purchaserow->vatentryID);
	
		$success = Table::deleteRow('accounting_purchaserows',$rowID);
	
		$purhcaserows = Table::load('accounting_purchaserows'," WHERE PurchaseID=" . $purchaseID);
		$this->updatepurchasesums($purchaseID, $purhcaserows);
		$this->updatepurchasereceipt($purchase->receiptID, $comments);
	
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID ,null);
	}
	
	
	

	public function updatepurchaserowAction() {
	
		$comments = false;
		if ($comments) setUrlParamComments(true);
	
		$purchaserowID = getIntParam('id');
		$purchaseID = getIntParam('purchaseID');
		$costpoolID = getIntParam('costpoolID');
		$vatID = getIntParam('vatID');
		$netamount = getFloatParam('netamount');
		$grossamount = getFloatParam('grossamount');
		$vatamount = getFloatParam('vatamount');
		$costpooltype = getIntParam('costpooltype');
		$targetID = getIntParam('targetID');
	
		if ($vatamount === null) {
			if ($comments) echo "<br>Vat nulli - " . $vatamount;
			$vatamount = 0;
		}
		if ($comments) echo "<br>gross - " . $grossamount;
		if ($comments) echo "<br>net - " . $netamount;
		if ($comments) echo "<br>alv - " . $vatamount;
	
	
	
		$costpool = Table::loadRow('accounting_costpools',$costpoolID);
		$dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
		$purchaserow = Table::loadRow('accounting_purchaserows',$purchaserowID);
		$receiptID = $purchase->receiptID;
	
		
		if ($purchase->state != 0) {
			echo "<br>Ainoastaan avoimen laskun rivejä voidaan muokata";
			exit;
		}
		
		
		
		if ($receiptID == 0) {
			echo "<br>ReceiptID puuttuu";
			exit;
		} else {
			if ($comments) echo "<br>ReceiptID ----- " . $receiptID;
		}
	
		$vat = null;
		$vatentryID = $purchaserow->vatentryID;
		$vatpercent = 0;
		$vat = Table::loadRow('system_vats',$vatID);
			
		// Päivitetään alv-vienti, mikäli sellainen on, jos alv on muutettu nollaksi, niin poistetaan vatentryrivi
		/*
		if ($vatID > 0) {
			if ($comments) echo "<br>Vatti ei nolla";
			$vat = Table::loadRow('system_vats',$vatID);
			$this->checkVat($vat, $grossamount, $netamount);
			if ($vat->percent > 0) {
				$vataccountID = Settings::getSetting('accounting_vatrecievablesaccountID');
				if ($comments) echo "<br>alv tilin lisäys - " . $vataccountID;
				if ($vatentryID == 0) {
					if ($comments) echo "<br>alv viennin lisäys - " . $vataccountID;
					$vatentryID = $this->createEntry($purchase->receiptID, $vataccountID, $purchase->purchasedate, $vatamount, 7, $dimensions, $purchase);
				} else {
					if ($comments) echo "<br>alv viennin update - " . $vataccountID;
	
					$values = array();
					$values['ReceiptID'] = $receiptID;
					$values['AccountID'] = $vataccountID;
					$values['Entrydate'] = $purchase->purchasedate;
					$values['Amount'] = $vatamount;
					$values['VatcodeID'] = 7;
					if (count($dimensions) > 0) {
						foreach($dimensions as $index => $dimension) {
							$variable = 'dimension'.+ $dimension->dimensionID;
							if (isset($_GET[$variable])) {
								$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
							} else {
								$values['Dimension'. $dimension->dimensionID] = $purchaserow->$variable;
							}
						}
					}
					Table::updateRow("accounting_entries", $values, $vatentryID);
				}
				$vatpercent = $vat->percent;
			} else {
				if ($comments) echo "<br>Vatti on nolla";
	
				if ($purchaserow->vatentryID > 0) {
					if ($comments) echo "<br>poistetaan aiemp vat rivi - " . $purchaserow->vatentryID;
					Table::deleteRow('accounting_entries', $purchaserow->vatentryID);
					$vatentryID = 0;
				}
			}
		}
	
	
		// Päivitetään ostovienti
		$values = array();
		$values['ReceiptID'] = $purchase->receiptID;
		$values['AccountID'] = $costpool->expenseaccountID;
		$values['Entrydate'] = $purchase->purchasedate;
		$values['Amount'] = $netamount;
		$values['VatcodeID'] = 0;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension'.+ $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $purchaserow->$variable;
				}
			}
		}
		if ($comments) echo "<br>accounting_entries update - " . $purchaserow->purchaseentryID;
		if (($purchaserow->purchaseentryID == NULL) || ($purchaserow->purchaseentryID == 0)) {
			if ($comments) echo "<br>PurhaseentryID ei ole aiemmin ollut";
			$purchaseentryID = $this->createEntry($purchase->receiptID, $costpool->expenseaccountID,  $purchase->purchasedate, $netamount, 0, $dimensions, $purchase, $comments);
			$purchaserow->purchaseentryID = $purchaseentryID;
		} else {
			Table::updateRow("accounting_entries", $values, $purchaserow->purchaseentryID);
		}
	
	
	
		// Ostovelkavienti - tili haetaan yleis-asetuksista
		$accountID = 0;
		if ($costpool->deptaccountID > 0) {
			$accountID = $costpool->deptaccountID;
			//echo "<br>Deptaccount";
		} else {
			// TODO: Pitänee ottaa maksutavasta...
			if ($purchase->paymentmethodID > 0) {
				$paymentmethodID = $purchase->paymentmethodID;
				$paymentmethod = Table::loadRow('accounting_paymentmethods',$paymentmethodID);
				$accountID = $paymentmethod->accountID;
			} else {
				$payablesaccountID = Settings::getSetting('accounting_payablesaccountID');
				$accountID = $payablesaccountID;
			}
	
			//echo "<br>general payablesaccountID";
		}
		$values = array();
		$values['ReceiptID'] = $purchase->receiptID;
		$values['AccountID'] = $accountID;
		$values['Entrydate'] = $purchase->purchasedate;
		$values['Amount'] = -1 * $grossamount;
		$values['VatcodeID'] = 0;
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension'.+ $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $purchaserow->$variable;
				}
			}
		}
		if ($comments) echo "<br>accounting_entries payableentryID - " . $purchaserow->payablesentryID;
		if (($purchaserow->payablesentryID == NULL) || ($purchaserow->payablesentryID == 0)) {
			$payableentryID = $this->createEntry($purchase->receiptID, $accountID, $purchase->purchasedate, -1 * $grossamount, 0, $dimensions, $purchase);
			$purchaserow->payablesentryID = $payableentryID;
		} else {
			$success = Table::updateRow("accounting_entries", $values, $purchaserow->payablesentryID);
		}
		*/	
	
	
		// päivitetään itse purchaseinvoicerow
		$values = array();
		$values['CostpoolID'] = $costpool->costpoolID;
		$costpoolname = $costpool->name;
		if ($comments) echo "<br>costpooltype - " . $costpool->costpooltype;
		if ($comments) echo "<br>Costpoolname - " . $costpoolname;
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_ASSET) {
			$asset = Table::loadRow("accounting_assets", $targetID);
			$costpoolname = $costpoolname . ", " . $asset->name;
			$values['AssetID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_WORKER) {
			$worker = Table::loadRow("hr_workers", $targetID);
			$costpoolname = $costpoolname . ", " . $worker->lastname . " " . $worker->firstname;
			$values['WorkerID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
			$client = Table::loadRow("crm_clients", $targetID);
			$costpoolname = $costpoolname . ", " . $client->lastname . " " . $client->firstname;
			$values['ClientID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
			$supplier = Table::loadRow("accounting_suppliers", $targetID);
			$costpoolname = $costpoolname . ", " . $supplier->name;
			$values['SupplierID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
			$liability = Table::loadRow("accounting_liabilities", $targetID);
			$costpoolname = $costpoolname . ", " . $liability->name;
			$values['LiabilityID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		$values['Costpoolname'] = $costpoolname;
		$values['Costpooltype'] = $costpool->costpooltype;
	
		if ($vat == null) {
			$values['VatID'] = 0;
			$values['Vatpercent'] = 0;
		} else {
			$values['VatID'] = $vat->vatID;
			$values['Vatpercent'] = $vat->vatpercent;
		}
		$values['Netamount'] = $netamount;
		$values['Vatamount'] = $vatamount;
		$values['Grossamount'] = $grossamount;
		$values['AccountID'] = $costpool->expenseaccountID;
		$values['PurchaseentryID'] = $purchaserow->purchaseentryID;
		$values['PayablesentryID'] = $purchaserow->payablesentryID;
		$values['VatentryID'] = $vatentryID;
	
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $purchaserow->$variable;
				}
			}
		}
		if ($comments) echo "<br>aa gross - " . $grossamount;
		if ($comments) echo "<br>aanet - " . $netamount;
		if ($comments) {
			if ($vatamount == null) echo "<br>Vatamount on nulli";
		}
		if ($comments) echo "<br>aa alv - " . $vatamount;
		if ($comments) echo "<br>Päivitetään purchaserow - " . $purchaserowID;
		$success = Table::updateRow("accounting_purchaserows", $values, $purchaserowID);
	
		
		$purhcaserows = Table::load('accounting_purchaserows'," WHERE PurchaseID=" . $purchaseID);
		$this->updatepurchasesums($purchaseID, $purhcaserows);
		
		// Tätä ei mielestäni tarvita, koska entryjä ei ole
		//$this->updatepurchasereceipt($purchase->receiptID, $comments);
	
		if (!$comments) redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
	}
	
	
	
	
	

	private function insertPurchaseRow($purchase, $costpool, $targetID, $vat, $netamount, $vatamount, $grossamount, $purchaseentryID, $receivablesentryID, $vatentryID, $dimensions) {
		
		$values = array();
		$values['PurchaseID'] = $purchase->purchaseID;
		$values['CostpoolID'] = $costpool->costpoolID;
		
		if ($vat == null) {
			$values['VatID'] = 0;
			$values['Vatpercent'] = 0;
		} else {
			$values['VatID'] = $vat->vatID;
			$values['Vatpercent'] = $vat->vatpercent;
		}
		$values['Purchasedate'] = $purchase->purchasedate;
		$values['Netamount'] = $netamount;
		$values['Vatamount'] = $vatamount;
		$values['Grossamount'] = $grossamount;
		$values['AccountID'] = $costpool->expenseaccountID;
		$values['PurchaseentryID'] = $purchaseentryID;
		$values['PayablesentryID'] = $receivablesentryID;
		$values['VatentryID'] = $vatentryID;
		
		
		$costpoolname = $costpool->name;
		echo "<br>costpooltype - " . $costpool->costpooltype;
		echo "<br>Costpoolname - " . $costpoolname;
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_ASSET) {
			$asset = Table::loadRow("accounting_assets", $targetID);
			$costpoolname = $costpoolname . ", " . $asset->name;
			$values['AssetID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_WORKER) {
			$worker = Table::loadRow("hr_workers", $targetID);
			$costpoolname = $costpoolname . ", " . $worker->lastname . " " . $worker->firstname;
			$values['WorkerID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
			$client = Table::loadRow("crm_clients", $targetID);
			$costpoolname = $costpoolname . ", " . $client->lastname . " " . $client->firstname;
			$values['ClientID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
			$supplier = Table::loadRow("accounting_suppliers", $targetID);
			$costpoolname = $costpoolname . ", " . $supplier->name;
			$values['SupplierID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		if ($costpool->costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
			$liability = Table::loadRow("accounting_liabilities", $targetID);
			$costpoolname = $costpoolname . ", " . $liability->name;
			$values['LiabilityID'] = $targetID;
			$values['TargetID'] = $targetID;
		}
		$values['Costpoolname'] = $costpoolname;
		$values['Costpooltype'] = $costpool->costpooltype;
		
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension' . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
				} else {
					$values['Dimension'. $dimension->dimensionID] = $purchase->$variable;
				}
			}
		}
		$purchaserowID = Table::addRow("accounting_purchaserows", $values, false);
		return $purchaserowID;
	}
	
	
	
	private function checkVat($vat, $grossamount, $netAmount) {
		if ($vat != null) {
			if ($vat->percent > 0) {
				if ($grossamount == $netAmount) {
					echo "<br>Vat percent suurempi kuin nolla: rowGrossAmount == rowNetaAount .... " . $grossamount . " vs. " . $netAmount;
					exit;
				} 	
			} else {
				if ($grossamount != $netAmount) {
					echo "<br>Vatcode nolla: rowGrossAmount != rowNetaAount .... " . $grossamount . " vs. " . $netAmount;
					exit;
				} 	
			}
		} else {
			if ($grossamount != $netAmount) {
				echo "<br>Vatcode nolla: rowGrossAmount != rowNetaAount .... " . $grossamount . " vs. " . $netAmount;
				exit;
			}
		}
	}
	
	
	private function createEntry($receiptID, $accountID, $entrydate, $amount, $vatcode, $dimensions, $entity, $costpoolID = 0, $comments = false) {

		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $accountID;
		$values['Entrydate'] = $entrydate;
		$values['Amount'] = $amount;
		$values['VatcodeID'] = $vatcode;
		$values['CostpoolID'] = $costpoolID;
		
		/*
		if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
				$variable = 'dimension'.+ $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
						
				} else {
					$values['Dimension'. $dimension->dimensionID] = $entity->$variable;
				}
			}
		}
		*/
		
		$entryID = Table::addRow("accounting_entries", $values, false);
			
		return $entryID;
	}
	
	

	private function createEntryToDatabase($entry, $receiptID, $comments = false) {
	
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['Entrydate'] = $entry->entrydate;
		$values['AccountID'] = $entry->accountID;
		$values['AccounttypeID'] = $entry->accounttypeID;
		$values['PurchaseID'] = $entry->purchaseID;
		$values['Amount'] = $entry->amount;
		$values['VatcodeID'] = $entry->vatcodeID;
		$values['CostpoolID'] = $entry->costpoolID;
		$values['Costpooltype'] = $entry->costpooltype;
		$values['SupplierID'] = $entry->supplierID;
		$values['WorkerID'] = $entry->workerID;
		$values['AssetID'] = $entry->assetID;
		$values['ClientID'] = $entry->clientID;
		$values['LiabilityID'] = $entry->liabilityID;
		$values['TargetID'] = $entry->targetID;
		
		// TODO: Dimensions puuttuu
		/*
			if (count($dimensions) > 0) {
			foreach($dimensions as $index => $dimension) {
			$variable = 'dimension'.+ $dimension->dimensionID;
			if (isset($_GET[$variable])) {
			$values['Dimension'. $dimension->dimensionID] = $_GET[$variable];
	
			} else {
			$values['Dimension'. $dimension->dimensionID] = $entity->$variable;
			}
			}
			}
			*/
	
		$entryID = Table::addRow("accounting_entries", $values, false);
			
		return $entryID;
	}
	

	public function insertentryAction() {
	
		$comments = false;
	
		$purchaseID = $_GET['purchaseID'];
	
		$receiptID = $_GET['receiptID'];
		$accountID = $_GET['accountID'];
		$vatcodeID = $_GET['vatcodeID'];
	
		$grossamount = floatval(str_replace(",",".",$_GET['amount']));
	
		$account = Table::loadRow('accounting_accounts',$accountID);
	
		if (($account->accounttypeID == 2) || ($account->accounttypeID == 4)) {
			$grossamount = $grossamount * -1;
		}
	
		if ($comments) echo "<br><br>Tehdään kulutilivienti";
		$values = array();
		$values['ReceiptID'] = $receiptID;
		$values['AccountID'] = $accountID;
		$values['VatcodeID'] = $vatcodeID;
		if (isset($_GET['entrydate'])) {
			$values['Entrydate'] = $_GET['entrydate'];
		} else {
			$receipt = Table::loadRow('accounting_receipts',$receiptID);
			$values['Entrydate'] = $receipt->receiptdate;
		}
		$values['Amount'] = $grossamount;
		if ($comments) echo "<br>";
		if ($comments) var_dump($values);
	
		$success = Table::addRow("accounting_entries", $values, $comments);
		if (!$comments) redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
	}
	
	
	
	
	
	// Kopioitu Receipts->getNextReceiptNumber, pitäisi ehkä siirtää johonkin yleiseen paikkaan, accounting?
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
	
	

	
	private function deducePayablesaccountID($purchase) {
		
	}
	
	
	private function deducevatcode($supplier) {
		
		$vatcodeID = 0;
		if ($country->countrytype == 3) {		// EU ulkopuolinen osto
			if ($comments) echo "<br>Osto EU ulkopuolelta";
		
			if ($invoicerow->productorservice == 'P') {
				$vatcodeID = 19;			// TUU - Vero tavaroiden maahantuonnista EU:n ulkopuolelta, tämä lasketaan mukaan
			}
		
		} if ($country->countrytype == 2) {		// EU osto
			if ($comments) echo "<br>Myynti EU:n sisälle";
			if ($costpool->service == 1) {
				$vatcodeID = 11; // MPEU;
			} else {
				$vatcodeID = 10;  //"MTEU";
			}
		} elseif ($country->countrytype == 1) {	// kotimaan myynti
		
			if ($comments) echo "<br>Kotimaan osto";
		
			$vat = $vats[$invoicerow->vatID];
			if ($vat->percent > 0) {
				$entry->vatcodeID = 7;
			} else {
				$vatcodeID = 0;
			}
		} else {
			if ($comments) echo "<br>Unknown countrycode";
		}
		
	}
	
	
	
	private function createEntriesFromPurchase($purchase, $purchaserows, $supplier, $costpools, $accounts, $vats) {
	
		$comments = false;
		
		//echo "<br>createEntriesFromPurchase";
		
		$vatpayableaccountID = Settings::getSetting('accounting_vatrecievablesaccountID');
		$payableaccountID = Settings::getSetting('accounting_payablesaccountID');
		$vatcodes = Table::load('accounting_vatreportcodes');
		
		if ($purchase->payableaccountID > 0) {
			$payableaccountID = $purchase->payableaccountID;
		}
		
		
		$supplierID = 0;
		$workerID = 0;
		$assetID = 0;
		$clientID = 0;
		$liabilityID = 0;
		$costpooltype = 0;
		$targetID = 0;
		
		
		
		
		/*
		echo "<br>Jaa-1";
		$paymentmethodID = $purchase->paymentmethodID;
		echo "<br>Jaa- -- " . $paymentmethodID;
		$paymentmethod = Table::loadRow('accounting_paymentmethods',$paymentmethodID);
		echo "<br>Jaa-2";
		
		if ($paymentmethod->accountID != 0) {
			$payableaccountID = $paymentmethod->accountID;
		}
		*/
		
		$country = Table::loadRow('system_countries',$supplier->countryID);
		//echo "<br>Jaa-3";
		
		$entries = array();
		
		foreach($purchaserows as $index => $purchaserow) {
			
			$supplierID = 0;
			$workerID = 0;
			$assetID = 0;
			$clientID = 0;
			$liabilityID = 0;
			$costpool = $costpools[$purchaserow->costpoolID];
			$costpooltype = $costpool->costpooltype;
			$targetID = 0;
			
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_ASSET) {
				$assetID = $purchaserow->assetID;
				$targetID = $purchaserow->assetID;
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_WORKER) {
				$workerID = $purchaserow->workerID;
				$targetID = $purchaserow->workerID;
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_CLIENT) {
				$clientID = $purchaserow->clientID;
				$targetID = $purchaserow->clientID;
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_SUPPLIER) {
				$supplierID = $purchaserow->supplierID;
				$targetID = $purchaserow->supplierID;
			}
			if ($costpool->costpooltype == Collections::COSTPOOLTYPE_LIABILITY) {
				$liabilityID = $purchaserow->liabilityID;
				$targetID = $purchaserow->liabilityID;
			}
			
			
			
			if ($comments) echo "<br>Invoicerow - " . $purchaserow->rowID;
			if ($comments) echo "<br>Invoicerow - " . $purchaserow->costpoolID;
				
			if ($comments) echo "<br>Invoice - vatID=" . $purchaserow->vatID . ", vatpercent=" . $purchaserow->vatpercent . ", netamount:" . $purchaserow->netamount . ", gross:" . $purchaserow->grossamount . "";
			if ($comments) echo "<br>Country - countryID=" . $supplier->countryID . ", name=" . $country->name . ", countrytype:" . $country->countrytype;
	
			$vatcodeID = 0;
			$alvvatcodeID = '000';
			
			$vat = $vats[$purchaserow->vatID];
			$costvatcodeID = $vat->costvatcodeID;
			
			if ($comments) echo "<br>Vatcode > 0";
			if ($vat->percent > 0) {
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $purchase->purchasedate;
				$entry->accountID = $vatpayableaccountID;
				$account = $accounts[$entry->accountID];
				$entry->accounttypeID = $account->accounttypeID;
				$entry->amount = ($purchaserow->grossamount - $purchaserow->netamount);
				$entry->purchaseID = $purchase->purchaseID;
				$entry->supplierID = 0;
				$entry->workerID = 0;
				$entry->assetID = 0;
				$entry->clientID = 0;
				$entry->liabilityID = 0;
				$entry->costpoolID = 0;
				$entry->costpooltype = 0;
				$entry->targetID = 0;
				$entry->vatcodeID = 7;
				$entries[] = $entry;
			}
			if ($vat->addpercent > 0) {
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $purchase->purchasedate;
				$entry->accountID = $vatpayableaccountID;
				$account = $accounts[$entry->accountID];
				$entry->accounttypeID = $account->accounttypeID;
				$entry->amount = $purchaserow->grossamount * ($vat->addpercent / 100);
				$entry->purchaseID = $purchase->purchaseID;
				$entry->supplierID = 0;
				$entry->workerID = 0;
				$entry->assetID = 0;
				$entry->clientID = 0;
				$entry->liabilityID = 0;
				$entry->costpoolID = 0;
				$entry->costpooltype = 0;
				$entry->targetID = 0;
				$entry->vatcodeID = 7;
				$entries[] = $entry;
				
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $purchase->purchasedate;
				$entry->accountID = $vatpayableaccountID;
				$account = $accounts[$entry->accountID];
				$entry->accounttypeID = $account->accounttypeID;
				$entry->amount =  - $purchaserow->grossamount * ($vat->addpercent / 100);
				$entry->purchaseID = $purchase->purchaseID;
				$entry->supplierID = 0;
				$entry->workerID = 0;
				$entry->assetID = 0;
				$entry->clientID = 0;
				$entry->liabilityID = 0;
				$entry->costpoolID = 0;
				$entry->costpooltype = 0;
				$entry->targetID = 0;
				$entry->vatcodeID = $vat->vatcodeID;
				$entries[] = $entry;
			}			
			
			
			/*
			if ($country->countrytype == 3) {		// EU ulkopuolinen osto
				if ($comments) echo "<br>Osto EU ulkopuolelta";
				
				if ($purchaserow->productorservice == 'P') {
					$vatcodeID = 19;			// TUU - Vero tavaroiden maahantuonnista EU:n ulkopuolelta, tämä lasketaan mukaan
				}
	
			} if ($country->countrytype == 2) {		// EU osto
				if ($comments) echo "<br>Myynti EU:n sisälle";
				if ($costpool->service == 1) {
					$vatcodeID = 11; // MPEU;
				} else {
					$vatcodeID = 10;  //"MTEU";
				}
			} elseif ($country->countrytype == 1) {	// kotimaan myynti
	
				if ($comments) echo "<br>Kotimaan osto";
				
				$vat = $vats[$purchaserow->vatID];
				if ($vat->percent > 0) {
					$entry = new Row();
					$entry->entryID = 0;
					$entry->entrydate = $purchase->purchasedate;
					$entry->accountID = $vatpayableaccountID;
					$account = $accounts[$entry->accountID];
					$entry->accounttypeID = $account->accounttypeID;
					$entry->amount = ($purchaserow->grossamount - $purchaserow->netamount);
					$entry->purchaseID = $purchase->purchaseID;
					$entry->supplierID = 0;
					$entry->workerID = 0;
					$entry->assetID = 0;
					$entry->clientID = 0;
					$entry->liabilityID = 0;
					$entry->costpoolID = 0;
					$entry->costpooltype = 0;
					$entry->targetID = 0;
					$entry->vatcodeID = 7;
					$entries[] = $entry;
				} else {
					$vatcodeID = 0;
				}
			} else {
				if ($comments) echo "<br>Unknown countrycode";
			}
			*/
			
			// Luodaan kuluvienti (tai omaisuusvienti)
			$entry = new Row();
			$entry->entryID = 0;
			$entry->entrydate = $purchase->purchasedate;
			$entry->accountID = $costpool->expenseaccountID;
			$account = $accounts[$entry->accountID];
			$entry->accounttypeID = $account->accounttypeID;
			$entry->purchaseID = $purchase->purchaseID;
			$entry->amount = $purchaserow->netamount;
			$entry->vatcodeID = $costvatcodeID;
			$entry->supplierID = $supplierID;
			$entry->workerID = $workerID;
			$entry->assetID = $assetID;
			$entry->clientID = $clientID;
			$entry->liabilityID = $liabilityID;
			$entry->costpoolID = $costpool->costpoolID;
			$entry->costpooltype = $costpooltype;
			$entry->targetID = $targetID;
			$entries[] = $entry;
			if ($comments) echo "<br>Creating expenseaccount entry";
			
			// luodaan velkavienti, yleensä ostovelat, mutta saattaa olla muukin, velat työntekijöille ainakin
			$entry = new Row();
			$entry->entryID = 0;
			$entry->entrydate = $purchase->purchasedate;
			$entry->accountID = $payableaccountID;
			$account = $accounts[$entry->accountID];
			$entry->accounttypeID = $account->accounttypeID;
			$entry->purchaseID = $purchase->purchaseID;
			$entry->amount = -1 * $purchaserow->grossamount;
			$entry->vatcodeID = 0;
			$entry->supplierID = $purchase->supplierID;
			$entry->workerID = 0;
			$entry->assetID = 0;
			$entry->clientID = 0;
			$entry->liabilityID = 0;
			$entry->costpoolID = 0;
			$entry->costpooltype = 0;
			$entry->targetID = 0;
			$entries[] = $entry;
			if ($comments) echo "<br>Creating purchase entry";
				
		}
	
	
		// Ainakin myyntisaamiset tilin rivi pitää yhdistää...
		$summedentries = array();
		foreach($entries as $index => $entry) {
			$key = $entry->accountID . "-" . $entry->vatcodeID;
			if (isset($summedentries[$key])) {
				$sumentry = $summedentries[$key];
				$sumentry->amount = $sumentry->amount + $entry->amount;
			} else {
				$summedentries[$key] = $entry;
			}
		}
		return $summedentries;
		
		return $entries;
	}
	


	

	

	/**
	 * TODO: Päivämäärää ei saisi muuttaa jälkikäteen, ainakin jos alvit on jo ilmoitettu.
	 *
	 * Status ei muutu vaikka päivää siirretään...
	 *
	 */
	public function updatepurchaseAction() {
	
		$comments = false;
		$values = array();
		
		$purchaseID = $_GET['id'];
		$purchase = Table::loadRow('accounting_purchases', $purchaseID, $comments);
		$purchasedate = $_GET['purchasedate'];
		 
		if (isset($_GET['paymentmethodID'])) {
			
			$paymentmethodID = $_GET['paymentmethodID'];
			
			if ($purchase->paymentmethodID != $paymentmethodID) {
				
				$paymentmethod = Table::loadRow("accounting_paymentmethods", $paymentmethodID);
				$values['PaymentmethodID'] = $_GET['paymentmethodID'];
				$values['PayableaccountID'] = $paymentmethod->accountID;
				$values['PaymentmethodID'] = $_GET['paymentmethodID'];
				$purchase->payableaccountID = $paymentmethod->accountID;
				$purchase->paymentmethodID = $_GET['paymentmethodID'];

				// Luodaan entriessit uudelleen ainoastaan, jos state on jokin muu kuin avoin. 
				// En nyt muista miksi entriessit jossainvaiheessa muutettiin niin, että entryjä luoaan
				// samalla kun rivejä lisätään...
				if ($purchase->state > 0) {
					$this->reCreateEntries($purchase);
				}
			} else {
				if (isset($_GET['payableaccountID'])) {
					$values['PayableaccountID'] = $_GET['payableaccountID'];
				}
			}
		}
		
		
		$grossamount = str_replace(',','.',$_GET['grossamount']);
		if ($comments) echo "<br>grossamount - " . $grossamount;
		$values['Purchasedate'] = $_GET['purchasedate'];
		//$values['Duedate'] = $_GET['duedate'];
		$values['SupplierID'] = $_GET['supplierID'];
		$values['Referencenumber'] = $_GET['referencenumber'];
		$values['Grossamount'] = $grossamount;
		$values['Netamount'] = $grossamount;
	
		$duedate = null;
		if (isset($_GET['duedate'])) {
			$duedate = $_GET['duedate'];
			$values['Duedate'] = $duedate;
		}
		
		
		
		//$values['Purchasetype'] = $_GET['purchasetype'];
		if (isset($_GET['note'])) {
			$values['Note'] = $_GET['note'];
		}
		if (isset($_GET['paymenttype'])) {
			$values['Paymenttype'] = $_GET['paymenttype'];
		}
		
		/*
		if (isset($_GET['payableaccountID'])) {
			$values['PayableaccountID'] = $_GET['payableaccountID'];
		}
		*/
		
		
		$this->registry->dimensions = Table::load('system_dimensions','WHERE Usedinsales=1');
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$variable = "dimension" . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					$columname = "Dimension" . $dimension->dimensionID;
		
					if (isset($_GET[$variable])) {
						if ($purchase->$variable != $_GET[$variable]) {
							if ($comments) echo "<br>Dimensio muuttunut, päivitetään kaikkiin riveihin ja entryihin";
							$this->updatePurchaseDimension($purchase, $dimension, $_GET[$variable]);
							$values[$columname] = $_GET[$variable];
						}
					} else {
						if ($comments) echo "<br>Dimensiota ei tullut parametrina";
					}
				}
			}
		}
		$success = Table::updateRow("accounting_purchases", $values, $purchaseID, false);
		
		// Päivitetään receiptin supplierID tarvittaessa
		if (isset($_GET['supplierID'])) {
			if ($purchase->receiptID > 0) {
				$values = array();
				$values['SupplierID'] = $_GET['supplierID'];
				$success = Table::updateRow("accounting_receipts", $values, $purchase->receiptID);
			}
		}
		
		
		if ($purchase->grossamount != $grossamount) {
			
			/*
			echo "<br>Grossi muutettu - " . $purchase->grossamount . " != " . $grossamount;
			$values = array();
			$values['Grossamount'] = $grossamount;
			$success = Table::updateRowsWhere("accounting_payments", $values, "WHERE Paymentsource=" . Collections::PAYMENTSOURCE_PURCHASES . " AND PaymentsourceID=" . $purchaseID . " AND PaymentStatus=" . Collections::PAYMENTSTATUS_OPEN, true);
			*/
		} else {
			echo "<br>Grossamount not changed";
		}
		
		if (($purchasedate != $purchase->purchasedate) || ($duedate != $purchase->duedate)) {
			if ($comments) echo "<br>Päivämäärä muuttunut, päivitetään kaikki - " . $purchasedate . " vs. " . $purchase->purchasedate;
			$this->updatePurchaseDate($purchase, $purchasedate, $duedate, $comments);
		}
		
		if (!$comments) redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
	}
	
	
	
	private function updatePurchaseDate($purchase, $purchasedate, $duedate, $comments = false) {
	
		if ($comments) echo "<br>Purhcasedate update - " . $purchasedate . " - " . $purchase->purchaseID;
		// Päivitetään
		$values = array();
		$values['Purchasedate'] = $purchasedate;
		$success = Table::updateRowsWhere("accounting_purchaserows", $values, " WHERE PurchaseID=" . $purchase->purchaseID, $comments);
	
		if ($purchase->receiptID > 0) {
				
			if ($comments) echo "<br>receiptID update - " . $purchase->receiptID;
			
			$values = array();
			$values['Receiptdate'] = $purchasedate;
			$success = Table::updateRow("accounting_receipts", $values, $purchase->receiptID,$comments);
				
			$values = array();
			$values['Entrydate'] = $purchasedate;
			$success = Table::updateRowsWhere("accounting_entries", $values, "WHERE ReceiptID=" .  $purchase->receiptID, $comments);
			
			$values = array();
			$values['Creationdate'] = $purchasedate;
			if ($duedate != null) {
				$values['Duedate'] = $duedate;
			}	
			
			if ($duedate != null) {
				if ($purchase->duedate != $duedate) {
					$values['Duedate'] = $duedate;
				}
			}
			if ($comments) echo "<br>duedate - " . $duedate;
			if ($comments) echo "<br>duedate - " . $values['Duedate'];
			//$success = Table::updateRowsWhere("accounting_payments", $values, "WHERE Paymentsource=" . Collections::PAYMENTSOURCE_PURCHASES . " AND PaymentsourceID=" .  $purchase->purchaseID, $comments);
		}
	}
	
	
	
	
	private function updatePurchaseDimension($purchase, $dimension, $dimensionvalueID) {
	
		$variable = 'dimension' . $dimension->dimensionID;
		$columname = 'Dimension' . $dimension->dimensionID;
		$values = array();
		$values[$columname] = $dimensionvalueID;
		$success = Table::updateRowsWhere("accounting_purchaserows", $values, " WHERE PurchaseID=" . $purchase->purchaseID);
	
		if ($purchase->receiptID > 0) {
			$values = array();
			$values[$columname] = $dimensionvalueID;
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" .  $purchase->receiptID);
		}
	}
	
	

	
	public function updateentryAction() {
	
		$purchaseID = $_GET['purchaseID'];
		$receiptID = $_GET['receiptID'];
		$entryID = $_GET['entryID'];
		$values = array();
		$values['Entrydate'] = $_GET['entrydate'];
		$values['AccountID'] = $_GET['accountID'];
		$values['VatcodeID'] = $_GET['vatcodeID'];
		$values['CostpoolID'] = $_GET['costpoolID'];
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
	
		$amount = 0;
		if ($debet > $credit) $amount = $debet;
		else $amount = -1 * $credit;
	
		$values['Amount'] = $amount;
		
		$success = Table::updateRow('accounting_entries', $values, $entryID, true);
		//$this->updateReceiptAccounted($receiptID, $comments);
	
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID,null);
	}
	
	
	


	// Tämä poisto on hieman kyseenalainen, saattaa mennä jotain epäsynkkaan kannassa.
	//  -- ei ehkä saisi poistaa ihan kaikissa tapauksissa, ei ainakaan silloin kun maksu on suoritettu
	// TODO: liitetiedostoa ei tällähetkellä poistata, pitää poistaa jos ei esiinny kopioiduissa laskuissa
	
	public function removepurchaseAction() {
	
		$comments = false;
		
		$purchaseID = $_GET['purchaseID'];
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
		
		Table::deleteRowsWhere("accounting_purchaserows", " WHERE PurchaseID=" . $purchaseID, $comments);
		if ($purchase->receiptID > 0) {
			Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $purchase->receiptID, $comments);
		} else {
			if ($comments) echo "<br>No receipts";
		}
		//Table::deleteRowsWhere("accounting_payments", " WHERE Paymentsource=". Collections::PAYMENTSOURCE_PURCHASES . " AND PaymentsourceID=" . $purchase->purchaseID, $comments);
		
		$success = Table::deleteRow('accounting_receipts',$purchase->receiptID, $comments);
		$success = Table::deleteRow('accounting_purchases',$purchaseID, $comments);
		
		if (!$comments) redirecttotal('accounting/purchases/showpurchases',null);
	}
	
	
	/*
	public function removepaymentAction() {
		$purchaseID = $_GET['purchaseID'];
		$paymentID = $_GET['id'];
		$success = Table::deleteRow('accounting_payments',$paymentID);
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID ,null);
	}
	*/
	
	
	

	public function removeentryAction() {
	
		$comments = false;
		
		$purchaseID = $_GET['purchaseID'];
		$entryID = $_GET['id'];
	
		if ($comments) echo "<br>EntryID - " . $entryID;
		if ($comments) echo "<br>purchaseID - " . $purchaseID;
		
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
	
		$success = Table::deleteRow('accounting_entries',$entryID);
		
		
		$purhcaserows = Table::load('accounting_purchaserows'," WHERE PurchaseID=" . $purchaseID);
		foreach($purhcaserows as $index => $purchaserow) {
			
			$values = array();
			if ($purchaserow->purchaseentryID == $entryID) {
				if ($comments) echo "<br>changed purchaseentryID";
				$values['PurchaseentryID'] = 0;
			}
			if ($purchaserow->payablesentryID == $entryID) {
				if ($comments) echo "<br>changed payablesentryID ";
				$values['PayablesentryID'] = 0;
			}
			if ($purchaserow->vatentryID == $entryID) {
				if ($comments) echo "<br>changed vatentryID ";
				$values['VatentryID'] = 0;
			}
			if (count($values) > 0) {
				if ($comments) echo "<br>updating values - " .  $purchaserow->rowID;
				Table::updateRow("accounting_purchaserows", $values, $purchaserow->rowID);
			}
		}
		
		
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID ,null);
	}
	
	
	
	private function updatepurchasesums($purchaseID, $purhcaserows, $comments = false) {
			
		$grossamount = 0;
		$netamount = 0;
		$vatamount = 0;
		foreach($purhcaserows as $index => $purchaserow) {
			$grossamount = $grossamount + $purchaserow->grossamount;
			$netamount = $netamount + $purchaserow->netamount;
			$vatamount = $vatamount + $purchaserow->vatamount;
		}
		$values = array();
		//$values['Grossamount'] = $grossamount;
		$values['Netamount'] = $netamount;
		$values['Vatamount'] = $vatamount;
		$success = Table::updateRow("accounting_purchases", $values, $purchaseID, $comments);
		
		
		// Jos grossamountti täsmää, niin päivitetään myös maksutiedot / maksutila
		// Jos on odotaa siirtoa velkoihin, odottaa käteismaksua
		
		
		// TODO: Päivitetään receiptin debet ja credit, näiden pitäisi aina täsmätä kyllä?
		
		
	}

	
	private function updatepurchasereceipt($receiptID, $comments = false) {

		//$comments = true;
		//echo "<br>REceiptID - " . $receiptID;
		$entries = Table::load("accounting_entries", "WHERE ReceiptID=" . $receiptID);
		
		$debet = 0;
		$credit = 0;
		foreach($entries as $index => $entry) {
			if ($entry->amount > 0) {
				$debet = $debet + $entry->amount;
			} else {
				$credit = $credit + $entry->amount;
			}
		}
		$values = array();
		$values['Debet'] = $debet;
		$values['Credit'] = $credit;
		$success = Table::updateRow("accounting_receipts", $values, $receiptID, $comments);	
	}
	
	
	



	public function returntoopenAction() {
	
		$comments = false;
		$purchaseID = $_GET['purchaseID'];
		$purchase = Table::loadRow('accounting_purchases', $purchaseID);
		//$this->reCreateEntries($purchase);
		
		
		// Ostolasku on jo kohdistettu tiliotteelta, pitäisi poistaa myös kohdistus tosite
		if ($purchase->state == Collections::PURCHASESTATE_CONFIRMED) {
			
			if ($purchase->bankstatementrow > 0) {
				
				Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $purchase->receiptID);
				
				
				$statementreceipt = Table::loadRow('accounting_receipts', "WHERE PurchaseID=" . $purchaseID . " AND BankstatementrowID IS NOT NULL", true);
				if ($statementreceipt == null) {
					echo "<br>statementreceipt is null";
				}
				echo "<br>statementreceipt - "  .$statementreceipt->purchaseID;
				echo "<br>statementreceipt - "  .$statementreceipt->receiptID;
					
				// Poista entryt --> statementreceipt->receiptID
				Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $statementreceipt->receiptID);
				
				// Poista receiptID --> $statementreceipt->receiptID;
				Table::deleteRow("accounting_receipts", $statementreceipt->receiptID);
					
				// Aseta bankstatemenrow->state = 1 ja bankstatementrow->receiptID = 0
				$values = array();
				$values['State'] = 1;
				$values['ReceiptID'] = 0;
				$success = Table::updateRow("accounting_bankstatementrows", $values, $statementreceipt->receiptID, $comments);
				
				// Päivitä purchase: paymentdate = 0000-00-00, state=0
				$values = array();
				$values['State'] = 0;
				$values['Paymentdate'] = '0000-00-00';
				$success = Table::updateRow("accounting_purchases", $values, $purchaseID, $comments);
				
				$values = array();
				$values['Debet'] = 0;
				$values['Credit'] = 0;
				$success = Table::updateRow("accounting_receipts", $values, $purchase->receiptID, $comments);
				
				
				
			} else {
				
				Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $purchase->receiptID);
				// Table::deleteRow("accounting_receipts", $purchase->receiptID);
				
				$values = array();
				$values['State'] = 0;
				$values['Paymentdate'] = '0000-00-00';
				$success = Table::updateRow("accounting_purchases", $values, $purchaseID, $comments);
				

				$values = array();
				$values['Debet'] = 0;
				$values['Credit'] = 0;
				$success = Table::updateRow("accounting_receipts", $values, $purchase->receiptID, $comments);
				
			}
			
			redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
				
		} else {
			
			Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $purchase->receiptID);
			
			$values = array();
			$values['State'] = 0;
			$success = Table::updateRow("accounting_purchases", $values, $purchaseID, $comments);
				
			$values = array();
			$values['Debet'] = 0;
			$values['Credit'] = 0;
			$success = Table::updateRow("accounting_receipts", $values, $purchase->receiptID, $comments);
			
			redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		}
		
		/*
		$values = array();
		$values['State'] = 0;
		$success = Table::updateRow("accounting_purchases", $values, $purchaseID, $comments);
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		*/
	}
	
	
	private function reCreateEntries($purchase, $comments = false) {

		$receipt = Table::loadRow("accounting_receipts", " WHERE ReceiptID=" . $purchase->receiptID);
		$paymentmethods = Table::load("accounting_paymentmethods");
		
		// Poistetaan kaikki viennit ennen uusien luontia
		Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $receipt->receiptID);
		
		// Luodaan kaikkien ostolaskurivien viennit uudelleen.
		$purhcaserows = Table::load('accounting_purchaserows'," WHERE PurchaseID=" . $purchase->purchaseID);
		foreach($purhcaserows as $rowID => $purchaserow) {
		
			$vatID = $purchaserow->vatID;
			$costpoolID = $purchaserow->costpoolID;
			$costpool = Table::loadRow('accounting_costpools',$costpoolID);
			$paymentmethod = $paymentmethods[$purchase->paymentmethodID];
		
			$grossamount = $purchaserow->grossamount;
			$netamount = $purchaserow->netamount;
			$vatamount = $purchaserow->vatamount;
				
			$dimensions = array();
				
			$vat = null;
			$vatentryID = 0;
			if ($vatID > 0) {
				$vat = Table::loadRow('system_vats',$vatID);
				$this->checkVat($vat, $grossamount, $netamount);
				if ($vat->percent > 0) {
					$vataccountID = Settings::getSetting('accounting_vatrecievablesaccountID');
					$vatentryID = $this->createEntry($receipt->receiptID, $vataccountID, $purchase->purchasedate, $vatamount, 7, $dimensions, $purchase);
				}
			}
				
			// Ostovienti - tilinumero napataan valitusta kustannuspaikkasta
			$purchaseentryID = $this->createEntry($receipt->receiptID, $costpool->expenseaccountID,  $purchase->purchasedate, $netamount, 0, $dimensions, $purchase, $costpoolID, $comments);
				
			// Ostovelkavienti - tili haetaan ostotavasta.
			$payablesentryID = $this->createEntry($receipt->receiptID, $paymentmethod->accountID, $purchase->purchasedate, -1 * $grossamount, 0, $dimensions, $purchase, 0, $comments);
				
			$values = array();
			$values['PurchaseentryID'] = $purchaseentryID;
			$values['PayablesentryID'] = $payablesentryID;
			$values['VatentryID'] = $vatentryID;
			$success = Table::updateRow("accounting_purchaserows", $values, $purchaserow->rowID, $comments);
		}
		
	}
	
	
	public function acceptpurchaseAction() {
	
		$comments = false;
		$purchaseID = $_GET['purchaseID'];
		$purchase = Table::loadRow('accounting_purchases', $purchaseID, $comments);
		$entries = Table::load('accounting_entries', "WHERE ReceiptID=" . $purchase->receiptID, $comments);
		
		if (count($entries) > 0) {
			echo "<br>Virhetilanne, ostolasku sisältää jo entryjä...";
			exit;
		}
				
		$purchaserows = Table::load('accounting_purchaserows', "WHERE PurchaseID=" . $purchaseID, $comments);
		$supplier = Table::loadRow('accounting_suppliers', $purchase->supplierID, $comments);
		$costpools = Table::load('accounting_costpools', "ORDER BY Name");
		$accounts = Table::load('accounting_accounts');
		$vats = Table::load('system_vats');
		
		$entries = $this->createEntriesFromPurchase($purchase, $purchaserows, $supplier, $costpools, $accounts, $vats);
		foreach($entries as $index => $entry) {
			if ($entry->amount < 0) {
				$entry->debet = 0;
				$entry->credit = -1 * $entry->amount;
			} else {
				$entry->debet = $entry->amount;
				$entry->credit = 0;
			}
		}
		foreach($entries as $index => $entry) {
			$this->createEntryToDatabase($entry, $purchase->receiptID);
		}
		
		// päivitetään receiptin debet ja credit...
		$this->updatepurchasereceipt($purchase->receiptID);
				
		// Asetetaan ostolasku hyväksytty tilaan
		$values = array();
		$values['State'] = 1;	// TODO: vakio jostain
		$success = Table::updateRow("accounting_purchases", $values, $purchaseID, $comments);
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		
		//$this->reCreateEntries($purchase);
		
		
		/*
		
		// Tämä on vanha toteutus, silloin ilmeisesti päivitettiin rivien lisäyksen yhteydessä vientejä
		// eli state=0 ei sisällä vientejä lainkaan, state=1 ne luodaan
		$entriestoremove = array();
		$entriestoupdate = array();
		
		// Yhdistetään samalle kirjanpitotilille menevät viennit
		$summedentries = array();
		foreach($entries as $index => $entry) {
			$key = $entry->accountID . "-" . $entry->vatcodeID;
			if (isset($summedentries[$key])) {
				$sumentry = $summedentries[$key];
				$sumentry->amount = $sumentry->amount + $entry->amount;
				$sumentry->linktypeID = Collections::ENTRY_LINKTYPE_PURCHASEINVOICE;
				$sumentry->linktargetID = $purchaseID;
				$entriestoremove[] = $entry;
				$entriestoupdate[] = $sumentry;
			} else {
				$summedentries[$key] = $entry;
			}
		}
		
		// Poistetaan yhdistetyt viennit
		foreach($entriestoremove as $index => $entry) {
			if ($comments) echo "<br> -- entry to remove " . $entry->entryID;
			foreach($purchaserows as $index => $row) {
				//echo "<br> -- -- RowID - " . $row->rowID;
				//echo "<br> -- -- purchaseentryID - " . $row->purchaseentryID;
				//echo "<br> -- -- payablesentryID - " . $row->payablesentryID;
				//echo "<br> -- -- vatentryID - " . $row->vatentryID;
				if ($row->purchaseentryID == $entry->entryID) {
					if ($comments) echo "<br> -- purchaseentryID found in row - " . $row->rowID;
					$values = array();
					$values['PurchaseentryID'] = 0;
					$success = Table::updateRow("accounting_purchaserows", $values, $row->rowID, $comments);
				}				
				if ($row->payablesentryID == $entry->entryID) {
					if ($comments) echo "<br> -- payablesentryID found in row - " . $row->rowID;
					$values = array();
					$values['PayablesentryID'] = 0;
					$success = Table::updateRow("accounting_purchaserows", $values, $row->rowID, $comments);
				}	
				if ($row->vatentryID == $entry->entryID) {
					if ($comments) echo "<br> -- vatentryID found in row - " . $row->rowID;
					$values = array();
					$values['VatentryID'] = 0;
					$success = Table::updateRow("accounting_purchaserows", $values, $row->rowID, $comments);
				}
			}
			$success = Table::deleteRow('accounting_entries',$entry->entryID);
		}		
		
		// päivitetään yhdistetyt viennit
		foreach($entriestoupdate as $index => $entry) {
			if ($comments) echo "<br> -- entry to update " . $entry->entryID . " - " . $entry->amount;
			foreach($purchaserows as $index => $row) {
				//echo "<br> -- -- RowID - " . $row->rowID;
				//echo "<br> -- -- purchaseentryID - " . $row->purchaseentryID;
				//echo "<br> -- -- payablesentryID - " . $row->payablesentryID;
				//echo "<br> -- -- vatentryID - " . $row->vatentryID;
				if ($row->purchaseentryID == $entry->entryID) {
					if ($comments) echo "<br> -- purchaseentryID found in row - " . $row->rowID;
					$values = array();
					$values['PurchaseentryID'] = 0;
					$success = Table::updateRow("accounting_purchaserows", $values, $row->rowID, $comments);
				}				
				if ($row->payablesentryID == $entry->entryID) {
					if ($comments) echo "<br> -- payablesentryID found in row - " . $row->rowID;
					$values = array();
					$values['PayablesentryID'] = 0;
					$success = Table::updateRow("accounting_purchaserows", $values, $row->rowID, $comments);
				}	
				if ($row->vatentryID == $entry->entryID) {
					if ($comments) echo "<br> -- vatentryID found in row - " . $row->rowID;
					$values = array();
					$values['VatentryID'] = 0;
					$success = Table::updateRow("accounting_purchaserows", $values, $row->rowID, $comments);
				}
			}
			$values = array();
			$values['Amount'] = $entry->amount;
			$values['LinktypeID'] = $entry->linktypeID;
			$values['LinktargetID'] = $entry->linktargetID;
			$success = Table::updateRow("accounting_entries", $values, $entry->entryID, $comments);
		}
		*/
		
	}
	
	
	
	// TODO: tsekkaa toiminto, maksupäivä pitäisi varmaankin tulla parametrina.
	//   Tätä on nyt säädetty niin, että mitään tarkistuksia ei tehdä, vaan siirretään
	//   vain lasku maksettu ja linkitetty tilaan ja aseteaan paymentdateksi eräpäivä.
	//   Tämä yleensä käytetään esim. käteismaksuista, useimmiten purchasestate muutetaan
	//   pankkitiliotteelta kohdistuksen jälkeen.
	//   Tämä toiminta olettaa, että tarpeelliset viennit on suoritettu, velkatilin
	//   nollausvienti pitää tehdä erikseen omilla vienneillään...
	public function markaspayedAction() {
	
	
		$purchaseID = $_GET['purchaseID'];
		$purchase = Table::loadRow('accounting_purchases',$purchaseID);
		
		$values['State'] = Collections::PURCHASESTATE_CONFIRMED;
		//$values['Paymentdate'] = $purchase->duedate;
		$values['Paymentdate'] = "0000-00-00";
		$values['Paymenttype'] = Collections::PAYMENTTYPE_UNKNOWN;
		$values['PaymentreceiptID'] = 0;
		$success = Table::updateRow("accounting_purchases", $values, $purchaseID, true);
		redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		
		/*
		const PAYMENTTYPE_CASH = 1;
		const PAYMENTTYPE_DEPTHS = 2;
		const PAYMENTTYPE_BANKACCOUNT = 3;
		const PAYMENTTYPE_SALARY = 4;
		
		const PURCHASETYPE_CASHRECEIPT = 1;
		const PURCHASETYPE_CARD = 2;
		const PURCHASETYPE_BANKACCOUNT = 3;
		const PURCHASETYPE_INVOICE = 4;
		const PURCHASETYPE_PERSON = 5;
		*/
		/*	
		if ($purchase->purchasetype == Collections::PURCHASETYPE_CASHRECEIPT) {
			$values = array();
			$values['State'] = 4;
			$values['Paymenttype'] = Collections::PURCHASETYPE_CASHRECEIPT;
			$values['Paymentdate'] = $purchase->purchasedate;
			$success = Table::updateRow("accounting_purchases", $values, $purchaseID, true);
			redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		}
		
		if ($purchase->purchasetype == Collections::PURCHASETYPE_CARD) {
			echo "<br>Korttimaksua pitää merkitä maksetuksi kohdistuksen kautta...";
			exit;
		}
		
		if ($purchase->purchasetype == Collections::PURCHASETYPE_BANKACCOUNT) {
			echo "<br>Laskun pankkitilimaksu pitää merkitä maksetuksi kohdistuksen kautta...";
			exit;
		}
		
		if ($purchase->purchasetype == Collections::PURCHASETYPE_INVOICE) {
			echo "<br>Lasku pitää merkitä maksetuksi kohdistuksen kautta...";
			exit;
		}
		
		if ($purchase->purchasetype == Collections::PURCHASETYPE_PERSON) {
			$values = array();
			$values['State'] = 4;
			$values['Paymenttype'] = Collections::PAYMENTTYPE_DEPTHS;
			$values['Paymentdate'] = $purchase->purchasedate;
			$success = Table::updateRow("accounting_purchases", $values, $purchaseID, true);
			redirecttotal('accounting/purchases/showpurchase&id=' . $purchaseID, null);
		}
		
		echo "<br>Tuntematon purchasetype = " . $purchase->purchasetype;
		exit;
		*/
	}
	
	
	
	public function copypurchaseAction() {
	
		$sourcepurchaseID = $_GET['purchaseID'];
		$sourcepurchase = Table::loadRow('accounting_purchases',$sourcepurchaseID);
	
		$purchasedate = $_GET['purchasedate'];
		if (isset($_GET['supplierID'])) {
			$values['SupplierID'] = $_GET['supplierID'];
		} else {
			$values['SupplierID'] = $sourcepurchase->supplierID;
		}
		$values['PaymentmethodID'] = $sourcepurchase->paymentmethodID;
		$values['Purchasedate'] = $_GET['purchasedate'];
		
		$duedate = $_GET['duedate'];
		$values['Duedate'] = $duedate;

		// TODO: dimensioiden kopiointi
		
		$values['Grossamount'] = $sourcepurchase->grossamount;
		$values['Netamount'] = $sourcepurchase->netamount;
		$values['Vatamount'] = $sourcepurchase->vatamount;
		$values['Purchasetype'] = $sourcepurchase->purchasetype;
		$values['Paymenttype'] = $sourcepurchase->paymenttype;
		$values['CardID'] = $sourcepurchase->cardID;
		$values['PersonID'] = $sourcepurchase->personID;
		$values['PayableaccountID'] = $sourcepurchase->payableaccountID;
		$values['PayablecostpoolID'] = $sourcepurchase->payablecostpoolID;
		$values['Note'] = $sourcepurchase->note;
		$values['State'] = Collections::PURCHASESTATE_ACCEPTED;
		
		$newpurchaseID = Table::addRow("accounting_purchases", $values);
	
		$sourcepurchaserows = Table::load('accounting_purchaserows'," WHERE PurchaseID=" . $sourcepurchaseID);
		foreach($sourcepurchaserows as $index => $sourcepurchaserow) {
			$values = array();
			$values['CostpoolID'] = $sourcepurchaserow->costpoolID;
			$values['Costpoolname'] = $sourcepurchaserow->costpoolname;	// costpoolin nimi saattaa olla muuttunut, tai poistunut
			$values['VatID'] = $sourcepurchaserow->vatID;
			$values['Vatpercent'] = $sourcepurchaserow->vatpercent;
			$values['PurchaseID'] = $newpurchaseID;
			$values['Purchasedate'] = $purchasedate;
			$values['Grossamount'] = $sourcepurchaserow->grossamount;
			$values['Netamount'] = $sourcepurchaserow->netamount;
			// TODO: dimensiot mukaan
			$values['Vatamount'] = $sourcepurchaserow->vatamount;
			$values['AccountID'] = $sourcepurchaserow->accountID;
			$newpurchaserowID = Table::addRow("accounting_purchaserows", $values, false);
		}
		
		//$newpurchaserows = Table::load('accounting_purchaserows',"WHERE PurchaseID=" . $newpurchaseID);
		//$this->updatepurchasesums($newpurchaseID, $newpurchaserows);
		$newpurchase = Table::loadRow('accounting_purchases',$newpurchaseID);
		
	
		$oldreceipt = Table::loadRow('accounting_receipts','WHERE PurchaseID=' . $sourcepurchaseID, false);
			
		$receiptnumber = $this->getNextReceiptNumber($oldreceipt->receiptsetID);
			
		$values = array();
		$values['Receiptdate'] = $_GET['purchasedate'];
		// TODO: uusi receiptnumber pitänee luoda
		$values['Receiptnumber'] = $receiptnumber;
		$values['ReceiptsetID'] = $oldreceipt->receiptsetID;
		$values['Explanation'] = "Ostolasku " . $newpurchaseID;
		//$values['ReceiverID'] = 0;	// pitäisi asettaa asiakasyritys
		//$values['CostpoolID'] = 0;	// Pitäisi asettaa jokin sopiva kustannuspaikka, myynti? Tämä on lähinnä ostoja varten
		//$values['Grossamount'] = $newpurchase->grossamount;
		//$values['Netamount'] = $newpurchase->netamount;
		//$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
		//$values['Paymentstatus'] = 0;
		$values['PurchaseID'] = $newpurchaseID;
		$values['Files'] = $oldreceipt->files;
		$values['SupplierID'] = $sourcepurchase->supplierID;
		$newreceiptID = Table::addRow("accounting_receipts", $values, false);
		
		
		$values = array();
		$values['ReceiptID'] = $newreceiptID;
		Table::updateRow("accounting_purchases", $values, $newpurchaseID);
		
		
		$sourceentries = Table::load('accounting_entries'," WHERE ReceiptID=" . $oldreceipt->receiptID);
			
		foreach($sourceentries as $index => $sourceentry) {
			$values = array();
			$values['ReceiptID'] = $newreceiptID;
			$values['AccountID'] = $sourceentry->accountID;
			$values['Entrydate'] = $newpurchase->purchasedate;
			$values['Amount'] = $sourceentry->amount;
			$values['VatcodeID'] = $sourceentry->vatcodeID;
			$values['CostpoolID'] = $sourceentry->costpoolID;
			$newentryID = Table::addRow("accounting_entries", $values, false);
			
			if ($sourceentry->entryID == $sourcepurchase->purchaseentryID) {
				$values = array();
				$values['PurchseentryID'] = $newentryID;
				$success = Table::updateRow("accounting_purchases", $values, $newpurchase->purchaseID, false);
			}
			
			if ($sourceentry->entryID == $sourcepurchase->vatentryID) {
				$values = array();
				$values['VatentryID'] = $newentryID;
				$success = Table::updateRow("accounting_purchases", $values, $newpurchase->purchaseID, false);
			}
				
			if ($sourceentry->entryID == $sourcepurchase->payableentryID) {
				$values = array();
				$values['PayableentryID'] = $newentryID;
				$success = Table::updateRow("accounting_purchases", $values, $newpurchase->purchaseID, false);
			}
		}
		
		
		if ($sourcepurchase->purchasetype == Collections::PURCHASETYPE_INVOICE) {
			
			/*
			$values = array();
			$values['Purchasedate'] = $purchasedate;
			$values['Duedate'] = $duedate;
			$values['Grossamount'] = $sourcepurchase->grossamount;;
			$values['Paymentsource'] = Collections::PAYMENTSOURCE_PURCHASES;
			$values['PaymentsourceID'] = $newpurchase->purchaseID;
			$values['Paymentstatus'] = Collections::PAYMENTSTATUS_OPEN;
			$values['PaymenttargetaccountID'] = $sourcepurchase->payableaccountID;;
			$values['PaymentsourceaccountID'] = 0;
			$values['Paymenttype'] = Collections::PAYMENTTYPE_BANKACCOUNT;
			$paymentID = Table::addRow("accounting_payments", $values);
			*/
			
		} else {
			echo "<br>Payment creation not implemented";	
		}
		
		redirecttotal('accounting/purchases/showpurchase&id=' . $newpurchaseID, null);
	}
	
	
	
	
}

?>
