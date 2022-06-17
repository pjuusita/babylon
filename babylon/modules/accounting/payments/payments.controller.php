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
class PaymentsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php','fileuploader.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','fileuploader.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showoutgoingpaymentsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showoutgoingpaymentsAction() {
	
		$comments = true;
		
		//$this->registry->payments = Table::load('accounting_payments', "WHERE Paymentstatus=" . Collections::PAYMENTSTATUS_OPEN . " ORDER BY Cdate");
		
		$purchaselist = array();
		
		foreach($this->registry->payments as $index => $payment) {
			if ($payment->paymentsource == Collections::PAYMENTSOURCE_PURCHASES) {
				echo "<br>Ostolaskun maksu - " . $payment->paymentsourceID;
			}
		}
		
		/*
		$periodID = getSessionVar('periodID',AccountingModule::getBookkeepingPeriod());
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		*/
		
		
		//$this->registry->suppliers = Table::load('accounting_suppliers', ' ORDER BY Name');
		//$this->registry->bankaccounts = Table::load('accounting_bankaccounts', ' ORDER BY Name');
		//$this->registry->persons = Table::load('hr_workers', ' ORDER BY Lastname,Firstname');
		//$this->registry->paymentcards = Table::load('accounting_paymentcards');
		//$this->registry->purchasetypes = Collections::getPurchaseTypes();
		
		//foreach($this->registry->purchasetypes as $index => $value) {
		//	echo "<br>" . $index . " - " . $value;
		//}
		
		//foreach($this->registry->paymentcards as $index => $card) {
		//	$person = $this->registry->persons[$card->workerID];
		//	$card->name = $card->number . " - " . $person->lastname . " " . $person->firstname;
		//}
		
		//echo "<br>periodID - " . $periodID;
		//$oldperiodID = getOldModuleSessionVar('periodID');
		
		//if ($periodID != $oldperiodID) {
		//	echo "<br>Tilikautta muutettu";
		//}
		
		//if ($comments) echo "<br>OldperiodID - " . $oldperiodID;	
		//$periodID = AccountingModule::getBookkeepingPeriod();
		//if ($comments) echo "<br>PeriodID - " . $periodID;
		
		
		
		//$selectionID = getSessionVar('selectionID',0);
		//if ($comments) echo "<br>selectionID - " . $selectionID;
		//$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		//$this->registry->selection = $selection;
		//$this->registry->selectionID = $selectionID;
		
		//if ($selectionID == 0) {
		//	foreach($selection as $index => $sel) {
		//		$selectionID = $index;
		//		break;
		//	}			
		//}
		//$selectedmonth = $selection[$selectionID];
		
		//$startdate = $selectedmonth->startsql;
		//$enddate = $selectedmonth->endsql;
		//if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;

		/*		
		$this->registry->invoices = Table::load('accounting_purchases', " WHERE Purchasedate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Purchasedate");
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		
		$this->registry->lastdate = null;
		$purchaselist = array();
		foreach($this->registry->invoices as $index => $invoice) {
			$invoice->alvamount = $invoice->grossamount - $invoice->netamount;
			if ($this->registry->lastdate == null) $this->registry->lastdate = $invoice->purchasedate;
			if ($this->registry->lastdate < $invoice->purchasedate) $this->registry->lastdate = $invoice->purchasedate;
			$purchaselist[$invoice->purchaseID] = $invoice->purchaseID;
		}
		
		$receipts = Table::loadWhereInArray("accounting_receipts","PurchaseID", $purchaselist, "WHERE SystemID=" . $_SESSION['systemID']);
		foreach($receipts as $index => $receipt) {
			//echo "<br>receipt - " . $index . " - " . $receipt->receiptID;
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
					echo "<br>Receipt not found 3 - " . $invoice->receiptID;	
				}
			}
			//$invoice->file = $invoice->receiptID;
			
			//$invoice->link = $invoice->receiptID;
			//echo "<br>inv - " . $invoice->purchaseID . " - " . $invoice->receiptID;
		}
		if ($this->registry->lastdate == null) $this->registry->lastdate = date('Y-m-d');
		*/
		//echo "<br>ssi - " . $_SESSION['accounting/purchases/showpurchases_periodID'];
		
		$this->registry->template->show('accounting/payments','outgoing');
	}
	
	public function showincomingpaymentsAction() {
	
		$this->registry->template->show('accounting/payments','incoming');
	}

	
	public function showcashflowAction() {
	
		$this->registry->template->show('accounting/payments','cashflow');
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
	

	

	


	



	
	
}

?>
