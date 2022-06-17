<?php


// TODO: Mietitään pitäisikö tämä costpools toiminnallisuus olla jossain muualla, esimerkiksi accounting-modulissa
//		 Toistaiseksi epäselvää tarvitaanko tätä myös jossain muualla

class CostpoolsController extends AbstractController {

	
	public function getCSSFiles() {
		return array('menu.css','babylon.css');
	}
	
	
	public function getJSFiles() {
		return array('jquery.min.js','jquery-ui.js');
	}
	
	
	public function indexAction() {
		//$this->showcostpoolsAction();
		$this->registry->template->show('system/error','unknown');
	}	
	
	

	public function showcostpoolsAction() {

		updateActionPath("Kustannuspaikat");
		
		$this->registry->costpooltypes = Collections::getCostpoolTypes();
		$this->registry->accounts = Table::load('accounting_accounts');
		//$this->registry->vats = Table::load('system_vats');
		$this->registry->vats = Table::load('system_vats');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		
		//$this->registry->costpools = Table::loadHierarchy('accounting_costpools','parentID','ORDER BY Name');
		$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		
		/*
		foreach($this->registry->costpools as $index => $costpool) {
			if ($costpool->incomeaccountID > 0) {
				$costpool->accountID = $costpoo->incomeaccountID;
			}
			if ($costpool->expenseaccountID > 0) {
				$costpool->accountID = $costpoo->expenseaccountID;
			}
		}
		*/
		
		
		$this->registry->template->show('accounting/costpools','costpools');
	}
	
	
	public function showcostpoolAction() {

		$costpoolID = $_GET['id'];
		$this->registry->costpool = Table::loadRow('accounting_costpools', $costpoolID);
		updateActionPath($this->registry->costpool->name);
		$this->registry->accounts = Table::load('accounting_accounts');
		$this->registry->vats = Table::load('system_vats');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->costpools = Table::load('accounting_costpools','ORDER BY Name');
		$this->registry->purchaserows = Table::load('accounting_purchaserows','WHERE CostpoolID=' . $costpoolID);
		$this->registry->defaultpurchaserows = Table::load('accounting_defaultpurchaserows','WHERE CostpoolID=' . $costpoolID);
		$this->registry->entries = Table::load('accounting_entries','WHERE CostpoolID=' . $costpoolID);
		$this->registry->purchases = Table::load('accounting_purchases','WHERE payablecostpoolID=' . $costpoolID);
		$this->registry->workers = Table::load('hr_workers','WHERE CostpoolID=' . $costpoolID);
		$this->registry->costpooltypes = Collections::getCostpoolTypes();
		$this->registry->expenditures = Table::load('accounting_expenditures','WHERE CostpoolID=' . $costpoolID);
		$this->registry->vats = Table::load('system_vats');
		
		$this->registry->template->show('accounting/costpools','costpool');
	}
	
	
	
	public function insertcostpoolAction() {
		$values = array();
		$values['Name'] = $_GET['name'];
		$defaultVatID = getSetting('system_settings_defaultvatID');
		$values['VatID'] = $defaultVatID;
		$values['Disabled'] = 0;
		$success = Table::addRow("accounting_costpools", $values, true);
		redirecttotal('accounting/costpools/showcostpools',null);
	}
	
	


	public function insertexpenditureAction () {
	
		$values = array();
		$values['Name'] =  $_GET['name'];
		$values['AccountID'] =  $_GET['accountID'];
		$values['CostpoolID'] =  $_GET['costpoolID'];
		$values['VatID'] =  $_GET['vatID'];
		
		$accountID = Table::addRow("accounting_expenditures", $values, true);
	
		redirecttotal('accounting/costpools/showcostpool&id=' . $_GET['costpoolID']);
	}
	
	
	
	public function updatecostpoolAction() {
	
		$comments = false;
		
		$costpoolID = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		if (isset($_GET['parentID'])) $values['ParentID'] = $_GET['parentID'];
		if (isset($_GET['vatID'])) $values['VatID'] = $_GET['vatID'];
		if (isset($_GET['incomeaccountID'])) $values['IncomeaccountID'] = $_GET['incomeaccountID'];
		if (isset($_GET['expenseaccountID'])) $values['ExpenseaccountID'] = $_GET['expenseaccountID'];
		if (isset($_GET['deptaccountID'])) $values['DeptaccountID'] = $_GET['deptaccountID'];
		if (isset($_GET['costpooltype'])) $values['Costpooltype'] = $_GET['costpooltype'];
		$success = Table::updateRow("accounting_costpools", $values, $costpoolID, $comments);
	
		if (!$comments) redirecttotal('accounting/costpools/showcostpool&id=' . $costpoolID, null);
	}
	
	
	public function removecostpoolAction() {
	
		$costpoolID = $_GET['costpoolID'];
		$purchase = Table::loadRow('accounting_costpools',$costpoolID);
		
		$subcostpools = Table::load('accounting_costpools', "WHERE ParentID=" . $costpoolID);
		if (count($subcostpools) > 0) {
			echo "<br>Sisältää ali costpooleja, ei voida poistaa";
			die('');
		}

		$success = Table::deleteRow('accounting_costpools',$costpoolID);
		redirecttotal('accounting/costpools/showcostpools',null);
	}
	
	

	public function disablecostpoolAction() {
	
		$comments = false;
	
		$costpoolID = $_GET['costpoolID'];
		$values = array();
		$values['Disabled'] = 1;
		$success = Table::updateRow("accounting_costpools", $values, $costpoolID, $comments);
	
		if (!$comments) redirecttotal('accounting/costpools/showcostpool&id=' . $costpoolID, null);
	}
	
	
}
