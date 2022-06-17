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
	
		$this->registry->labouragreements = Table::load('hr_labouragreements');
		$this->registry->payrollperiods = Table::load('payroll_periods', ' ORDER BY Startdate');
		$this->registry->worktitles = Table::load('hr_worktitles');
		$this->registry->salarytypes = Table::load('hr_salarytypes');
		$this->registry->units = Table::load('system_units');
		$this->registry->salarycategories = Table::load('hr_salarycategories');
		
		/*
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		*/
		
		$this->registry->template->show('payroll/payrollsettings','payrollsettings');
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
	
	

	public function insertworktitleAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("hr_worktitles", $values, true);
		redirecttotal('payroll/payrollsettings/showsettings',null);
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
		$values['UnitID'] = $_GET['unitID'];
		$values['ExpenceaccountID'] = $_GET['expenceaccountID'];
		$values['PayableaccountID'] = $_GET['payableaccountID'];
		$values['SalarycategoryID'] = $_GET['salarycategoryID'];
		$success = Table::addRow("hr_salarytypes", $values, true);
		redirecttotal('payroll/payrollsettings/showsettings',null);
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
		redirecttotal('payroll/payrollsettings/showsettings',null);
	}
	
	

	public function updatesalarytypeAction() {
	
		$salarytypeID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['UnitID'] = $_GET['unitID'];
		$values['ExpenceaccountID'] = $_GET['expenceaccountID'];
		$values['PayableaccountID'] = $_GET['payableaccountID'];
		$values['SalarycategoryID'] = $_GET['salarycategoryID'];
		$success = Table::updateRow("hr_salarytypes", $values, $salarytypeID);
		redirecttotal('payroll/payrollsettings/showsettings',null);
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
