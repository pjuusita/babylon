<?php


// TODO: Mietitään pitäisikö tämä costpools toiminnallisuus olla jossain muualla, esimerkiksi accounting-modulissa
//		 Toistaiseksi epäselvää tarvitaanko tätä myös jossain muualla

class LiabilitiesController extends AbstractController {

	
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
	
	
	public function showliabilitiesAction() {
		
		updateActionPath("Lainat");
		
		//$this->registry->assethierarchy = Table::loadHierarchy('accounting_assets','parentID');
		$this->registry->liabilities = Table::load('accounting_liabilities');
		
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->periods = Table::load('accounting_periods',' ORDER BY Startdate');
		$periodID = getSessionVar('periodID',AccountingModule::getBookkeepingPeriod());
		foreach($this->registry->periods as $index => $period) {
			if ($period->periodID == $periodID) $this->registry->period = $period;
			$period->fullname = "Tilikausi " . $period->name;
		}
		$this->registry->periodID = $periodID;
		$period = $this->registry->period;
		
		$this->registry->template->show('accounting/liabilities','liabilities');
	}
		

	public function showliabilityAction() {
	
		$liabilityID = $_GET['id'];
		$this->registry->liability = Table::loadRow('accounting_liabilities', $liabilityID);
		updateActionPath($this->liability->asset->name);
		
		$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		$this->registry->liabilities = Table::load('accounting_liabilities', ' ORDER BY Name');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		$this->registry->template->show('accounting/liabilities','liability');
	}
	
	
	
	public function insertliabilityAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ParentID'] = 0;
		$success = Table::addRow("accounting_liabilities", $values, false);
	
		redirecttotal('accounting/liabilities/showliabilities',null);
	}
	
	
	public function updateliabilityAction() {
	
		$id = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		//$values['CostpoolID'] = $_GET['costpoolID'];
		//$values['ParentID'] = $_GET['parentID'];
		$success = Table::updateRow("accounting_assets", $values, $id, true);
	
		redirecttotal('accounting/liabilities/showliabilities',null);
	}
	
	
	
}
