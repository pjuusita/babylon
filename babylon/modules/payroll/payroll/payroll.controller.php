<?php



class PayrollController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}
	
	
	public function indexAction() {
		//$this->showpayrollAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showpayrollAction() {
	
		$comments = false;
		updateActionPath("Palkkalaskelmat");
		
		$periodID = getSessionVar('periodID',PayrollModule::getBookkeepingPeriod());
		$this->registry->periodID = $periodID;
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		
		

		$selectionID = getSessionVar('selectionID',0);
		$selection = Collections::generatePeriodTimescales($this->registry->period, $selectionID);
		$this->registry->selection = $selection;
		$this->registry->selectionID = $selectionID;
		
		/*
		if ($selectionID == 0) {
			foreach($selection as $index => $sel) {
				$selectionID = $index;
				break;
			}
		}
		
		*/
		
		if ($selectionID == 0) {
			$startdate = $this->registry->period->startdate;
			$enddate = $this->registry->period->enddate;

			$periodstart = $this->registry->period->startdate;
			$periodend = $this->registry->period->enddate;
				
		} else {
			$selectedmonth = $selection[$selectionID];
				
			$startdate = $selectedmonth->startsql;
			$enddate = $selectedmonth->endsql;
			
			$periodstart = $this->registry->period->startdate;
			$periodend = $this->registry->period->enddate;
				
		}
		
		
		if ($comments) echo "<br>Startdate - " . $startdate;
		if ($comments) echo "<br>Enddate - " . $enddate;
		if ($comments) echo "<br>Period Startdate - " . $periodstart;
		if ($comments) echo "<br>Period Enddate - " . $periodend;
		
		if ($comments) echo "<br>startdate - " . $startdate;
		if ($comments) echo "<br>enddate - " . $enddate;
		if ($comments) echo "<br>selectionID - " . $selectionID;
		
		if ($comments) echo "<br>dates - " . $startdate . " - " . $enddate;
		
		$this->registry->years = $this->getYears(2018);
		$this->registry->months = Collections::getMonths();
		$workerID = 0;
		
		if ($workerID != 0) {
			if ($selectionID == 0) {
				$this->registry->paychecks = Table::load('payroll_paychecks', " WHERE CompanyID=" . $_SESSION['companyID'] . " AND Startdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $this->registry->period->enddate . "' AND WorkerID=" . $workerID . " ORDER BY Startdate, Paymentdate", $comments);
			} else {
				$this->registry->paychecks = Table::load('payroll_paychecks', " WHERE CompanyID=" . $_SESSION['companyID'] . " AND Startdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' AND WorkerID=" . $workerID . " ORDER BY Startdate, Paymentdate", $comments);
			}
		} else {
			if ($selectionID == 0) {
				$this->registry->paychecks = Table::load('payroll_paychecks', " WHERE CompanyID=" . $_SESSION['companyID'] . " AND Startdate BETWEEN '" . $this->registry->period->startdate . "' AND '" . $this->registry->period->enddate . "' ORDER BY Startdate, Paymentdate", $comments);
			} else {
				$this->registry->paychecks = Table::load('payroll_paychecks', " WHERE CompanyID=" . $_SESSION['companyID'] . " AND Startdate BETWEEN '" . $startdate . "' AND '" . $enddate . "' ORDER BY Startdate, Paymentdate", $comments);
			}
		}
		
		
		
		
		
		
		$paychecklist = array();
		foreach($this->registry->paychecks as $index => $paycheck) {
			$paychecklist[$paycheck->paycheckID] = $paycheck->paycheckID;
			
			if ($paycheck->state == Collections::PAYCHECKSTATE_OPEN) {
				$paycheck->statestr = "X---";
			}
			if ($paycheck->state == Collections::PAYCHECKSTATE_CHECKED) {
				$paycheck->statestr = "XX--";
			}
			if ($paycheck->state == Collections::PAYCHECKSTATE_PAID) {
				$paycheck->statestr = "XXX-";
			}
			if ($paycheck->state == Collections::PAYCHECKSTATE_LINKED) {
				$paycheck->statestr = "XXXX";
			}
		}
		
		$receipts = Table::loadWhereInArray("accounting_receipts","PaycheckID", $paychecklist, "WHERE SystemID=" . $_SESSION['systemID']);
		foreach($receipts as $index => $receipt) {
			//echo "<br>receipt - " . $index . " - " . $receipt->receiptID;
		}
		foreach($this->registry->paychecks as $index => $paycheck) {
			if ($paycheck->receiptID > 0) {
				if (isset($receipts[$paycheck->receiptID])) {
					$receipt = $receipts[$paycheck->receiptID];
					if ($receipt->files != "") {
						$resultarray = array();
						$filearray = explode(",",$receipt->files);
						$counter = 1;
						foreach($filearray as $index => $filename) {
							$ext = getFileExtension($filename);
							//echo "<br>Filename - " . $filename . " - " . $this->getExtension($filename);
							if (isset($resultarray[$ext])) {
								$resultarray[$ext . "_" . $counter] = $filename . "&paycheckID=" . $paycheck->paycheckID;
							} else {
								$resultarray[$ext] = $filename . "&paycheckID=" . $paycheck->paycheckID;
							}
							$counter = 0;
						}
						$paycheck->file = $resultarray;
						//$invoice->link = "<a target='_blank' href='" .getUrl("accounting/purchases/upload") ."&id=" . $invoice->purchaseID . "&file=" . $receipt->files . "'>PDF</a>";
					} else {
						//$invoice->link = $invoice->receiptID;
						$paycheck->link = "";
					}
				} else {
					echo "<br>Receipt not found 1 - " . $paycheck->receiptID;
				}
			}
		}
		
		
		$this->registry->startdate = $startdate;
		$this->registry->enddate = $enddate;
		//$this->registry->year = $year;
		//$this->registry->month = $month;
		
		foreach($this->registry->paychecks as $index => $paycheck) {
			$paycheck->timespan = sqlDateToStr($paycheck->startdate) . "-" . sqlDateToStr($paycheck->enddate);
		}
		
		$this->registry->workers = Table::load('hr_workers');
		foreach($this->registry->workers as $index => $worker) {
			$worker->fullname = $worker->firstname . " " . $worker->lastname;
		}
		
		
		$this->registry->labouragreements = Table::load('hr_labouragreements');
		$this->registry->companies = Table::load('system_companies');
		
		
		$this->registry->payrollperiods = Table::load('payroll_periods', "WHERE (Startdate >= '". $periodstart ."' AND Startdate <= '" . $periodend. "') OR (Enddate >= '". $periodstart . "' AND Enddate <= '" . $periodend. "')", $comments);
		$selectedperiod = null;
		
		foreach($this->registry->payrollperiods as $index => $period) {
			$period->name = sqlDateToStr($period->startdate) . " - " . sqlDateToStr($period->enddate);
			if (($startdate >= $period->startdate) && ($startdate <= $period->enddate)) {
				if ($comments) echo "<br>Selected period found - " . $period->name;
				$this->registry->payrollperiodID = $period->payrollperiodID;
			}
		}
		if ($comments) echo "<br>Selected period found id - " . $this->registry->payrollperiodID;
		
		
		/*
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		$dimensionvalues = array();
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$dimensionvalues[$dimension->dimensionID] = Table::load('system_dimensionvalues',' WHERE DimensionID=' . $dimension->dimensionID);
			}
		}
		$this->registry->dimensionvalues = $dimensionvalues;
		*/
		
		
		$this->registry->template->show('payroll/payroll','payroll');
	}
	



	private function getStartDate($year, $month) {
		if ($month == 0) {
			return $year . '-01-01';
		} else {
			if ($month < 10) {
				return $year . "-0" . $month . "-01";
			} else {
				return $year . "-" . $month . "-01";
			}
		}
	}
	
	
	private function getEndDate($year, $month) {
		return date("Y-m-t", strtotime($this->getStartDate($year, $month)));
	}
	
	


	public function insertpaycheckAction() {

		$comments = false;
		$workerID = $_GET['workerID'];
		$periodID = $_GET['periodID'];
	
		// Lisätään uusi period
		if ($periodID == -1) {
			// TODO: Lisää palkkakausi...
		}
		
		$period = Table::loadRow("payroll_periods","WHERE PayrollperiodID=" . $periodID);
	
		
		$contracts = Table::load("hr_workcontracts", "WHERE WorkerID=" . $workerID . " AND Startdate<='" . $period->enddate . "' AND (Enddate>='" . $period->startdate . "' OR Enddate='0000-00-00')", $comments);
		$contract = null;
		$values = array();
		$values['WorkerID'] = $workerID;
		//$values['LabouragreementID'] = $_GET['labouragreementID'];
		$values['PayrollperiodID'] = $periodID;
		$values['WorkcontractID'] = 0;
		$values['LabouragreementID'] = 0;
		$values['PensioninsurancetypeID'] = 0;
		if (count($contracts) == 0) {
			if ($comments) echo "<br>Ei työsopimuksia";
		} else {
			if (count($contracts) > 1) {
				echo "<br>Multible työsopimus";
				exit();
			} else {
				foreach($contracts as $index => $cont) {
					$contract = $cont;
				}
				$values['WorkcontractID'] = $contract->workcontractID;
				$values['LabouragreementID'] = $contract->labouragreementID;
				$values['PensioninsurancetypeID'] = $contract->pensioninsurancetypeID;
			}
		}
		
		//$values['Bookkeepingdate'] = $_GET['bookkeepingdate'];
	
		if (isset($_GET['paymentdate'])) {
			$sqldate = dateStrToSql($_GET['paymentdate']);
			$values['Paymentdate'] = $sqldate;
		} else {
			$values['Paymentdate'] = $period->paymentdate;
		}
	
		if (isset($_GET['startdate'])) {
			$sqldate = dateStrToSql($_GET['startdate']);
			$values['Startdate'] = $sqldate;
		} else {
			$values['Startdate'] = $period->startdate;
		}
	
		if (isset($_GET['enddate'])) {
			$sqldate = dateStrToSql($_GET['enddate']);
			$values['Enddate'] = $sqldate;
		} else {
			$values['Enddate'] = $period->enddate;
		}
	
		if (isset($_GET['bookkeepingdate'])) {
			$sqldate = dateStrToSql($_GET['bookkeepingdate']);
			$values['Bookkeepingdate'] = $sqldate;
		} else {
			$values['Bookkeepingdate'] = $period->bookkeepingdate;
		}
	
		if (isset($_GET['companyID'])) {
			$values['CompanyID'] = $_GET['companyID'];
		} else {
			$values['CompanyID'] = $_SESSION['companyID'];
		}
	
	
		$values['State'] = 0;
		$paycheckID = Table::addRow("payroll_paychecks", $values, $comments);
		if (!$comments) redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID,null);
	}
	
	
	
	// TODO: näytetään lisää asioita jos kirjanpito on käytössä
	//			- miten modulin olemassaolo selvitetään?
	
	public function showpaycheckAction() {
	
		$comments = false;
		updateActionPath("Palkkalaskelma");
		
		$errors = array();
		$paycheckID = $_GET['id'];
		$this->registry->paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		$this->registry->paycheckstates = Collections::getPaycheckStates();
		
		//$this->registry->taxcard = $this->getTaxCardForDate($paycheckID, $this->registry->paycheck->paymentdate);
		
		if ($this->registry->paycheck == null) {
			echo "<br>Palkkalaskelmaa ei löytynyt - " . $paycheckID;
			exit();
		}
		
		$workerID = $this->registry->paycheck->workerID;
		$paymentdate = $this->registry->paycheck->paymentdate;
		
		$this->registry->companies = Table::load('system_companies');
		$this->registry->labouragreements = Table::load('hr_labouragreements');
		$this->registry->payrollperiods = Table::load('payroll_periods');
		$this->registry->salarytypes = Table::load('hr_salarytypes');
		$this->registry->person = Table::loadRow('hr_workers', "WHERE WorkerID=" . $this->registry->paycheck->workerID);
		if ($this->registry->person->identificationnumber == "") {
			$errors[] = "Henkilön sosiaaliturvatunnus puuttuu";
		}
		if ($this->registry->person->bankaccountnumber== "") {
			$errors[] = "Henkilön pankkitilinumero puuttuu";
		}
		if (($this->registry->person->streetaddress == "") || ($this->registry->person->postalcode == "") || ($this->registry->person->city == "")) {
			$errors[] = "Henkilön osoitetiedot puutteelliset";
		}		
		
		$this->registry->deductionpercents = $this->loadDeductionPercents($this->registry->paycheck);
		
		if ($this->registry->paycheck->receiptID > 0) {
			$this->registry->receipt = Table::loadRow("accounting_receipts","WHERE ReceiptID=" . $this->registry->paycheck->receiptID);
			$this->registry->paycheck->files = $this->registry->receipt->files;
		}
		
		
		$this->registry->deductions = Table::load('payroll_deductions');
		$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID);
		$this->registry->paycheckrows = $paycheckrows;
		$pensioninsurancetypes = Collections::getPensionInsuranceTypes();
		$this->registry->pensioninsurancetypes = $pensioninsurancetypes;
		
		$workcontracts = Table::load('hr_workcontracts', "WHERE WorkerID=" . $workerID . " AND StartDate<='" . $this->registry->paycheck->paymentdate . "' AND (Enddate>='" . $this->registry->paycheck->paymentdate . "' OR Enddate='0000-00-00')");
		
		foreach($workcontracts as $index => $contract) {
			$labouragreement = $this->registry->labouragreements[$contract->labouragreementID];
			
			$startdate = strtotime($contract->startdate);
			$startstr = date('d.m.Y', $startdate);
			$endstr = "";
			if ($contract->enddate == '0000-00-00') {
				$endstr = "";
			} else {
				$enddate = strtotime($contract->enddate);
				$endstr = date('d.m.Y', $enddate);
			}
			$insurancetype = $pensioninsurancetypes[$contract->pensioninsurancetypeID];
			//echo "<br>labouragreement->abbreviation - " . $labouragreement->abbreviation;
			$contract->name = "" . $labouragreement->abbreviation . ", " . $startstr . " - " . $endstr . " (" . $contract->pensioninsurancetypeID . ")";
			//echo "<br>Contract xx - " . $contract->name .  " - xx " . $contract->workcontractID;
		}
		
		//echo "<br>paycheckrows - " . count($paycheckrows);
		$this->registry->workcontracts = $workcontracts;
		
		
		$accounts = Table::load('accounting_accounts');
		foreach($accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->accounts = $accounts;

		
		$this->registry->taxcard = $this->getTaxCardForDate($this->registry->paycheck->workerID, $this->registry->paycheck->paymentdate);
		
		if ($this->registry->taxcard != null) {
			
			if ($this->registry->paycheck->state == 0) {
					
				if ($comments) echo "<br>State on nolla";
				
					
				$finalpaycheckrows = array();
				foreach($paycheckrows as $index => $paycheckrow) {
					if ($comments) echo "<br> -- paycheckrow final - " . $paycheckrow->rowID;
					$finalpaycheckrows[] = $paycheckrow;
				}
				
				$newpaycheckrows = $this->calculateDeductionsFromPaycheckRows($this->registry->paycheck, $this->registry->person, $paycheckrows, $this->registry->salarytypes, $this->registry->deductionpercents, $this->registry->taxcard);
				foreach($newpaycheckrows as $index => $paycheckrow) {
					$finalpaycheckrows[] = $paycheckrow;
				}
				
				$newpaycheckrows = $this->calculateExpensesFromPaycheckRows($this->registry->paycheck, $this->registry->person, $paycheckrows, $this->registry->salarytypes, $this->registry->deductionpercents);
				foreach($newpaycheckrows as $index => $paycheckrow) {
					$finalpaycheckrows[] = $paycheckrow;
				}
				
				
				
				
				$this->registry->paycheckrows = $finalpaycheckrows;
				
				
				$this->registry->entries = $this->createEntriesFromPaycheckRows($this->registry->paycheck, $finalpaycheckrows, $this->registry->salarytypes, $this->registry->deductions);
				foreach($this->registry->entries as $index => $entry) {
					if ($entry->amount < 0) {
						$entry->debet = 0;
						$entry->credit = -1 * + round($entry->amount,2);
						if ($comments) echo "<br>credit amo " . $entry->amount;
						if ($comments) echo "<br>credit  " . $entry->credit;
					} else {
						$entry->debet = $entry->amount;
						$entry->credit = 0;
					}
				}
				
				
					
			} else {
				
				if ($comments) echo "<br>State " . $this->registry->paycheck->state;
				if ($comments) echo "<br>PaycheckID " . $paycheckID;
				if ($comments) echo "<br>ReceiptID " . $this->registry->paycheck->paycheckID;
				if ($comments) echo "<br>ReceiptID " . $this->registry->paycheck->receiptID;
				
				$this->registry->receipt = Table::loadRow("accounting_receipts","WHERE PaycheckID=" . $paycheckID, $comments);
			
				if ($this->registry->receipt == null) {
					echo "<br>Receipts not found, virhetila. State != 0, receipts pitäisi olla";
					//exit;
				} else {
					//$this->registry->purchase->receiptnumber = $this->registry->receipt->receiptnumber;
					$this->registry->entries= Table::load('accounting_entries', "WHERE ReceiptID=" . $this->registry->paycheck->receiptID);
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
					
				
				// autogeneraten tsekkaukseen
				
				$finalpaycheckrows = array();
				$temppaycheckrows = array();
				foreach($paycheckrows as $index => $paycheckrow) {
					if (($paycheckrow->salarycategoryID == Collections::SALARYCATEGORY_SALARY) || ($paycheckrow->salarycategoryID == Collections::SALARYCATEGORY_REPAYMENT)) {
						$temppaycheckrows[] = $paycheckrow;
					}
					$finalpaycheckrows[] = $paycheckrow;
				}
				
				$newpaycheckrows = $this->calculateDeductionsFromPaycheckRows($this->registry->paycheck, $this->registry->person, $temppaycheckrows, $this->registry->salarytypes, $this->registry->deductionpercents, $this->registry->taxcard);
				foreach($newpaycheckrows as $index => $paycheckrow) {
					$temppaycheckrows[] = $paycheckrow;
					$finalpaycheckrows[] = $paycheckrow;
				}
				
				$newpaycheckrows = $this->calculateExpensesFromPaycheckRows($this->registry->paycheck, $this->registry->person, $temppaycheckrows, $this->registry->salarytypes, $this->registry->deductionpercents);
				foreach($newpaycheckrows as $index => $paycheckrow) {
					$temppaycheckrows[] = $paycheckrow;
					$finalpaycheckrows[] = $paycheckrow;
				}
				
				$this->registry->autogeneratedpaycheckrows = $finalpaycheckrows;
				
				$this->registry->autoentries = $this->createEntriesFromPaycheckRows($this->registry->paycheck, $temppaycheckrows, $this->registry->salarytypes, $this->registry->deductions, true);
				foreach($this->registry->autoentries as $index => $entry) {
					if ($entry->amount < 0) {
						$entry->debet = 0;
						$entry->credit = -1 * + round($entry->amount,2);
						if ($comments) echo "<br>credit amo " . $entry->amount;
						if ($comments) echo "<br>credit  " . $entry->credit;
					} else {
						$entry->debet = $entry->amount;
						$entry->credit = 0;
					}
				}
				
				// Verrataan autogenerated entriessejä tietokannan arvoihin
				
				foreach($this->registry->autoentries as $index => $autoentry) {
				
					$foundcounter = 0;
					foreach($this->registry->entries as $index => $entry) {
						if ((round($autoentry->amount,2) == round($entry->amount,2)) && ($autoentry->accountID == $entry->accountID)) {
							$foundcounter++;							
						}
					}
					//echo "<br>Foundcounter - " . $foundcounter . " - " . $autoentry->amount;
					if ($foundcounter == 1) {
						$autoentry->color = 'lightgreen';
					} else {
						$autoentry->color = 'pink';
					}					
				}
				
			}
		} else {
			$errors[] = "Työntekijän verokortti puuttuu";
		}
		$this->registry->errors = $errors;
		$this->registry->template->show('payroll/payroll','paycheck');
	}

	
	
	
	
	public function paycheckpdfAction() {
	
		$paycheckID = $_GET['paycheckID'];
		$paycheck = Table::loadRow('payroll_paychecks', $paycheckID);

		if ($paycheck == null) {
			echo "<br>Palkkalaskelmaa ei löytynyt - " . $paycheckID;
			exit();
		}
		
		$this->registry->paycheck = $paycheck;
		
		//echo "<br>PaycheckID - " . $paycheckID;
		//echo "<br>PaycheckID - " . $paycheck->paycheckID;
		
		
		$company = Table::loadRow('system_companies', $paycheck->companyID);
		$this->registry->company = $company;
		//echo "<br>CompanyID - " . $paycheck->companyID;
		//echo "<br>CompanyID - " . $this->registry->company->name;
		
		$address = $this->selectAddress($paycheck);
		$this->registry->companyaddress = $address;
				
		$person = Table::loadRow('hr_workers', "WHERE WorkerID=" . $paycheck->workerID);
		$this->registry->person = $person;
		
		/*
		$taxcard = null;
		if ($paycheck->taxcardID == null) {
			$taxcards = Table::load('hr_taxcards', "WHERE StartDate<='" . $paycheck->paymentdate . "' AND Enddate>='" . $paycheck->paymentdate . "'");
			//echo "<br>Taxcard - " . count($taxcards);
			if (count($taxcards) == 0) {
				$taxcard = null;
			}
			if (count($taxcards) == 1) {
				foreach($taxcards as $index => $temp) $taxcard = $temp;				
			} else {
				echo "<br>Useampi verokortti voimassa samaan aikaan";
				exit;
			}
		} else {
			$taxcard = Table::loadRow('hr_taxcards', "WHERE TaxcardID=" . $paycheck->taxcardID . "");
		}
		*/
		//echo "<br>taxcard - " . $taxcard->taxcardID;
		//echo "<br>taxcard - " . $taxcard->percent1;
		//echo "<br>taxcard - " . $taxcard->percent2;
		$this->registry->taxcard = $this->getTaxCardForDate($paycheck->workerID, $paycheck->paymentdate);
				
		$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID);
		$this->registry->paycheckrows = $paycheckrows;
		$this->registry->deductions = Table::load('payroll_deductions');
		
		//echo "<br>paycheckrowcount - " . count($this->registry->paycheckrows);
		
		$this->registry->salarytypes = Table::load('hr_salarytypes');
		//echo "<br>salarytypes count - " . count($this->registry->salarytypes);
		
		$period = new Row();
		$period->startdate = "2019-04-01";
		$period->enddate = "2019-04-30";
		$period->paymentdate = "2019-04-30";
		$this->registry->period = $period;
		
		$this->registry->template->show('payroll/payroll','paycheckpdf');
	}
	
	

	// Tässä funktiossa ratkaistaan mikä osoite valitaan osoitteiden joukosta, tarvitaan pdf-luontiin
	private function selectAddress($paycheck) {
	
		//echo "<br>Paycheck officeID - " . $paycheck->officeID . ", branchID - " . $paycheck->branchID;
		$addresses = Table::load('system_addresses', "WHERE SystemID=" . $_SESSION['systemID']);

		if (count($addresses) == 0) {
			echo "<br>Yrityksen postiosoiteita ei löytynyt";
			exit;
		}
		
		// 1. haetaan osoitetta jossa companyID ja officeID ja branchID täsmää
		// 2. haetaan osoitetta jossa companyID ja officeID täsmää
		
		// 3. haetaan postiosoitetta jossa companyID ja täsmää
		foreach($addresses as $index => $address) {
			if (($address->companyID == $paycheck->companyID) && ($address->addresstype == Collections::ADDRESSTYPE_POSTAL)) {
					return $address;				
			}
		}
		
		// 4. haetaan käyntiosoitetta jossa companyID ja täsmää
		foreach($addresses as $index => $address) {
			if (($address->companyID == $paycheck->companyID) && ($address->addresstype == Collections::ADDRESSTYPE_LOCATION)) {
				return $address;
			}
		}
		
		// 5. haetaan yrityksen mitä tahansa osoitetta
		foreach($addresses as $index => $address) {
			if ($address->companyID == $paycheck->companyID) {
				return $address;
			}
		}
		
		echo "<br>Yritykselle ei löytynyt postiosoitetta";
		exit;
	}
	
	
	public function insertpaycheckrowAction() {

		$comments = false;
		$paycheckID = $_GET['paycheckID'];
		$paycheck = Table::loadRow('payroll_paychecks', $paycheckID);
		$salarytype = Table::loadRow('hr_salarytypes', $_GET['salarytypeID']);
		
		$values = array();
		$values['WorkerID'] = $_GET['workerID'];
		$values['PaycheckID'] = $paycheckID;
		$values['SalarytypeID'] = $_GET['salarytypeID'];
		$values['Amount'] = $_GET['amount'];
		$values['Unitprice'] = $_GET['unitprice'];
		$values['Total'] = $_GET['total'];
		$values['Paymentdate'] = $paycheck->paymentdate;
		$values['SalarycategoryID'] = $salarytype->salarycategoryID;
		$values['IncomeregistercodeID'] = $salarytype->incomeregistercodeID;
		$success = Table::addRow("payroll_paycheckrows", $values);
		
		
		// Päivitetään paycheckin yleiset rivit...
		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		$person = Table::loadRow('hr_workers', "WHERE WorkerID=" . $paycheck->workerID);
		$paycheckrows = Table::load('payroll_paycheckrows',"WHERE PaycheckID=" . $paycheckID . " AND SalarycategoryID=" . Collections::SALARYCATEGORY_SALARY);
		echo "<br>aaa";
		$salarytypes = Table::load('hr_salarytypes');
		echo "<br>bbb";
		$deductionpercents = $this->loadDeductionPercents($paycheck);
		$deductions = Table::load('payroll_deductions');
			
		
		$finalpaycheckrows = array();
		foreach($paycheckrows as $index => $paycheckrow) {
			$finalpaycheckrows[] = $paycheckrow;
		}
			
		$taxcard = $this->getTaxCardForDate($paycheck->workerID, $paycheck->paymentdate);
		$newpaycheckrows = $this->calculateDeductionsFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $taxcard);
		foreach($newpaycheckrows as $index => $paycheckrow) {
			$finalpaycheckrows[] = $paycheckrow;
		}
			
		$newpaycheckrows = $this->calculateExpensesFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		foreach($newpaycheckrows as $index => $paycheckrow) {
			$finalpaycheckrows[] = $paycheckrow;
		}
			
		$values = $this->calculatePaycheckTotals($paycheck, $finalpaycheckrows);
		$success = Table::updateRow("payroll_paychecks", $values, $paycheckID);
		
		redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID,null);
	}
	
	


	
	public function removepaycheckrowAction() {
		
		$comments = false;
		$rowID = $_GET['id'];
		$paycheckID = $_GET['paycheckID'];
		$success = Table::deleteRow("payroll_paycheckrows", $rowID, $comments);
		
		// TODO: update paycheck deductions (ja myös paycheck total yms. kentät)
		// TODO: mahdollisesti viennitkin joutuu päivittämään?
		
		if (!$comments) redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID,null);
	}
	
	


	public function removepaycheckAction() {
	
		$comments = true;
		$paycheckID = $_GET['paycheckID'];
		echo "<br>Removepaycheck - " . $paycheckID;

		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		
		
		// PaycheckID viitteitä saattaa olla receipts-taulusta, mikäli löytyy niin ei saisi poistaa
		//	- Tämä on ainakin hieman hankala, koska receipts-riveihin voi olla viitteitä useammastakin
		//	  paikasta varmaankin...
		$receipts = Table::load('accounting_receipts', "WHERE PaycheckID=" . $paycheckID);

		if ($paycheck->receiptID > 0) {
			if (count($receipts) > 0) {
				echo "<br>Palkkalaskelmalla on receiptID ja receipts-taulusta löytyy, ei voida poistaa";
				exit;
			} else {
				echo "<br>Palkkalaskelmalla on receiptID, mutta receipts-taulusta ei löytyy, virhetila? Ei voida poistaa";
				exit;
			}
		} else {
			if (count($receipts) > 0) {
				if ($comments) echo "<br>Palkkalaskelman receiptID = 0, mutta receipts-taulusta löytyi rivi";
				exit;
			} else {
				if ($comments) echo "<br>Palkkalaskelman receiptID = 0, eikä receipts-taulun rivejä, voidaan poistaa";
			}
		}
		
		// bankstatementrows
		$bankstatementrows = Table::load('accounting_bankstatementrows', "WHERE PaycheckID=" . $paycheckID);
		if (count($bankstatementrows) > 0) {
			echo "<br>Linkitetty bankstatementrow, ei voida poistaa (pitänee poistaa receipt)";
			exit;
		}
		
		
		// Poista viitteet paycheckrows-taulusta
		$success = Table::deleteRowsWhere("payroll_paycheckrows", "WHERE PaycheckID=" . $paycheckID, $comments);
		
		// Poista viitteet paychecks-taulusta
		$success = Table::deleteRow("payroll_paychecks", "WHERE PaycheckID=" . $paycheckID, $comments);
		
		if (!$comments) redirecttotal('payroll/payroll/showpayroll', null);
	}
	
	
	private function loadDeductionPercents($paycheck) {
		
		$deductionstemp = Table::load('payroll_deductionpercents', "WHERE Startdate<='" . $paycheck->paymentdate . "' AND Enddate>='" . $paycheck->paymentdate . "'");
		$deductions = array();
		foreach($deductionstemp as $index => $deduction) {
			if (isset($deductions[$deduction->deductionID])) {
				echo "<br>Multible deduction found for same paymentdate - " . $deduction->deductionID . " - " . $paycheck->paymentdate . " - " . $deductions[$deduction->deductionID]->rowID;
				exit;
			} else {
				$deductions[$deduction->deductionID] = $deduction;
			}
		}
		return $deductions;
	}
	

	

	public function updatepaycheckAction() {
	
		$paycheckID = $_GET['id'];
		$paycheck = Table::loadRow('payroll_paychecks', $paycheckID);
		
		$values = array();
		//$values['WorkerID'] = $_GET['workerID'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$values['Bookkeepingdate'] = $_GET['bookkeepingdate'];
		$values['Paymentdate'] = $_GET['paymentdate'];
		
		if (isset($_GET['workcontractID'])) {
			if ($paycheck->workcontractID != $_GET['workcontractID']) {
				$values['WorkcontractID'] = $_GET['workcontractID'];
				$workcontract = Table::loadRow('hr_workcontracts', $_GET['workcontractID']);
				$values['LabouragreementID'] = $workcontract->labouragreementID;
				$values['PensioninsurancetypeID'] = $workcontract->pensioninsurancetypeID;
			}
		}
		
		$this->registry->dimensions = Table::load('system_dimensions',' WHERE Usedinsales=1');
		if (count($this->registry->dimensions) > 0) {
			foreach($this->registry->dimensions as $index => $dimension) {
				$variable = "dimension" . $dimension->dimensionID;
				if (isset($_GET[$variable])) {
					if ($paycheck->$variable != $_GET[$variable]) {
						echo "<br>Dimensio muuttunut, päivitetään kaikkiin riveihin ja entryihin";
						$this->updatePaycheckDimension($paycheck, $dimension, $_GET[$variable]);
						$values["Dimension" . $dimension->dimensionID] = $_GET[$variable];
					}
				} else {
					echo "<br>Dimensiota ei tullut parametrina";
				}
			}
		}
		$success = Table::updateRow("payroll_paychecks", $values, $paycheckID);
		
		redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	}
	
	
	public function updatepaychecktotalsAction() {
		$paycheckID = $_GET['paycheckID'];
		
		$paycheck = Table::loadRow('payroll_paychecks', $paycheckID);
		if ($paycheck->state == Collections::PAYCHECKSTATE_OPEN) {
			
			echo "<br>Open ...";
			$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
			$person = Table::loadRow('hr_workers', "WHERE WorkerID=" . $paycheck->workerID);
			$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID . " AND (SalarycategoryID=" . Collections::SALARYCATEGORY_SALARY . " OR SalarycategoryID=" . Collections::SALARYCATEGORY_REPAYMENT . ")");
			$salarytypes = Table::load('hr_salarytypes');
			$deductionpercents = $this->loadDeductionPercents($paycheck);
			$deductions = Table::load('payroll_deductions');
			

			$finalpaycheckrows = array();
			foreach($paycheckrows as $index => $paycheckrow) {
				$finalpaycheckrows[] = $paycheckrow;
			}
			
			$taxcard = $this->getTaxCardForDate($paycheck->workerID, $paycheck->paymentdate);
			$newpaycheckrows = $this->calculateDeductionsFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $taxcard);
			foreach($newpaycheckrows as $index => $paycheckrow) {
				$finalpaycheckrows[] = $paycheckrow;
			}
			
			$newpaycheckrows = $this->calculateExpensesFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
			foreach($newpaycheckrows as $index => $paycheckrow) {
				$finalpaycheckrows[] = $paycheckrow;
			}
			
			$values = $this->calculatePaycheckTotals($paycheck, $finalpaycheckrows);
			$success = Table::updateRow("payroll_paychecks", $values, $paycheckID, false);
			
		} else {
			$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID);
			$values = $this->calculatePaycheckTotals($paycheck, $paycheckrows);
			$success = Table::updateRow("payroll_paychecks", $values, $paycheckID, false);
		}
	}
	

	public function updatedeductionsAction() {
	
		$paycheckID = $_GET['paycheckID'];
	
		$paycheck = Table::loadRow('payroll_paychecks', $paycheckID);
		$person = Table::loadRow('hr_workers', $paycheck->workerID);
		$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID . " AND SalarycategoryID=" . Collections::SALARYCATEGORY_SALARY);
		$salarytypes = Table::load('hr_salarytypes');
		$deductionpercents = $this->loadDeductionPercents($paycheck);
	
		$this->updateDeductions($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
	
	
		// Sitten lasketaan työnantajan sivukulujen maksut, näitä tarvitaan kirjanpitoon (ja tulorekisteri-ilmoon)
	
	}
	
	
	
	/**
	 * Tätä funktiota kutsutaan vanhemmilla palkkalaskelmilla, joille kulurivejä ei ole generoitu
	 * suoraan palkkalaskelmaan (viennit on ilmeisesti tehty aiemmin manuaalisesti). Ei näillä riveillä
	 * muuta virkaa ole kuin yhtenäisyyden vuoksi ja vientien uudelleengenerointiin.
	 * 
	 * Voisi nämä luoda automaattisestikkin.
	 * 
	 * Expenserowt pitää myös päivittää myös jos palkkarivit muuttuu 
	 * 
	 */
	public function updateexpenserowsAction() {
		
		$paycheckID = $_GET['paycheckID'];
		
		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		$person = Table::loadRow('hr_workers', $paycheck->workerID);
		$deductionpercents = $this->loadDeductionPercents($paycheck);
		$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID .  " AND SalarycategoryID=" . Collections::SALARYCATEGORY_SALARY);
		$salarytypes = Table::load('hr_salarytypes');
		
		$this->updateExpenses($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		
		// Redirect
	}
	
	


	public function updatepaycheckrowAction() {
	
		$paycheckrowID = $_GET['id'];
		$paycheckID = $_GET['paycheckID'];
	
		$values = array();
		$values['PaycheckID'] = $paycheckID;
		$values['SalarytypeID'] = $_GET['salarytypeID'];
		$values['Amount'] = $_GET['amount'];
		$values['Unitprice'] = $_GET['unitprice'];
		$values['Total'] = $_GET['total'];
		//$values['Paymentdate'] = $_GET['paymentdate'];
		$success = Table::updateRow("payroll_paycheckrows", $values, $paycheckrowID);
		
		redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	}
	
	
	
	
	
	
	private function updatePaycheckDimension($paycheck, $dimension, $dimensionvalueID) {
	
		$variable = 'dimension' . $dimension->dimensionID;
		$columname = 'Dimension' . $dimension->dimensionID;
		$values = array();
		$values[$columname] = $dimensionvalueID;
		$success = Table::updateRowsWhere("payroll_paycheckrows", $values, " WHERE PaycheckID=" . $paycheck->paycheckID);
		
		if ($paycheck->receiptID > 0) {
	
			$values = array();
			$values[$columname] = $dimensionvalueID;
			$success = Table::updateRow("accounting_receipts", $values, $paycheck->receiptID);
			$success = Table::updateRowsWhere("accounting_entries", $values, " WHERE ReceiptID=" .  $paycheck->receiptID);
		}
	}
	
	



	public function markaspaidAction() {
	
	
		$comments = false;
		$paycheckID = $_GET['paycheckID'];
		
		$values = array();
		$values['State'] = Collections::PAYCHECKSTATE_LINKED;
		$success = Table::updateRow("payroll_paychecks", $values, $paycheckID, $comments);
	
		redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	}
	
	
	

	public function returntoopenAction() {
		
		
		$comments = false;
		$paycheckID = $_GET['paycheckID'];
		
		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		if ($paycheck->receiptID > 0) {
			if ($comments) echo "<br><br>Deleting receipt - " . $receipt->receiptID;
			Table::deleteRowsWhere("accounting_entries", "WHERE ReceiptID=" . $paycheck->receiptID, $comments);
		}
		Table::deleteRowsWhere("payroll_paycheckrows", "WHERE PaycheckID=" . $paycheck->paycheckID . " AND (SalarycategoryID=" . Collections::SALARYCATEGORY_EXPENSE . " OR SalarycategoryID=" . Collections::SALARYCATEGORY_DEDUCTION . ")", $comments);
		
		$values = array();
		$values['State'] = 0;
		$success = Table::updateRow("payroll_paychecks", $values, $paycheckID, $comments);
		
		if (!$comments) redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	}
	
	


	public function uploadpaycheckAction() {
	
		$paycheckID = $_GET['id'];
		$systemID = $_SESSION['systemID'];
	
		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		
		if ($paycheck->receiptID == 0) {
			return null;
		}
		
		$receipt = Table::loadRow('accounting_receipts',$paycheck->receiptID);
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
		Table::updateRow("accounting_receipts", $values, $paycheck->receiptID);
	
		//$randi =  mt_rand(10000000,99999999);
	
		$result = $uploader->handleUpload($path, $name, false);
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	}
	
	

	public function removepaycheckattachmentAction() {
	
		echo "<br>Remove atachment";
		$paycheckID = $_GET['paycheckID'];
		$file = $_GET['file'];
		$systemID = $_SESSION['systemID'];
	
		if ($file == "") {
			echo "<br>Empty file";
			die();
		}
	
		echo "<br>paycheckID - " . $paycheckID;
		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		echo "<br>Receipt - " . $paycheck->receiptID;
		$receipt = Table::loadRow('accounting_receipts',$paycheck->receiptID);
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
				Table::updateRow("accounting_receipts", $values, $paycheck->receiptID);
				redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	
			} else {
				echo "<br>File unlink failed";
				die();
			}
		} else {
			//echo "<br>File not foud failed - " . $filename;
			$values = array();
			$values['Files'] = $filestr;
			Table::updateRow("accounting_receipts", $values, $paycheck->receiptID);
			redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
		}
	}
	
	

	public function downloadpaycheckAction() {
	
		$comments = false;
		$systemID = $_SESSION['systemID'];
			
		if (isset($_GET['paycheckID'])) {
			$paycheckID = $_GET['paycheckID'];
			$file = null;
			if (isset($_GET['id'])) {
				$file = $_GET['id'];
			}
		} else {
			$paycheckID = $_GET['id'];
			$file = $_GET['file'];
		}
	
		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		$receipt = Table::loadRow('accounting_receipts',$paycheck->receiptID);
		$year = substr($receipt->receiptdate, 0,4);
		$path = SAVEROOT . "bookkeeping-" . $systemID . "/" . $year . "/receipts/";
		//$path = SAVEROOT . "bookkeeping-" . $systemID . "/receipts/";
		if ($comments) {
			echo "<br>path - " . $path;
			echo "<br>file - " . $receipt->files;
		} else {
			if ($file ==  null) {
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename=" . $file);
				readfile($path . $receipt->files);
			} else {
				header("Content-type:application/pdf");
				header("Content-Disposition:inline;filename=" . $file);
				readfile($path . $file);
			}
		}
	}
	
	
	
	public function acceptpaycheckAction() {
	
		$paycheckID = $_GET['paycheckID'];

		$paycheck = Table::loadRow('payroll_paychecks',$paycheckID);
		$person = Table::loadRow('hr_workers', "WHERE WorkerID=" . $paycheck->workerID);
		$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheckID . " AND (SalarycategoryID=" . Collections::SALARYCATEGORY_SALARY . " OR SalarycategoryID=" . Collections::SALARYCATEGORY_REPAYMENT . ")");
		$salarytypes = Table::load('hr_salarytypes');
		$deductionpercents = $this->loadDeductionPercents($paycheck);
		$deductions = Table::load('payroll_deductions');
		
		$finalpaycheckrows = array();
		foreach($paycheckrows as $index => $paycheckrow) {
			$finalpaycheckrows[] = $paycheckrow;
		}
		$newpaycheckrows = $this->updateDeductions($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		foreach($newpaycheckrows as $index => $paycheckrow) {
			$finalpaycheckrows[] = $paycheckrow;
		}
		
		$newpaycheckrows = $this->updateExpenses($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		foreach($newpaycheckrows as $index => $paycheckrow) {
			$finalpaycheckrows[] = $paycheckrow;
		}
		
		$this->updateEntries($paycheck, $finalpaycheckrows, $salarytypes, $deductions);
		$this->updatePaycheckTotals($paycheck);
		$values = array();
		$values['State'] = 1;
		$success = Table::updateRow("payroll_paychecks", $values, $paycheckID, false);
		
		redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	}
	
	
	private function updatePaycheckTotals($paycheck, $comments = false) {
		
		$paycheckrows = Table::load('payroll_paycheckrows'," WHERE PaycheckID=" . $paycheck->paycheckID);
		//$comments = true;
		
		$salaryamount = 0;
		$deductionamount = 0;
		$sidecost = 0;
		
		foreach($paycheckrows as $index => $row) {
			if ($comments) echo "<br>Salary row  - " . $row->total . " - " . $row->salarycategoryID;
			
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_SALARY) {
				$salaryamount = $salaryamount + $row->total;
			}
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_EXPENSE) {
				$sidecost = $sidecost + $row->total;
			}
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_DEDUCTION) {
				$deductionamount = $deductionamount + $row->total;
			}
			
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_REPAYMENT) {
				if ($comments) echo "<br>Salary repayment - " . $row->total;
				$deductionamount = $deductionamount + -1 * $row->total;
			}
			
		}
		
		$values = array();
		$values['Netamount'] = $salaryamount - $deductionamount;
		$values['Grossamount'] = $salaryamount;
		$values['Sidecost'] = $sidecost;
		$values['Totalcost'] = $sidecost + $salaryamount;
		
		if ($comments) {
			echo "<br> --- deductionamount - " . $deductionamount;
			echo "<br> --- Netamount - " . $values['Netamount'];
			echo "<br> --- Grossamount - " . $values['Grossamount'];
			echo "<br> --- Sidecost - " . $values['Sidecost'];
			echo "<br> --- Totalcost - " . $values['Totalcost'];
		}
		
		$success = Table::updateRow("payroll_paychecks", $values, $paycheck->paycheckID, false);
		
	}
	
	
	private function calculatePaycheckTotals($paycheck, $paycheckrows) {

		$salaryamount = 0;
		$deductionamount = 0;
		$sidecost = 0;
		
		foreach($paycheckrows as $index => $row) {
			//echo "<br>Salary row  - " . $row->total . " - " . $row->salarycategoryID;
			
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_SALARY) {
				$salaryamount = $salaryamount + $row->total;
			}
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_EXPENSE) {
				$sidecost = $sidecost + $row->total;
			}
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_DEDUCTION) {
				$deductionamount = $deductionamount + $row->total;
			}
			
			if ($row->salarycategoryID == Collections::SALARYCATEGORY_REPAYMENT) {
				//echo "<br>Salary repayment - " . $row->total;
				$salaryamount = $salaryamount + $row->total;
			}
		}
		
		$values = array();
		$values['Netamount'] = $salaryamount - $deductionamount;
		$values['Grossamount'] = $salaryamount;
		$values['Sidecost'] = $sidecost;
		$values['Totalcost'] = $sidecost + $salaryamount;
		return $values;
	}

	
	private function updateDeductions($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {

		$comments = false;
		$taxcards = Table::load('hr_taxcards', "WHERE WorkerID=" . $paycheck->workerID . " AND Startdate<='" . $paycheck->paymentdate . "' AND Enddate>='" . $paycheck->paymentdate . "'", $comments);
		if ($taxcards == null) {
			echo "<br>Verokortti puuttuu";
			exit;
		}
		if (count($taxcards) != 1) {
			echo "<br>Verokorttejä väärä määrä - " . count($taxcards);
			exit;
		}
		$taxcard = null;
		foreach($taxcards as $index => $value) $taxcard = $value;
		if ($comments) echo "<br>Taxcard - " . $taxcard->taxcardID . ", p1=" . $taxcard->percent1 . ", p2=" . $taxcard->percent2;
		// Pitää ladata kaikki palkkarivit
	
		$deductions = $this->calculateDeductionsFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $taxcard);
		Table::deleteRowsWhere("payroll_paycheckrows", " WHERE PaycheckID=" . $paycheck->paycheckID . " AND SalarycategoryID=" . Collections::SALARYCATEGORY_DEDUCTION, $comments);
		foreach($deductions as $index => $deduction) {
			$values = array();
			$values['PaycheckID'] = $deduction->paycheckID;
			$values['SalarytypeID'] = $deduction->salarytypeID;
			$values['DeductionID'] = $deduction->deductionID;
			$values['Amount'] = $deduction->amount;
			$values['Unitprice'] = $deduction->unitprice;
			$values['Total'] = $deduction->total;
			$values['UnitID'] = $deduction->unitID;
			$values['Paymentdate'] = $deduction->paymentdate;
			$values['WorkerID'] = $deduction->workerID;
			$values['SystemID'] = $_SESSION['systemID'];
			$values['IncomeregistercodeID'] = $deduction->incomeregistercodeID;
			$values['SalarycategoryID'] = $deduction->salarycategoryID;
	
			$rowID = Table::addRow("payroll_paycheckrows", $values);
		}
		return $deductions;
	}
	
	
	private function updateExpenses($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {
	
		$comments = false;
		$expenserows = $this->calculateExpensesFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
	
		Table::deleteRowsWhere("payroll_paycheckrows", "WHERE PaycheckID=" . $paycheck->paycheckID . " AND SalarycategoryID=" . Collections::SALARYCATEGORY_EXPENSE, $comments);
		foreach($expenserows as $index => $expense) {
			$values = array();
			$values['PaycheckID'] = $expense->paycheckID;
			$values['SalarytypeID'] = $expense->salarytypeID;
			$values['DeductionID'] = $expense->deductionID;
			$values['Amount'] = $expense->amount;
			$values['Unitprice'] = $expense->unitprice;
			$values['Total'] = $expense->total;
			$values['UnitID'] = $expense->unitID;
			$values['Paymentdate'] = $expense->paymentdate;
			$values['WorkerID'] = $expense->workerID;
			$values['SystemID'] = $_SESSION['systemID'];
			$values['IncomeregistercodeID'] = $expense->incomeregistercodeID;
			$values['SalarycategoryID'] = $expense->salarycategoryID;
	
			$rowID = Table::addRow("payroll_paycheckrows", $values);
		}
		return $expenserows;
	}
	
	
	
	private function updateEntries($paycheck, $paycheckrows, $salarytypes, $deductions) {
		
		if ($paycheck->receiptID == 0) {

			$receiptsetID =  Settings::getSetting('accounting_payrollreceiptsetID', 0);
			$receiptnumber = $this->getNextReceiptNumber($receiptsetID);
			$values = array();
			$values['Receiptdate'] = $paycheck->bookkeepingdate;
			$values['Receiptnumber'] = $receiptnumber;
			$values['ReceiptsetID'] = $receiptsetID;
			$values['Explanation'] = "Palkkalaskelma " . $paycheck->paycheckID;
			$values['ReceiverID'] = 0;	// pitäisi asettaa asiakasyritys
			$values['CostpoolID'] = 0;	// Pitäisi asettaa jokin sopiva kustannuspaikka, myynti? Tämä on lähinnä ostoja varten
			$values['Grossamount'] = $paycheck->grossamount;
			$values['Netamount'] = $paycheck->netamount;
			$values['Accounted'] = 0;	// Tämä ehkä pitää päivittää entryistä
			$values['Paymentstatus'] = 0;
			$values['PurchaseID'] = 0;
			$values['PaycheckID'] = $paycheck->paycheckID;
			$receiptID = Table::addRow("accounting_receipts", $values, false);
			

			$values = array();
			$values['ReceiptID'] = $receiptID;
			$success = Table::updateRow("payroll_paychecks", $values, $paycheck->paycheckID, false);
				
			
			
		} else {
			$receiptID = $paycheck->receiptID;
		}
		
		$entries = $this->createEntriesFromPaycheckRows($paycheck, $paycheckrows, $salarytypes, $deductions);
		Table::deleteRowsWhere("accounting_entries", " WHERE ReceiptID=" . $receiptID);
		foreach($entries as $index => $entry) {
			$values = array();
			$values['ReceiptID'] = $receiptID;
			$values['AccountID'] = $entry->accountID;
			$values['Entrydate'] = $paycheck->bookkeepingdate;
			$values['Amount'] = $entry->amount;
			$values['VatcodeID'] = 0;
			
			//echo "<br>Update receipt - " . $entry->accountID . " - " . $entry->amount;
			
			$success = Table::addRow("accounting_entries", $values, false);
		}
		
	}
	


	public function updateentryAction() {
	
		$paycheckID = $_GET['paycheckID'];
		$receiptID = $_GET['receiptID'];
		$entryID = $_GET['entryID'];
		$values = array();
		$values['Entrydate'] = $_GET['entrydate'];
		$values['AccountID'] = $_GET['accountID'];
		$values['VatcodeID'] = $_GET['vatcodeID'];
		
	
		$debet = str_replace(",",".",$_GET['debet']);
		$credit = str_replace(",",".",$_GET['credit']);
	
		$amount = 0;
		if ($debet > $credit) $amount = $debet;
		else $amount = -1 * $credit;
	
		$values['Amount'] = $amount;
		
		$success = Table::updateRow('accounting_entries', $values, $entryID, true);
		//$this->updateReceiptAccounted($receiptID, $comments);
	
		redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID,null);
	}
	
	
	


	public function insertentryAction() {
	
		$comments = false;
	
		$paycheckID = $_GET['paycheckID'];
		
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
		if (!$comments) redirecttotal('payroll/payroll/showpaycheck&id=' . $paycheckID, null);
	}
	
	
	
	private function getYears($startyear) {
		$currentyear = intval(date("Y"));
		$startyear = intval($startyear);
		$currentyear++;
		//echo "<br>currentyear - " . $currentyear;
		//echo "<br>startyear - " . $startyear;
		
		$yearlist = array();
		$counter = 0;
		for($i = $startyear;$i<$currentyear;$i++) {
			$yearlist[$i] = $i;
			//echo "<br>year - " . $i;
			if ($counter > 10) break;
			$counter++;
		}
		return $yearlist;
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
	
	
	private function getTaxCardForDate($workerID, $paymentdate) {
	
		global $mysqli;
	
		$taxcards = Table::load('hr_taxcards', "WHERE WorkerID=" . $workerID . " AND Startdate<='" .$paymentdate . "' AND Enddate>='" . $paymentdate . "'", false);
		
		if (count($taxcards) == 0) {
			return null;
		}

		if (count($taxcards) > 1) {
			return null;
		}
		
		$currenttaxcard = null;
		foreach($taxcards as $index => $taxcard){
			$currenttaxcard = $taxcard;
		}
		return $currenttaxcard;
	}
	
	
	
	
	/**
	 * Tämä funktio laskee uudet vähennysrivit palkkalaskelmalle.
	 * 
	 * 
	 * @return multitype:Row
	 */
	private function calculateDeductionsFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $taxcard, $comments = false) {
	
		//$comments = true;
	
		if ($comments) echo "<br>---------------------------------- calculate DeductionsFromPaycheckRows";
		$deductionrows = array();
		
		
		// Lasketaan palkansaajan työeläkemaksu
		$deduction = $this->calculatePensionInsuranceDeduction($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $comments);
		//if ($comments) echo "<br>---- pensioninsurancefound - " . $deduction->amount;
		if ($deduction != null) $deductionrows[] = $deduction;
		
		$deduction = $this->calculateUnemploymentInsuranceDeduction($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $comments);
		if ($deduction != null) $deductionrows[] = $deduction;
		
		$witholdingtax = $this->calculateWitholdingTaxDeduction($taxcard, $paycheck, $paycheckrows, $salarytypes, $comments);
		if ($witholdingtax != null) $deductionrows[] = $witholdingtax;
		
		return $deductionrows;
	}
	
	

	// Eläkevakuutuksen laskenta
	private function calculatePensionInsuranceDeduction($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $comments = false) {
	
		//$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>get PensionInsuranceDeduction";
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>Työeläkevakuutusta ei tarvitse maksaa YEL-vakuutetulle";
			return null;
		}
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_TYEL) {
			$year = substr($person->identificationnumber,4,2);
			$month = intval(substr($person->identificationnumber,2,2));
			$divider  = substr($person->identificationnumber,6,1);
			if ($divider == "-") {
				$year = 1900 + intval($year);
			} else {
				$year = 2000 + intval($year);
			}
			if ($month == 12) {
				$year = $year+1;
				$month = 1;
			} else {
				$month = $month + 1;
			}
			if ($month < 10) $month = "0" . $month;
			$minyear = $year + 17;
			$mindate = $minyear . "-" . $month . "-01";
			if ($comments) echo "<br>paydate - '" . $paycheck->paymentdate . "'";
			if ($comments) echo "<br>mindate - '" . $mindate . "'";
			$selectedDeductionID = 0;
			if ($paycheck->paymentdate < $mindate) {
				if ($comments) echo "<br>Eläkevakuutusta ei tarvitse maksaa alle 17-vuotiaasta";
				return null;
			} else {
				$minyear = $year + 53;
				$mindate = $minyear . "-" . $month . "-01";
				if ($comments) echo "<br>mindate53 - '" . $mindate . "'";
				if ($paycheck->paymentdate < $mindate) {
					if ($comments) echo "<br>Työttömyysvakuutusmaksu 17-52v";
					if (!isset($deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_17_52])) {
						echo "<br>Deductionpercent  puuttuu x2 - " . PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_17_52 . " - missing";
						exit();
					}
					$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_17_52];
					$selectedDeductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_17_52;
				} else {
	
					$minyear = $year + 62;
					$mindate = $minyear . "-" . $month . "-01";
					if ($comments) echo "<br>mindate62 - '" . $mindate . "'";
					if ($paycheck->paymentdate < $mindate) {
						if ($comments) echo "<br>Työttömyysvakuutusmaksu 53-62v";
						if (!isset($deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_53_62])) {
							if ($comments) echo "<br>Deductionpercent " . PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_53_62 . " - missing";
							exit();
						}
						$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_53_62];
						$selectedDeductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_53_62;
					} else {
						$minyear = $year + 67;
						$mindate = $minyear . "-" . $month . "-01";
						if ($comments) echo "<br>mindate67 - '" . $mindate . "'";
						if ($paycheck->paymentdate < $mindate) {
							if ($comments) echo "<br>Työttömyysvakuutusmaksu 63-67v";
							if (!isset($deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_63_67])) {
								echo "<br>Deductionpercent puuttuu x3 -  " . PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_63_67 . " - missing";
								exit();
							}
							$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_63_67];
							$selectedDeductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYEE_PENSION_63_67;
						} else {
							if ($comments) echo "<br>Työttömyysvakuutusmaksua ei yli 67v";
						}
					}
				}
			}
				
				
				
				
			if ($deductionpercent == null) return null;
				
			if ($deductionpercent->percent > 0) {
				$sum = $this->getPensionInsuranceBaseSum($paycheckrows, $salarytypes);
				if ($comments) echo "<br>Sum - " . $sum;
				
				// TODO: Tämä alaraja on ilmeisesti pelkästään tilapäisillä työnantajilla ja kun summa
				//       on alle 9006e per 6kk.... eli tämän alarajan käyttö on harvinaista, ja pitäisi
				//       varmaan ehto koodata jonnekkin muualle.
				// https://www.yrittajat.fi/tyonantajalle/tyosuhde/palkka-ja-muut-korvaukset/tyonantajamaksut-2022/
				
				if ($sum < 62.88) {
					//echo "<br>eplyee sum low - " . $sum;
					if ($paycheck->pensioninsurancetypeID == 0) {
						return null;
					}
				}
				
				
				$pensioninsuranceamount = round($sum * $deductionpercent->percent / 100,2);
				if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
				if ($comments) echo "<br>getPensionInsuranceBaseSum - " . $pensioninsuranceamount;
				if ($pensioninsuranceamount > 0) {
					$row = new Row();
					$row->rowID = '-';
					$row->paycheckID = $paycheck->paycheckID;
					$row->total = $pensioninsuranceamount;
					$row->amount = $sum;
					$row->unitprice = $deductionpercent->percent;
					$row->unitID = 6;				// PERCENT
					$row->workerID = $paycheck->workerID;
					$row->paymentdate= $paycheck->paymentdate;
					$row->salarytypeID = 0;
					$row->salarycategoryID = Collections::SALARYCATEGORY_DEDUCTION;
					$row->deductionID = $selectedDeductionID;
					$row->incomeregistercodeID = 413;	// Työntekijän työeläkemaksu
					return $row;
				}
			} else {
				if ($comments) echo "<br>Työeeläkevakuutus prosentti nolla ";
				return null;
			}
		}
	
		if ($comments) echo "<br>Tuntematon eläkevakuutus - " . $paycheck->pensioninsurancetypeID;
		return null;
	}
	
	

	private function calculateWitholdingTaxDeduction($taxcard, $paycheck, $paycheckrows, $salarytypes, $comments) {
	
		//$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>get WitholdingTaxDeduction";
	
		$paychecksum = $this->getWitholdingTaxDeductionBaseSum($paycheckrows,$salarytypes);
		
		$paymentyear = substr($paycheck->paymentdate,0,4);
		if ($comments) echo "<br>paymentdate - " . $paycheck->paymentdate;
		if ($taxcard->startdate < $paycheck->paymentdate) {
			$alldate = $paycheck->paymentdate;
		} else {
			$alldate = $taxcard->startdate;
		}
		$allpaycheckrows = Table::load('payroll_paycheckrows'," WHERE WorkerID=" . $paycheck->workerID . " AND Paymentdate BETWEEN '" . $alldate  . "' AND '" . $paycheck->paymentdate . "' AND PaycheckID!=" . $paycheck->paycheckID, $comments);
		$sumstartyear = $this->getWitholdingTaxDeductionBaseSum($allpaycheckrows,$salarytypes);
		$taxsum = 0;
		$unitprice = null;
		if ($comments) echo "<br> - taxbasesum = " . $paychecksum;
		if ($comments) echo "<br> - taxbasesum from the start of the year = " . $sumstartyear;
			
		if (($paychecksum + $sumstartyear) < $taxcard->taxlimit) {
			if ($comments) echo "<br> - under taxlimit, percent1 = " . $taxcard->percent1;
			$taxsum = round($paychecksum * ($taxcard->percent1 / 100),2);
			$unitprice = $taxcard->percent1;
		} else {
			if ($sumstartyear > $taxcard->taxlimit) {
				if ($comments) echo "<br> - over taxlimit, percent2 = " . $taxcard->percent2;
				$taxsum = round($paychecksum * ($taxcard->percent2 / 100),2);
				$unitprice = $taxcard->percent2;
			} else {
				// nykyinen palkka ylittää juuri taxlimitin
				if ($comments) echo "<br> - between taxlimit, percent2 = " . $taxcard->percent2;
				$oversum = ($paychecksum+$sumstartyear) - $taxcard->taxlimit;
				if ($comments) echo "<br> - over amount = " . $oversum;
				$lowsum = $paychecksum - $oversum;
				if ($comments) echo "<br> -- low amount = " . $lowsum;
				$taxsum = round($lowsum * ($taxcard->percent1 / 100) + $oversum * ($taxcard->percent2 / 100),2);
				$unitprice = $taxsum / $paychecksum * 100;
				if ($comments) echo "<br>Taxsum - " . $taxsum;
			}
		}
		if ($comments) echo "<br> - witholding tax = " . $taxsum;
	
		if ($taxsum > 0) {
			$row = new Row();
			$row->rowID = "-";
			$row->paycheckID = $paycheck->paycheckID;
			$row->total = $taxsum;
			$row->amount = $paychecksum;
			$row->unitprice = $unitprice;
			$row->unitID = 6;				// PERCENT
			$row->workerID = $paycheck->workerID;
			$row->paymentdate= $paycheck->paymentdate;
			$row->salarytypeID = 0;
			$row->salarycategoryID = 4;
			$row->deductionID = PayrollModule::DEDUCTIONTYPE_WITHOLDINGTAX;
			$row->incomeregistercodeID = 402;	// Ennakonpidätys
			return $row;
		}
		return null;
	}
	
	
	
	
	private function getEmployerPensionInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {
	
		$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>employer get PensionInsuranceDeduction";
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>Työeläkevakuutusta ei tarvitse maksaa YEL-vakuutetulle";
			return null;
		}
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_TYEL) {
			$year = substr($person->identificationnumber,4,2);
			$month = intval(substr($person->identificationnumber,2,2));
			$divider  = substr($person->identificationnumber,6,1);
			if ($divider == "-") {
				$year = 1900 + intval($year);
			} else {
				$year = 2000 + intval($year);
			}
			if ($month == 12) {
				$year = $year+1;
				$month = 1;
			} else {
				$month = $month + 1;
			}
			if ($month < 10) $month = "0" . $month;
			$minyear = $year + 17;
			$mindate = $minyear . "-" . $month . "-01";
			if ($comments) echo "<br>mindate - '" . $mindate . "'";
			$selectedDeductionID = 0;
			if ($paycheck->paymentdate < $mindate) {
				if ($comments) echo "<br>Eläkevakuutusta ei tarvitse maksaa alle 17-vuotiaasta";
				return null;
			} else {
				$minyear = $year + 53;
				$mindate = $minyear . "-" . $month . "-01";
				if ($comments) echo "<br>mindate53 - '" . $mindate . "'";
				if ($paycheck->paymentdate < $mindate) {
					if ($comments) echo "<br>Työttömyysvakuutusmaksu 17-52v";
					if (!isset($deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_17_52])) {
						echo "<br>Deductionpercent puuttuu x1 - " . PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_17_52 . " - missing";
						exit();
					}
					$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_17_52];
					$selectedDeductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_17_52;
				} else {
	
					$minyear = $year + 62;
					$mindate = $minyear . "-" . $month . "-01";
					if ($comments) echo "<br>mindate62 - '" . $mindate . "'";
					if ($paycheck->paymentdate < $mindate) {
						if ($comments) echo "<br>Työttömyysvakuutusmaksu 53-62v";
						if (!isset($deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_53_62])) {
							if ($comments) echo "<br>Deductionpercent puuttuu x5 - " . PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_53_62 . " - missing";
							exit();
						}
						$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_53_62];
						$selectedDeductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_53_62;
					} else {
						$minyear = $year + 67;
						$mindate = $minyear . "-" . $month . "-01";
						if ($comments) echo "<br>mindate67 - '" . $mindate . "'";
						if ($paycheck->paymentdate < $mindate) {
							if ($comments) echo "<br>Työttömyysvakuutusmaksu 63-67v";
							if (!isset($deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_63_67])) {
								echo "<br>Deductionpercent puuttuu x6 - " . PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_63_67 . " - missing";
								exit();
							}
							$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_63_67];
							$selectedDeductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_PENSION_63_67;
						} else {
							if ($comments) echo "<br>Työttömyysvakuutusmaksua ei yli 67v";
						}
					}
				}
			}
	
	
	
	
			if ($deductionpercent == null) return null;
	
			if ($deductionpercent->percent > 0) {
				$sum = $this->getPensionInsuranceBaseSum($paycheckrows, $salarytypes);
				if ($comments) echo "<br>Sum - " . $sum;
				
				// TODO: Tämä alaraja on ilmeisesti pelkästään tilapäisillä työnantajilla ja kun summa
				//       on alle 9006e per 6kk.... eli tämän alarajan käyttö on harvinaista, ja pitäisi
				//       varmaan ehto koodata jonnekkin muualle.
				// https://www.yrittajat.fi/tyonantajalle/tyosuhde/palkka-ja-muut-korvaukset/tyonantajamaksut-2022/
				//echo "<br>Basesum - " . $sum;
				if ($sum < 62.88) {
					//echo "<br>Basesum low - " . $sum;
					if ($paycheck->pensioninsurancetypeID == 0) {
						return null;
					}
				}
				
				$pensioninsuranceamount = round($sum * $deductionpercent->percent / 100,2);
				if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
				if ($comments) echo "<br>getPensionInsuranceBaseSum - " . $pensioninsuranceamount;
				if ($pensioninsuranceamount > 0) {
					$row = new Row();
					$row->rowID = '-';
					$row->paycheckID = $paycheck->paycheckID;
					$row->total = $pensioninsuranceamount;
					$row->amount = $sum;
					$row->unitprice = $deductionpercent->percent;
					$row->unitID = 6;				// PERCENT
					$row->workerID = $paycheck->workerID;
					$row->paymentdate= $paycheck->paymentdate;
					$row->salarytypeID = 0;
					$row->salarycategoryID = Collections::SALARYCATEGORY_EXPENSE;
					$row->deductionID = $selectedDeductionID;
					$row->incomeregistercodeID = 413;	// Työntekijän työeläkemaksu
					return $row;
				}
			} else {
				if ($comments) echo "<br>Työeeläkevakuutus prosentti nolla ";
				return null;
			}
		}
	
		if ($comments) echo "<br>Tuntematon eläkevakuutus - " . $paycheck->pensioninsurancetypeID;
		return null;
	}
	
	
	
	
	
	// Työttömyyseläkevakuutus laskenta
	private function calculateUnemploymentInsuranceDeduction($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $comments) {
	
		//$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>get UnemploymentInsuranceDeduction";
	
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>Työttömyysvakuutusmaksua ei tarvitse maksaa YEL-vakuutetulle";
			return null;
		}
	
	
		if ($comments) echo "<br>Person sotu - " . $person->identificationnumber;
		if ($comments) echo "<br>Paymentdate - " . $paycheck->paymentdate;
	
		$year = substr($person->identificationnumber,4,2);
		$month = intval(substr($person->identificationnumber,2,2));
		$divider  = substr($person->identificationnumber,6,1);
		if ($divider == "-") {
			$year = 1900 + intval($year);
		} else {
			$year = 2000 + intval($year);
		}
		if ($month == 12) {
			$year = $year+1;
			$month = 1;
		} else {
			$month = $month + 1;
		}
		$maxyear = $year + 65;
		if ($comments) echo "<br>year - '" . $maxyear . "'";
		if ($comments) echo "<br>month - '" . $month . "'";
		if ($comments) echo "<br>divider - '" . $divider . "'";
		if ($month < 10) $month = "0" . $month;
		$maxdate = $maxyear . "-" . $month . "-01";
		if ($comments) echo "<br>Työttömyysvakuutusmax - " . $maxdate;
	
		$minyear = $year + 17;
		$mindate = $minyear . "-" . $month . "-01";
	
		if ($comments) echo "<br>Työttömyysvakuutusmin - " . $mindate;
	
		if (($paycheck->paymentdate > $maxdate) || ($paycheck->paymentdate < $mindate)) {
			if ($comments) echo "<br>Työttömyysvakuutusmaksua ei tarvitse maksaa";
			if ($paycheck->paymentdate > $maxdate) {
				if ($comments) echo " - ei peritä yli 65-vuotiailta";
			}
			if ($paycheck->paymentdate < $mindate) {
				if ($comments) echo " - ei peritä alle 17-vuotiailta";
			}
		} else {
			if ($comments) echo "<br>Työttömyysvakuutusmaksu pitää maksaa";
			$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYEE_UNEMPLOYMENT];
			if ($deductionpercent == null) {
				echo "<br> - Työttömyysvakuutusmaksuprosenttia ei löytynyt";
				exit;
			} else {
				if ($comments) echo "<br> - prosentti = " . $deductionpercent->percent;
	
				if ($deductionpercent->percent > 0) {
					$sum = $this->getUnemploymentInsuranceBaseSum($paycheckrows, $salarytypes);
					if ($sum > 0) {
						if ($comments) echo "<br>Sum - " . $sum;
						$unemploymentinsuranceamount = round($sum * $deductionpercent->percent / 100,2);
						if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
						if ($comments) echo "<br>getUnemploymentInsuranceBaseSum - " . $unemploymentinsuranceamount;
	
						if ($unemploymentinsuranceamount > 0) {
							$row = new Row();
							$row->rowID = '-';
							$row->paycheckID = $paycheck->paycheckID;
							$row->total = $unemploymentinsuranceamount;
							$row->amount = $sum;
							$row->unitprice = $deductionpercent->percent;
							$row->unitID = 6;
							$row->workerID = $paycheck->workerID;
							$row->paymentdate= $paycheck->paymentdate;
							$row->salarytypeID = 0;
							$row->salarycategoryID = 4;
							$row->deductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYEE_UNEMPLOYMENT;
							$row->incomeregistercodeID = 414;
							return $row;
						}
					}
				}
			}
		}
		return null;
	}
	
	
	
	
	private function getWitholdingTaxDeductionBaseSum($paycheckrows, $salarytypes) {
		$sum = 0;
		foreach($paycheckrows as $index => $row) {
			if ($row->salarytypeID > 0) {
				$salarytype = $salarytypes[$row->salarytypeID];
				if ($salarytype->salarycategoryID == 1) {
					if ($salarytype->witholdingtax == 1) {
						$sum = $sum + $row->total;
						//echo "<br> - tax summaus - " .  $row->total;
					} else {
						//echo "<br> - no witholding - rowID - " . $row->rowID . ", salarycategoryID=" . $row->salarycategoryID . ", salarytypeID=" . $row->salarytypeID;
					}
				}
			}
		}
		return $sum;
	}
	
	
	
	private function getPensionInsuranceBaseSum($paycheckrows, $salarytypes) {
		$sum = 0;
		foreach($paycheckrows as $index => $row) {
			if ($row->salarytypeID > 0) {
				$salarytype = $salarytypes[$row->salarytypeID];
				if ($salarytype->pensioninsurance == 1) {
					$sum = $sum + $row->total;
				}
			}
		}
		return $sum;
	}
	
	
	
	private function getUnemploymentInsuranceBaseSum($paycheckrows, $salarytypes) {
		$sum = 0;
		foreach($paycheckrows as $index => $row) {
			if ($row->salarytypeID > 0) {
				$salarytype = $salarytypes[$row->salarytypeID];
				if ($salarytype->unemploymentinsurance == 1) {
					$sum = $sum + $row->total;
				}
			}
		}
		return $sum;
	}
	
	
	
	private function getSicknessInsuranceBaseSum($paycheckrows, $salarytypes) {
		$sum = 0;
		foreach($paycheckrows as $index => $row) {
			if ($row->salarytypeID > 0) {
				$salarytype = $salarytypes[$row->salarytypeID];
				if ($salarytype->sicknessinsurance == 1) {
					$sum = $sum + $row->total;
				}
			}
		}
		return $sum;
	}
	
	
	

	private function getVacationReservationBaseSum($paycheckrows, $salarytypes) {
		$sum = 0;
		foreach($paycheckrows as $index => $row) {
			if ($row->salarytypeID > 0) {
				$salarytype = $salarytypes[$row->salarytypeID];
				if ($salarytype->vacationaccumulation == 1) {
					$sum = $sum + $row->total;
				}
			}
		}
		return $sum;
	}
	
	
	
	
	private function getAccidentInsuranceBaseSum($paycheckrows, $salarytypes) {
		$sum = 0;
		foreach($paycheckrows as $index => $row) {
			$salarytype = $salarytypes[$row->salarytypeID];
			if ($salarytype->accidentinsurance == 1) {
				$sum = $sum + $row->total;
			}
		}
		return $sum;
	}
	
	
	
	
	
	private function calculateExpensesFromPaycheckRows($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {

		$expenserows = array();
		
		// Työnantajan työeläkemaksu, TyEL
		$expense = $this->getEmployerPensionInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		if ($expense != null) $expenserows[] = $expense;
		
		// Työnantajan työttömyysvakuutusmaksu
		$expense = $this->getEmployerUnemploymentInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		if ($expense != null) $expenserows[] = $expense;
		
		// Työantajan sairasvakuutusmaksu (vanha sotumaksu)
		$expense = $this->getEmployerSicknessInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		if ($expense != null) $expenserows[] = $expense;
		
		// Työnantajan tapaturmavakuutus
		$expense = $this->getEmployerAccidentInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		if ($expense != null) $expenserows[] = $expense;
		
		// Työnantajan ryhmähenkivakuutus
		$expense = $this->getEmployerLifeInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		if ($expense != null) $expenserows[] = $expense;
		
		// Työnantajan lomapalkkavaraus
		$expense = $this->getEmployerVacationSalaryReservation($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents);
		if ($expense != null) $expenserows[] = $expense;
		
		return $expenserows;
	}
	
	


	private function getEmployerUnemploymentInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {
	
		$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>getEmployerUnemploymentInsuranceExpense";
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>Työeläkevakuutusta ei tarvitse maksaa YEL-vakuutetulle";
			return null;
		}
	
	
		if ($comments) echo "<br>Person sotu - " . $person->identificationnumber;
		if ($comments) echo "<br>Paymentdate - " . $paycheck->paymentdate;
	
		$year = substr($person->identificationnumber,4,2);
		$month = intval(substr($person->identificationnumber,2,2));
		$divider  = substr($person->identificationnumber,6,1);
		if ($divider == "-") {
			$year = 1900 + intval($year);
		} else {
			$year = 2000 + intval($year);
		}
		if ($month == 12) {
			$year = $year+1;
			$month = 1;
		} else {
			$month = $month + 1;
		}
		$maxyear = $year + 65;
		if ($comments) echo "<br>year - '" . $maxyear . "'";
		if ($comments) echo "<br>month - '" . $month . "'";
		if ($comments) echo "<br>divider - '" . $divider . "'";
		if ($month < 10) $month = "0" . $month;
		$maxdate = $maxyear . "-" . $month . "-01";
		if ($comments) echo "<br>Työttömyysvakuutusmax - " . $maxdate;
	
		$minyear = $year + 17;
		$mindate = $minyear . "-" . $month . "-01";
	
		if ($comments) echo "<br>Työttömyysvakuutusmin - " . $mindate;
	
		if (($paycheck->paymentdate > $maxdate) || ($paycheck->paymentdate < $mindate)) {
			if ($comments) echo "<br>Työttömyysvakuutusmaksua ei tarvitse maksaa";
			if ($paycheck->paymentdate > $maxdate) {
				if ($comments) echo " - ei peritä yli 65-vuotiailta";
			}
			if ($paycheck->paymentdate < $mindate) {
				if ($comments) echo " - ei peritä alle 17-vuotiailta";
			}
		} else {
			if ($comments) echo "<br>Työttömyysvakuutusmaksu pitää maksaa";
			$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_UNEMPLOYMENT];
			if ($deductionpercent == null) {
				echo "<br> - Työttömyysvakuutusmaksuprosenttia ei löytynyt";
				exit;
			} else {
				if ($comments) echo "<br> - prosentti = " . $deductionpercent->percent;
	
				if ($deductionpercent->percent > 0) {
					$sum = $this->getUnemploymentInsuranceBaseSum($paycheckrows, $salarytypes);
					if ($sum > 0) {
						if ($comments) echo "<br>Sum - " . $sum;
						$unemploymentinsuranceamount = round($sum * $deductionpercent->percent / 100,2);
						if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
						if ($comments) echo "<br>getUnemploymentInsuranceBaseSum - " . $unemploymentinsuranceamount;
	
						if ($unemploymentinsuranceamount > 0) {
							$row = new Row();
							$row->rowID = '-';
							$row->paycheckID = $paycheck->paycheckID;
							$row->total = $unemploymentinsuranceamount;
							$row->amount = $sum;
							$row->unitprice = $deductionpercent->percent;
							$row->unitID = 6;
							$row->workerID = $paycheck->workerID;
							$row->paymentdate= $paycheck->paymentdate;
							$row->salarytypeID = 0;
							$row->salarycategoryID = Collections::SALARYCATEGORY_EXPENSE;
							$row->deductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_UNEMPLOYMENT;
							$row->incomeregistercodeID = 0;
							return $row;
						}
					}
				}
			}
		}
		return null;
	}
	
	
	
	
	private function getEmployerSicknessInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents, $comments = false) {
	
		//$comments = true;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>getEmployerSicknessInsuranceExpense";
	
		/*
			if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			echo "<br>Työeläkevakuutusta ei tarvitse maksaa YEL-vakuutetulle";
			return null;
			}
			*/
	
	
		if ($comments) echo "<br>Person sotu - " . $person->identificationnumber;
		if ($comments) echo "<br>Paymentdate - " . $paycheck->paymentdate;
	
		$year = substr($person->identificationnumber,4,2);
		$month = intval(substr($person->identificationnumber,2,2));
		$divider  = substr($person->identificationnumber,6,1);
		if ($divider == "-") {
			$year = 1900 + intval($year);
		} else {
			$year = 2000 + intval($year);
		}
		if ($month == 12) {
			$year = $year+1;
			$month = 1;
		} else {
			$month = $month + 1;
		}
		$maxyear = $year + 67;
		if ($comments) echo "<br>year - '" . $maxyear . "'";
		if ($comments) echo "<br>month - '" . $month . "'";
		if ($comments) echo "<br>divider - '" . $divider . "'";
		if ($month < 10) $month = "0" . $month;
		$maxdate = $maxyear . "-" . $month . "-01";
		if ($comments) echo "<br>Työttömyysvakuutusmax - " . $maxdate;
	
		$minyear = $year + 16;
		$mindate = $minyear . "-" . $month . "-01";
	
		if ($comments) echo "<br>sairasvakuutusmin - " . $mindate;
	
		if (($paycheck->paymentdate > $maxdate) || ($paycheck->paymentdate < $mindate)) {
			if ($comments) echo "<br>sairasvakuutus ei tarvitse maksaa";
			if ($paycheck->paymentdate > $maxdate) {
				if ($comments) echo " - ei peritä yli 67-vuotiailta";
			}
			if ($paycheck->paymentdate < $mindate) {
				if ($comments) echo " - ei peritä alle 16-vuotiailta";
			}
		} else {
			if ($comments) echo "<br>sairasvakuutus pitää maksaa - " . PayrollModule::DEDUCTIONTYPE_EMPLOYER_SICKNESSINSURANCE;
			$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_SICKNESSINSURANCE];
			if ($deductionpercent == null) {
				echo "<br> - sairasvakuutus ei löytynyt";
				exit;
			} else {
				if ($comments) echo "<br> - prosentti = " . $deductionpercent->percent;
	
				if ($deductionpercent->percent > 0) {
					$sum = $this->getSicknessInsuranceBaseSum($paycheckrows, $salarytypes);
					if ($comments) echo "<br>Base Sum - " . $sum;
					if ($sum > 0) {
						if ($comments) echo "<br>Sum - " . $sum;
						$sicknessinsuranceamount = round($sum * $deductionpercent->percent / 100,2);
						if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
						if ($comments) echo "<br>getSicknessInsuranceBaseSum - " . $sicknessinsuranceamount;
	
						if ($sicknessinsuranceamount > 0) {
							$row = new Row();
							$row->rowID = '-';
							$row->paycheckID = $paycheck->paycheckID;
							$row->total = $sicknessinsuranceamount;
							$row->amount = $sum;
							$row->unitprice = $deductionpercent->percent;
							$row->unitID = 6;
							$row->workerID = $paycheck->workerID;
							$row->paymentdate= $paycheck->paymentdate;
							$row->salarytypeID = 0;
							$row->salarycategoryID = Collections::SALARYCATEGORY_EXPENSE;
							$row->deductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_SICKNESSINSURANCE;
							$row->incomeregistercodeID = 0;
							return $row;
						}
					}
				}
			}
		}
		return null;
	}
	
	
	private function getEmployerAccidentInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {
	
		$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>getEmployerAccidentInsuranceExpense";
		if ($comments) echo "<br>Paymentdate - " . $paycheck->paymentdate;
		if ($comments) echo "<br>AccidentInsuranceExpense pitää maksaa";
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>AccidentInsuranceExpense ei tarvitse maksaa YEL-vakuutetulle";
			if ($comments) echo "<br>Tähän tehdään oma valinta vakuutuksiin...";
			return null;
		}
	
	
		$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_ACCIDENTINSURANCE];
		if ($deductionpercent == null) {
			echo "<br> - sairasvakuutus ei löytynyt";
			exit;
		} else {
			if ($comments) echo "<br> - prosentti = " . $deductionpercent->percent;
			if ($deductionpercent->percent > 0) {
				$sum = $this->getSicknessInsuranceBaseSum($paycheckrows, $salarytypes);
				if ($sum > 0) {
					if ($comments) echo "<br>Sum - " . $sum;
					$sicknessinsuranceamount = round($sum * $deductionpercent->percent / 100,2);
					if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
					if ($comments) echo "<br>getSicknessInsuranceBaseSum - " . $sicknessinsuranceamount;
					if ($sicknessinsuranceamount > 0) {
						$row = new Row();
						$row->rowID = '-';
						$row->paycheckID = $paycheck->paycheckID;
						$row->total = $sicknessinsuranceamount;
						$row->amount = $sum;
						$row->unitprice = $deductionpercent->percent;
						$row->unitID = 6;
						$row->workerID = $paycheck->workerID;
						$row->paymentdate= $paycheck->paymentdate;
						$row->salarytypeID = 0;
						$row->salarycategoryID = Collections::SALARYCATEGORY_EXPENSE;
						$row->deductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_ACCIDENTINSURANCE;
						$row->incomeregistercodeID = 0;
						return $row;
					}
				}
			}
		}
		return null;
	}
	
	
	
	private function getEmployerLifeInsuranceExpense($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {
	
		$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>getEmployerLifeInsuranceExpense";
		if ($comments) echo "<br>Paymentdate - " . $paycheck->paymentdate;
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>AccidentInsuranceExpense ei tarvitse maksaa YEL-vakuutetulle";
			if ($comments) echo "<br>Tähän tehdään oma valinta vakuutuksiin...";
			return null;
		}
	
		if ($comments) echo "<br>LifeInsuranceExpense pitää maksaa";
	
		$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_LIFEINSURANCE];
		if ($deductionpercent == null) {
			echo "<br> - lifeinsurance ei löytynyt";
			exit;
		} else {
			if ($comments) echo "<br> - prosentti = " . $deductionpercent->percent;
			if ($deductionpercent->percent > 0) {
				$sum = $this->getSicknessInsuranceBaseSum($paycheckrows, $salarytypes);
				if ($sum > 0) {
					if ($comments) echo "<br>Sum - " . $sum;
					$sicknessinsuranceamount = round($sum * $deductionpercent->percent / 100,2);
					if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
					if ($comments) echo "<br>getSicknessInsuranceBaseSum - " . $sicknessinsuranceamount;
					if ($sicknessinsuranceamount > 0) {
						$row = new Row();
						$row->rowID = '-';
						$row->paycheckID = $paycheck->paycheckID;
						$row->total = $sicknessinsuranceamount;
						$row->amount = $sum;
						$row->unitprice = $deductionpercent->percent;
						$row->unitID = 6;
						$row->workerID = $paycheck->workerID;
						$row->paymentdate= $paycheck->paymentdate;
						$row->salarytypeID = 0;
						$row->salarycategoryID = Collections::SALARYCATEGORY_EXPENSE;
						$row->deductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_LIFEINSURANCE;
						$row->incomeregistercodeID = 0;
						return $row;
					}
				}
			}
		}
		return null;
	}
	
	
	private function getEmployerVacationSalaryReservation($paycheck, $person, $paycheckrows, $salarytypes, $deductionpercents) {
	
		$comments = false;
		if ($comments) echo "<br>--------------------------------------------------------";
		if ($comments) echo "<br>getEmployerVacationSalaryReservation";
		if ($comments) echo "<br>Paymentdate - " . $paycheck->paymentdate;
	
		if ($paycheck->pensioninsurancetypeID == Collections::PENSIONINSURANCETYPE_YEL) {
			if ($comments) echo "<br>VacationSalaryReservation ei tarvitse maksaa YEL-vakuutetulle";
			if ($comments) echo "<br>Tähän tehdään oma valinta vakuutuksiin...";
			return null;
		}
	
		if ($comments) echo "<br>VacationSalaryReservation luodaan";
	
		$deductionpercent = $deductionpercents[PayrollModule::DEDUCTIONTYPE_EMPLOYER_VACATIONSALARYRESERVATION];
		if ($deductionpercent == null) {
			echo "<br> - VacationSalaryReservation ei löytynyt";
			exit;
		} else {
			if ($comments) echo "<br> - prosentti = " . $deductionpercent->percent;
			if ($deductionpercent->percent > 0) {
				$sum = $this->getVacationReservationBaseSum($paycheckrows, $salarytypes);
				if ($sum > 0) {
					if ($comments) echo "<br>Sum - " . $sum;
					$sicknessinsuranceamount = round($sum * $deductionpercent->percent / 100,2);
					if ($comments) echo "<br>Percent - " . $deductionpercent->percent;
					if ($comments) echo "<br>getSicknessInsuranceBaseSum - " . $sicknessinsuranceamount;
					if ($sicknessinsuranceamount > 0) {
						$row = new Row();
						$row->rowID = '-';
						$row->paycheckID = $paycheck->paycheckID;
						$row->total = $sicknessinsuranceamount;
						$row->amount = $sum;
						$row->unitprice = $deductionpercent->percent;
						$row->unitID = 6;
						$row->workerID = $paycheck->workerID;
						$row->paymentdate= $paycheck->paymentdate;
						$row->salarytypeID = 0;
						$row->salarycategoryID = Collections::SALARYCATEGORY_EXPENSE;
						$row->deductionID = PayrollModule::DEDUCTIONTYPE_EMPLOYER_VACATIONSALARYRESERVATION;
						$row->incomeregistercodeID = 0;
						return $row;
					}
				}
			}
		}
		return null;
	}
	
	
	
	
	
	
	private function createEntriesFromPaycheckRows($paycheck, $paycheckrows, $salarytypes, $deductions, $collapse = true) {
	
		$comments = false;
		if ($comments) echo "<br>---------------------------------- create EntriesFromPaycheckRows";
	
		$entries = array();
		foreach($paycheckrows as $index => $paycheckrow) {
			
			if ($paycheckrow->salarytypeID == 0) {
				
				$deduction = $deductions[$paycheckrow->deductionID];
				if ($comments) echo "<br>deduction - " . $paycheckrow->total;
				
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $paycheck->paymentdate;
				$entry->accountID = $deduction->expenceaccountID;;
				$entry->amount = $paycheckrow->total;
				$entry->vatcodeID = 0;
				$entries[] = $entry;
				
				$entry = new Row();
				$entry->entryID = 0;
				$entry->entrydate = $paycheck->paymentdate;
				$entry->accountID = $deduction->deptaccountID;
				$entry->amount = -1 * $paycheckrow->total;
				$entry->vatcodeID = 0;
				$entries[] = $entry;
				
			} else {
				$salarytype = $salarytypes[$paycheckrow->salarytypeID];
				
				if ($salarytype->salarycategoryID == Collections::SALARYCATEGORY_SALARY) {
					if ($comments) echo "<br>Grossalaryadd - " . $paycheckrow->total;
					
					$entry = new Row();
					$entry->entryID = 0;
					$entry->entrydate = $paycheck->paymentdate;
					$entry->accountID = $salarytype->expenceaccountID;;
					$entry->amount = $paycheckrow->total;
					$entry->vatcodeID = 0;
					$entries[] = $entry;
				
					if ($comments) echo "<br>Grossalaryadd - " . $paycheckrow->total;
						
					$entry = new Row();
					$entry->entryID = 0;
					$entry->entrydate = $paycheck->paymentdate;
					$entry->accountID = $salarytype->payableaccountID;;
					$entry->amount = -1 * $paycheckrow->total;
					$entry->vatcodeID = 0;
					$entries[] = $entry;
					if ($comments) echo "<br>Grossalaryadd amount - " . $entry->amount;
						
					
				} else {
					if ($comments) echo "<br>no taxable - " . $paycheckrow->total;
					
					// TODO: Mutta pitää tästä viennit luoda silti...
					if ($salarytype->salarycategoryID == Collections::SALARYCATEGORY_REPAYMENT) {
						$entry = new Row();
						$entry->entryID = 0;
						$entry->entrydate = $paycheck->paymentdate;
						$entry->accountID = $salarytype->expenceaccountID;;
						$entry->amount = $paycheckrow->total;
						$entry->vatcodeID = 0;
						$entries[] = $entry;
						
						if ($comments) echo "<br>Grossalaryadd repayment - " . $paycheckrow->total;
						
						$entry = new Row();
						$entry->entryID = 0;
						$entry->entrydate = $paycheck->paymentdate;
						$entry->accountID = $salarytype->payableaccountID;;
						$entry->amount = -1 * $paycheckrow->total;
						$entry->vatcodeID = 0;
						$entries[] = $entry;
						if ($comments) echo "<br>Grossalaryadd repayment amount - " . $entry->amount;
						
					}
					
					
				}	
			}
		}
		
		if ($collapse == true) {
			$finalentries = array();
			foreach($entries as $index => $entry) {
				if ($comments) echo "<br> - - collapse entry - " . $entry->accountID . " - " . $entry->amount;
				
				if ($comments) if ($entry->number == '2940') echo "<br> - " . $entry->amount;
				if (isset($finalentries[$entry->accountID])) {
					$finalentry = $finalentries[$entry->accountID];
					$finalentry->amount = $finalentry->amount + round($entry->amount,2);
				} else {
					$finalentries[$entry->accountID] = $entry;
				}
					
			}
			if ($comments) echo "<br>collapse true 2 - " . count($finalentries);
				
			return $finalentries;
		} else {
			if ($comments) echo "<br>collapsetrue";
				
			return $entries;
		}
		
	}
	
	
	public function getworkerpaycheckdataJSONAction() {
		
		$comments = false;
		$workerID = $_GET['workerID'];
		
		$currentdate = date('Y-m-d');
		if ($comments) echo "<br>Currentdate";
		
		$pensioninsurancetypes = Collections::getPensionInsuranceTypes();
		$labouragreements = Table::load('hr_labouragreements');
		
		$contracts = Table::load('hr_workcontracts', " WHERE WorkerID=" . $workerID . " ORDER BY Startdate", $comments);
		$selectedcontract = 0;
		foreach($contracts as $index => $contract) {
			if (($contract->startdate <= $currentdate) && (($contract->enddate >= $currentdate) || ($contract->enddate == '0000-00-00'))) {
				if ($comments) echo "<br>Dates matches - startdate - " . $contract->stardate;
				if ($comments) echo "<br>Dates matches - enddate - " . $contract->enddate;
				$selectedcontract = $contract;				
			}
		}
		
		if ($comments) echo "<br><br><br>";
		echo "{";
		$first = true;
		
		echo "	\"contracts\": ";
		echo "[";
		
		foreach($contracts as $index => $contract) {
			if ($first) $first = false;
			else echo ",";
			$contractstr = $contract->startdate . " - ";
			if ($contract->enddate == '0000-00-00') {
				$contractstr = $contractstr . "toistaiseksi";
			} else {
				$contractstr = $contractstr . $contract->enddate;
			}
			$pensioninsurancetype = $pensioninsurancetypes[$contract->pensioninsurancetypeID];
			//$contractstr = $contractstr . ", " . $pensioninsurancetype->shortname;
			$labouragreement = $labouragreements[$contract->labouragreementID];
			//$contractstr = $contractstr . ", " . $labouragreement->abbreviation;
				
			echo " {";
			echo "		\"contractID\":\"" . $contract->workcontractID . "\",";
			echo "		\"name\":\"" . $contractstr . "\",";
			echo "		\"startdate\":\"" . $contract->startdate . "\",";
			echo "		\"enddate\":\"" . $contract->enddate . "\",";
			echo "		\"labouragreement\":\"" . $labouragreement->abbreviation . "\",";
			echo "		\"pensioninsurancetype\":\"" . $contract->pensioninsurancetypeID . "\"";
			echo "}";
		}
		echo "	]";
		echo "}";
		if ($comments) echo "<br><br><br>";
		if ($comments) echo "selected contract - " . $selectedcontract->workcontractID . " - " . $selectedcontract->startdate . "- " . $selectedcontract->enddate;	
	}
	
	
	public function getpayrollperiodsJSONAction() {
	
		$comments = true;
		$labouragreementID = $_GET['labouragreementID'];
	
		$currentdate = date('Y-m-d');
		if ($comments) echo "<br>Currentdate";
	
		$pensioninsurancetypes = Collections::getPensionInsuranceTypes();
		$labouragreements = Table::load('hr_labouragreements');
	
		$contracts = Table::load('hr_workcontracts', " WHERE WorkerID=" . $workerID . " ORDER BY Startdate", $comments);
		$selectedcontract = 0;
		foreach($contracts as $index => $contract) {
			if (($contract->startdate <= $currentdate) && (($contract->enddate >= $currentdate) || ($contract->enddate == '0000-00-00'))) {
				if ($comments) echo "<br>Dates matches - startdate - " . $contract->stardate;
				if ($comments) echo "<br>Dates matches - enddate - " . $contract->enddate;
				$selectedcontract = $contract;
			}
		}
	
		if ($comments) echo "<br><br><br>";
		echo "{";
		$first = true;
	
		echo "	\"contracts\": ";
		echo "[";
	
		foreach($contracts as $index => $contract) {
			if ($first) $first = false;
			else echo ",";
			$contractstr = $contract->startdate . " - ";
			if ($contract->enddate == '0000-00-00') {
				$contractstr = $contractstr . "toistaiseksi";
			} else {
				$contractstr = $contractstr . $contract->enddate;
			}
			$pensioninsurancetype = $pensioninsurancetypes[$contract->pensioninsurancetypeID];
			//$contractstr = $contractstr . ", " . $pensioninsurancetype->shortname;
			$labouragreement = $labouragreements[$contract->labouragreementID];
			//$contractstr = $contractstr . ", " . $labouragreement->abbreviation;
	
			echo " {";
			echo "		\"contractID\":\"" . $contract->workcontractID . "\",";
			echo "		\"name\":\"" . $contractstr . "\",";
			echo "		\"startdate\":\"" . $contract->startdate . "\",";
			echo "		\"enddate\":\"" . $contract->enddate . "\",";
			echo "		\"labouragreement\":\"" . $labouragreement->abbreviation . "\",";
			echo "		\"pensioninsurancetype\":\"" . $contract->pensioninsurancetypeID . "\"";
			echo "}";
		}
		echo "	]";
		echo "}";
		if ($comments) echo "<br><br><br>";
		if ($comments) echo "selected contract - " . $selectedcontract->workcontractID . " - " . $selectedcontract->startdate . "- " . $selectedcontract->enddate;
	}
	
	
	
	
	
}

?>
