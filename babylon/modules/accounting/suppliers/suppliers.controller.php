<?php


function comparesupplieramount($a, $b) {
	if ($a->amount == $b->amount) {
		return 0;
	}
	if ($a->amount < $b->amount) return 1;
	return -1;
}


class SuppliersController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showsuppliersAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showsuppliersAction() {

		updateActionPath("Toimittajat");
		
		$this->registry->suppliers = Table::load("accounting_suppliers");
		$this->registry->countries = Table::load('system_countries');
		$this->registry->template->show('accounting/suppliers','suppliers');
		
		
	}
	
	


	public function showsuplyvolumesAction() {
	
		$periodID = getSessionVar('periodID',AccountingModule::getBookkeepingPeriod());
		//echo "<br>PeriodID - " . $periodID;
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		$this->registry->suppliers = Table::load("accounting_suppliers");
		$this->registry->purchases = Table::load('accounting_purchases', "WHERE Purchasedate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "'", false);

		foreach($this->registry->suppliers as $index => $supplier) {
			$supplier->amount = 0;			
		}
		
		foreach($this->registry->purchases as $index => $purchase) {
			$supplierID = $purchase->supplierID;
			if (isset($this->registry->suppliers[$supplierID])) {
				$supplier = $this->registry->suppliers[$supplierID];
				$supplier->amount = $supplier->amount + $purchase->grossamount;
				//echo "<br>PurchaseID - " . $purchase->purchaseID  . " - sss " . $purchase->grossamount;
				//echo "<br>Supplier - " . $supplier->name . " - " . $supplier->amount;
			} else {
				echo "<br>Unknown supplier - " . $supplierID;
			}
		}
		
		$newsuppliers = array();
		foreach($this->registry->suppliers as $index => $supplier) {
			if ($supplier->amount > 0) $newsuppliers[] = $supplier;
		}
		
		usort($newsuppliers, 'comparesupplieramount');
		$this->registry->suppliers = $newsuppliers;
		
		$this->registry->template->show('accounting/suppliers','supplyvolumes');
	}
	


	public function showsupplierAction() {
	
		
		$supplierID = $_GET['id'];
		$this->registry->supplier = Table::loadRow('accounting_suppliers',$supplierID);
		updateActionPath("" . $this->registry->supplier->name);
		$this->registry->vats = Table::load('system_vats');
		$this->registry->countries = Table::load('system_countries');
		$this->registry->costpools = Table::load('accounting_costpools');
		$this->registry->paymentmethods = Table::load('accounting_paymentmethods');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->defaultrows = Table::load('accounting_defaultpurchaserows', ' WHERE SupplierID=' . $supplierID);
		
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		$periodID = getSessionVar('periodID',AccountingModule::getBookkeepingPeriod());
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		$this->registry->periodID = $periodID;
		$period = $this->registry->period;
		/*
		if ($periodID == 0) {
			$this->registry->purchases = Table::load('accounting_purchases', "WHERE Purchasedate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "'", false);
		} else {
			$this->registry->purchases = Table::load('accounting_purchases', "WHERE SupplierID=" . $supplierID . " AND Purchasedate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "'", false);
		}
		*/
		
		$this->registry->invoices = Table::load('accounting_purchases', "WHERE SupplierID=" . $supplierID . " AND Purchasedate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "' ORDER BY Purchasedate", false);
		//$this->registry->invoices = Table::load('accounting_purchases', " WHERE Purchasedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Purchasedate");
		$this->registry->startdate = $period->startdate;
		$this->registry->enddate = $period->enddate;
		
		$this->registry->lastdate = null;
		$purchaselist = array();
		$receiptlist = array();
		foreach($this->registry->invoices as $index => $invoice) {
			$invoice->alvamount = $invoice->grossamount - $invoice->netamount;
			if ($this->registry->lastdate == null) $this->registry->lastdate = $invoice->purchasedate;
			if ($this->registry->lastdate < $invoice->purchasedate) $this->registry->lastdate = $invoice->purchasedate;
			$purchaselist[$invoice->purchaseID] = $invoice->purchaseID;
			$receiptlist[$invoice->receiptID] = $invoice->receiptID;
		
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
		
		$receipts = Table::loadWhereInArray("accounting_receipts","ReceiptID", $receiptlist, "WHERE SystemID=" . $_SESSION['systemID']);
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
					echo "<br>Receipt not found 2 - " . $invoice->receiptID;
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
		
		
		// TODO: Pitäisi ladata kaikki receiptit, ja sitten filtteröidä kaikki ko. tilikauden ulkopuoliset
		// 		 luoda niistä tilikauden alkusaldo, ja sitten näyttää ko. tilikauden receiptit.
		//		 tämä on hankalaa, koska aiempien tilikausien kohdistuksia ei ole hoidettu oikein
		
		
		// TODO: voitaisiin kokeilla tähän entryjen lataamista ao. kustannuspaikalta...
		
		$receipts = Table::load("accounting_receipts", "WHERE SupplierID=" . $supplierID . " AND Receiptdate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "' ORDER BY Receiptdate");
		foreach($receipts as $index => $receipt) {
			$receipt->text = $receipt->explanation;
			//$entry->sum = $entry->amount;
		}
		$this->registry->receipts = $receipts;		
		
		$payableaccountID = Settings::getSetting('accounting_payablesaccountID');
		
		$entries = Table::load("accounting_entries", "WHERE SupplierID=" . $supplierID . " AND AccountID=" . $payableaccountID . " AND Entrydate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "' ORDER BY Entrydate");
		$receiptlist = array();
		$sum = 0;
		foreach($entries as $index => $entry) {
			$entry->text = $this->registry->accounts[$entry->accountID]->name;
			$sum = $sum + $entry->amount;
			$entry->saldo = $sum;
				
			$receiptlist[$entry->receiptID] = $entry->receiptID;
			
			/*
			if ($entry->costpoolID == 0) {
				$entry->text = $this->registry->accounts[$entry->accountID]->name;
				$entry->sum = $entry->amount;
			} else {
				$entry->text = "EntryID: " . $entry->entryID . ", receiptID: " . $entry->receiptID;
				$entry->sum = $entry->amount;
			}
			*/
			//$receiptlist[$entry->receiptID] = $entry->receiptID;			
		}

		$entryreceipts = Table::loadWhereInArray("accounting_receipts","ReceiptID", $receiptlist, "WHERE SystemID=" . $_SESSION['systemID']);
		
		foreach($entries as $index => $entry) {
			$receipt = $entryreceipts[$entry->receiptID];
			$entry->text = $receipt->explanation;
		}
		$this->registry->entries = $entries;
		
		/*
		$receipts = Table::load("accounting_receipts", "WHERE SupplierID=" . $supplierID . " AND Receiptdate BETWEEN '" . $period->startdate . "' AND '" . $period->enddate . "' ORDER BY Receiptdate");
		//$receipts = Table::load("accounting_receipts", "WHERE SupplierID=" . $supplierID . " ORDER BY Receiptdate");
		$previous = null;
		$receiptsets = Table::load("accounting_receiptsets");
				
		foreach($receipts as $index => $receipt) {
			
			$receipt->date = $receipt->receiptdate;
			$receiptset = $receiptsets[$receipt->receiptsetID];
			
			if ($receipt->purchaseID > 0) {
				$receipt->text = "Ostolasku " . $receipt->purchaseID;
				$receipt->link = "";
				$receipt->sum = $receipt->debet;
				if ($receipt->sum < 0) $receipt->sum = -1 * $receipt->sum;
			} else {
				if ($receipt->bankstatementrowID > 0) {
					$receipt->text = "Maksu pankkitililtä";
					$receipt->date = $receipt->receiptdate;
					if ($receipt->sum > 0) $receipt->sum = -1 * $receipt->sum;
				} else {
					if ($receipt->supplierID > 0) {
						$receipt->text = "Supplier joku";
						$receipt->date = $receipt->receiptdate;
						if ($receipt->sum > 0) $receipt->sum = -1 * $receipt->sum;
					}
				}
			}
				
			// Kyse on avaustositteesta...
			if ($receiptset->receiptsettype == 1) {
				$receipt->text = "Avaustosite";
				$receipt->date = $receipt->receiptdate;
				$receipt->sum = $receipt->debet - $receipt->credit;
				//if ($sum < 0) $receipt->sum = -1 * $receipt->sum;
				//if ($receipt->sum < 0) $receipt->sum = $receipt->sum;
			}
			
			if ($previous == null) {
				$receipt->saldo = $receipt->sum;
			} else {
				$receipt->saldo = $previous->saldo + $receipt->sum;
			}
			$previous = $receipt;
		}
		*/
		
		
		
		
		$this->registry->template->show('accounting/suppliers','supplier');
	}
	
	

	private function getExtension($filename) {
		$pos = strpos($filename, ".");
		//echo "<br>Pos - " . $pos;
		$str = substr($filename, $pos+1);
		//echo "<br>substr - " . $str;
		return $str;
	}
	
	
	public function insertreceiverAction() {
	
	}
	
	
	public function updatereceiverAction() {
	
	}
	
	
	public function insertsupplierAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Iban'] = $_GET['iban'];
		$values['Referencenumber'] = $_GET['referencenumber'];
		$values['CountryID'] = $_GET['countryID'];
		
		$values['Paymenttime'] = null;
		$values['PaymentmethodID'] = 0;
		$values['Paymenttimemanual'] = 0;
		
		$success = Table::addRow("accounting_suppliers", $values, false);
	
		redirecttotal('accounting/suppliers/showsuppliers',null);
	}
	
	
	public function updatesupplierAction() {
		
		$supplierID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Iban'] = $_GET['iban'];
		$values['Referencenumber'] = $_GET['referencenumber'];
		if (isset($_GET['paymentmethodID'])) {
			$values['PaymentmethodID'] = $_GET['paymentmethodID'];
			if ($_GET['paymenttime'] == "") {
				$values['Paymenttimemanual'] = 0;
			}
		}
		if (isset($_GET['paymenttime'])) {
			$values['Paymenttime'] = $_GET['paymenttime'];
			if ($_GET['paymenttime'] == "") {
				$values['Paymenttimemanual'] = 0;
			} else {
				$values['Paymenttimemanual'] = 1;
			}
		}
		
		$values['CountryID'] = $_GET['countryID'];
		$success = Table::updateRow("accounting_suppliers", $values, $supplierID);
		
		redirecttotal('accounting/suppliers/showsupplier&id=' . $supplierID,null);
	}
	
	

	public function getsupplierJSONAction() {
	
		if (isset($_GET['supplierID'])) {
			$supplierID = $_GET['supplierID'];
			$supplier = Table::loadRow("accounting_suppliers", $supplierID);
	
			echo " {";
			echo "	  \"supplierID\":\"" . $supplier->supplierID . "\",";
			echo "	  \"name\":\"" . $supplier->name . "\",";
			echo "	  \"countryID\":\"" . $supplier->countryID. "\",";
			$paymentmethodID = $supplier->paymentmethodID;
			if ($supplier->paymentmethodID == null) $paymentmethodID = 0;
			echo "	  \"paymentmethodID\":\"" . $paymentmethodID . "\",";
			echo "	  \"paymenttime\":\"" . $supplier->paymenttime. "\"";
			echo " }\n";
			return;
		}
		echo " {";
		echo "	  \"supplierID\":\"0\",";
		echo "	  \"name\":\"\",";
		echo "	  \"countryID\":\"0\"";
		echo "	  \"paymentmethodID\":\"0\"";
		echo "	  \"paymenttime\":\"0\"";
		echo " }\n";
		return;
		
	}
	
	
	
	public function insertdefaultrowAction() {
	
		$values = array();
		$values['SupplierID'] = $_GET['supplierID'];
		$values['CostpoolID'] = $_GET['costpoolID'];
		$values['VatID'] = $_GET['vatID'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::addRow("accounting_defaultpurchaserows", $values);
	
		redirecttotal('accounting/suppliers/showsupplier&id=' . $_GET['supplierID'],null);
	}
	
	
	public function getdefaultrowsAction() {
	
		$supplierID = $_GET['supplierID'];
		
		$supplier = Table::loadRow('accounting_suppliers',$supplierID);
		$defaultrows = Table::load('accounting_defaultpurchaserows', ' WHERE SupplierID=' . $supplierID);
		
		echo " { ";
		echo "  \"paymentmethod\":\"" . $supplier->paymentmethodID . "\",";
		echo " \"rows\": [";
		$first = true;
		foreach($defaultrows as $index => $row) {
			if ($first == true) {
				$first = false;
			} else {
				echo ",";
			}
			echo "	{ \"costpoolID\":\"" . $row->costpoolID . "\", \"vatID\":\"" . $row->vatID . "\", \"accountID\":\"" . $row->accountID . "\" }";
		}
		echo " ]";
		echo " }";
			
	}
	
	

	public function updatedefaultrowAction() {
	
		$rowID = $_GET['id'];
		$supplierID = $_GET['supplierID'];
		
		$values = array();
		$values['CostpoolID'] = $_GET['costpoolID'];
		$values['VatID'] = $_GET['vatID'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::updateRow("accounting_defaultpurchaserows", $values, $rowID);
		
		redirecttotal('accounting/suppliers/showsupplier&id=' . $supplierID,null);
	}
	
	
	public function removedefaultrowAction() {
	
		$rowID = $_GET['id'];
		$supplierID = $_GET['supplierID'];
		
		$success = Table::deleteRow("accounting_defaultpurchaserows", $rowID, true);
		
		//redirecttotal('accounting/suppliers/showsupplier&id=' . $supplierID,null);
	}
	
	
}
