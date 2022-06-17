<?php


// TODO: Mietitään pitäisikö tämä costpools toiminnallisuus olla jossain muualla, esimerkiksi accounting-modulissa
//		 Toistaiseksi epäselvää tarvitaanko tätä myös jossain muualla

class AssetsController extends AbstractController {

	
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
	
	
	public function showassetsAction() {
		
		updateActionPath("Tuotantotekijät");
		
		$this->registry->assethierarchy = Table::loadHierarchy('accounting_assets','parentID');
		
		$this->registry->assets = Table::load('accounting_assets', ' ORDER BY Name');
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
		
		
		
		$this->registry->template->show('accounting/assets','assets');
	}
		

	public function showassetAction() {
	
		$assetID = $_GET['id'];
		$this->registry->asset = Table::loadRow('accounting_assets', $assetID);
		updateActionPath($this->registry->asset->name);
		
		$this->registry->costpools = Table::load('accounting_costpools', ' ORDER BY Name');
		$this->registry->assets = Table::load('accounting_assets', ' ORDER BY Name');
		$this->registry->accounts = Table::load('accounting_accounts');
		foreach($this->registry->accounts as $index => $account) {
			$account->fullname = $account->number . " " . $account->name;
		}
		
		$this->registry->entries = Table::load('accounting_entries', "WHERE AssetID=" . $assetID . " ORDER BY Entrydate, CostpoolID");
		$assetsvalue = array();
		$liabilitiesvalue = array();
		$incomesvalue = array();
		$expensesvalue = array();
		$others = array();
		
		foreach($this->registry->entries as $index => $entry) {
			
			switch ($entry->accounttypeID) {
				case 1:
					$assetsvalue[$entry->entryID] = $entry;
					break;
				case 2:
					$liabilitiesvalue[$entry->entryID] = $entry;
					break;
				case 3:
					$incomesvalue[$entry->entryID] = $entry;
					break;
				case 4:
					$expensesvalue[$entry->entryID] = $entry;
					break;
				default:
					$others[$entry->entryID] = $entry;
						
			}
			
			$this->registry->assetentries = $assetsvalue;
			$this->registry->liabilityentries = $liabilitiesvalue;
			$this->registry->incomeentries = $incomesvalue;
			$this->registry->expenseentries = $expensesvalue;
			$this->registry->others = $others;
				
		}
		
		$this->registry->template->show('accounting/assets','asset');
	}
	
	
	
	public function insertassetAction() {
	
		$values = array();
		$values['Name'] = $_GET['name'];
		$values['ParentID'] = $_GET['parentID'];
		$success = Table::addRow("accounting_assets", $values, false);
	
		redirecttotal('accounting/assets/showassets',null);
	}
	
	
	public function updateassetAction() {
	
		$id = $_GET['id'];
		$values = array();
		$values['Name'] = $_GET['name'];
		//$values['CostpoolID'] = $_GET['costpoolID'];
		$values['ParentID'] = $_GET['parentID'];
		$success = Table::updateRow("accounting_assets", $values, $id, true);
	
		redirecttotal('accounting/assets/showassets',null);
	}
	
	
	
}
