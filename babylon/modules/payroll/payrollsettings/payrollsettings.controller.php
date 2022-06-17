<?php



class PayrollsettingsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','testcss.php');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js','chosen.jquery.js','prism.js');
	}


	public function indexAction() {
		//$this->showsettingsAction();
		$this->registry->template->show('system/error','unknown');
	}
	

	//******************************************************************************************************
	//***** ACTIONS
	//******************************************************************************************************
	
	
	public function showsettingsAction() {
	
		updateActionPath("Palkanlaskenta-asetukset");
		
		$this->registry->settings = $this->loadPayrollSettings();
		$this->registry->labouragreements = Table::load('hr_labouragreements');
		//$this->registry->payrollperiods = Table::load('payroll_periods', ' ORDER BY Startdate');
		//$this->registry->salarytypes = Table::load('hr_salarytypes');
		//$this->registry->units = Table::load('system_units');
		//$this->registry->salarycategories = Table::load('hr_salarycategories');
		
		$worktitles = Table::load('hr_worktitles');
		$worktitlelinks = Table::load('hr_worktitlelinks');
		
		foreach ($worktitles as $index => $worktitle) {
			$agreementstr = "";
			foreach($this->registry->labouragreements as $index2 => $labouragreement) {
				
				$found = false;
				foreach($worktitlelinks as $index3 => $link) {
					if (($link->worktitleID == $worktitle->worktitleID) && ($link->labouragreementID == $labouragreement->labouragreementID)) {
						$found = true; 
						break;
					}
				}
				
				if ($found == true) {
					$var = "labouragreement-" . $labouragreement->labouragreementID;
					$worktitle->$var = 1;
					if ($agreementstr == "") {
						$agreementstr = $labouragreement->abbreviation;
					} else {
						$agreementstr = $agreementstr . ", " . $labouragreement->abbreviation;
					}
					$worktitle->agreements = $agreementstr;
				} else {
					$var = "labouragreement-" . $labouragreement->labouragreementID;
					$worktitle->$var = 0;
				}
			}
			if ($agreementstr == "") $worktitle->agreements = "-";
		}
		$this->registry->worktitles = $worktitles;
		
		
		$this->registry->salarytypes = Table::load('hr_salarytypes');
		$salarytypelinks = Table::load('payroll_labouragreementlinks');
		
		foreach ($this->registry->salarytypes as $index => $salarytype) {
			$agreementstr = "";
			foreach($this->registry->labouragreements as $index2 => $labouragreement) {
		
				$found = false;
				foreach($salarytypelinks as $index3 => $link) {
					if (($link->salarytypeID == $salarytype->salarytypeID) && ($link->labouragreementID == $labouragreement->labouragreementID)) {
						$found = true;
						break;
					}
				}
		
				if ($found == true) {
					if ($agreementstr == "") {
						$agreementstr = $labouragreement->abbreviation;
					} else {
						$agreementstr = $agreementstr . ", " . $labouragreement->abbreviation;
					}
					$salarytype->agreements = $agreementstr;
				}
			}
			if ($agreementstr == "") $worktitle->agreements = "-";
		}
		
		
		
		
		$this->registry->units = Table::load('system_units');
		
		$this->registry->salarycategories = Table::load('hr_salarycategories');
		//echo "<br>Salarycategorycount - " . count($this->registry->salarycategories);
		$this->registry->deductions = Table::load('payroll_deductions');
		
		/*
		foreach($this->registry->deductions as $index => $deduction) {
			if (($deduction->agemin > 0) || ($deduction->agemax > 0)) {
				$deduction->info = "Ikä " . $deduction->agemin . "-" . $deduction->agemax . "v";
			}
		}
		*/
		
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->template->show('payroll/payrollsettings','payrollsettings');
	}
	

	private function loadPayrollSettings() {
		$settingsrow = new Row();
		$settingsrow->systemID = 1;
		$settingsrow->payablesaccountID = Settings::getSetting('payroll_payablesaccountID', 0);
		return $settingsrow;
	}
	

	public function updatesettingsAction() {
		Settings::saveSetting("payroll_payablesaccountID", $_GET['payablesaccountID'], 0);
		//$this->registry->template->show('payroll/payrollsettings','payrollsettings');
	}
	
	
	public function showsalarytypeAction() {
		$salarytypeID = $_GET['id'];

		$this->registry->salarytype = Table::loadRow('hr_salarytypes', $salarytypeID);
		$this->registry->units = Table::load('system_units');
		$this->registry->salarycategories = Table::load('hr_salarycategories');
		$this->registry->incomeregistercodes = Table::load('payroll_incomeregistercodes');
		$this->registry->labouragreements = Table::load('hr_labouragreements');
		$this->registry->labouragreementlinks = Table::load('payroll_labouragreementlinks',"WHERE SalarytypeID=" . $salarytypeID);
		$this->registry->units = Table::load('system_units');
		

		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		foreach($this->registry->incomeregistercodes as $index => $code) {
			$code->fullname = $code->incomeregistercodeID . " - " . $code->name;
		}
		
		$this->registry->template->show('payroll/payrollsettings','salarytype');
	}
	

	public function insertlabouragreementAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Abbreviation'] = $_GET['abbreviation'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$success = Table::addRow("hr_labouragreements", $values, true);
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	

	public function insertlabouragreementtosalarytypeAction() {

		$labouragreementID = $_GET['labouragreementID'];
		$salarytypeID = $_GET['salarytypeID'];	
		
		$values = array();
		$values['LabouragreementID'] = $labouragreementID;
		$values['SalarytypeID'] = $salarytypeID;
		$success = Table::addRow("payroll_labouragreementlinks", $values);
		redirecttotal('payroll/payrollsettings/showsalarytype&id=' . $salarytypeID,null);
	}
	
	
	
	public function insertworktitleAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("hr_worktitles", $values, true);
		
		// TODO: lisätään automaattisesti kaikkiin tesseihin? Vai ei mihinkään?
			
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	

	public function insertlabouragreementworktitleAction() {
		
		$labouragreementID = $_GET['labouragreementID'];

		$values = array();
		$values['Name'] = $_GET['name'];
		$worktitleID = Table::addRow("hr_worktitles", $values, true);
		
		$values = array();
		$values['WorktitleID'] = $worktitleID;
		$values['LabouragreementID'] = $labouragreementID;
		$success = Table::addRow("hr_worktitlelinks", $values, true);
		
		redirecttotal('payroll/labouragreements/showlabouragreement&id=' . $labouragreementID,null);
	}
	
	
	
	public function insertpayrollperiodAction() {
	
		$values = array();
		$values['LabouragreementID'] = $_GET['labouragreementID'];
		$values['Name'] = $_GET['name'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$values['Bookkeepingdate'] = $_GET['bookkeepingdate'];
		$values['Paymentdate'] = $_GET['paymentdate'];
		$success = Table::addRow("payroll_periods", $values, true);
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	

	public function insertsalarytypeAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		//$values['UnitID'] = $_GET['unitID'];
		//$values['ExpenceaccountID'] = $_GET['expenceaccountID'];
		//$values['PayableaccountID'] = $_GET['payableaccountID'];
		//$values['SalarycategoryID'] = $_GET['salarycategoryID'];
		$salarytypeID = Table::addRow("hr_salarytypes", $values, true);
		redirecttotal('payroll/payrollsettings/showsalarytype?id=' . $salarytypeID,null);
	}
	


	public function updatelabouragreementAction() {
	
		$labouragreementID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Abbreviation'] = $_GET['abbreviation'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$success = Table::updateRow("hr_labouragreements", $values, $labouragreementID);
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	

	public function updatepayrollperiodAction() {
	
		$periodID = $_GET['id'];
		$values = array();
		$values['LabouragreementID'] = $_GET['labouragreementID'];
		$values['Name'] = $_GET['name'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$values['Bookkeepingdate'] = $_GET['bookkeepingdate'];
		$values['Paymentdate'] = $_GET['paymentdate'];
		$success = Table::updateRow("payroll_periods", $values, $periodID);
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	
	


	public function updateworktitleAction() {
	
		$worktitleID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("hr_worktitles", $values, $worktitleID);
		
		$labouragreements = Table::load('hr_labouragreements');
		
		foreach($labouragreements as $index => $labouragreement) {
			$getstr = "labouragreement-" . $labouragreement->labouragreementID;
			if (isset($_GET[$getstr])) {
				if ($_GET[$getstr] == 1) {
					$values = array();
					$values['WorktitleID'] = $worktitleID;
					$values['LabouragreementID'] = $labouragreement->labouragreementID;
					$success = Table::addRow("hr_worktitlelinks", $values, true);
				}
			}
		}
		
		//redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	

	public function updatesalarytypeAction() {
	
		$salarytypeID = $_GET['id'];
		$incomeregistercodeID = $_GET['incomeregistercodeID'];
		
		$incomeregister = Table::loadRow('payroll_incomeregistercodes', $incomeregistercodeID);
		
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Number'] = $_GET['number'];
		$values['UnitID'] = $_GET['unitID'];
		
		
		$values['Witholdingtax'] = $incomeregister->witholdingtax;
		$values['Pensioninsurance'] = $incomeregister->pensioninsurance;
		$values['Accidentinsurance'] = $incomeregister->accidentinsurance;
		$values['Unemploymentinsurance'] = $incomeregister->unemploymentinsurance;
		$values['Sicknessinsurance'] = $incomeregister->sicknessinsurance;
		$values['Insuranceinfotype'] = $incomeregister->insuranceinfotype;
		
		$values['SalarycategoryID'] = $_GET['salarycategoryID'];
		$values['IncomeregistercodeID'] = $incomeregistercodeID;
		$success = Table::updateRow("hr_salarytypes", $values, $salarytypeID);
		redirecttotal('payroll/payrollsettings/showsalarytype&id=' . $salarytypeID,null);
	}
	


	public function updatesalarytypeaccountAction() {
	
		$salarytypeID = $_GET['id'];
		$expenceaccountID = $_GET['expenceaccountID'];
		$payableaccountID = $_GET['payableaccountID'];
		
		$values['ExpenceaccountID'] = $expenceaccountID;
		$values['PayableaccountID'] = $payableaccountID;
		$success = Table::updateRow("hr_salarytypes", $values, $salarytypeID);
		redirecttotal('payroll/payrollsettings/showsalarytype&id=' . $salarytypeID,null);
	}
	
	

	public function updateexpenseaccountAction() {
	
		$rowID = $_GET['id'];
		$expenceaccountID = $_GET['expenceaccountID'];
		$deptaccountID = $_GET['deptaccountID'];
	
		$values['ExpenceaccountID'] = $expenceaccountID;
		$values['DeptaccountID'] = $deptaccountID;
		$success = Table::updateRow("payroll_deductions", $values, $rowID);
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	
	public function updatedeductionaccountAction() {
	
		$rowID = $_GET['id'];
		$expenceaccountID = $_GET['expenceaccountID'];
		$deptaccountID = $_GET['deptaccountID'];
	
		$values['ExpenceaccountID'] = $expenceaccountID;
		$values['DeptaccountID'] = $deptaccountID;
		$success = Table::updateRow("payroll_deductions", $values, $rowID, true);
		//redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	/*
	public function updatesettingsAction() {
		Settings::saveSetting("accounting_vatpayablesaccountID", $_GET['vatpayablesaccountID'], 0);
		Settings::saveSetting("accounting_vatrecievablesaccountID", $_GET['vatrecievablesaccountID'], 0);
		Settings::saveSetting("accounting_recievablesaccountID", $_GET['recievablesaccountID'], 0);
		Settings::saveSetting("accounting_payablesaccountID", $_GET['payablesaccountID'], 0);
		Settings::saveSetting("accounting_salesreceiptsetID", $_GET['receiptsetID'], 0);
		redirecttotal('accounting/bookkeepingsettings/showsettings', null);
	}
	
	
	
	private function loadBookkeepingSettings() {
		$settingsrow = new Row();
		$settingsrow->systemID = 1;
		$settingsrow->vatpayablesaccountID = Settings::getSetting('accounting_vatpayablesaccountID', 0);
		$settingsrow->vatrecievablesaccountID = Settings::getSetting('accounting_vatrecievablesaccountID', 0);
		$settingsrow->recievablesaccountID = Settings::getSetting('accounting_recievablesaccountID', 0);
		$settingsrow->payablesaccountID = Settings::getSetting('accounting_payablesaccountID', 0);
		$settingsrow->receiptsetID = Settings::getSetting('accounting_salesreceiptsetID', 0);
		return $settingsrow;
	}
	
	
	public function insertperiodAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		$success = Table::addRow("accounting_periods", $values, true);
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	
	

	public function insertreceiptsetAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Code'] = $_GET['code'];
		$values['Startnumber'] = $_GET['startnumber'];
		$values['Endnumber'] = $_GET['endnumber'];
		$success = Table::addRow("accounting_receiptsets", $values, true);
	
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	


	public function insertvatAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Percent'] = $_GET['percent'];
		$success = Table::addRow("system_vats", $values, true);
	
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	
	

	*/
}

?>
