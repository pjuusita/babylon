<?php



class BookkeepingsettingsController extends AbstractController {

	
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
	
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		$this->registry->settings = $this->loadBookkeepingSettings();
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		$this->registry->vats = Table::load('system_vats');
		$this->registry->dimensions = Table::load("system_dimensions");
		
		/*
		$this->registry->dimensionvalues = Table::load("system_dimensionvalues");
		*/
		
		//$this->registry->costpooltypes = Table::load("accounting_costpooltypes");
		//$this->registry->costpooltypeaccounts = Table::load("accounting_costpooltypeaccounts");
		
		$this->registry->template->show('accounting/bookkeepingsettings','bookkeepingsettings');
	}
	
	
	public function loadsettingsAction() {
	
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		$this->registry->settings = $this->loadBookkeepingSettings();
		$this->registry->receiptsets = Table::load('accounting_receiptsets');
		$this->registry->vats = Table::load('system_vats');
		$this->registry->dimensions = Table::load("system_dimensions");
		$this->registry->dimensionvalues = Table::load("system_dimensionvalues");
		//$this->registry->costpooltypes = Table::load("accounting_costpooltypes");

		$this->registry->settingsfile = "accounting/bookkeepingsettings/bookkeepingsettings.php";
	}
	
	
	
	public function updatesettingsAction() {
		Settings::saveSetting("accounting_vatpayablesaccountID", $_GET['vatpayablesaccountID'], 0);
		Settings::saveSetting("accounting_vatrecievablesaccountID", $_GET['vatrecievablesaccountID'], 0);
		Settings::saveSetting("accounting_recievablesaccountID", $_GET['recievablesaccountID'], 0);
		Settings::saveSetting("accounting_payablesaccountID", $_GET['payablesaccountID'], 0);
		Settings::saveSetting("accounting_salesreceiptsetID", $_GET['receiptsetID'], 0);
		Settings::saveSetting("accounting_bankstatementreceiptsetID", $_GET['bankstatementsetID'], 0);
		Settings::saveSetting("accounting_cashaccountID", $_GET['cashaccountID'], 0);
		Settings::saveSetting("accounting_hrdebtsaccountID", $_GET['hrdebtsaccountID'], 0);
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
		$settingsrow->bankstatementsetID = Settings::getSetting('accounting_bankstatementreceiptsetID', 0);
		$settingsrow->cashaccountID = Settings::getSetting('accounting_cashaccountID', 0);
		$settingsrow->hrdebtsaccountID = Settings::getSetting('accounting_hrdebtsaccountID', 0);
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
	

	
	

	/*
	public function insertcostpooltypeAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::addRow("accounting_costpooltypes", $values, true);
	
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	*/
	
	
	/*
	public function updatecostpooltypeAction() {
	
		$costpooltypeID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$success = Table::updateRow("accounting_costpooltypes", $values, $costpooltypeID);
	
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	*/
	

	/*
	public function insertcostpooltypeaccountAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['CostpooltypeID'] = $_GET['costpooltypeID'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::addRow("accounting_costpooltypeaccounts", $values, true);
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	*/

	/*
	public function updatecostpooltypeaccountAction() {
	
		$rowID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['AccountID'] = $_GET['accountID'];
		$success = Table::updateRow("accounting_costpooltypeaccounts", $values, $rowID);
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	*/
	
	public function insertvatAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Percent'] = $_GET['percent'];
		$success = Table::addRow("system_vats", $values, true);
	
		redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	
	
	
	
	
	public function updateperiodAction() {
	
		$id = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['Startdate'] = $_GET['startdate'];
		$values['Enddate'] = $_GET['enddate'];
		echo "<br>Not implemented";
		die();
		//redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	
	


	public function updatedimensionAction() {
	
		$comments = false;
		$dimensionID = $_GET['id'];
		
		$name = $_GET['name'];
		$usedinsales = $_GET['usedinsales'];
		$usedinpurchases = $_GET['usedinpurchases'];
		$usedinpayroll = $_GET['usedinpayroll'];
		
		if ($comments) echo "<br>dimensionID - " . $dimensionID; 
		if ($comments) echo "<br>name - " . $name; 
		if ($comments) echo "<br>usedinsales - " . $usedinsales;
		if ($comments) echo "<br>usedinpurchases - " . $usedinpurchases;
		if ($comments) echo "<br>usedinpayroll - " . $usedinpayroll;
		
		$dimension = Table::loadRow('system_dimensions', $dimensionID);
		$this->insertDimensionColumn('accounting_entries', $dimensionID, $comments);
		$this->insertDimensionColumn('accounting_receipts', $dimensionID, $comments);
		
		if ($dimension->usedinsales == 0 AND $usedinsales == 1) {
			$this->insertDimensionColumn('sales_invoices', $dimensionID, $comments);
			$this->insertDimensionColumn('sales_invoicerows', $dimensionID, $comments);
		}
		
		if ($dimension->usedinpurchases == 0 AND $usedinpurchases == 1) {
			$this->insertDimensionColumn('accounting_purchases', $dimensionID, $comments);
			$this->insertDimensionColumn('accounting_purchaserows', $dimensionID, $comments);
		}
		
		if ($dimension->usedinpayroll == 0 AND $usedinpayroll == 1) {
			$this->insertDimensionColumn('payroll_paychecks', $dimensionID, $comments);
			$this->insertDimensionColumn('payroll_paycheckrows', $dimensionID, $comments);
		}
		
		
		$values = array();
		$values['Name'] = $_GET['name'];;
		$values['Abbreviation'] = $_GET['abbreviation'];
		$values['Usedinsales'] = $usedinsales;
		$values['Usedinpurchases'] = $usedinpurchases;
		$values['Usedinpayroll'] = $usedinpayroll;
		$success = Table::updateRow("system_dimensions", $values, $dimensionID, $comments);
		
		//redirecttotal('accounting/bookkeepingsettings/showsettings',null);
	}
	
	
	private function insertDimensionColumn($tablename, $dimensionID, $comments = false) {
		
		if ($comments) echo "<br>Insert dimension - " . $tablename . " - " . $dimensionID;

		$table = Table::getTable($tablename);
		$columns = $table->getColumns();
		$dimensioncolumnname = "Dimension" . $dimensionID;
			
		if ($comments) echo "<br>Checkkind if exists - " . $dimensioncolumnname;
		foreach($columns as $index => $column) {
			if ($comments) echo "<br>Checking - " . $column->columnname . " - " . $dimensioncolumnname;
			if ($column->columnname == $dimensioncolumnname) {
				if ($comments) echo "<br>found - " .  $column->columnname;
				return false;
			}
		}

		$tableID = $table->getId();
		$variablename = "dimension" . $dimensionID;
		$columnname = "Dimension" . $dimensionID;
		$name = "Dimension" . $dimensionID;
		$success = Table::insertColumn($tableID, $variablename,$columnname,$name,Column::COLUMNTYPE_INTEGER,0,0,0,NULL,NULL);
		if ($comments) echo "<br>Column luotu - " . $table->name . " - " . $name;
		return $success;
	}
	
}

?>
